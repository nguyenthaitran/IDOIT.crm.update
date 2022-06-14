<?php

namespace app\services\messages;

defined('BASEPATH') or exit('No direct script access allowed');

use app\services\messages\AbstractPopupMessage;

class FirstTagCreated extends AbstractPopupMessage
{
    public function isVisible(...$params)
    {
        $tag_id = $params[0];

        return $tag_id == 1;
    }

    public function getMessage(...$params)
    {
        return 'Chúc mừng! Bạn đã tạo nhãn đầu tiên! <br /> Bạn có thể quản trị các nhãn đã thêm vào hệ thống tại đây: Hệ thống -> Cài đặt -> Gắn nhãn';
    }
}
