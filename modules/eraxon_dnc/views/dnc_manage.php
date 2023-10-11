<?php defined('BASEPATH') or exit('No direct script access allowed'); 

		//echo $response->getStatusCode(); // 200
		//echo '<br>--------------------------';
       // echo $response->getHeaderLine('Content-Type'); // OK Authorization
       // echo '<br>--------------------------';
		//echo $response->getHeaderLine('Cookie');
//echo '<br>--------------------------';
      // echo $response->getBody();
		//echo '<br>--------------------------<br>';
		//echo $response2->getStatusCode();
		echo '<br>--------------------------<br>';
		//echo $response3->getBody();
		//var_dump(json_decode($response->getBody()));
       // $result = json_decode($response->getBody()->getContents(true));
		echo '<br>--------------------------<br>';
		$result2 = json_decode($response3->getBody()->getContents(true));
        //echo 'Message: '.$message;
		echo '<br>--------------------------<br>';
		//$result2 = json_decode($response3->getBody()->getContents(true));
        echo 'Auth: '.$auth;

		var_dump($result2);
	
