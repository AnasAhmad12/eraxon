<?php defined('BASEPATH') or exit('No direct script access allowed'); 

$this->load->model('hr_profile/hr_profile_model');
$data_dash_user_profile = $this->hr_profile_model->get_hr_profile_dashboard_data();

?>

<div class="widget" id="widget-<?php echo create_widget_id(); ?>" data-name="<?php echo 'User Profile'; ?>">
    <div class="panel_s user-data">
        <div class="panel-body home-activity">
            <div class="widget-dragger"></div>

            <!-- <div class="container">
                <div class="clearfix"></div>
               <div class="row">
                      <div class="col-md-12"> -->
                            <!-- Column -->
                            <div class="card"> 

                                <?php echo staff_profile_cover_image($current_user->staffid,array('img','img-responsive','hr-card-img-top','picture-src'),'thumb', ['id' => 'wizardPicturePreview2']);
                                        ?>
                                <!-- <img class="hr-card-img-top" src="https://i.imgur.com/K7A78We.jpg" alt="Card image cap"> -->
                                <span class="hr-upload-icon"><a href="<?php echo admin_url('hr_profile/member/').$current_user->staffid; ?>" ><i class="fa fa-upload"></i></a></span>
                                <div class="card-body little-profile">
                                    <div class="pro-img">

                                        <!-- <img src="https://i.imgur.com/8RKXAIV.jpg" alt="user"> -->
                                        <?php echo staff_profile_image($current_user->staffid,array('img','img-responsive','staff-profile-image-thumb','picture-src'),'thumb', ['id' => 'wizardPicturePreview']);
                                        ?>
                                       

                                        <span class="pro-text">
                                            <h3 class="m-b-0"><?php echo $current_user->firstname.' '. $current_user->lastname; ?></h3>
                                            <p><b>Job Position: </b><?php echo hr_profile_get_job_position_name($current_user->job_position); ?></p>
                                        </span>
                                    
                                </div>
                                   <!--  <h3 class="m-b-0">Brad Macullam</h3>
                                    <p>Web Designer &amp; Developer</p> <a href="javascript:void(0)" class="m-t-10 waves-effect waves-dark btn btn-primary btn-md btn-rounded" data-abc="true">Follow</a>
                                    <div class="row text-center m-t-20">
                                        <div class="col-lg-4 col-md-4 m-t-20">
                                            <h3 class="m-b-0 font-light">10434</h3><small>Articles</small>
                                        </div>
                                        <div class="col-lg-4 col-md-4 m-t-20">
                                            <h3 class="m-b-0 font-light">434K</h3><small>Followers</small>
                                        </div>
                                        <div class="col-lg-4 col-md-4 m-t-20">
                                            <h3 class="m-b-0 font-light">5454</h3><small>Following</small>
                                        </div>
                                    </div> -->
                                </div>
                            </div>
                     <!--    </div>
                    </div>
            </div> -->
           
        </div>
    </div>
</div>

