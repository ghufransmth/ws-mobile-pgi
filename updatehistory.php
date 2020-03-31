<?php
	include "koneksi.php";

	class usr{}

	$user_id    = $_POST["user_id"];
	$history    = $_POST["history"];

	if ((empty($user_id))) {
		$response = new usr();
		$response->success = 0;
		$response->message = "Login terlebih dahulu";
		die(json_encode($response));
	} else {
		if (!empty($user_id)){
			$query = mysqli_query($conn, "INSERT INTO tbl_history (user_id, history, created_on) VALUES($user_id,'".$history."',NOW())");

			$response = new usr();
			$response->success = 1;
			$response->message = "Data History telah dimasukkan.";
			die(json_encode($response));

// 			$stm = mysqli_affected_rows($query);

// 			if ($stm > 0){
// 					$response = new usr();
// 					$response->success = 1;
// 					$response->message = "Data History telah dimasukkan.";
// 					die(json_encode($response));

// 			} else {
// 					$response = new usr();
// 					$response->success = 0;
// 					$response->message = "Anda bukan user.";
// 					die(json_encode($response));
// 			}

		}
	}

	mysqli_close($con);
?>
