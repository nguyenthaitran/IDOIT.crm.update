<?php

namespace app\services\messages;

defined('BASEPATH') or exit('No direct script access allowed');

use app\services\messages\AbstractPopupMessage;

class FirstLeadCreated extends AbstractPopupMessage
{
    public function isVisible(...$params)
    {
        $lead_id = $params[0];

        return $lead_id == 1;
    }

    public function getMessage(...$params)
    {
        return 'Cơ hội đầu tiên được tạo! <br /> <span style="font-size:26px;">Bạn cũng có thể lấy form cơ hội để nhúng vào trang web khác bằng cách vào (Hệ thống -> Cơ hội -> Tích hợp trang web).</span>';
    }
}
