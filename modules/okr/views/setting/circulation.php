<div class="t2-col-md-12">
	<p class="text-info"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> <b>Chu kỳ OKR</b> thường chia theo QUÝ hoặc NĂM.</p>
	<?php if(has_permission('okr','','create') || is_admin()){ ?>
	<a href="#" onclick="add_setting_circulation(); return false;" class="btn btn-info mbot15 pull-left">
    	<?php echo _l('add'); ?>
	</a>
	<?php } ?>
</div>
<div class="t2-col-md-12">
	<?php
	    $table_data = array(
	        _l('name'),
	        _l('from_date'),
	        _l('to_date'),
	        _l('option'),
	        );
	    render_datatable($table_data,'circulation');
	?>
</div>
<div class="modal fade" id="setting_circulation" tabindex="-1" role="dialog">
<?php echo form_open(admin_url('okr/setting_circulation'),array('id'=>'form_setting_circulation')); ?>             
	<div class="modal-dialog">
    	<div class="modal-content">
	        <div class="modal-header">
	            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            <h4 class="modal-title">
	                <span class="add-title"><?php echo _l('add_setting_circulation'); ?></span>
	                <span class="update-title hide"><?php echo _l('update_setting_circulation'); ?></span>
	            </h4>
	        </div>
	        <div class="modal-body">
			<p class="text-info"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> <b>Chu kỳ:</b> bạn có thể thiết lập OKRs theo chu kỳ THÁNG, QUÝ hoặc NĂM.</p>
	          <?php echo form_hidden('id'); ?>
	          <?php echo render_input('name_circulation', 'name_circulation'); ?>
	          <?php echo render_date_input('from_date','from_date'); ?>
	          <?php echo render_date_input('to_date','to_date'); ?>
	        </div>
	        <div class="modal-footer">
	            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
	            <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
	        </div>
      	</div>
    </div>
<?php echo form_close(); ?>                 
</div>
