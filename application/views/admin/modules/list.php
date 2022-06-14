<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="panel_s">
            <div class="panel-body">
              <div class="row">
                <div class="col-md-12">
					<div class="table-responsive">
                        <table class="table dt-table" data-order-type="asc" data-order-col="0">
                            <thead>
                                <tr>
                                    <th>
                                        <?php echo _l('module'); ?>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($modules as $module) {
                                    $system_name = $module['system_name'];
                                    $database_upgrade_is_required = $this->app_modules->is_database_upgrade_required($system_name);
                                     ?>
                                    <tr class="<?php if($module['activated'] === 1 && !$database_upgrade_is_required){echo 'alert-info';} ?><?php if($database_upgrade_is_required){echo ' alert-warning';} ?>">
                                        <td data-order="<?php echo $system_name; ?>">
                                            <p>
                                                <b>
                                                    <?php echo $module['headers']['module_name']; ?>
                                                </b>
                                            </p>
                                            <?php
                                            $action_links = [];
                                            $versionRequirementMet = $this->app_modules->is_minimum_version_requirement_met($system_name);
                                            $action_links = hooks()->apply_filters("module_{$system_name}_action_links", $action_links);
                                            
                                            if($database_upgrade_is_required) {
                                                $action_links[] = '<a href="' . admin_url('modules/upgrade_database/' . $system_name) . '" class="text-success bol">' . _l('module_upgrade_database') . '</a>';
                                            }

                                            echo implode('&nbsp;|&nbsp;', $action_links);

                                            if(!$versionRequirementMet) {
                                                echo '<div class="alert alert-warning mtop5 p7">';
                                                echo 'Yêu cầu phiên bản thấp nhất là v' . $module['headers']['requires_at_least'] . ' của myCRM.';
                                                if($module['activated'] === 0) {
                                                    echo ' Do đó, không thể được kích hoạt';
                                                }
                                                echo '</div>';
                                            }

                                            if($newVersionData = $this->app_modules->new_version_available($system_name)) {
                                                echo '<div class="alert alert-success mtop5 p7">';

                                                    echo 'Có một phiên bản mới của '.$module['headers']['module_name'].' có sẵn. ';
                                                    $version_actions = [];

                                                    if(isset($newVersionData['changelog']) && !empty($newVersionData['changelog'])) {
                                                         $version_actions[] = '<a href="'.$newVersionData['changelog'].'" target="_blank">Ghi chú phát hành ('.$newVersionData['version'].')</a>';
                                                    }

                                                    if($this->app_modules->is_update_handler_available($system_name)) {
                                                        $version_actions[] = '<a href="'.admin_url('modules/update_version/'.$system_name).'" id="update-module-'.$system_name.'">Cập nhật</a>';
                                                    }

                                                    echo implode('&nbsp;|&nbsp;', $version_actions);
                                                echo '</div>';
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
<?php init_tail(); ?>
<script>
    $(function(){
        appValidateForm($('#module_install_form'), {module:{required:true,extension:"zip"}});
    });
</script>
</body>
</html>
