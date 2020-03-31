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

			// $sql = "SELECT A.tanggal, 'Absensi Kehadiran' as keterangan, B.nama_user as approve_by FROM tbl_absensi as A LEFT JOIN user as B ON (A.nik = B.username) WHERE A.nik = '".$user_id."'
      //           UNION
      //           SELECT C.tanggal, 'Lembur' as keterangan, D.nama_user as approve_by FROM tbl_absensi as C LEFT JOIN user as D ON (C.nik = D.username) WHERE C.nik = '".$user_id."'
      //           UNION
      //           SELECT E.created_on as tanggal, E.reason as keterangan, F.nama_user as approve_by FROM tbl_timeoff as E LEFT JOIN user as F ON (E.nik = F.username)  WHERE E.nik = '".$user_id."'
      //           ORDER BY tanggal DESC";
			$sql = "SELECT E.created_on as tanggal, E.reason as keterangan, E.approve_by FROM tbl_timeoff as E LEFT JOIN user as F ON (E.nik = F.username)  WHERE E.nik = '".$user_id."'
                ORDER BY tanggal DESC";
			$query = mysqli_query($conn, $sql);
      $count = mysqli_num_rows($query);

			if ($count > 0){
					$response = new usr();
					$response->success = 1;
					$response->data = array();
            		while ($row = mysqli_fetch_array($query)) {
            			// temp user array
            			$json = array();
            		    $json['tanggal']= date("F j, Y", strtotime($row['tanggal']));

            		    $json['waktu']= date("g:i a", strtotime($row['tanggal']));

            		    $json['keterangan'] = $row['keterangan'];

            		    // if($row['status_approv'] == 1)
            		    // $row['status_approv'] = 'Menunggu Persetujuan';
            		    // else if($row['status_approv'] == 2)
            		    // $row['status_approv'] = 'Tidak Disetujui';
            		    // else
            		    // $row['status_approv'] = 'Disetujui';
            		    // $json['status']= $row['status_approv'];

            		    $json['approve_by'] = $row['approve_by'];

            		    // push single puasa into final response array
			            array_push($response->data, $json);
            		}
					$response->message = "Data Notifikasi.";
					$response->counts = $count;
					die(json_encode($response));

			} else {
					$response = new usr();
					$response->success = 0;
					$response->message = "Gagal data notif.";
					die(json_encode($response));
			}



		}
	}

	mysqli_close($con);
?>
