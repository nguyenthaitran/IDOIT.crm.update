<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Estimate_merge_fields extends App_merge_fields
{
    public function build()
    {
        return [
                [
                    'name'      => 'Liên kết xem báo giá',
                    'key'       => '{estimate_link}',
                    'available' => [
                        'estimate',
                    ],
                ],
                [
                    'name'      => 'Số báo giá',
                    'key'       => '{estimate_number}',
                    'available' => [
                        'estimate',
                    ],
                ],
                [
                    'name'      => 'Số tham chiếu',
                    'key'       => '{estimate_reference_no}',
                    'available' => [
                        'estimate',
                    ],
                ],
                [
                    'name'      => 'Ngày hết hạn báo giá',
                    'key'       => '{estimate_expirydate}',
                    'available' => [
                        'estimate',
                    ],
                ],
                [
                    'name'      => 'Ngày báo giá',
                    'key'       => '{estimate_date}',
                    'available' => [
                        'estimate',
                    ],
                ],
                [
                    'name'      => 'Tình trạng báo giá',
                    'key'       => '{estimate_status}',
                    'available' => [
                        'estimate',
                    ],
                ],
                [
                    'name'      => 'Người báo giá',
                    'key'       => '{estimate_sale_agent}',
                    'available' => [
                        'estimate',
                    ],
                ],
                [
                    'name'      => 'Tổng giá trị',
                    'key'       => '{estimate_total}',
                    'available' => [
                        'estimate',
                    ],
                ],
                [
                    'name'      => 'Tổng tiền',
                    'key'       => '{estimate_subtotal}',
                    'available' => [
                        'estimate',
                    ],
                ],
                [
                    'name'      => 'Dự án',
                    'key'       => '{project_name}',
                    'available' => [
                        'estimate',
                    ],
                ],
            ];
    }

    /**
     * Merge fields for estimates
     * @param  mixed $estimate_id estimate id
     * @return array
     */
    public function format($estimate_id)
    {
        $fields = [];
        $this->ci->db->where('id', $estimate_id);
        $estimate = $this->ci->db->get(db_prefix().'estimates')->row();

        if (!$estimate) {
            return $fields;
        }

        $currency = get_currency($estimate->currency);

        $fields['{estimate_sale_agent}']   = get_staff_full_name($estimate->sale_agent);
        $fields['{estimate_total}']        = app_format_money($estimate->total, $currency);
        $fields['{estimate_subtotal}']     = app_format_money($estimate->subtotal, $currency);
        $fields['{estimate_link}']         = site_url('estimate/' . $estimate_id . '/' . $estimate->hash);
        $fields['{estimate_number}']       = format_estimate_number($estimate_id);
        $fields['{estimate_reference_no}'] = $estimate->reference_no;
        $fields['{estimate_expirydate}']   = _d($estimate->expirydate);
        $fields['{estimate_date}']         = _d($estimate->date);
        $fields['{estimate_status}']       = format_estimate_status($estimate->status, '', false);
        $fields['{project_name}']          = get_project_name_by_id($estimate->project_id);
        $fields['{estimate_short_url}']    = get_estimate_shortlink($estimate);

        $custom_fields = get_custom_fields('estimate');
        foreach ($custom_fields as $field) {
            $fields['{' . $field['slug'] . '}'] = get_custom_field_value($estimate_id, $field['id'], 'estimate');
        }

        return hooks()->apply_filters('estimate_merge_fields', $fields, [
        'id'       => $estimate_id,
        'estimate' => $estimate,
     ]);
    }
}
