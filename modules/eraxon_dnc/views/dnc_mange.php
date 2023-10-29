<?php 

		echo $response->getStatusCode(); // 200
		echo '<br>--------------------------';
        echo $response->getReason(); // OK
        echo '<br>--------------------------';
        echo $response->getProtocolVersion(); // 1.1
        echo '<br>--------------------------';
        echo $response->getBody();
	
 ?>