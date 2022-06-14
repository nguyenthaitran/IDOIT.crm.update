<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Other_merge_fields extends App_merge_fields
{
    public function build()
    {
        $available_for = [
                        'ticket',
                        'client',
                        'staff',
                        'invoice',
                        'estimate',
                        'contract',
                        'tasks',
                        'proposals',
                        'project',
                        'leads',
                        'credit_note',
                        'subscriptions',
                        'gdpr',
                        'estimate_request'
                    ];

        $available_for = hooks()->apply_filters('other_merge_fields_available_for', $available_for);

        return [
                [
                    'name'        => 'Liên kết File Logo',
                    'key'         => '{logo_url}',
                    'fromoptions' => true,
                    'available'   => $available_for,
                ],
                [
                    'name'        => 'Logo',
                    'key'         => '{logo_image_with_url}',
                    'fromoptions' => true,
                    'available'   => $available_for,
                ],
                [
                    'name'        => 'Logo nền tối',
                    'key'         => '{dark_logo_image_with_url}',
                    'fromoptions' => true,
                    'available'   => $available_for,
                ],
                [
                    'name'        => 'Liên kết tới T2CRM',
                    'key'         => '{crm_url}',
                    'fromoptions' => true,
                    'available'   => $available_for,
                ],
                [
                    'name'        => 'Liên kết quản trị',
                    'key'         => '{admin_url}',
                    'fromoptions' => true,
                    'available'   => $available_for,
                ],
                [
                    'name'        => 'Tên miền chính',
                    'key'         => '{main_domain}',
                    'fromoptions' => true,
                    'available'   => $available_for,
                ],
                [
                    'name'        => 'Công ty',
                    'key'         => '{companyname}',
                    'fromoptions' => true,
                    'available'   => $available_for,
                ],
                [
                    'name'        => 'Chữ ký email',
                    'key'         => '{email_signature}',
                    'fromoptions' => true,
                    'available'   => $available_for,
                ],
                [
                    'name'        => 'Liên kết tới điều khoản dịch vụ',
                    'key'         => '{terms_and_conditions_url}',
                    'fromoptions' => true,
                    'available'   => $available_for,
                ],
                [
                    'name'        => 'Liên kết tới chính sách riêng tư',
                    'key'         => '{privacy_policy_url}',
                    'fromoptions' => true,
                    'available'   => $available_for,
                ],
            ];
    }

    public function format()
    {
        $fields               = [];
        $fields['{logo_url}'] = base_url('uploads/company/' . get_option('company_logo'));

        $logo_width = hooks()->apply_filters('merge_field_logo_img_width', '');

        $fields['{logo_image_with_url}'] = '<a href="' . site_url() . '" target="_blank"><img src="' . base_url('uploads/company/' . get_option('company_logo')) . '"' . ($logo_width != '' ? ' width="' . $logo_width . '"' : '') . '></a>';

        $fields['{dark_logo_image_with_url}'] = '';
        if (get_option('company_logo_dark') != '') {
            $fields['{dark_logo_image_with_url}'] = '<a href="' . site_url() . '" target="_blank"><img src="' . base_url('uploads/company/' . get_option('company_logo_dark')) . '"' . ($logo_width != '' ? ' width="' . $logo_width . '"' : '') . '></a>';
        }

        $fields['{crm_url}']     = rtrim(site_url(), '/');
        $fields['{admin_url}']   = admin_url();
        $fields['{main_domain}'] = get_option('main_domain');
        $fields['{companyname}'] = get_option('companyname');

        if (!is_staff_logged_in() || is_client_logged_in()) {
            $fields['{email_signature}'] = get_option('email_signature');
        } else {
            $this->ci->db->select('email_signature')->from(db_prefix().'staff')->where('staffid', get_staff_user_id());
            $signature = $this->ci->db->get()->row()->email_signature;
            if (empty($signature)) {
                $fields['{email_signature}'] = get_option('email_signature');
            } else {
                $fields['{email_signature}'] = $signature;
            }
        }

        if(!is_html($fields['{email_signature}'])) {
            $fields['{email_signature}'] = nl2br($fields['{email_signature}']);
        }

        $fields['{terms_and_conditions_url}'] = terms_url();
        $fields['{privacy_policy_url}']       = privacy_policy_url();


        return hooks()->apply_filters('other_merge_fields', $fields);
    }
}
