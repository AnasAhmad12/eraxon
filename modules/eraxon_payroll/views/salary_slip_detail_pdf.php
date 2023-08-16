<?php 

$company_logo = get_option('company_logo_dark');
$content = ''; 
$content .= '
<html>
<head></head>
</body>

<style>
    body{margin:0;}
     table tr th {
		          	  border: 1px solid #000;
		          	}
		        	table td
		        	{
		        		border: 1px solid #000;

		        		
		        	} 
</style>		
	<div style="100%"> 
	<img src="'.base_url('uploads/company/').$company_logo.'" width="100%">	
	';
    
 $content .= '</div>	
    <br>';
   if(!empty($salary_details)) { 
                foreach($salary_details as $salary_detail) { 

                    $total_leaves = 0.00;
                    $total_leaves = $salary_detail->absents + ($salary_detail->half_days*0.5);

                  

        $content .='<table class="table table-bordered" style="width:50%">
                        <tr>
                            <td>Full Name</td>
                            <td>'.$salary_detail->name.' ( '.get_custom_field_value($salary_detail->staffid,'staff_pseudo','staff',true).' )'.'</td>
                        </tr>
                        <tr>
                            <td>Job Group</td>
                            <td>'.$salary_detail->rolename.'</td>
                        </tr>
                         <tr>
                            <td>Grade</td>
                            <td>'.hr_profile_get_job_position_name($salary_detail->job_position).'</td>
                        </tr>
                         <tr>
                            <td>Email</td>
                            <td>'.$salary_detail->email.'</td>
                        </tr>
                         <tr>
                            <td>Salary Month</td>
                            <td>'.date('F Y', strtotime($salary_detail->date)).'</td>
                        </tr>
                    </table>';            







     }
    
    }           

$content .='</body></html>'; 


$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetTitle('Tabish');
$pdf->SetHeaderMargin(1);
$pdf->SetTopMargin(1);
$pdf->setFooterMargin(2);
$pdf->SetAutoPageBreak(true);
$pdf->setPrintHeader(false);  
$pdf->setPrintFooter(false); 
$pdf->SetAuthor('Eraxon');
$pdf->SetDisplayMode('real', 'default');
$pdf->SetMargins('10', '2', '10');
$pdf->AddPage();
$pdf->SetFont('Times','','8');
$pdf->writeHTML($content, true, false, true, false,'');  

   ob_clean();
$pdf->Output( 'test.pdf', 'I');

  ?>