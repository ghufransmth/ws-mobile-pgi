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
		  header('Access-Control-Allow-Origin: *');
			$query = mysqli_query($conn, "SELECT tanggal,image,jam_masuk,jam_keluar,created_on FROM tbl_absensi WHERE nik='".$user_id."'  AND lembur != 0 ORDER BY tanggal DESC");
			$count = mysqli_num_rows($query);

			if ($count > 0){
					$response = new usr();
					$response->success = 1;
					$response->data = array();
            		while ($row = mysqli_fetch_array($query)) {
            			// temp user array
            			$json = array();
            		    $json['created_on']= date("F j, Y", strtotime($row['created_on']));
            		    $json['time']= date("g:i a", strtotime($row['jam_masuk']));
            		    $json['start_hour']= $row['jam_masuk'];
            		    $json['end_hour']= $row['jam_keluar'];
            		    $json['date']= date("F j, Y", strtotime($row['tanggal']));
										$json['status']= 'Lembur';
										// $jam = date("H", strtotime($row['jam_keluar']));
            		    // if($jam > 0)
            		    // $row['status_approv'] = 'Telah Masuk';
            		    // else
            		    // $row['status_approv'] = 'Telah Keluar';
            		    // $json['status']= $row['status_approv'];

            		    // push single puasa into final response array
			            array_push($response->data, $json);
            		}
					$response->message = "Data Report.";
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
