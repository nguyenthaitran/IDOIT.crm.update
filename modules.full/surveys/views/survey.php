<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
<div class="content">
   <div class="row">
      <div class="col-md-8" id="survey-add-edit-wrapper">
         <div class="row">
            <div class="col-md-12">
               <div class="panel_s">
                  <?php echo form_open($this->uri->uri_string(), array('id'=>'survey_form')); ?>
                  <div class="panel-body">
                     <h4 class="no-margin">
                        <?php echo $title; ?>
                     </h4>
                     <hr class="hr-panel-heading" />
                     <?php $value = (isset($survey) ? $survey->subject : ''); ?>
                     <?php $attrs = (isset($survey) ? array() : array('autofocus'=>true)); ?>
                     <?php echo render_input('subject','survey_add_edit_subject',$value,'text',$attrs); ?>
					 <?php $value = (isset($survey) ? $survey->fromname : ''); ?>
                     <?php echo render_input('fromname','survey_add_edit_from',$value); ?>
                     <p class="bold"><?php echo _l('survey_add_edit_email_description'); ?></p>
                     <?php $contents = ''; if(isset($survey)){$contents = $survey->description;} ?>
                     <?php echo render_textarea('description','',$contents,array(),array(),'','tinymce-email-description'); ?>
                     <?php if($found_custom_fields){ ?>
                     <hr />
                     <p class="bold tooltip-pointer" data-toggle="tooltip" title="<?php echo _l('survey_mail_lists_custom_fields_tooltip'); ?>"><?php echo _l('survey_available_mail_lists_custom_fields'); ?></p>
                     <?php } ?>
                     <?php
                        foreach($mail_lists as $list){
                           if(count($list['customfields']) == 0){
                              continue;
                          }
                          ?>
                     <div class="btn-group">
                        <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php echo $list['name']; ?> <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                           <?php foreach($list['customfields'] as $custom_field){ ?>
                           <li><a href="#" class="add_email_list_custom_field_to_survey" data-toggle="tooltip" title="{<?php echo $custom_field['fieldslug']; ?>}" data-slug="{<?php echo $custom_field['fieldslug']; ?>}"><?php echo $custom_field['fieldname']; ?></a></li>
                           <?php } ?>
                        </ul>
                     </div>
                     <?php } ?>
                     <hr />
                     <div class="clearfix"></div>
                     <div class="checkbox checkbox-primary">
                        <input type="checkbox" name="disabled" id="disabled" <?php if(isset($survey) && $survey->active == 0){echo 'checked';} ?>>
                        <label for="disabled"><?php echo _l('survey_add_edit_disabled'); ?></label>
                     </div>
                     <div class="checkbox checkbox-primary">
                        <input type="checkbox" name="onlyforloggedin" id="onlyforloggedin" <?php if(isset($survey) && $survey->onlyforloggedin == 1){echo 'checked';} ?>>
                        <label for="onlyforloggedin"><?php echo _l('survey_add_edit_only_for_logged_in'); ?></label>
                     </div>
                     <button type="submit" class="btn btn-info pull-right"><?php echo _l('submit'); ?></button>
                  </div>
                  <?php echo form_close(); ?>
               </div>
            </div>

         </div>
      </div>
      <div class="col-md-4" id="survey_questions_wrapper">
         <div class="panel_s">
            <div class="panel-body">
               <?php if(isset($survey)){ ?>
               <div class="tab-content">
                  <div role="tabpanel" class="tab-pane active" id="survey_send_tab">
                     <?php echo form_open('admin/surveys/send/'.$survey->surveyid); ?>
                           <p class="mbot30 text-warning"><?php echo _l('survey_send_mail_lists_note_logged_in'); ?></p>
                           <div class="form-group">
                              <div class="checkbox checkbox-primary">
                                 <input type="checkbox" name="send_survey_to[clients]" id="ml_clients">
                                 <label for="ml_clients"><?php echo _l('survey_send_mail_list_clients'); ?></label>
                              </div>

                              <div class="customer-groups" style="display:none;">

                                 <div class="clearfix"></div>
                                 <div class="checkbox checkbox-primary mleft10">
                                    <input type="checkbox" checked name="ml_customers_all" id="ml_customers_all">
                                    <label for="ml_customers_all"><?php echo _l('survey_customers_all'); ?></label>
                                 </div>
                                      <hr class="hr-10" />
                                 <?php foreach($customers_groups as $group){ ?>
                                 <div class="checkbox checkbox-primary mleft10 survey-customer-groups">
                                    <input type="checkbox" name="customer_group[<?php echo $group['id']; ?>]" id="ml_customer_group_<?php echo $group['id']; ?>">
                                    <label for="ml_customer_group_<?php echo $group['id']; ?>"><?php echo $group['name']; ?></label>
                                 </div>
                                 <?php } ?>
                                 <?php
                                if(is_gdpr() && (get_option('gdpr_enable_consent_for_contacts') == '1')) { ?>
                                    <select name="contacts_consent[]" title="<?php echo _l('gdpr_consent'); ?>" multiple="true" id="contacts_consent" class="selectpicker" data-width="100%">
                                      <?php foreach($purposes as $purpose) { ?>
                                      <option value="<?php echo $purpose['id']; ?>">
                                         <?php echo $purpose['name']; ?>
                                      </option>
                                      <?php } ?>
                                   </select>
                                <?php } ?>
                              </div>
                              <hr />
                              <div class="checkbox checkbox-primary">
                                 <input type="checkbox" name="send_survey_to[leads]" id="ml_leads">
                                 <label for="ml_leads"><?php echo _l('leads'); ?></label>
                              </div>
                              <div class="leads-statuses" style="display:none;">
                                 <div class="clearfix"></div>
                                  <div class="checkbox checkbox-primary mleft10">
                                    <input type="checkbox" checked name="leads_all" id="ml_leads_all">
                                    <label for="ml_leads_all"><?php echo _l('leads_all'); ?></label>
                                 </div>
                                <hr class="hr-10" />

                                 <?php foreach($leads_statuses as $status){ ?>
                                 <div class="checkbox checkbox-primary mleft10 survey-lead-status">
                                    <input type="checkbox" name="leads_status[<?php echo $status['id']; ?>]" id="ml_leads_status_<?php echo $status['id']; ?>">
                                    <label for="ml_leads_status_<?php echo $status['id']; ?>"><?php echo $status['name']; ?></label>
                                 </div>
                                 <?php } ?>
                                  <?php
                                if(is_gdpr() && (get_option('gdpr_enable_consent_for_leads') == '1')) { ?>
                                    <select name="leads_consent[]" title="<?php echo _l('gdpr_consent'); ?>" multiple="true" id="leads_consent" class="selectpicker" data-width="100%">
                                      <?php foreach($purposes as $purpose) { ?>
                                      <option value="<?php echo $purpose['id']; ?>">
                                         <?php echo $purpose['name']; ?>
                                      </option>
                                      <?php } ?>
                                   </select>
                                <?php } ?>
                              </div>
                               <hr />
                              <div class="checkbox checkbox-primary">
                                 <input type="checkbox" name="send_survey_to[staff]" id="ml_staff">
                                 <label for="ml_staff"><?php echo _l('survey_send_mail_list_staff'); ?></label>
                              </div>
                              <?php if(count($mail_lists) > 0){ ?>
                               <hr />
                              <p class="bold"><?php echo _l('survey_send_mail_lists_string'); ?></p>
                              <?php foreach($mail_lists as $list){ ?>
                              <div class="checkbox checkbox-primary">
                                 <input type="checkbox" id="ml_custom_<?php echo $list['listid']; ?>" name="send_survey_to[<?php echo $list['listid']; ?>]">
                                 <label for="ml_custom_<?php echo $list['listid']; ?>"><?php echo $list['name']; ?></label>
                              </div>
                              <?php } ?>
                              <?php } ?>


                           <?php if(total_rows(db_prefix().'surveysendlog',array('iscronfinished'=>0,'surveyid'=>$survey->surveyid)) > 0){ ?>
                           <p class="text-warning"><?php echo _l('survey_send_notice'); ?></p>
                           <?php } ?>
                           <?php foreach($send_log as $log){ ?>
                           <p>
                              <?php if(has_permission('surveys','','delete')){ ?>
                              <a href="<?php echo admin_url('surveys/remove_survey_send/'.$log['id']); ?>" class="_delete text-danger"><i class="fa fa-remove"></i></a>
                              <?php } ?>
                              <?php echo _l('survey_added_to_queue',_dt($log['date'])); ?>
                              ( <?php echo ($log['iscronfinished'] == 0 ? _l('survey_send_till_now'). ' ' : '') ?>
                              <?php echo _l('survey_send_to_total',$log['total']); ?> )
                              <br />
                              <b class="bold">
                              <?php echo _l('survey_send_finished',($log['iscronfinished'] == 1 ? _l('settings_yes') : _l('settings_no'))); ?>
                              </b>
                           </p>
                           <?php if(!empty($log['send_to_mail_lists'])){ ?>
                           <p>
                              <b><?php echo _l('survey_send_to_lists'); ?>:</b> <?php
                                 $send_lists = unserialize($log['send_to_mail_lists']);
                                 foreach($send_lists as $send_list){
                                    $last = end($send_lists);
                                    echo _l($send_list,'',false) . ($last == $send_list ? '':',');
                                 }
                                 ?>
                           </p>
                           <?php } ?>
                           <hr />
                           <?php } ?>

                        <button type="submit" class="btn btn-info"><?php echo _l('survey_send_string'); ?></button>
                     </div>
                     <?php echo form_close(); ?>

                  </div>
                  <?php } else { ?>
                  <p class="no-margin"><?php echo _l('survey_create_first'); ?></p>
                  <?php } ?>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<?php init_tail(); ?>
<script>
   $(function(){
       init_editor('.tinymce-email-description');
       init_editor('.tinymce-view-description');
   });
</script>
</body>
</html>
