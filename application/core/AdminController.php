<?php

defined('BASEPATH') or exit('No direct script access allowed');

class AdminController extends App_Controller
{
    public function __construct()
    {
        parent::__construct();

        if ($this->app->is_db_upgrade_required($this->current_db_version)) {
            if ($this->input->post('upgrade_database')) {
                hooks()->do_action('pre_upgrade_database');

                $this->app->upgrade_database();
            }

            die(include_once(VIEWPATH . 'admin/includes/db_update_required.php'));
        }

        hooks()->do_action('pre_admin_init');

        if (!is_staff_logged_in()) {
            if (strpos(current_full_url(), get_admin_uri() . '/authentication') === false) {
                redirect_after_login_to_current_url();
            }

            redirect(admin_url('authentication'));
        }

        if ($this->uri->segment(3) != 'notifications_check') {
            // In case staff have setup logged in as client - This is important don't change it
            foreach (['client_user_id', 'contact_user_id', 'client_logged_in', 'logged_in_as_client'] as $sk) {
                if ($this->session->has_userdata($sk)) {
                    $this->session->unset_userdata($sk);
                }
            }
        }

        // Update staff last activity
        $this->db->where('staffid', get_staff_user_id());
        $this->db->update('staff', ['last_activity' => date('Y-m-d H:i:s')]);

        $this->load->model('staff_model');

        // Do not check on ajax requests
        if (!$this->input->is_ajax_request()) {
            if (ENVIRONMENT == 'production' && is_admin()) {
                if ($this->config->item('encryption_key') === '') {
                    die('<h1>Encryption key not sent in application/config/app-config.php</h1>For more info visit <a href="https://help.perfexcrm.com/encryption-key-explained/">encryption key explained</a>');
                } elseif (strlen($this->config->item('encryption_key')) != 32) {
                    die('<h1>Encryption key length should be 32 charachters</h1>For more info visit <a href="https://help.perfexcrm.com/encryption-key-explained/">encryption key explained</a>');
                }
            }

            _maybe_system_setup_warnings();

            $this->init_quick_actions_links();
        }

        $currentUser = $this->staff_model->get(get_staff_user_id());

        // Deleted or inactive but have session
        if (!$currentUser || $currentUser->active == 0) {
            $this->authentication_model->logout();
            redirect(admin_url('authentication'));
        }

        $GLOBALS['current_user'] = $currentUser;

        init_admin_assets();

        hooks()->do_action('admin_init');

        $vars = [
            'current_user'    => $currentUser,
            'current_version' => $this->current_db_version,
            'task_statuses'   => $this->tasks_model->get_statuses(),
        ];

        if (!$this->input->is_ajax_request()) {
            $vars['sidebar_menu'] = $this->app_menu->get_sidebar_menu_items();
            $vars['setup_menu']   = $this->app_menu->get_setup_menu_items();
        }

        /**
         * Autoloaded view variables
         * @var array
         */
        $vars = hooks()->apply_filters('admin_area_auto_loaded_vars', $vars);
        $this->load->vars($vars);
    }

    private function init_quick_actions_links()
    {	
		$this->app->add_quick_actions_link([
            'name'            => _l('t2link_lead'),
            'url'             => '#',
            'custom_url'      => true,
            'permission'      => 'is_staff_member',
            'href_attributes' => [
                'onclick' => 'init_lead(); return false;',
                ],
            'position' => 10,
            ]);
			
        $this->app->add_quick_actions_link([
            'name'       => _l('t2link_client'),
            'permission' => 'customers',
            'url'        => 'clients/client',
            'position'   => 20,
            ]);

        $this->app->add_quick_actions_link([
            'name'       => _l('t2link_proposal'),
            'permission' => 'proposals',
            'url'        => 'proposals/proposal',
            'position'   => 30,
            ]);

        $this->app->add_quick_actions_link([
            'name'       => _l('t2link_estimate'),
            'permission' => 'estimates',
            'url'        => 'estimates/estimate',
            'position'   => 40,
            ]);
			
        $this->app->add_quick_actions_link([
            'name'       => _l('t2link_invoice'),
            'permission' => 'invoices',
            'url'        => 'invoices/invoice',
            'position'   => 50,
            ]);
			
        $this->app->add_quick_actions_link([
            'name'       => _l('t2link_project'),
            'url'        => 'projects/project',
            'permission' => 'projects',
            'position'   => 60,
            ]);

        $this->app->add_quick_actions_link([
            'name'            => _l('t2link_task'),
            'url'             => '#',
            'custom_url'      => true,
            'href_attributes' => [
                'onclick' => 'new_task();return false;',
                ],
            'permission' => 'tasks',
            'position'   => 70,
            ]);

        $this->app->add_quick_actions_link([
            'name'       => _l('t2link_contract'),
            'permission' => 'contracts',
            'url'        => 'contracts/contract',
            'position'   => 80,
            ]);
			
        $tickets = [
            'name'     => _l('t2link_ticket'),
            'url'      => 'tickets/add',
            'position' => 90,
            ];

        if (get_option('access_tickets_to_none_staff_members') == 0 && !is_staff_member()) {
            $tickets['permission'] = 'is_staff_member';
        }
        $this->app->add_quick_actions_link($tickets);

        $this->app->add_quick_actions_link([
            'name'       => _l('t2link_staff_member'),
            'url'        => 'staff/member',
            'permission' => 'staff',
            'position'   => 100,
            ]);

        $this->app->add_quick_actions_link([
            'name'       => _l('t2link_expense'),
            'permission' => 'expenses',
            'url'        => 'bd_expenses/expense',
            'position'   => 110,
            ]);
		
		$this->app->add_quick_actions_link([
            'name'       => _l('kb_article'),
            'permission' => 'knowledge_base',
            'url'        => 'knowledge_base/article',
            'position'   => 120,
            ]);
    }
}
