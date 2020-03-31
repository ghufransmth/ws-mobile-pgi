<?php
	include "koneksi.php";
	
	class usr{}

	$username    = $_POST["username"];
	
	$sql = "SELECT * FROM dbo.tb_users WHERE username='".$username."' AND attempt <= 3";
	$query = sqlsrv_query($conn,$sql,array(),array("scrollable" => 'static'));

			if ($query){
			    
			     $query2 = sqlsrv_query($conn, "UPDATE dbo.tb_users SET attempt=attempt + 1 WHERE username='".$username."'",array(),array("scrollable" => 'static'));
			     
			     if($query2){
			        $response = new usr();
					$response->success = 1;
					$response->message = "Update attempt success.";
					die(json_encode($response));
			     }else{
			        $response = new usr();
					$response->success = 0;
					$response->message = "Failed to update attempt.";
					die(json_encode($response));
			     }
					

			} else {
					$response = new usr();
					$response->success = 0;
					$response->message = "Please Contact Call Center.";
					die(json_encode($response));
			}

	mysqli_close($con);
?>