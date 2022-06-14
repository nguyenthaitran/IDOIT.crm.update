<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
   <div class="content">
      <div class="row">
         <div class="col-md-12">
			<?php if(is_admin()){ ?>
			<div class="panel_s">
                <div class="panel-body _buttons">
                    <a href="<?php echo admin_url('estimate_request/statuses'); ?>" class="btn btn-info pull-left display-block"><?php echo _l('acs_estimate_request_statuses_submenu'); ?></a>
					<a href="<?php echo admin_url('estimate_request/forms'); ?>" class="btn btn-default mleft5"><?php echo _l('acs_estimate_request_forms'); ?></a>
                </div>
            </div>
			<?php } ?>
            <div class="panel_s">
               <div class="panel-body">
                  <div>
                     <?php
                     $table_data = array();
                     $_table_data = array(
                        array(
                           'name' => _l('the_number_sign'),
                           'th_attrs' => array('class' => 'toggleable', 'id' => 'th-number')
                        ),
                        array(
                           'name' => _l('estimate_request_dt_email'),
                           'th_attrs' => array('class' => 'toggleable', 'id' => 'th-email')
                        ),
                     );
                     $_table_data[] =  array(
                        'name' => _l('tags'),
                        'th_attrs' => array('class' => 'toggleable', 'id' => 'th-tags')
                     );
                     $_table_data[] = array(
                        'name' => _l('estimate_request_dt_assigned'),
                        'th_attrs' => array('class' => 'toggleable', 'id' => 'th-assigned')
                     );
                     $_table_data[] = array(
                        'name' => _l('estimate_request_dt_status'),
                        'th_attrs' => array('class' => 'toggleable', 'id' => 'th-status')
                     );
                     $_table_data[] = array(
                        'name' => _l('estimate_request_dt_datecreated'),
                        'th_attrs' => array('class' => 'date-created toggleable', 'id' => 'th-date-created')
                     );
                     foreach ($_table_data as $_t) {
                        array_push($table_data, $_t);
                     }
                     $table_data = hooks()->apply_filters('estimate_request_table_columns', $table_data);
                     render_datatable(
                        $table_data,
                        'estimates_request',
                        [],
                        array(
                           'id' => 'table-estimate-request',
                        )
                     ); ?>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<?php init_tail(); ?>
<script>
   initDataTable('#table-estimate-request', admin_url + 'estimate_request/table', undefined, undefined, 'undefined', [5, 'desc']);

   function mark_estimate_request_as(status_id, request_id) {
      var data = {};
      data.status = status_id;
      data.requestid = request_id;
      $.post(admin_url + 'estimate_request/update_request_status', data).done(function (response) {
         $('table#table-estimate-request').DataTable().ajax.reload(null, false);
      });
   }

</script>
</body>

</html>
