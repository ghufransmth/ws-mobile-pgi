<?php
	include "koneksi.php";

	class usr{}

	$id    		= $_POST["id"];
	$user_id    = $_POST["user_id"];

	if ((empty($id))) {
		$response = new usr();
		$response->success = 0;
		$response->message = "No Value Post";
		die(json_encode($response));
	} else {
		if (!empty($id)){

			header('Access-Control-Allow-Origin: *');
			$query = mysqli_query($conn, "UPDATE tbl_absensi SET status_approv = 0 , approve_by = '".$user_id."' WHERE id = '".$id."'",array());
			$stm = mysqli_affected_rows($conn);

			if ($stm > 0){
					$response = new usr();
					$response->success = 1;
					$response->message = "Data Overtime telah diapprove.";
					die(json_encode($response));

			} else {
					$response = new usr();
					$response->success = 0;
					$response->message = "Gagal mengupdate.";
					die(json_encode($response));
			}



		}
	}

	mysqli_close($con);
?>
