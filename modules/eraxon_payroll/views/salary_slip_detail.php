<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); 

$pdfName ='';
?>

<div id="wrapper">
    <div class="content">
        <div class="row panel" id="pdf_file" style="padding:20px;">
            <div class="col-md-6 text-center">
                <div class="" style="width:45%;">
                 <?php get_dark_company_logo(); ?>
                </div>
            </div>
            <div class="col-md-6 text-right">
                <!-- <a href="<?=admin_url('eraxon_payroll/salary_pdf_view/').$slip_id ?>"><button class="btn btn-primary">PDF</button></a> -->
                 <button class="btn btn-primary" id="create_pdf" data-html2canvas-ignore="true">Download PDF</button>
            </div>
            <div class="clearfix"></div>
            <?php if(!empty($salary_details)) { 
                foreach($salary_details as $salary_detail) { 

                    $total_leaves = 0.00;
                    $total_leaves = $salary_detail->absents + ($salary_detail->half_days*0.5);
                    $pdfName = $salary_detail->name.'('.date('F Y', strtotime($salary_detail->date)).')';
                    ?>
                 <div class="col-md-6">

                    <table class="table table-bordered">
                        <tr>
                            <td>Full Name</td>
                            <td><?php echo $salary_detail->name.' ( '.get_custom_field_value($salary_detail->staffid,'staff_pseudo','staff',true).' )'; ?></td>
                        </tr>
                        <tr>
                            <td>Job Group</td>
                            <td><?php echo $salary_detail->rolename; ?></td>
                        </tr>
                         <tr>
                            <td>Grade</td>
                            <td><?php echo hr_profile_get_job_position_name($salary_detail->job_position) ?></td>
                        </tr>
                         <!-- <tr>
                            <td>Email</td>
                            <td><?php echo $salary_detail->email; ?></td>
                        </tr> -->
                         <tr>
                            <td>Salary Month</td>
                            <td><?php echo date('F Y', strtotime($salary_detail->date)).' <span class="">('.$salary_detail->status.')</span>'; ?></td>
                        </tr>
                    </table>
                    

                 </div>
                 <div class="col-md-6">
                    
                    <table class="table table-bordered">
                        <tr>
                            <th colspan="5" class="text-center"><h4>Attendance</h4></th>
                        </tr>
                        <tr>
                            <th>Absents</th>
                            <th>Half Days</th>
                            <th>Paid leaves</th>
                            <th>Total Leaves</th>
                            <th>Late Docks</th>
                        </tr>   
                        <tr>
                            <td><?php echo $salary_detail->absents; ?></td>
                            <td><?php echo ($salary_detail->half_days); ?></td>
                            <td><?php echo $salary_detail->paid_leaves; ?></td>
                            <td><?php echo $total_leaves; ?></td>
                            <td><?php echo $salary_detail->late; ?> x 200</td>
                        </tr>
                    </table>
                    
                 </div>
                 <div class="clearfix"></div>
                <div class="col-md-6">
                    <hr/>
                    <!-- <h4>Allowances</h4> -->
                    <table class="table">
                        <thead>
                            <th>
                                Earnings
                            </th>
                            <th>
                                Amount
                            </th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    Basic Salary
                                </td>
                                <td>
                                    <?php echo number_format($salary_detail->basic_salary,0,".",",");?>
                                </td>

                            </tr>
                            <?php 
                                if(!empty($allowances)) {
                                    foreach($allowances as $allowance) { ?>
                                <tr>
                                    <td><?php echo $allowance->name; ?></td>
                                    <td><?php echo number_format($allowance->allowance_amount,0,".",","); ?></td>
                                </tr>
                            <?php }} ?>
                            <?php 
                                if(!empty($adjustments)) {
                                    foreach($adjustments as $adjustment) { 
                                        if($adjustment->type == 'add'){?>
                                <tr>
                                    <td><?php echo $adjustment->name; ?></td>
                                    <td><?php echo number_format($adjustment->amount,0,".",","); ?></td>
                                </tr>
                            <?php }}} ?>
                            <tr>
                                <td class="text-right">
                                    Gross Salary
                                </td>
                                <td >
                                    <?php echo number_format($salary_detail->gross_salary,0,".",","); ?>
                                </td>
                            </tr>
                        
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <hr/>
                    <!-- <h4>Allowances</h4> -->
                    <table class="table">
                        <thead>
                            <th>
                                Deductions
                            </th>
                            <th>
                                Amount
                            </th>
                        </thead>
                        <tbody>
                            
                            <?php 
                                if(!empty($deductions)) {
                                    foreach($deductions as $deduction) { ?>
                                <tr>
                                    <td><?php echo $deduction->name; ?></td>
                                    <td><?php echo number_format($deduction->deduction_amount,0,".",","); ?></td>
                                </tr>
                            <?php }} ?>
                            <tr>
                                <td class="text-right">
                                    Total Deductions
                                </td>
                                <td >
                                    <?php echo number_format($salary_detail->total_deductions,0,".",","); ?>
                                </td>
                            </tr>
                            <?php 
                                if(!empty($adjustments)) {
                                    foreach($adjustments as $adjustment) { 
                                        if($adjustment->type == 'deduct'){?>
                                <tr>
                                    <td><?php echo $adjustment->name; ?></td>
                                    <td><?php echo number_format($adjustment->amount,0,".",","); ?></td>
                                </tr>
                            <?php }}} ?>
                            <tr>
                                <td class="text-right">
                                    Leaves
                                </td>
                                <td >
                                    <?php echo number_format($salary_detail->total_amount,0,".",","); ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-right">
                                    Late Docks
                                </td>
                                  <td><?php echo number_format($salary_detail->total_late,0,".",","); ?></td>
                             </tr>
                            <tr>
                                <td class="text-right">
                                    Net Salary
                                </td>
                                <td >
                                    <?php echo number_format($salary_detail->net_salary,0,".",","); ?>
                                </td>
                            </tr>
                        
                        </tbody>
                    </table>
                </div>
                <div class="col-md-12 table-responsive" id="timesheet" data-html2canvas-ignore="true">
                    <h3>Employee Timesheet</h3>
                    <?php  $attendance = json_decode($salary_detail->sandwitch_timesheet); ?>
                    <table class="table table-bordered table-sm" cellspacing="0" width="100%">
                          
                            <tr>
                                <th>Date</th>
                                <?php 

                                foreach ($attendance as $key => $value) 
                                {
                                    foreach ($value as $field => $fieldValue) 
                                    {
                                        
                                        if($field == 'date')
                                        {
                                            echo '<td>'.$fieldValue.'</td>';
                                        }

                                    }
                                }
                                    

                                 ?>
                            </tr>
                            <tr>
                                <th>Attendance</th>
                                <?php 

                                foreach ($attendance as $key => $value) 
                                {
                                    foreach ($value as $field => $fieldValue) 
                                    {
                                        
                                        if($field == 'attendance')
                                        {
                                            echo '<td>'.$fieldValue.'</td>';
                                        }

                                    }
                                }

                                 ?>
                            </tr> 

                         </table> 
                </div>
                <p><b>Disclaimer:</b> This is computer generated slip, no signature required. The author assumes no responsibility or liability for any error or omissions in the content of this slip. Not valid for court proceedings.</p>
            <?php }} ?>
        </div>
    </div>
</div>

<?php init_tail(); ?>

<script>
$(document).ready(function () {  

    $('#create_pdf').on('click', function (){ 

       
        var element = document.getElementById('pdf_file');
        var ignore = document.getElementById('timesheet');
        var opt = {
          margin:       0,
          filename:     '<?php echo $pdfName; ?>.pdf',
          image:        { type: 'jpeg', quality: 0.98 },
          html2canvas:  { scale: 2, ignoreElements:'true' },
          jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
        };
        html2pdf().set(opt).from(element).save();
      
    });

    
/*$('#create_pdf').on('click', function () {  
         let pdf = new jsPDF('p', 'pt', 'a4');
        let section=$('.pdf_file');
        let page= function() {
            pdf.save('pagename.pdf');
            //pdf.output('pagename.pdf'); 
        }; 

        pdf.addHTML(section,page);
    });  */


    /*var form = $('.pdf_file'),  
    cache_width = form.width(),  
    a4 = [595.28, 841.89]; // for a4 size paper width and height  

    $('#create_pdf').on('click', function () {  
        $('body').scrollTop(0);  
        createPDF();  
    });  
    
    function createPDF() {  
        getCanvas().then(function (canvas) {  
            var  
             img = canvas.toDataURL("image/png"),  
             doc = new jsPDF({  
                 unit: 'px',  
                format: 'a4'  
             });  
            doc.addImage(img, 'JPEG', 20, 20);  
            doc.save('techsolutionstuff.pdf');  
            form.width(cache_width);  
        });  
    }  
      
    function getCanvas() {  
        form.width((a4[0] * 1.33333) - 80).css('max-width', 'none');  
        return html2canvas(form, {  
            imageTimeout: 2000,  
            removeContainer: true  
        });  
    }*/
});
</script>
