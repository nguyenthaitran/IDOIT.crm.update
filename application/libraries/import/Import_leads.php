<?php
/* nguyenthaitran */
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'libraries/import/App_import.php');

class Import_leads extends App_import
{
    private $uniqueValidationFields = [];

    protected $notImportableFields = [];

    protected $requiredFields = ['name'];

    public function __construct()
    {
        $this->notImportableFields = hooks()->apply_filters('not_importable_leads_fields', ['id', 'source', 'assigned', 'status', 'dateadded', 'last_status_change', 'addedfrom', 'leadorder', 'date_converted', 'lost', 'junk', 'is_imported_from_email_integration', 'email_integration_uid', 'is_public', 'dateassigned', 'client_id', 'lastcontact', 'last_lead_status', 'from_form_id', 'default_language', 'hash']);

        $uniqueValidationFields = json_decode(get_option('lead_unique_validation'));
        if (count($uniqueValidationFields) > 0) {
            $this->uniqueValidationFields = $uniqueValidationFields;
            $message                      = '';
            foreach ($uniqueValidationFields as $key => $field) {
                if ($key === 0) {
                    $message .= 'Các cơ hội <b class="text-danger">trùng lặp</b> được thiết lập <a href="' . admin_url('settings?group=leads#unique_validation_wrapper') . '" target="_blank">tại đây</a>, <b>sẽ không được</b> nhập vào hệ thống nếu:<br />';
                }
                $message .= '- Cơ hội có <b>' . $field . '</b> đã tồn tại. ok';
            }

            if ($message != '') {
                $message = substr($message, 0, -3);
            }

            $message .= '<br /><br />Nếu bạn vẫn muốn nhập tất cả cơ hội, hãy hủy bỏ việc kiểm tra trùng lặp';

            $this->addImportGuidelinesInfo($message);
        }

        parent::__construct();
    }

    public function perform()
    {
        $this->initialize();

        $databaseFields      = $this->getImportableDatabaseFields();
        $totalDatabaseFields = count($databaseFields);

        foreach ($this->getRows() as $rowNumber => $row) {
            $insert = [];
            for ($i = 0; $i < $totalDatabaseFields; $i++) {
                $row[$i] = $this->checkNullValueAddedByUser($row[$i]);

                if ($databaseFields[$i] == 'name' && empty($row[$i])) {
                    $row[$i] = '/';
                } elseif ($databaseFields[$i] == 'country') {
                    $row[$i] = $this->countryValue($row[$i]);
                }

                $insert[$databaseFields[$i]] = $row[$i];
            }

            $insert = $this->trimInsertValues($insert);

            if (count($insert) > 0) {
                if ($this->isDuplicateLead($insert)) {
                    continue;
                }

                $this->incrementImported();

                $id = null;

                if (!$this->isSimulation()) {
                    if (!isset($insert['dateadded'])) {
                        $insert['dateadded'] = date('Y-m-d H:i:s');
                    }

                    if (!isset($insert['addedfrom'])) {
                        $insert['addedfrom'] = get_staff_user_id();
                    }

                    $insert['status'] = $this->ci->input->post('status');
                    $insert['source'] = $this->ci->input->post('source');

                    if ($this->ci->input->post('responsible')) {
                        $insert['assigned'] = $this->ci->input->post('responsible');
                    }

                    $tags = '';
                    if (isset($insert['tags']) || is_null($insert['tags'])) {
                        if (!is_null($insert['tags'])) {
                            $tags = $insert['tags'];
                        }
                        unset($insert['tags']);
                    }

                    $this->ci->db->insert(db_prefix() . 'leads', $insert);
                    $id = $this->ci->db->insert_id();

                    if ($id) {
                        handle_tags_save($tags, $id, 'lead');
                    }
                } else {
                    $this->simulationData[$rowNumber] = $this->formatValuesForSimulation($insert);
                }

                $this->handleCustomFieldsInsert($id, $row, $i, $rowNumber, 'leads');
            }

            if ($this->isSimulation() && $rowNumber >= $this->maxSimulationRows) {
                break;
            }
        }
    }

    protected function tags_formatSampleData()
    {
        return 'Nhãn 1, Nhãn 2';
    }

    public function formatFieldNameForHeading($field)
    {
        if (strtolower($field) == 'title') {
            return 'Chức vụ';
        }
		if (strtolower($field) == 'name') {
            return 'Họ tên';
        }
		if (strtolower($field) == 'company') {
            return 'Công ty';
        }
		if (strtolower($field) == 'description') {
            return 'Mô tả';
        }
		if (strtolower($field) == 'country') {
            return 'Quốc gia';
        }
		if (strtolower($field) == 'zip') {
            return 'Mã bưu chính';
        }
		if (strtolower($field) == 'city') {
            return 'Quận/Huyện';
        }
		if (strtolower($field) == 'state') {
            return 'Tỉnh/Thành phố';
        }
		if (strtolower($field) == 'address') {
            return 'Địa chỉ';
        }
		if (strtolower($field) == 'phonenumber') {
            return 'Điện thoại';
        }
		if (strtolower($field) == 'lead_value') {
            return 'Giá trị cơ hội';
        }
		if (strtolower($field) == 'vat') {
            return 'Mã số thuế';
        }
		if (strtolower($field) == 'tags') {
            return 'Gắn nhãn';
        }
        return parent::formatFieldNameForHeading($field);
    }

    protected function email_formatSampleData()
    {
        return uniqid() . '@mycrm.vn';
    }

    protected function failureRedirectURL()
    {
        return admin_url('leads/import');
    }

    private function isDuplicateLead($data)
    {
        foreach ($this->uniqueValidationFields as $field) {
            if ((isset($data[$field]) && $data[$field] != '')
                && total_rows(db_prefix() . 'leads', [$field => $data[$field]]) > 0) {
                return true;
            }
        }

        return false;
    }

    private function formatValuesForSimulation($values)
    {
        foreach ($values as $column => $val) {
            if ($column == 'country' && !empty($val) && is_numeric($val)) {
                $country = $this->getCountry(null, $val);
                if ($country) {
                    $values[$column] = $country->short_name;
                }
            }
        }

        return $values;
    }

    private function getCountry($search = null, $id = null)
    {
        if ($search) {
            $this->ci->db->where('iso2', $search);
            $this->ci->db->or_where('short_name', $search);
            $this->ci->db->or_where('long_name', $search);
        } else {
            $this->ci->db->where('country_id', $id);
        }

        return  $this->ci->db->get(db_prefix() . 'countries')->row();
    }

    private function countryValue($value)
    {
        if ($value != '') {
            if (!is_numeric($value)) {
                $country = $this->getCountry($value);
                $value   = $country ? $country->country_id : 0;
            }
        } else {
            $value = 0;
        }

        return $value;
    }
}
