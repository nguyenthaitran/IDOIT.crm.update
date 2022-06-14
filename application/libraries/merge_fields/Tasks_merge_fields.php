<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Tasks_merge_fields extends App_merge_fields
{
    public function build()
    {
        return [
                [
                    'name'      => 'Nhân viên/Liên hệ thực hiện công việc này',
                    'key'       => '{task_user_take_action}',
                    'available' => [
                        'tasks',
                    ],
                ],
                [
                    'name'      => 'Liên kết xem công việc',
                    'key'       => '{task_link}',
                    'available' => [
                        'tasks',
                    ],
                ],
                [
                    'name'      => 'Liên kết bình luận',
                    'key'       => '{comment_link}',
                    'available' => [
                    ],
                    'templates' => [
                        'task-commented',
                        'task-commented-to-contacts',
                    ],
                ],
                [
                    'name'      => 'Tên công việc',
                    'key'       => '{task_name}',
                    'available' => [
                        'tasks',
                    ],
                ],
                [
                    'name'      => 'Mô tả công việc',
                    'key'       => '{task_description}',
                    'available' => [
                        'tasks',
                    ],
                ],
                [
                    'name'      => 'Tình trạng công việc',
                    'key'       => '{task_status}',
                    'available' => [
                        'tasks',
                    ],
                ],
                [
                    'name'      => 'Bình luận công việc',
                    'key'       => '{task_comment}',
                    'available' => [

                    ],
                    'templates' => [
                        'task-commented',
                        'task-commented-to-contacts',
                    ],
                ],
                [
                    'name'      => 'Mức độ ưu tiên',
                    'key'       => '{task_priority}',
                    'available' => [
                        'tasks',
                    ],
                ],
                [
                    'name'      => 'Ngày bắt đầu',
                    'key'       => '{task_startdate}',
                    'available' => [
                        'tasks',
                    ],
                ],
                [
                    'name'      => 'Hạn hoàn thành',
                    'key'       => '{task_duedate}',
                    'available' => [
                        'tasks',
                    ],
                ],
                [
                    'name'      => 'Liên quan đến',
                    'key'       => '{task_related}',
                    'available' => [
                        'tasks',
                    ],
                ],
            ];
    }

    /**
     * Merge fields for tasks
     * @param  mixed  $task_id         task id
     * @param  boolean $client_template is client template or staff template
     * @return array
     */
    public function format($task_id, $client_template = false)
    {
        $fields = [];

        $this->ci->db->where('id', $task_id);
        $task = $this->ci->db->get(db_prefix() . 'tasks')->row();

        if (!$task) {
            return $fields;
        }

        // Client templateonly passed when sending to tasks related to project and sending email template to contacts
        // Passed from tasks_model  _send_task_responsible_users_notification function
        if ($client_template == false) {
            $fields['{task_link}'] = admin_url('tasks/view/' . $task_id);
        } else {
            $fields['{task_link}'] = site_url('clients/project/' . $task->rel_id . '?group=project_tasks&taskid=' . $task_id);
        }

        if (is_client_logged_in()) {
            $fields['{task_user_take_action}'] = get_contact_full_name(get_contact_user_id());
        } else {
            $fields['{task_user_take_action}'] = get_staff_full_name(get_staff_user_id());
        }

        $fields['{task_comment}'] = '';
        $fields['{task_related}'] = '';
        $fields['{project_name}'] = '';

        if ($task->rel_type == 'project') {
            $this->ci->db->select('name, clientid');
            $this->ci->db->from(db_prefix() . 'projects');
            $this->ci->db->where('id', $task->rel_id);
            $project = $this->ci->db->get()->row();
            if ($project) {
                $fields['{project_name}'] = $project->name;
            }
        }

        if (!empty($task->rel_id)) {
            $rel_data                 = get_relation_data($task->rel_type, $task->rel_id);
            $rel_values               = get_relation_values($rel_data, $task->rel_type);
            $fields['{task_related}'] = $rel_values['name'];
        }

        $fields['{task_name}']        = $task->name;
        $fields['{task_description}'] = $task->description;

        $languageChanged = false;

        // The tasks status may not be translated if the client language is not loaded
        if (!is_client_logged_in()
        && $task->rel_type == 'project'
        && $project
        && isset($GLOBALS['SENDING_EMAIL_TEMPLATE_CLASS'])
        && !$GLOBALS['SENDING_EMAIL_TEMPLATE_CLASS']->get_staff_id() // email to client
    ) {
            load_client_language($project->clientid);
            $languageChanged = true;
        } else {
            if (isset($GLOBALS['SENDING_EMAIL_TEMPLATE_CLASS'])) {
                $sending_to_staff_id = $GLOBALS['SENDING_EMAIL_TEMPLATE_CLASS']->get_staff_id();
                if ($sending_to_staff_id) {
                    load_admin_language($sending_to_staff_id);
                    $languageChanged = true;
                }
            }
        }

        $fields['{task_status}']   = format_task_status($task->status, false, true);
        $fields['{task_priority}'] = task_priority($task->priority);

        $custom_fields = get_custom_fields('tasks');
        foreach ($custom_fields as $field) {
            $fields['{' . $field['slug'] . '}'] = get_custom_field_value($task_id, $field['id'], 'tasks');
        }

        if (!is_client_logged_in() && $languageChanged) {
            load_admin_language();
        } elseif (is_client_logged_in() && $languageChanged) {
            load_client_language();
        }

        $fields['{task_startdate}'] = _d($task->startdate);
        $fields['{task_duedate}']   = _d($task->duedate);
        $fields['{comment_link}']   = '';

        $this->ci->db->where('taskid', $task_id);
        $this->ci->db->limit(1);
        $this->ci->db->order_by('dateadded', 'desc');
        $comment = $this->ci->db->get(db_prefix() . 'task_comments')->row();

        if ($comment) {
            $fields['{task_comment}'] = $comment->content;
            $fields['{comment_link}'] = $fields['{task_link}'] . '#comment_' . $comment->id;
        }

        return hooks()->apply_filters('task_merge_fields', $fields, [
        'id'              => $task_id,
        'task'            => $task,
        'client_template' => $client_template,
     ]);
    }
}
