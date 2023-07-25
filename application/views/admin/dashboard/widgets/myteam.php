<?php defined('BASEPATH') or exit('No direct script access allowed'); 

//$this->load->model('hr_profile/hr_profile_model');
$teams = array();
$teamlead_roleid = $this->roles_model->get_roleid_by_name("Team Lead");
$csr_roleid = $this->roles_model->get_roleid_by_name("CSR");
$get_teamleads_data = $this->staff_model->get('',["role"=>$teamlead_roleid,"active"=>1]);

$counter = 1;
foreach($get_teamleads_data as $leads)
{
    $teams['team'.$counter] = $leads;                                
    $teams['team'.$counter]['staff'] = $this->staff_model->get('',["team_manage"=>$leads["staffid"],"active"=>1]);
    $counter++;                              
}    
$cid = get_staff_user_id();

function search_exif($exif, $field)
{
    foreach ($exif as $data)
    {
        if ($data['staffid'] == $field)
            return 1;
    }
    return 0;
}
//$get_staff_data = $this->staff_model->get('',["active"=>1]);


?>
<div class="widget" id="widget-<?php echo create_widget_id(); ?>" data-name="<?php echo 'My Team'; ?>">
    <div class="panel_s user-data">
        <div class="panel-body home-activity">
            <div class="widget-dragger"></div>

            <div id="container">
               
                    <div class="clearfix"></div>
                    <div class="row">
                        <div class="col-md-12">
                            
                            <h4><p class="padding-5 bold"><?php echo "My Team"; ?></p></h4>
                            <hr class="hr-panel-heading-dashboard">
                            
                            <!-- <h3>Under Construction</h3> -->

                            <?php 
                                foreach($teams as $lkey => $tt)
                                {
                                    //echo search_exif($tt['staff'],$cid);
                                    if(search_exif($tt['staff'],$cid) || is_admin())
                                    {
                                        echo "<a href='".admin_url('hr_profile/member/').$tt['staffid']."'>".staff_profile_image($tt['staffid'],'staff-profile-image','small',["data-toggle"=>"tooltip","data-original-title"=>$tt['full_name']." (Team Lead)"])."</a> ";

                                        //echo $tt['full_name'].'<br>';

                                        foreach ($tt['staff'] as $staf) 
                                        {
                                             echo "<a href='".admin_url('hr_profile/member/').$staf['staffid']."'>".staff_profile_image($staf['staffid'],'staff-profile-image','small',["data-toggle"=>"tooltip","data-original-title"=>$staf['full_name']])."</a> ";
                                            // echo $staf['full_name'].' ';
                                        }
                                    }else{
                                        break;
                                    }
                                   
                                }

                                // /print_r($teams);
                             ?>

                        </div>
                    </div>
                
            </div>

        </div>
    </div>
</div>
