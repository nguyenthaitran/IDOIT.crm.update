<div class="t2-col-md-12">
	<p class="text-info"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> <b>Phản hồi (Feedback)</b>: là những lời đánh giá, nhận xét dành cho nhóm làm việc. Feedback có thể là quản lý nhận xét nhân viên hoặc nhân viên nhận định về quản lý.<br>
	<b>Ghi nhận (Recognition)</b> là những lời khen, sự công nhận đặc biệt từ quản lý đối với nhân viên về sự nỗ lực trong công việc.</p>
	<?php if(has_permission('okr','','create') || is_admin()){ ?>
	<a href="#" onclick="add_setting_evaluation_criteria(); return false;" class="btn btn-info mbot15 pull-left">
    	<?php echo _l('add'); ?>
	</a>
	<?php } ?>
	
</div>
<div class="t2-col-md-12">
	<?php
	    $table_data = array(
	        _l('group_criteria'),
	        _l('name'),
	        _l('scores'),
	        _l('option'),
	        );
	    render_datatable($table_data,'evaluation-criteria');
	?>
</div>
<div class="modal fade" id="evaluation_criteria" tabindex="-1" role="dialog">
<?php echo form_open(admin_url('okr/setting_evaluation_criteria'),array('id'=>'form_evaluation_criteria')); ?>             
	<div class="modal-dialog">
    	<div class="modal-content">
	        <div class="modal-header">
	            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            <h4 class="modal-title">
	                <span class="add-title"><?php echo _l('add_evaluation_criteria'); ?></span>
	                <span class="update-title hide"><?php echo _l('update_evaluation_criteria'); ?></span>
	            </h4>
	        </div>
	        <div class="modal-body">
			<p class="text-info"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> <b>Phản hồi (Feedback)</b>: là những lời đánh giá, nhận xét dành cho nhóm làm việc. Feedback có thể là quản lý nhận xét nhân viên hoặc nhân viên nhận định về quản lý.<br>
			<b>Ghi nhận (Recognition)</b> là những lời khen, sự công nhận đặc biệt từ quản lý đối với nhân viên về sự nỗ lực trong công việc.</p>
	          <?php echo form_hidden('id'); ?>
	          <?php 
	          	$group_criteria = [
	          		['id' => 1, 'name' => _l('checkin')],
	          		['id' => 2, 'name' => _l('acknowledge')],
	          		['id' => 3, 'name' => _l('other_feedback')],
	          	];
	          	echo render_select('group_criteria',$group_criteria,array('id','name'),'group_criteria'); 
	          ?>
	          <?php echo render_input('name', 'name'); ?>
	          <?php echo render_input('scores', 'scores', '', 'number'); ?>
	        </div>
	        <div class="modal-footer">
	            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
	            <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
	        </div>
      	</div>
    </div>
<?php echo form_close(); ?>                 
</div>