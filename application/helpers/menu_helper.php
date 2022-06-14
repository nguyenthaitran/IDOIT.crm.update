<?php
defined('BASEPATH') or exit('No direct script access allowed');

function app_init_admin_sidebar_menu_items()
{
    $CI = &get_instance();	
//BẢNG TIN
    $CI->app_menu->add_sidebar_menu_item('dashboard', [
        'name'     => _l('als_dashboard'),
        'href'     => admin_url(),
        'position' => 10,
        'icon'     => 'fa fa-dashboard',
    ]);
//END BẢNG TIN

//CƠ HỘI
	if (is_staff_member()) {
        $CI->app_menu->add_sidebar_menu_item('leads', [
			'name'     => _l('leads'),
            'href'     => admin_url('leads'),
            'position' => 20,
			'icon'     => 'fa fa-volume-control-phone',
        ]);
    }
//END CƠ HỘI

//KHÁCH HÀNG
	if (
        has_permission('customers', '', 'view')
        || (have_assigned_customers()
            || (!have_assigned_customers() && has_permission('customers', '', 'create')))
    ) {
        $CI->app_menu->add_sidebar_menu_item('customers', [
			'name'     => _l('als_clients'),
			'href'     => admin_url('clients'),
			'position' => 30,
			'icon'     => 'fa fa-user-circle',
		]);
    }
//END KHÁCH HÀNG

//BÁN HÀNG
    $CI->app_menu->add_sidebar_menu_item('sales', [
        'collapse' => true,
        'name'     => _l('als_sales'),
        'position' => 40,
        'icon'     => 'fa fa-cart-plus',
    ]);

    if ((has_permission('proposals', '', 'view') || has_permission('proposals', '', 'view_own'))
        || (staff_has_assigned_proposals() && get_option('allow_staff_view_proposals_assigned') == 1)
    ) {
        $CI->app_menu->add_sidebar_children_item('sales', [
            'slug'     => 'proposals',
            'name'     => _l('proposals'),
            'href'     => admin_url('proposals'),
            'position' => 5,
        ]);
    }

    if ((has_permission('estimates', '', 'view') || has_permission('estimates', '', 'view_own'))
        || (staff_has_assigned_estimates() && get_option('allow_staff_view_estimates_assigned') == 1)
    ) {
        $CI->app_menu->add_sidebar_children_item('sales', [
            'slug'     => 'estimates',
            'name'     => _l('estimates'),
            'href'     => admin_url('estimates'),
            'position' => 10,
        ]);
    }

    if ((has_permission('invoices', '', 'view') || has_permission('invoices', '', 'view_own'))
        || (staff_has_assigned_invoices() && get_option('allow_staff_view_invoices_assigned') == 1)
    ) {
        $CI->app_menu->add_sidebar_children_item('sales', [
            'slug'     => 'invoices',
            'name'     => _l('invoices'),
            'href'     => admin_url('invoices'),
            'position' => 15,
        ]);
    }

    if (has_permission('items', '', 'view')) {
        $CI->app_menu->add_sidebar_children_item('sales', [
            'slug'     => 'items',
            'name'     => _l('items'),
            'href'     => admin_url('invoice_items'),
            'position' => 20,
        ]);
    }

    if (
        has_permission('payments', '', 'view') || has_permission('invoices', '', 'view_own')
        || (get_option('allow_staff_view_invoices_assigned') == 1 && staff_has_assigned_invoices())
    ) {
        $CI->app_menu->add_sidebar_children_item('sales', [
            'slug'     => 'payments',
            'name'     => _l('payments'),
            'href'     => admin_url('payments'),
            'position' => 25,
        ]);
    }
	
	if (has_permission('credit_notes', '', 'view') || has_permission('credit_notes', '', 'view_own')) {
        $CI->app_menu->add_sidebar_children_item('sales', [
            'slug'     => 'credit_notes',
            'name'     => _l('credit_notes'),
            'href'     => admin_url('credit_notes'),
            'position' => 30,
        ]);
    }
//END BÁN HÀNG
	
//CÔNG VIỆC
    $CI->app_menu->add_sidebar_menu_item('t2tasks', [
        'name'     => _l('t2tasks'),
        'collapse' => true,
        'icon'     => 'fa fa-trello',
		'position' => 50,
    ]);
	$CI->app_menu->add_sidebar_children_item('t2tasks', [
        'slug'     => 't2tasks-projects',
		'name'     => _l('projects'),
        'href'     => admin_url('projects'),
        'position' => 5,
    ]);
	$CI->app_menu->add_sidebar_children_item('t2tasks', [
        'slug'     => 't2tasks-tasks',
		'name'     => _l('als_tasks'),
        'href'     => admin_url('tasks'),
        'position' => 10,
    ]);
	if (has_permission('contracts', '', 'view') || has_permission('contracts', '', 'view_own')) {
		$CI->app_menu->add_sidebar_children_item('t2tasks', [
			'slug'     => 't2tasks-contracts',
			'name'     => _l('contracts'),
			'href'     => admin_url('contracts'),
			'position' => 15,
		]);
	}
	$CI->app_menu->add_sidebar_children_item('t2tasks', [
        'slug'     => 't2tasks-support',
		'name'     => _l('support'),
        'href'     => admin_url(tickets),
        'position' => 20,
    ]);
	if ((has_permission('estimate_request', '', 'view') || has_permission('estimate_request', '', 'view_own'))) {
        $CI->app_menu->add_sidebar_children_item('t2tasks', [
            'slug'     => 't2tasks-estimate-request',
			'name'     => _l('estimate_request'),
            'href'     => admin_url('estimate_request'),
            'position' => 25,
        ]);
    }
//END CÔNG VIỆC
	
//NHÂN SỰ
    $CI->app_menu->add_sidebar_menu_item('t2hrm', [
		'name'     => _l('t2hrm'),
        'collapse' => true,
        'position' => 60,
        'icon'     => 'fa fa-address-book-o',
    ]);
	if (is_staff_member()) {
		$CI->app_menu->add_sidebar_children_item('t2hrm', [
			'slug'     => 'timesheets-reports',
			'name'     => _l('timesheets_overview'),
			'href'     => admin_url('staff/timesheets'),
			'position' => 5,
		]);
	}	
	if (is_admin()) {
		$CI->app_menu->add_sidebar_children_item('t2hrm', [
			'slug'     => 't2hrm-staff',
			'name'     => _l('t2hrm_staff'),
			'href'     => admin_url('staff'),
			'position' => 10,
		]);
	}
	if (is_admin()) {
		$CI->app_menu->add_sidebar_children_item('t2hrm', [
			'slug'     => 't2hrm-departments',
			'name'     => _l('t2hrm_departments'),
			'href'     => admin_url('departments'),
			'position' => 15,
		]);
	}
//END NHÂN SỰ
	
//TÀI CHÍNH
    $CI->app_menu->add_sidebar_menu_item('t2finance', [
        'name'     => _l('t2finance'),
        'collapse' => true,
        'position' => 70,
        'icon'     => 'fa fa-credit-card',
    ]);
//END TÀI CHÍNH

//TIỆN ÍCH
    $CI->app_menu->add_sidebar_menu_item('utilities', [
        'collapse' => true,
        'name'     => _l('als_utilities'),
        'icon'     => 'fa fa-database',
        'position' => 80,
    ]);
    $CI->app_menu->add_sidebar_children_item('utilities', [
        'slug'     => 'media',
        'name'     => _l('als_media'),
        'href'     => admin_url('utilities/media'),
        'position' => 5,
    ]);
    $CI->app_menu->add_sidebar_children_item('utilities', [
        'slug'     => 'calendar',
        'name'     => _l('als_calendar_submenu'),
        'href'     => admin_url('utilities/calendar'),
        'position' => 10,
    ]);
	if (has_permission('knowledge_base', '', 'view')) {
        $CI->app_menu->add_sidebar_children_item('utilities', [
            'slug'     => 'knowledgebase',
			'name'     => _l('als_kb'),
            'href'     => admin_url('knowledge_base'),
            'position' => 15,
        ]);
    }
    if (is_admin()) {
        $CI->app_menu->add_sidebar_children_item('utilities', [
            'slug'     => 'announcements',
            'name'     => _l('als_announcements_submenu'),
            'href'     => admin_url('announcements'),
            'position' => 20,
        ]);
//        $CI->app_menu->add_sidebar_children_item('utilities', [
//            'slug'     => 'activity-log',
//            'name'     => _l('als_activity_log_submenu'),
//            'href'     => admin_url('utilities/activity_log'),
//            'position' => 21,
//        ]);
    }
    if (has_permission('bulk_pdf_exporter', '', 'view')) {
        $CI->app_menu->add_sidebar_children_item('utilities', [
            'slug'     => 'bulk-pdf-exporter',
            'name'     => _l('bulk_pdf_exporter'),
            'href'     => admin_url('utilities/bulk_pdf_exporter'),
            'position' => 25,
        ]);
    }
//END TIỆN ÍCH

//BÁO CÁO
	if (has_permission('reports', '', 'view')) {
        $CI->app_menu->add_sidebar_menu_item('reports', [
            'collapse' => true,
            'name'     => _l('als_reports'),
            'href'     => admin_url('reports'),
            'icon'     => 'fa fa-pie-chart',
            'position' => 90,
        ]);
        $CI->app_menu->add_sidebar_children_item('reports', [
            'slug'     => 'leads-reports',
            'name'     => _l('als_reports_leads_submenu'),
            'href'     => admin_url('reports/leads'),
            'position' => 5,
        ]);
        $CI->app_menu->add_sidebar_children_item('reports', [
            'slug'     => 'expenses-reports',
            'name'     => _l('als_reports_expenses'),
            'href'     => admin_url('reports/expenses'),
            'position' => 10,
        ]);
        $CI->app_menu->add_sidebar_children_item('reports', [
            'slug'     => 'sales-reports',
            'name'     => _l('als_reports_sales_submenu'),
            'href'     => admin_url('reports/sales'),
            'position' => 15,
        ]);
        $CI->app_menu->add_sidebar_children_item('reports', [
            'slug'     => 'expenses-vs-income-reports',
            'name'     => _l('als_expenses_vs_income'),
            'href'     => admin_url('reports/expenses_vs_income'),
            'position' => 20,
        ]);
    }

//END BÁO CÁO

//THIẾT LẬP
    if (is_admin()) {		
		// Cơ hội
		$CI->app_menu->add_setup_menu_item('leads', [
            'collapse' => true,
            'name'     => _l('acs_leads'),
			'icon'     => 'fa fa-volume-control-phone',
            'position' => 10,
        ]);
        $CI->app_menu->add_setup_children_item('leads', [
            'slug'     => 'leads-statuses',
            'name'     => _l('acs_leads_statuses_submenu'),
            'href'     => admin_url('leads/statuses'),
            'position' => 5,
        ]);
        $CI->app_menu->add_setup_children_item('leads', [
            'slug'     => 'leads-sources',
            'name'     => _l('acs_leads_sources_submenu'),
            'href'     => admin_url('leads/sources'),
            'position' => 10,
        ]);
        $CI->app_menu->add_setup_children_item('leads', [
            'slug'     => 'leads-email-integration',
            'name'     => _l('leads_email_integration'),
            'href'     => admin_url('leads/email_integration'),
            'position' => 15,
        ]);
        $CI->app_menu->add_setup_children_item('leads', [
            'slug'     => 'web-to-lead',
            'name'     => _l('web_to_lead'),
            'href'     => admin_url('leads/forms'),
            'position' => 20,
        ]);
		// END Cơ hội
		
		// Tài chính
        $CI->app_menu->add_setup_menu_item('finance', [
            'collapse' => true,
            'name'     => _l('acs_finance'),
			'icon'     => 'fa fa-credit-card',
            'position' => 20,
        ]);
        $CI->app_menu->add_setup_children_item('finance', [
            'slug'     => 'taxes',
            'name'     => _l('acs_sales_taxes_submenu'),
            'href'     => admin_url('taxes'),
            'position' => 5,
        ]);
        $CI->app_menu->add_setup_children_item('finance', [
            'slug'     => 'currencies',
            'name'     => _l('acs_sales_currencies_submenu'),
            'href'     => admin_url('currencies'),
            'position' => 10,
        ]);
        $CI->app_menu->add_setup_children_item('finance', [
            'slug'     => 'expenses-categories',
            'name'     => _l('acs_expense_categories'),
            'href'     => admin_url('expenses/categories'),
            'position' => 15,
        ]);
        $CI->app_menu->add_setup_children_item('finance', [
            'slug'     => 'payment-modes',
            'name'     => _l('acs_sales_payment_modes_submenu'),
            'href'     => admin_url('paymentmodes'),
            'position' => 20,
        ]);
		// END Tài chính
		
		// Hỗ trợ khách hàng
        $CI->app_menu->add_setup_menu_item('support', [
            'collapse' => true,
            'name'     => _l('support'),
			'icon'     => 'fa fa-support',
            'position' => 30,
        ]);
        $CI->app_menu->add_setup_children_item('support', [
            'slug'     => 'tickets-services',
            'name'     => _l('acs_ticket_services_submenu'),
            'href'     => admin_url('tickets/services'),
            'position' => 5,
        ]);
        $CI->app_menu->add_setup_children_item('support', [
            'slug'     => 'tickets-statuses',
            'name'     => _l('acs_ticket_statuses_submenu'),
            'href'     => admin_url('tickets/statuses'),
            'position' => 10,
        ]);
        $CI->app_menu->add_setup_children_item('support', [
            'slug'     => 'tickets-priorities',
            'name'     => _l('acs_ticket_priority_submenu'),
            'href'     => admin_url('tickets/priorities'),
            'position' => 15,
        ]);
        $CI->app_menu->add_setup_children_item('support', [
            'slug'     => 'tickets-spam-filters',
            'name'     => _l('spam_filters'),
            'href'     => admin_url('spam_filters/view/tickets'),
            'position' => 20,
        ]);
        $CI->app_menu->add_setup_children_item('support', [
            'slug'     => 'tickets-predefined-replies',
            'name'     => _l('acs_ticket_predefined_replies_submenu'),
            'href'     => admin_url('tickets/predefined_replies'),
            'position' => 25,
        ]);
		// END Hỗ trợ khách hàng
		
		// Vai trò người dùng
		$CI->app_menu->add_setup_menu_item('roles', [
			'href'     => admin_url('roles'),
			'name'     => _l('acs_roles'),
			'icon'     => 'fa fa-user-md',
			'position' => 40,
		]);
		// END Vai trò người dùng
		
		// Trường tùy chỉnh
        $CI->app_menu->add_setup_menu_item('custom-fields', [
            'href'     => admin_url('custom_fields'),
            'name'     => _l('asc_custom_fields'),
			'icon'     => 'fa fa-sliders',
            'position' => 50,
        ]);
		// END Trường tùy chỉnh
		
		// Mẫu email
		if (has_permission('email_templates', '', 'view')) {
			$CI->app_menu->add_setup_menu_item('email-templates', [
				'href'     => admin_url('emails'),
				'name'     => _l('acs_email_templates'),
				'icon'     => 'fa fa-envelope-open-o',
				'position' => 60,
			]);
		}
		// END Mẫu email
		
		// Tính năng
        $modules_name = _l('modules');
        if ($modulesNeedsUpgrade = $CI->app_modules->number_of_modules_that_require_database_upgrade()) {
            $modules_name .= '<span class="badge menu-badge bg-warning">' . $modulesNeedsUpgrade . '</span>';
        }		
		$CI->app_menu->add_setup_menu_item('modules', [
			'slug'     => 'modules',
			'href'     => admin_url('modules'),
			'name'     => $modules_name,
			'icon'     => 'fa fa-code',
			'position' => 70,
		]);
	}
		// END Tính năng
		
		// Cài đặt
		if (has_permission('settings', '', 'view')) {
			$CI->app_menu->add_setup_menu_item('settings', [
				'href'     => admin_url('settings'),
				'name'     => _l('acs_settings'),
				'icon'     => 'fa fa-wrench',
				'position' => 80,
			]);
		}
		// END Cài đặt
}
//END THIẾT LẬP