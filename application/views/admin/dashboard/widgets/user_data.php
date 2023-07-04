<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="widget" id="widget-<?php echo create_widget_id(); ?>" data-name="<?php echo _l('user_widget'); ?>">
    <div class="panel_s user-data">
        <div class="panel-body home-activity">
            <div class="widget-dragger"></div>
            <div class="horizontal-scrollable-tabs panel-full-width-tabs">
                <div class="scroller scroller-left arrow-left"><i class="fa fa-angle-left"></i></div>
                <div class="scroller scroller-right arrow-right"><i class="fa fa-angle-right"></i></div>
                <div class="horizontal-tabs">
                    <ul class="nav nav-tabs nav-tabs-horizontal" role="tablist">
                      
                        <?php if (is_staff_member()) { ?>
                        <li role="presentation" class="active">
                            <a href="#home_announcements" onclick="init_table_announcements(true);"
                                aria-controls="home_announcements" role="tab" data-toggle="tab">
                                <i class="fa fa-bullhorn menu-icon"></i> <?php echo _l('home_announcements'); ?>
                                <?php if ($total_undismissed_announcements != 0) {
                            echo '<span class="badge">' . $total_undismissed_announcements . '</span>';
                        } ?>
                            </a>
                        </li>
                        <?php } ?>
                        <?php if (is_admin()) { ?>
                        <li role="presentation">
                            <a href="#home_tab_activity" aria-controls="home_tab_activity" role="tab" data-toggle="tab">
                                <i class="fa fa-window-maximize menu-icon"></i>
                                <?php echo _l('home_latest_activity'); ?>
                            </a>
                        </li>
                        <?php } ?>
                        <?php hooks()->do_action('after_user_data_widget_tabs'); ?>
                    </ul>
                </div>
            </div>
            <div class="tab-content tw-mt-5">
                 <?php if (is_staff_member()) { ?>
                <div role="tabpanel" class="tab-pane active" id="home_announcements">
                    <?php if (is_admin()) { ?>
                    <a href="<?php echo admin_url('announcements'); ?>"
                        class="mbot20 inline-block full-width"><?php echo _l('home_widget_view_all'); ?></a>
                    <div class="clearfix"></div>
                    <?php } ?>
                    <?php render_datatable([_l('announcement_name'), _l('announcement_date_list')], 'announcements'); ?>
                </div>
                <?php } ?>

               
               
                <?php if (is_admin()) { ?>
                <div role="tabpanel" class="tab-pane" id="home_tab_activity">
                    <a href="<?php echo admin_url('utilities/activity_log'); ?>"
                        class="mbot20 inline-block full-width"><?php echo _l('home_widget_view_all'); ?></a>
                    <div class="clearfix"></div>
                    <div class="activity-feed">
                        <?php foreach ($activity_log as $log) { ?>
                        <div class="feed-item">
                            <div class="date">
                                <span class="text-has-action" data-toggle="tooltip"
                                    data-title="<?php echo _dt($log['date']); ?>">
                                    <?php echo time_ago($log['date']); ?>
                                </span>
                            </div>
                            <div class="text">
                                <?php echo $log['staffid']; ?><br />
                                <?php echo $log['description']; ?>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
                <?php } ?>
                <?php hooks()->do_action('after_user_data_widge_tabs_content'); ?>
            </div>
        </div>
    </div>
</div>
