<?php

namespace app\services\messages;

defined('BASEPATH') or exit('No direct script access allowed');

use app\services\messages\AbstractMessage;

class CronJobFailure extends AbstractMessage
{
    protected $alertClass = 'warning';

    private $hoursCheck = 48;

    public function isVisible()
    {
        $last_cron_run = get_option('last_cron_run');
        $fromCli       = get_option('cron_has_run_from_cli');

        return ($last_cron_run != '' && $fromCli == '1' && is_admin()) && ($last_cron_run <= strtotime('-' . $this->hoursCheck . ' hours'));
    }

    public function getMessage()
    {
        // Check and clean locks for all cases if the cron somehow is stuck or locked
        if (file_exists(get_temp_dir() . 'pcrm-cron-lock')) {
            @unlink(get_temp_dir() . 'pcrm-cron-lock');
        }

        if (file_exists(TEMP_FOLDER . 'pcrm-cron-lock')) {
            @unlink(TEMP_FOLDER . 'pcrm-cron-lock');
        } ?>
        <h4><b>Cảnh báo Cron Job</b></h4>
        <hr class="hr-10" />
        <p>
         <b>Có vẻ như cron job của bạn đã không chạy trong <?php echo $this->hoursCheck; ?> giờ qua</b>, bạn nên kiểm tra lại xem cron job của mình có được cấu hình đúng hay không, thông báo này sẽ tự động biến mất sau 5 phút sau khi cron job bắt đầu hoạt động bình thường trở lại.
     </p>
     <?php
    }
}
