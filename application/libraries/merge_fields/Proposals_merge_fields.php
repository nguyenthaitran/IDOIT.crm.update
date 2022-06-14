<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Proposals_merge_fields extends App_merge_fields
{
    public function build()
    {
        return [
                [
                    'name'      => 'Mã đề xuất',
                    'key'       => '{proposal_id}',
                    'available' => [
                        'proposals',
                    ],
                ],
                [
                    'name'      => 'Số đề xuất',
                    'key'       => '{proposal_number}',
                    'available' => [
                        'proposals',
                    ],
                ],
                [
                    'name'      => 'Tiêu đề',
                    'key'       => '{proposal_subject}',
                    'available' => [
                        'proposals',
                    ],
                ],
                [
                    'name'      => 'Giá trị đề xuất',
                    'key'       => '{proposal_total}',
                    'available' => [
                        'proposals',
                    ],
                ],
                [
                    'name'      => 'Tổng giá trị đề xuất',
                    'key'       => '{proposal_subtotal}',
                    'available' => [
                        'proposals',
                    ],
                ],
                [
                    'name'      => 'Hiệu lực đến',
                    'key'       => '{proposal_open_till}',
                    'available' => [
                        'proposals',
                    ],
                ],
                [
                    'name'      => 'Chỉ định đề xuất',
                    'key'       => '{proposal_assigned}',
                    'available' => [
                        'proposals',
                    ],
                ],
                [
                    'name'      => 'Phân công đề xuất',
                    'key'       => '{proposal_proposal_to}',
                    'available' => [
                        'proposals',
                    ],
                ],
                [
                    'name'      => 'Địa chỉ',
                    'key'       => '{proposal_address}',
                    'available' => [
                        'proposals',
                    ],
                ],
                [
                    'name'      => 'Quận/Huyện',
                    'key'       => '{proposal_city}',
                    'available' => [
                        'proposals',
                    ],
                ],
                [
                    'name'      => 'Tỉnh/Thành phố',
                    'key'       => '{proposal_state}',
                    'available' => [
                        'proposals',
                    ],
                ],
                [
                    'name'      => 'Mã bưu chính',
                    'key'       => '{proposal_zip}',
                    'available' => [
                        'proposals',
                    ],
                ],
                [
                    'name'      => 'Quốc gia',
                    'key'       => '{proposal_country}',
                    'available' => [
                        'proposals',
                    ],
                ],
                [
                    'name'      => 'Email',
                    'key'       => '{proposal_email}',
                    'available' => [
                        'proposals',
                    ],
                ],
                [
                    'name'      => 'Điện thoại',
                    'key'       => '{proposal_phone}',
                    'available' => [
                        'proposals',
                    ],
                ],
                [
                    'name'      => 'Liên kết xem đề xuất',
                    'key'       => '{proposal_link}',
                    'available' => [
                        'proposals',
                    ],
                ],
            ];
    }

    /**
 * Merge fields for proposals
 * @param  mixed $proposal_id proposal id
 * @return array
 */
    public function format($proposal_id)
    {
        $fields = [];
        $this->ci->db->where('id', $proposal_id);
        $this->ci->db->join(db_prefix() . 'countries', db_prefix() . 'countries.country_id=' . db_prefix() . 'proposals.country', 'left');
        $proposal = $this->ci->db->get(db_prefix() . 'proposals')->row();


        if (!$proposal) {
            return $fields;
        }

        if ($proposal->currency != 0) {
            $currency = get_currency($proposal->currency);
        } else {
            $currency = get_base_currency();
        }

        $fields['{proposal_id}']          = $proposal_id;
        $fields['{proposal_number}']      = format_proposal_number($proposal_id);
        $fields['{proposal_link}']        = site_url('proposal/' . $proposal_id . '/' . $proposal->hash);
        $fields['{proposal_subject}']     = $proposal->subject;
        $fields['{proposal_total}']       = app_format_money($proposal->total, $currency);
        $fields['{proposal_subtotal}']    = app_format_money($proposal->subtotal, $currency);
        $fields['{proposal_open_till}']   = _d($proposal->open_till);
        $fields['{proposal_proposal_to}'] = $proposal->proposal_to;
        $fields['{proposal_address}']     = $proposal->address;
        $fields['{proposal_email}']       = $proposal->email;
        $fields['{proposal_phone}']       = $proposal->phone;

        $fields['{proposal_city}']        = $proposal->city;
        $fields['{proposal_state}']       = $proposal->state;
        $fields['{proposal_zip}']         = $proposal->zip;
        $fields['{proposal_country}']     = $proposal->short_name;
        $fields['{proposal_assigned}']    = get_staff_full_name($proposal->assigned);
        $fields['{proposal_short_url}']   = get_proposal_shortlink($proposal);

        $custom_fields = get_custom_fields('proposal');
        foreach ($custom_fields as $field) {
            $fields['{' . $field['slug'] . '}'] = get_custom_field_value($proposal_id, $field['id'], 'proposal');
        }

        return hooks()->apply_filters('proposal_merge_fields', $fields, [
        'id'       => $proposal_id,
        'proposal' => $proposal,
     ]);
    }
}
