<?php

namespace app\services\messages;

defined('BASEPATH') or exit('No direct script access allowed');

use app\services\messages\AbstractPopupMessage;

class ReOrderTaskChecklistItems extends AbstractPopupMessage
{
    public function isVisible(...$params)
    {
        $item_id = $params[0]['checklist_id'];
        $task_id = $params[0]['task_id'];

        return ($task_id == 1 || $task_id == 2 || $task_id == 3 || $task_id == 4 || $task_id == 5) && $item_id == 8;
    }

    public function getMessage(...$params)
    {
        return 'Có vẻ như bạn đang thích tạo các mục trong danh sách kiểm tra công việc, bạn có biết rằng bạn có thể dễ dàng sắp xếp lại các mục bằng cách kéo chúng lên trên hoặc xuống dưới không?';
    }
}
