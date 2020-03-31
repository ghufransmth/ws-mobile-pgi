<?php
	include "koneksi.php";

	class usr{}

	$user_id    = $_POST["user_id"];
	$start_date = $_POST["start_date"];
	$end_date   = $_POST["end_date"];
	$reason     = $_POST["reason"];
	//$created_on = $_POST["created_on"];
	$image      = $_FILES['image']['name'];

	if ((empty($user_id))) {
		$response = new usr();
		$response->success = 0;
		$response->message = "Login terlebih dahulu";
		die(json_encode($response));
	} else if ((empty($start_date))) {
		$response = new usr();
		$response->success = 0;
		$response->message = "Kolom awal tanggal tidak boleh kosong";
		die(json_encode($response));
	} else if ((empty($end_date))) {
		$response = new usr();
		$response->success = 0;
		$response->message = "Kolom akhir tanggal tidak boleh kosong";
		die(json_encode($response));
	} else if ((empty($reason))) {
		$response = new usr();
		$response->success = 0;
		$response->message = "Kolom reason tidak boleh kosong";
		die(json_encode($response));
	} else {
		if (!empty($user_id) && !empty($start_date) && !empty($end_date) && !empty($reason)){

		    if(validateDate($start_date) == true){

        		if(validateDate($end_date) == true){

						header('Access-Control-Allow-Origin: *');
						$reason = mysqli_query($conn, "SELECT * FROM tbl_alasan_cuti WHERE id = '".$reason."'");
					  while ($row = mysqli_fetch_array($reason)) {
					    $id_reason = $row['id'];
					    $reason_desc = $row['nama'];
					  }

						header('Access-Control-Allow-Origin: *');
						$query = mysqli_query($conn, "SELECT * FROM user WHERE username='".$user_id."'");
        		$num_rows = mysqli_num_rows($query);

						//IMAGE SETTINGS
				    $random = random_word(20);
				    $temp = explode(".", $image);
				    $newfilename = round(microtime(true)) . $user_id . '.' .end($temp);
				    $path = "images_cuti/".$newfilename;
				    // sesuiakan ip address laptop/pc atau URL server
				    $actualpath = $_SERVER["DOCUMENT_ROOT"]."/mobile_dev/$path";

        			if ($num_rows > 0){

								//CEK APAKAH BISA MENGAJUKAN CUTI
								$queryb = mysqli_query($conn, "SELECT * FROM tbl_kuota_cuti WHERE nik='".$user_id."'");
								while ($row = mysqli_fetch_array($queryb)) {
									$total_kuota_cuti = ($row['kuota'] != null) ? $row['kuota'] : 0;
								}
								$num_rowst = mysqli_num_rows($queryb);

								if($num_rowst > 0){
									//CEK BERAPA LAMA BEKERJA
									$queryd = mysqli_query($conn, "SELECT tanggal_masuk FROM tbl_karyawan WHERE nik='".$user_id."'");
									while ($row = mysqli_fetch_array($queryd)) {
								    $tgl_masuk = $row['tanggal_masuk'];
								  }
									date_default_timezone_set("Asia/Jakarta");
									$d1 = new DateTime($tgl_masuk);
									$d2 = new DateTime(date("Y-m-d"));
									$numberOfMonths = $d1->diff($d2)->m + ($d1->diff($d2)->y*12);

									//MENENTUKAN JUMLAH HARI CUTI TANPA HARI LIBUR
									$start = new DateTime($start_date);
									$end = new DateTime($end_date);
									// $diff = $later->diff($earlier)->format("%a");
									// $hitung = $diff+1;
									// otherwise the  end date is excluded (bug?)
									$end->modify('+1 day');

									$interval = $end->diff($start);

									// total days
									$days = $interval->days;

									// create an iterateable period of date (P1D equates to 1 day)
									$period = new DatePeriod($start, new DateInterval('P1D'), $end);

									// best stored as array, so you can add more than one
									$queryg = mysqli_query($conn, "SELECT tanggal FROM tbl_hari_libur");
									$holidays = array();
									while ($row = mysqli_fetch_array($queryg)) {
										$holidays[] = ($row['tanggal'] != null) ? $row['tanggal'] : '';
									}

									foreach($period as $dt) {
											$curr = $dt->format('D');

											// substract if Saturday or Sunday
											// if ($curr == 'Sat' || $curr == 'Sun') {
											//     $days--;
											// }

											if ($curr == 'Sun') {
													$days--;
											}

											// (optional) for the updated question
											elseif (in_array($dt->format('Y-m-d'), $holidays)) {
													$days--;
											}
									}

									//PEMBATASAN CUTI
									if($numberOfMonths > 16){

										if($total_kuota_cuti > 0){
												if($reason_desc == "Cuti Tahunan"){
													if($total_kuota_cuti >= $days){
														$hitung = $total_kuota_cuti - $days;
														header('Access-Control-Allow-Origin: *');
														$sql = "INSERT INTO tbl_cuti (nik, reason, status_approv, approve_by, jumlah_hari, potong_cuti, start_date, end_date, image) VALUES('".$user_id."','".$reason_desc."','0','','".$days."','0','".$start_date."','".$end_date."','".$newfilename."')";
								        		$query2 = mysqli_query($conn, $sql);
														header('Access-Control-Allow-Origin: *');
														$sql2 = "UPDATE tbl_kuota_cuti SET kuota = '".$hitung."' WHERE nik = '".$user_id."'";
								        		$query3 = mysqli_query($conn, $sql2);
														header('Access-Control-Allow-Origin: *');
														compress($_FILES['image']['tmp_name'], $actualpath, 65);

														$response = new usr();
														$response->success = 1;
														$response->message = "Data cuti telah ditambahkan.";
														die(json_encode($response));
													}else{
														$response = new usr();
														$response->success = 0;
														$response->message = "Tidak mencukupi, sisa cuti anda ".$total_kuota_cuti;
														die(json_encode($response));
													}
												}else if($reason_desc == "Cuti Kematian"){
													if($days <= 2){


														header('Access-Control-Allow-Origin: *');
														$sql = "INSERT INTO tbl_cuti (nik, reason, status_approv, approve_by, jumlah_hari, potong_cuti, start_date, end_date, image) VALUES('".$user_id."','".$reason_desc."','0','','".$days."','0','".$start_date."','".$end_date."','".$newfilename."')";
								        		$query2 = mysqli_query($conn, $sql);
														header('Access-Control-Allow-Origin: *');
														$sql2 = "UPDATE tbl_kuota_cuti SET kuota = '".$hitung."' WHERE nik = '".$user_id."'";
								        		$query3 = mysqli_query($conn, $sql2);
														header('Access-Control-Allow-Origin: *');
														compress($_FILES['image']['tmp_name'], $actualpath, 65);
														//echo $sql;
														//echo json_encode($num_rows2);

														$response = new usr();
														$response->success = 1;
														$response->message = "Data cuti telah ditambahkan.";
														die(json_encode($response));
													}else{
														$response = new usr();
														$response->success = 0;
														$response->message = "Maaf Cuti Dibatasi 2 Hari.";
														die(json_encode($response));
													}
												}else if($reason_desc == "Cuti Melahirkan"){
													if($days <= 60){


														header('Access-Control-Allow-Origin: *');
														$sql = "INSERT INTO tbl_cuti (nik, reason, status_approv, approve_by, jumlah_hari, potong_cuti, start_date, end_date, image) VALUES('".$user_id."','".$reason_desc."','0','','".$days."','0','".$start_date."','".$end_date."','".$newfilename."')";
								        		$query2 = mysqli_query($conn, $sql);
														header('Access-Control-Allow-Origin: *');
														$sql2 = "UPDATE tbl_kuota_cuti SET kuota = '".$hitung."' WHERE nik = '".$user_id."'";
								        		$query3 = mysqli_query($conn, $sql2);
														header('Access-Control-Allow-Origin: *');
														compress($_FILES['image']['tmp_name'], $actualpath, 65);
														//echo $sql;
														//echo json_encode($num_rows2);

														$response = new usr();
														$response->success = 1;
														$response->message = "Data cuti telah ditambahkan.";
														die(json_encode($response));
													}else{
														$response = new usr();
														$response->success = 0;
														$response->message = "Maaf Cuti Dibatasi 60 Hari.";
														die(json_encode($response));
													}
												}else if($reason_desc == "Cuti Nikah"){
													if($days <= 2){


														header('Access-Control-Allow-Origin: *');
														$sql = "INSERT INTO tbl_cuti (nik, reason, status_approv, approve_by, jumlah_hari, potong_cuti, start_date, end_date, image) VALUES('".$user_id."','".$reason_desc."','0','','".$days."','0','".$start_date."','".$end_date."','".$newfilename."')";
								        		$query2 = mysqli_query($conn, $sql);
														header('Access-Control-Allow-Origin: *');
														$sql2 = "UPDATE tbl_kuota_cuti SET kuota = '".$hitung."' WHERE nik = '".$user_id."'";
								        		$query3 = mysqli_query($conn, $sql2);
														header('Access-Control-Allow-Origin: *');
														compress($_FILES['image']['tmp_name'], $actualpath, 65);
														//echo $sql;
														//echo json_encode($num_rows2);

														$response = new usr();
														$response->success = 1;
														$response->message = "Data cuti telah ditambahkan.";
														die(json_encode($response));
													}else{
														$response = new usr();
														$response->success = 0;
														$response->message = "Maaf Cuti Dibatasi 2 Hari.";
														die(json_encode($response));
													}
												}else if($reason_desc == "Cuti Keagamaan"){
													if($days <= 2){


														header('Access-Control-Allow-Origin: *');
														$sql = "INSERT INTO tbl_cuti (nik, reason, status_approv, approve_by, jumlah_hari, potong_cuti, start_date, end_date, image) VALUES('".$user_id."','".$reason_desc."','0','','".$days."','0','".$start_date."','".$end_date."','".$newfilename."')";
								        		$query2 = mysqli_query($conn, $sql);
														header('Access-Control-Allow-Origin: *');
														$sql2 = "UPDATE tbl_kuota_cuti SET kuota = '".$hitung."' WHERE nik = '".$user_id."'";
								        		$query3 = mysqli_query($conn, $sql2);
														header('Access-Control-Allow-Origin: *');
														compress($_FILES['image']['tmp_name'], $actualpath, 65);
														//echo $sql;
														//echo json_encode($num_rows2);

														$response = new usr();
														$response->success = 1;
														$response->message = "Data cuti telah ditambahkan.";
														die(json_encode($response));
													}else{
														$response = new usr();
														$response->success = 0;
														$response->message = "Maaf Cuti Dibatasi 2 Hari.";
														die(json_encode($response));
													}
												}else{

												}

										}else{
											$response = new usr();
											$response->success = 0;
											$response->message = "Cuti Anda Sudah Habis.";
											die(json_encode($response));
										}


									}else{
										//Hitung Cuti
										if($reason_desc == "Cuti Tahunan"){
												$response = new usr();
												$response->success = 0;
												$response->message = "Anda belum bisa mengajukan cuti";
												die(json_encode($response));
										}else if($reason_desc == "Cuti Kematian"){
											if($days <= 2){


												$sql = "INSERT INTO tbl_cuti (nik, reason, status_approv, approve_by, jumlah_hari, potong_cuti, start_date, end_date, image) VALUES('".$user_id."','".$reason_desc."','0','','".$days."','0','".$start_date."','".$end_date."','".$newfilename."')";
												$query2 = mysqli_query($conn, $sql);
												header('Access-Control-Allow-Origin: *');
												compress($_FILES['image']['tmp_name'], $actualpath, 65);
												//echo $sql;
												//echo json_encode($num_rows2);

												$response = new usr();
												$response->success = 1;
												$response->message = "Data cuti telah ditambahkan.";
												die(json_encode($response));
											}else{
												$response = new usr();
												$response->success = 0;
												$response->message = "Maaf Cuti Dibatasi 2 Hari.";
												die(json_encode($response));
											}
										}else if($reason_desc == "Cuti Melahirkan"){
											if($days <= 60){


												$sql = "INSERT INTO tbl_cuti (nik, reason, status_approv, approve_by, jumlah_hari, potong_cuti, start_date, end_date, image) VALUES('".$user_id."','".$reason_desc."','0','','".$days."','0','".$start_date."','".$end_date."','".$newfilename."')";
												$query2 = mysqli_query($conn, $sql);
												header('Access-Control-Allow-Origin: *');
												compress($_FILES['image']['tmp_name'], $actualpath, 65);
												//echo $sql;
												//echo json_encode($num_rows2);

												$response = new usr();
												$response->success = 1;
												$response->message = "Data cuti telah ditambahkan.";
												die(json_encode($response));
											}else{
												$response = new usr();
												$response->success = 0;
												$response->message = "Maaf Cuti Dibatasi 60 Hari.";
												die(json_encode($response));
											}
										}else if($reason_desc == "Cuti Nikah"){
											if($days <= 2){

												$sql = "INSERT INTO tbl_cuti (nik, reason, status_approv, approve_by, jumlah_hari, potong_cuti, start_date, end_date, image) VALUES('".$user_id."','".$reason_desc."','0','claher','".$days."','0','".$start_date."','".$end_date."','".$newfilename."')";
												$query2 = mysqli_query($conn, $sql);
												header('Access-Control-Allow-Origin: *');
												compress($_FILES['image']['tmp_name'], $actualpath, 65);
												//echo $sql;
												//echo json_encode($num_rows2);

												$response = new usr();
												$response->success = 1;
												$response->message = "Data cuti telah ditambahkan.";
												die(json_encode($response));
											}else{
												$response = new usr();
												$response->success = 0;
												$response->message = "Maaf Cuti Dibatasi 2 Hari.";
												die(json_encode($response));
											}
										}else if($reason_desc == "Cuti Keagamaan"){
											if($days <= 2){


												$sql = "INSERT INTO tbl_cuti (nik, reason, status_approv, approve_by, jumlah_hari, potong_cuti, start_date, end_date, image) VALUES('".$user_id."','".$reason_desc."','0','','".$days."','0','".$start_date."','".$end_date."','".$newfilename."')";
												$query2 = mysqli_query($conn, $sql);
												header('Access-Control-Allow-Origin: *');
												compress($_FILES['image']['tmp_name'], $actualpath, 65);
												//echo $sql;
												//echo json_encode($num_rows2);

												$response = new usr();
												$response->success = 1;
												$response->message = "Data cuti telah ditambahkan.";
												die(json_encode($response));
											}else{
												$response = new usr();
												$response->success = 0;
												$response->message = "Maaf Cuti Dibatasi 2 Hari.";
												die(json_encode($response));
											}
										}else{

										}

									}

								}else{
									$response = new usr();
									$response->success = 0;
									$response->message = "Maaf Anda tidak memiliki kuota cuti.";
									die(json_encode($response));
								}



        			} else {
        				$response = new usr();
        				$response->success = 0;
        				$response->message = "Data cuti gagal ditambahkan.";
        				die(json_encode($response));
        			}

    		    } else{
    		        $response = new usr();
        			$response->success = 0;
        			$response->message = "Invalid end date";
        			die(json_encode($response));
    		    }

		    } else{
		        $response = new usr();
    			$response->success = 0;
    			$response->message = "Invalid start date";
    			die(json_encode($response));
		    }
		}
	}

	// fungsi random string pada gambar untuk menghindari nama file yang sama
	function random_word($id = 20){
		$pool = '1234567890abcdefghijkmnpqrstuvwxyz';

		$word = '';
		for ($i = 0; $i < $id; $i++){
			$word .= substr($pool, mt_rand(0, strlen($pool) -1), 1);
		}
		return $word;
	}

	function validateDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }


	//mysqli_close($con);
?>
