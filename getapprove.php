<?php
	include "koneksi.php";

	include "url_public.php";

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
			$sql = "SELECT A.id, A.nik, A.tanggal, A.jam_masuk, A.jam_keluar, A.image, A.lat, A.lang, A.created_on, A.modified_on, B.nama_user FROM tbl_absensi AS A LEFT JOIN user AS B ON A.nik = B.username WHERE B.id_atasan = '".$user_id."' ORDER BY A.tanggal DESC";
		  $query = mysqli_query($conn, $sql);
			$count = mysqli_num_rows($query);
			header('Access-Control-Allow-Origin: *');
			$sql2 = "SELECT A.id, A.nik, A.tanggal, A.jam_masuk, A.jam_keluar, A.image, A.lat, A.lang, A.created_on, A.modified_on, B.nama_user FROM tbl_absensi AS A LEFT JOIN user AS B ON A.nik = B.username WHERE A.lembur != 0 AND B.id_atasan = '".$user_id."' ORDER BY A.tanggal DESC";
			$query2 = mysqli_query($conn, $sql2);
			$count2 = mysqli_num_rows($query2);
			header('Access-Control-Allow-Origin: *');
			$sql3 = "SELECT A.id, A.nik, A.start_date, A.end_date, A.reason, A.status_approv, A.created_on, A.modified_on, B.nama_user FROM tbl_timeoff AS A LEFT JOIN user AS B ON A.nik = B.username WHERE A.status_approv = 0 AND B.id_atasan = '".$user_id."' ORDER BY A.created_on DESC";
			$query3 = mysqli_query($conn, $sql3);
			$count3 = mysqli_num_rows($query3);
			header('Access-Control-Allow-Origin: *');
			$sql4 = "SELECT A.id, A.nik, A.start_date, A.end_date, A.reason, A.status_approv, A.created_on, A.modified_on, B.nama_user FROM tbl_cuti AS A LEFT JOIN user AS B ON A.nik = B.username WHERE A.status_approv = 0 ORDER BY A.created_on DESC";
		  $query4 = mysqli_query($conn, $sql4);
			$count4 = mysqli_num_rows($query4);


			if ($count > 0 || $count2 > 0 || $count3 > 0 || $count4 > 0){
					$response = new usr();
					$response->success = 1;
					$response->dataatt = array();
            		while ($row = mysqli_fetch_array($query)) {
            		    $json = array();
            		    $json['id']= $row[0];
            		    $json['user_id']= $row['nik'];
            		    $json['start_date']= $row['jam_masuk'];
            		    $json['end_date']= $row['jam_keluar'];
            		    $json['keterangan']= '';
            		    $path = $url_public."images/".$row['image']."";
            		    $json['image']= $path;
            		    $json['lat']= $row['lat'];
            		    $json['lang']= $row['lang'];
            		    $json['status_approv']= 'Approved';
            		    $json['created_on']= date("F j, Y", strtotime($row['created_on']));
            		    $json['time']= date("g:i a", strtotime($row['jam_masuk']));
            		    $json['username']= $row['nama_user'];
            		    // push single puasa into final response array
            		    // push single puasa into final response array
			            array_push($response->dataatt, $json);
            		}
            		$response->dataovertime = array();
            		while ($row = mysqli_fetch_array($query2)) {
            		    $json = array();
            		    $json['id']= $row[0];
            		    $json['user_id']= $row['nik'];
            		    $json['date']= $row['tanggal'];
            		    $json['start_hour']= date("h:m", strtotime($row['jam_masuk']));
            		    $json['end_hour']= date("h:m", strtotime($row['jam_keluar']));
            		    $json['status_approv']= 'Approved';
            		    $json['created_on']= date("F j, Y", strtotime($row['created_on']));
            		    $json['time']= date("g:i a", strtotime($row['created_on']));
            		    $json['modified_on']= $row['modified_on'];
            		    $json['username']= $row['nama_user'];
            		    // push single puasa into final response array
			            array_push($response->dataovertime, $json);
            		}
            		$response->datatimeoff = array();
            		while ($row = mysqli_fetch_array($query3)) {
            		    $json = array();
            		    $json['id']= $row[0];
            		    $json['user_id']= $row['nik'];
            		    $json['start_date']= date("F j, Y", strtotime($row['start_date']));
            		    $json['end_date']= date("F j, Y", strtotime($row['end_date']));
            		    $json['reason']= $row['reason'];
            		    $json['status_approv']= $row['status_approv'];
            		    $json['created_on']= date("F j, Y", strtotime($row['created_on']));
            		    $json['time']= date("g:i a", strtotime($row['created_on']));
            		    $json['modified_on']= $row['modified_on'];
            		    $json['username']= $row['nama_user'];
            		    // push single puasa into final response array
			            array_push($response->datatimeoff, $json);
            		}
								$response->datacuti = array();
            		while ($row = mysqli_fetch_array($query4)) {
            		    $json = array();
            		    $json['id']= $row[0];
            		    $json['user_id']= $row['nik'];
            		    $json['start_date']= date("F j, Y", strtotime($row['start_date']));
            		    $json['end_date']= date("F j, Y", strtotime($row['end_date']));
            		    $json['reason']= $row['reason'];
            		    $json['status_approv']= $row['status_approv'];
            		    $json['created_on']= date("F j, Y", strtotime($row['created_on']));
            		    $json['time']= date("g:i a", strtotime($row['created_on']));
            		    $json['modified_on']= $row['modified_on'];
            		    $json['username']= $row['nama_user'];
            		    // push single puasa into final response array
			            array_push($response->datacuti, $json);
            		}
					$response->message = "Data Report.";
					die(json_encode($response));

			} else {
					$response = new usr();
					$response->success = 0;
					$response->message = "Anda Tidak Memiliki Data Approve.";
					die(json_encode($response));
			}



		}
	}

	mysqli_close($con);
?>
