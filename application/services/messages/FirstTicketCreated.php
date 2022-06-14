<?php

namespace app\services\messages;

defined('BASEPATH') or exit('No direct script access allowed');

use app\services\messages\AbstractPopupMessage;

class FirstTicketCreated extends AbstractPopupMessage
{
    public function isVisible(...$params)
    {
        $ticket_id = $params[0];

        return $ticket_id == 1;
    }

    public function getMessage(...$params)
    {
        return 'Yêu cầu hỗ trợ đầu tiên được tạo! <br /> <span style="font-size:26px;">Bạn cũng có thể lấy form yêu cầu hỗ trợ để nhúng vào trang web khác bằng cách vào (Hệ thống -> Cài đặt -> Hỗ trợ khách hàng -> Nhúng form yêu cầu vào web).</span>';
    }
}
