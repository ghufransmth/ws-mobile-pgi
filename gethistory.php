<?php
	include "koneksi.php";

	class usr{}

	$user_id    = $_POST["user_id"];

	if ((empty($user_id))) {
		$response = new usr();
		$response->success = 0;
		$response->message = "Login terlebih dahulu";
		die(json_encode($response));
	} else {
		if (!empty($user_id)){

		  //$sql = "SELECT CONVERT(created_on, DATE) AS created_on, CONVERT(created_on, TIME) AS time, history FROM tbl_history WHERE user_id=$user_id ORDER BY created_on DESC";
		  $sql = "SELECT tanggal, jam_masuk, jam_keluar FROM tbl_absensi WHERE nik=$user_id ORDER BY created_on DESC";
			$query = mysqli_query($conn, $sql);
			$count = mysqli_num_rows($query);

			if ($count > 0){
					$response = new usr();
					$response->success = 1;
					$response->data = array();
            		while ($row = mysqli_fetch_array($query)) {
            			// temp user array
            				$json = array();
            		    $json['created_on']= date("d/m/Y", strtotime($row['tanggal']));
										$json['time']= date("H:i a", strtotime($row['jam_masuk']));
										$hour_keluar   = date('H',strtotime($row['jam_keluar']));
										if($hour_keluar > 0){
											$timeout = date("H:i a", strtotime($row['jam_keluar']));
										}
										else{
											$timeout = '-';
										}
										$json['timeout']= $timeout;
            		    $json['history']= '';

            		    // push single puasa into final response array
			            array_push($response->data, $json);
            		}
					$response->message = "Data History.";
					die(json_encode($response));

			} else {
					$response = new usr();
					$response->success = 0;
					$response->message = "Anda bukan user.";
					die(json_encode($response));
			}



		}
	}

	mysqli_close($con);
?>
