<?php

namespace app\services\messages;

defined('BASEPATH') or exit('No direct script access allowed');

use app\services\messages\AbstractMessage;

class IsCronSetupRequired extends AbstractMessage
{
    private $used_features = [];

    public function isVisible()
    {
        if (get_option('cron_has_run_from_cli') == 1 || !is_admin()) {
            return false;
        }

        $used_features       = [];
        $using_cron_features = 0;
        $feature             = total_rows(db_prefix() . 'reminders');
        $using_cron_features += $feature;
        if ($feature > 0) {
            array_push($used_features, 'Nhắc nhở');
        }

        $feature = get_option('email_queue_enabled');
        $using_cron_features += $feature;
        if ($feature == 1) {
            array_push($used_features, 'Gửi email theo hàng đợi');
        }

        $feature = total_rows(db_prefix() . 'leads_email_integration', [
                'active' => 1,
            ]);
        $using_cron_features += $feature;

        if ($feature > 0) {
            array_push($used_features, 'Tự động nhập khách hàng tiềm năng từ email.');
        }
        $feature = total_rows(db_prefix() . 'invoices', [
                'recurring >' => 0,
            ]);
        $using_cron_features += $feature;
        if ($feature > 0) {
            array_push($used_features, 'Hóa đơn định kỳ');
        }
        $feature = total_rows(db_prefix() . 'expenses', [
                'recurring' => 1,
            ]);
        $using_cron_features += $feature;
        if ($feature > 0) {
            array_push($used_features, 'Chi phí định kỳ');
        }

        $feature = total_rows(db_prefix() . 'scheduled_emails');
        $using_cron_features += $feature;
        if ($feature > 0) {
            array_push($used_features, 'Gửi hóa đơn đã lên lịch');
        }

        $feature = total_rows(db_prefix() . 'tasks', [
                'recurring' => 1,
            ]);
        $using_cron_features += $feature;
        if ($feature > 0) {
            array_push($used_features, 'Công việc lặp lại');
        }

        $feature = total_rows(db_prefix() . 'events');
        $using_cron_features += $feature;

        if ($feature > 0) {
            array_push($used_features, 'Tùy chỉnh Lịch sự kiện');
        }

        $feature = total_rows(db_prefix() . 'departments', [
                'host !='     => '',
                'password !=' => '',
                'email !='    => '',
            ]);
        $using_cron_features += $feature;
        if ($feature > 0) {
            array_push($used_features, 'Nhập yêu cầu hỗ trợ từ email thông qua IMAP (Hệ thống -> Hỗ trợ khách hàng -> Danh sách phòng ban)');
        }

        $using_cron_features = hooks()->apply_filters('numbers_of_features_using_cron_job', $using_cron_features);
        $used_features       = hooks()->apply_filters('used_cron_features', $used_features);
        $this->used_features = $used_features;

        return $using_cron_features > 0 && get_option('hide_cron_is_required_message') == 0;
    }

    public function getMessage()
    {
        $html = '';
        $html .= 'Bạn đang sử dụng một số tính năng yêu cầu thiết lập cron job để hoạt động bình thường.';
        $html .= '<br />Vui lòng làm theo hướng dẫn thiết lập cron để tất cả các tính năng hoạt động tốt.';
        $html .= '<br />';
        $html .= '<p class="bold">Bạn đang sử dụng các tính năng sau mà yêu cầu thiết lập Công việc CRON:</p>';
        $i = 1;
        foreach ($this->used_features as $feature) {
            $html .= '&nbsp;' . $i . '. ' . $feature . '<br />';
            $i++;
        }
        $html .= '<a href="' . admin_url('misc/dismiss_cron_setup_message') . '" class="alert-link">Không hiển thị lại thông báo này.</a>';

        return $html;
    }
}
