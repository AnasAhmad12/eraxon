<?php 
echo 'timesheet check: '. $addintotimesheet.'<br>';
echo '<br>start_date:'.$start_date.'<br>';
	echo '<br>end_date:'.$end_date.'<br>';
	echo '<br>total_minutes:'.$total_minutes.'<br>';
	echo '<br>sixty_percent_time:'.$sixty_percent_time.'<br>';
	echo '<br>hours:'.$hours.'<br><br>';
	echo '<br><br><br>';

	echo 'Overall:<br>';
	var_dump($overall);

	echo 'Exception:<br>';
	var_dump($exception);
	/*echo '<br><br><br>';
	echo 'Timesheet';
	var_dump($timesheet);*/
	echo '<br>Counter:'.$counter.'<br><br>';
	//echo 'Attendance:<br>';
	//	print_r($attendance);
	echo 'Holiday:<br>';
	//	print_r($attendance);
	var_dump($holiday);

/*echo '---------------------------<br>';
	for ($i=1; $i <=count($attendance) ; $i++) { 
		
		   

		echo "staff ID: ".$attendance[$i]['staff_id'].' | '."Date: ".$attendance[$i]['date'].'|'."Attendance: ".$attendance[$i]['attendance'].'<br>';

		   echo '---------------------------<br>';
	}*/

 ?>

 <br><br>

<table border="1">
 	<tr>
 		<th>Staff ID</th>
 		<?php 

 		foreach ($attendance as $key => $value) 
		{
		    foreach ($value as $field => $fieldValue) 
		    {
		       	
		       	if($field == 'staff_id')
		       	{
		       		echo '<td>'.$fieldValue.'</td>';
		       	}

		    }
		}
 			

 		 ?>
 	</tr>
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
 		<th>attendance</th>
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

 <br><br>

<table border="1">
 	<tr>
 		<th>Staff ID</th>
 		<?php 

 		foreach ($SandwitchDates as $key => $value) 
		{
		    foreach ($value as $field => $fieldValue) 
		    {
		       	
		       	if($field == 'staff_id')
		       	{
		       		echo '<td>'.$fieldValue.'</td>';
		       	}

		    }
		}
 			

 		 ?>
 	</tr>
 	<tr>
 		<th>Date</th>
 		<?php 

 		foreach ($SandwitchDates as $key => $value) 
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
 		<th>attendance</th>
 		<?php 

 		foreach ($SandwitchDates as $key => $value) 
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