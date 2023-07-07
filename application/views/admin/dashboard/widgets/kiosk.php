<?php defined('BASEPATH') or exit('No direct script access allowed'); 

$this->load->model('hr_profile/hr_profile_model');
$data_dash = $this->hr_profile_model->get_hr_profile_dashboard_data();

?>
<div class="widget" id="widget-<?php echo create_widget_id(); ?>" data-name="<?php echo 'KIOSK'; ?>">
    <div class="panel_s user-data">
        <div class="panel-body home-activity">
            <div class="widget-dragger"></div>

            <div id="container">
               
                    <div class="clearfix"></div>
                    <div class="row">
                        <div class="col-md-12">
                            
                            <h4><p class="padding-5 bold"><?php echo "KIOSK"; ?></p></h4>
                            <hr class="hr-panel-heading-dashboard">
                            
                            <h3>Under Construction</h3>

                        </div>
                    </div>
                
            </div>

        </div>
    </div>
</div>
