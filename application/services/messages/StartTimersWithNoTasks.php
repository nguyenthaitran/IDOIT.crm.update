<?php

namespace app\services\messages;

defined('BASEPATH') or exit('No direct script access allowed');

use app\services\messages\AbstractPopupMessage;

class StartTimersWithNoTasks extends AbstractPopupMessage
{
    public function isVisible(...$params)
    {
        $task_id  = $params[0]['task_id'];
        $timer_id = $params[0]['timer_id'];

        return $task_id != '0' && $timer_id == 1;
    }

    public function getMessage(...$params)
    {
        return 'Chấm công đầu tiên đã bắt đầu!<br />
            <span style="font-size:26px;">Bạn có biết rằng bạn có thể bắt đầu hẹn giờ mà không cần tác vụ và chỉ định bộ hẹn giờ cho tác vụ sau đó không?</span>';
    }
}
