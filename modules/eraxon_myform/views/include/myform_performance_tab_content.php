<div class="row">
     <!-- <div class="quick-stats-leads col-xs-12 col-md-6 col-sm-6 <?php echo $initial_column; ?> tw-mb-2 sm:tw-mb-0">
            <div class="top_stats_wrapper">
                <?php
                  $where = '';
                  if (!is_admin()) {
                      $where .= '(addedfrom = ' .$staffid. ' OR assigned = ' . $staffid . ')';
                  }
                  // Junk leads are excluded from total
                  $total_leads = total_rows(db_prefix() . 'leads', ($where == '' ? 'junk=0' : $where .= ' AND junk =0'));
                  if ($where == '') {
                      $where .= 'status=3';
                  } else {
                      $where .= ' AND status =3';
                  }
                  $total_leads_converted         = total_rows(db_prefix() . 'leads', $where);
                  $percent_total_leads_converted = ($total_leads > 0 ? number_format(($total_leads_converted * 100) / $total_leads, 2) : 0);
                  ?>
                <div class="tw-text-neutral-800 mtop5 tw-flex tw-items-center tw-justify-between">
                    <div class="tw-font-medium tw-inline-flex text-neutral-600 tw-items-center tw-truncate">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="tw-w-6 tw-h-6 tw-mr-3 rtl:tw-ml-3 tw-text-neutral-600">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                        </svg>
                        <span class="tw-truncate">
                            <?php echo _l('leads_converted_to_client').$staffid; ?> 
                        </span>
                    </div>
                    <span class="tw-font-semibold tw-text-neutral-600 tw-shrink-0">
                        <?php echo $total_leads_converted; ?> /
                        <?php echo $total_leads; ?>
                    </span>
                </div>

            </div>
        </div> -->
        <div class="clearfix"></div>

        <div id="report_by_staffs" data-staffid="<?php echo $staffid; ?>"></div>

        <div class="clearfix"></div>
        <hr>

        <div id="report_by_day_leads_staffs" data-staffid="<?php echo $staffid; ?>"></div>
</div>