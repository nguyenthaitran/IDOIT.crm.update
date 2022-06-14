<?php
/* nguyenthaitran */
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'libraries/import/App_import.php');

class Import_items extends App_import
{
    protected $notImportableFields = ['id'];

    protected $requiredFields = ['description', 'rate'];

    public function __construct()
    {
        $this->addItemsGuidelines();

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

                if ($databaseFields[$i] == 'description' && $row[$i] == '') {
                    $row[$i] = '/';
                } elseif (startsWith($databaseFields[$i], 'rate') && !is_numeric($row[$i])) {
                    $row[$i] = 0;
                } elseif ($databaseFields[$i] == 'group_id') {
                    $row[$i] = $this->groupValue($row[$i]);
                } elseif ($databaseFields[$i] == 'tax' || $databaseFields[$i] == 'tax2') {
                    $row[$i] = $this->taxValue($row[$i]);
                }

                $insert[$databaseFields[$i]] = $row[$i];
            }

            $insert = $this->trimInsertValues($insert);

            if (count($insert) > 0) {
                $this->incrementImported();

                if (!empty($insert['tax2']) && empty($insert['tax'])) {
                    $insert['tax']  = $insert['tax2'];
                    $insert['tax2'] = 0;
                }

                $id = null;

                if (!$this->isSimulation()) {
                    $this->ci->db->insert(db_prefix().'items', $insert);
                    $id = $this->ci->db->insert_id();
                } else {
                    $this->simulationData[$rowNumber] = $this->formatValuesForSimulation($insert);
                }

                $this->handleCustomFieldsInsert($id, $row, $i, $rowNumber, 'items_pr');
            }

            if ($this->isSimulation() && $rowNumber >= $this->maxSimulationRows) {
                break;
            }
        }
    }

    public function formatFieldNameForHeading($field)
    {	
		if (strtolower($field) == 'tax') {
            return 'Thuế';
        }
		if (strtolower($field) == 'tax2') {
            return 'Thuế 2';
        }
		if (strtolower($field) == 'description') {
            return 'Tên sản phẩm';
        }
		if (strtolower($field) == 'long_description') {
            return 'Mô tả';
        }
		if (strtolower($field) == 'unit') {
            return 'Đơn vị tính';
        }
		if (strtolower($field) == 'commodity_code') {
            return 'Mã hàng hóa';
        }
		if (strtolower($field) == 'commodity_barcode') {
            return 'Mã vạch hàng hóa';
        }
        $this->ci->load->model('currencies_model');

        if (strtolower($field) == 'group_id') {
            return 'Nhóm sản phẩm';
        } elseif (startsWith($field, 'rate')) {
            $str = 'Giá - ';
            // Base currency
            if ($field == 'rate') {
                $str .= $this->ci->currencies_model->get_base_currency()->name;
            } else {
                $str .= $this->ci->currencies_model->get(strafter($field, 'rate_currency_'))->name;
            }

            return $str;
        }

        return parent::formatFieldNameForHeading($field);
    }

    protected function failureRedirectURL()
    {
        return admin_url('invoice_items/import');
    }

    private function addItemsGuidelines()
    {
        $this->addImportGuidelinesInfo('Trong cột <b>Thuế</b> và <b>Thuế 2</b>, bạn <b>must</b> buộc phải <b>Tên loại thuế hoặc ID Tên loại thuế</b>, bạn có thể thiết lập các loại thuế bằng cách truy cập vào <a href="' . admin_url('taxes') . '" target="_blank">Cài đặt -> Tài chính -> Các loại thuế</a>.');
        $this->addImportGuidelinesInfo('Trong cột <b>Tên nhóm</b>, bạn <b>buộc phải</b> thêm <b>Tên nhóm hoặc ID nhóm</b>, bạn có thể thiết lập nhóm <a href="' . admin_url('invoice_items?groups_modal=true') . '" target="_blank">Tại đây</a>.');
    }

    private function formatValuesForSimulation($values)
    {
        foreach ($values as $column => $val) {
            if ($column == 'group_id' && !empty($val) && is_numeric($val)) {
                $group = $this->getGroupBy('id', $val);
                if ($group) {
                    $values[$column] = $group->name;
                }
            } elseif (($column == 'tax' || $column == 'tax2') && !empty($val) && is_numeric($val)) {
                $tax = $this->getTaxBy('id', $val);
                if ($tax) {
                    $values[$column] = $tax->name . ' (' . $tax->taxrate . '%)';
                }
            }
        }

        return $values;
    }

    private function getTaxBy($field, $idOrName)
    {
        $this->ci->db->where($field, $idOrName);

        return $this->ci->db->get(db_prefix().'taxes')->row();
    }

    private function getGroupBy($field, $idOrName)
    {
        $this->ci->db->where($field, $idOrName);

        return $this->ci->db->get(db_prefix().'items_groups')->row();
    }

    private function taxValue($value)
    {
        if ($value != '') {
            if (!is_numeric($value)) {
                $tax   = $this->getTaxBy('name', $value);
                $value = $tax ? $tax->id : 0;
            }
        } else {
            $value = 0;
        }

        return $value;
    }

    private function groupValue($value)
    {
        if ($value != '') {
            if (!is_numeric($value)) {
                $group = $this->getGroupBy('name', $value);
                $value = $group ? $group->id : 0;
            }
        } else {
            $value = 0;
        }

        return $value;
    }
}
