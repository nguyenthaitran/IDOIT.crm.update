<?php

namespace app\services\messages;

defined('BASEPATH') or exit('No direct script access allowed');

use app\services\messages\AbstractMessage;

class ModSecurityEnabled extends AbstractMessage
{
    protected $alertClass = 'warning';

    public function isVisible()
    {
        if (!function_exists('apache_get_modules')) {
            return false;
        }

        $modules = @apache_get_modules();

        return is_array($modules) && in_array('mod_security', $modules) && is_admin();
    }

    public function getMessage()
    {
        ?>
        <h4><b>Cảnh báo Mod Security</b></h4>
        <hr class="hr-10" />
        <p>
             Mod Security được phát hiện trên máy chủ của bạn, bạn nên liên hệ với nhà cung cấp dịch vụ lưu trữ để vô hiệu hóa bảo mật mod khi cài đặt vì rất có thể bạn sẽ gặp sự cố khi cập nhật các mẫu email, gửi e.q. hóa đơn đến email, vv ... Các mô-đun PHP bảo mật mod trong hầu hết các trường hợp sẽ chặn dữ liệu này nhưng yêu cầu sẽ chứa HTML.
        </p>
        <?php
    }
}
