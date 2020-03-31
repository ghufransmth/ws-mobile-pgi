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
			$query = mysqli_query($conn, "SELECT tanggal,image,jam_masuk,jam_keluar FROM tbl_absensi WHERE nik='".$user_id."' ORDER BY tanggal DESC");
			$count = mysqli_num_rows($query);

			if ($count > 0){
					$response = new usr();
					$response->success = 1;
					$response->data = array();
            		while ($row = mysqli_fetch_array($query)) {
            			// temp user array
            			$json = array();
            		    /* $json['created_on']= date("F j, Y", strtotime($row['start_date']));
            		    $json['time']= date("g:i a", strtotime($row['start_date']));  */

						/* $rows = array();
						for ($i = 0; $i < count($row["start_date"]); $i++) {
							 $rows["date"] = $row["start_date"];
						}

						$json['created_on'] = $rows;  */
					    			$json['created_on']= date("F j, Y", strtotime($row['tanggal']));
										if(date("H", strtotime($row['jam_masuk'])) > 0)
            		    $jam_masuk = date("g:i a", strtotime($row['jam_masuk']));
            		    else
            		    $jam_masuk = '-';
            		    $json['time']= date("g:i a", strtotime($row['jam_masuk']));
										// $jam = date("G", strtotime($row['jam_masuk']));
            		    // if($jam > 0)
            		    // $row['status_approv'] = 'Telah Masuk';
            		    // else
            		    // $row['status_approv'] = 'Telah Keluar';
            		    $json['status']= 'Jam Masuk : '.$jam_masuk;
            		    // if($row['status_approv'] == 1)
            		    // $row['status_approv'] = 'Menunggu Persetujuan';
            		    // else if($row['status_approv'] == 2)
            		    // $row['status_approv'] = 'Tidak Disetujui';
            		    // else
            		    // $row['status_approv'] = 'Disetujui';
            		    // $json['status']= $row['status_approv'];
										//
            		    // if($row['work'] == 1)
            		    // $row['work'] = 'Sedang Bekerja';
            		    // else
            		    // $row['work'] = 'Telah Selesai Bekerja';
										if(date("H", strtotime($row['jam_keluar'])) > 0)
            		    $jam_keluar = date("g:i a", strtotime($row['jam_keluar']));
            		    else
            		    $jam_keluar = '-';
            		    $json['work']= 'Jam Keluar : '.$jam_keluar;

            		    $path = $url_public."/images/".$row['image']."";
            		    $json['image']= $path;

            		    // push single puasa into final response array
			            array_push($response->data, $json);
            		}
					$response->message = "Data Report.";
					die(json_encode($response));

			} else {
					$response = new usr();
					$response->success = 0;
					$response->message = "Tidak Ada Data Absen.";
					die(json_encode($response));
			}



		}
	}

	mysqli_close($con);
?>
