<?php defined('BASEPATH') or exit('No direct script access allowed'); 

$this->load->model('hr_profile/hr_profile_model');
$data_dash = $this->hr_profile_model->get_hr_profile_dashboard_data();

?>
<div class="widget" id="widget-<?php echo create_widget_id(); ?>" data-name="<?php echo 'Upcoming Birthdays'; ?>">
    <div class="panel_s user-data">
        <div class="panel-body home-activity">
            <div class="widget-dragger"></div>

            <div id="container">
               
                    <div class="clearfix"></div>
                    <div class="row">
                        <div class="col-md-12">
                            
                            <h4><p class="padding-5 bold"><i class="fa fa-birthday-cake"></i> <?php echo _l('hr_birthday_in_month'); ?></p></h4>
                            <hr class="hr-panel-heading-dashboard">
                            <table class="table dt-table scroll-responsive">
                                <thead>
                                    <th><?php echo _l('hr_hr_staff_name'); ?></th>
                                   <!--  <th><?php echo _l('staff_dt_email'); ?></th>
                                    <th><?php echo _l('staff_add_edit_phonenumber'); ?></th> -->
                                    <th><?php echo _l('hr_hr_birthday'); ?></th>
                                    <!-- <th><?php echo _l('hr_sex'); ?></th> -->
                                    <th><?php echo _l('departments'); ?></th>
                                </thead>
                                <tbody>

                                    <?php 
                                    $list_member_id = [];
                                    foreach($data_dash['staff_birthday'] as $staff){
                                        ?>

                                        <tr>
                                            <td><a href="<?php echo admin_url('hr_profile/member/' . $staff['staffid']); ?>"><?php echo staff_profile_image($staff['staffid'], ['staff-profile-image-small',]); ?></a>
                                                <a href="<?php echo admin_url('hr_profile/member/' . $staff['staffid']); ?>"><?php echo html_entity_decode($staff['firstname']) . ' ' . $staff['lastname'].' - '.$staff['staff_identifi']; ?></a>
                                            </td>
                                            <!-- <td><?php echo html_entity_decode($staff['email']); ?></td>
                                            <td><?php echo html_entity_decode($staff['phonenumber']); ?></td> -->
                                            <td><?php echo _d($staff['birthday']); ?></td>
                                           <!--  <td><?php echo _l($staff['sex']); ?></td> -->
                                            <td> 
                                                <?php
                                                $departments = $this->departments_model->get_staff_departments($staff['staffid']);
                                                if(isset($departments[0])){
                                                    $team = $this->hr_profile_model->hr_profile_get_department_name($departments[0]['departmentid']);
                                                    $str = '';
                                                    $j = 0;
                                                    foreach ($team as $value) {
                                                        $j++;
                                                        $str .= '<span class="label label-tag tag-id-1"><span class="tag">'.$value.'</span><span class="hide">, </span></span>&nbsp';
                                                        if($j%2 == 0){
                                                            $str .= '<br><br/>';
                                                        }
                                                    }
                                                    echo html_entity_decode($str);
                                                }
                                                else{
                                                    echo '';
                                                } ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>


                        </div>
                    </div>
                
            </div>

        </div>
    </div>
</div>
