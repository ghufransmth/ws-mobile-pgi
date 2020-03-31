<?php
	include "koneksi.php";
	
	class usr{}

	$username    = $_POST["username"];

	$sql1 = "SELECT username FROM dbo.tb_users WHERE username='".$username."'";
	$query1 = sqlsrv_query($conn,$sql1,array(),array("scrollable" => 'static'));
	$count1 = sqlsrv_num_rows($query1);

	if($count1){

	$sql = "SELECT attempt FROM dbo.tb_users WHERE username='".$username."' AND attempt <= 3";
	$query = sqlsrv_query($conn,$sql,array(),array("scrollable" => 'static'));
	$count = sqlsrv_num_rows($query);

			if ($count){
			    
			     	$response = new usr();
					$response->success = 1;
					$response->message = "Data attempt success.";
					while ($row = sqlsrv_fetch_array($query)) {
            			// temp user array
            		    $json[]= $row;
            		}
            		$response->data = $json;
					die(json_encode($response));
					

			} else {
					$response = new usr();
					$response->success = 0;
					$response->message = "Please Contact Call Center.";
					die(json_encode($response));
			}

	}else{
		$response = new usr();
		$response->success = 0;
		$response->message = "Username atau password salah"; //tidak terdaftar
		die(json_encode($response));
	}	

	mysqli_close($con);
?>