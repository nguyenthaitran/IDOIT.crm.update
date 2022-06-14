<div class="t2-col-md-12">
	<p class="text-info"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> <b>Đơn vị</b> dùng để đo lường "Kết quả then chốt" (Key Results).</p>
	<?php if(has_permission('okr','','create') || is_admin()){ ?>
	<a href="#" onclick="add_setting_unit(); return false;" class="btn btn-info mbot15 pull-left">
    	<?php echo _l('add'); ?>
	</a>
	<?php } ?>
	
</div>
<div class="t2-col-md-12">
	<?php
	    $table_data = array(
	        _l('unit'),
	        _l('option'),
	        );
	    render_datatable($table_data,'unit');
	?>
</div>
<div class="modal fade" id="setting_unit" tabindex="-1" role="dialog">
<?php echo form_open(admin_url('okr/setting_unit'),array('id'=>'form_setting_unit')); ?>             
	<div class="modal-dialog">
    	<div class="modal-content">
	        <div class="modal-header">
	            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            <h4 class="modal-title">
	                <span class="add-title"><?php echo _l('add_setting_unit'); ?></span>
	                <span class="update-title hide"><?php echo _l('update_setting_unit'); ?></span>
	            </h4>
	        </div>
	        <div class="modal-body">
	          <?php echo form_hidden('id'); ?>
	          <?php echo render_input('unit', 'unit'); ?>
			  <p class="text-info"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> <b>Đơn vị</b> dùng để đo lường "Kết quả then chốt" (Key Results).</p>
	        </div>
	        <div class="modal-footer">
	            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
	            <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
	        </div>
      	</div>
    </div>
<?php echo form_close(); ?>                 
</div>