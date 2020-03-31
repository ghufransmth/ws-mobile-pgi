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

			$query = mysqli_query($conn, "select A.id, A.nama_user AS fullname, A.username, A.level, NULL AS email, D.no_KTP, B.status_kontrak, B.id_jabatan, C.nama_jabatan FROM user A
		LEFT JOIN tbl_karyawan B ON B.nik = A.username
		LEFT JOIN tbl_jabatan C ON C.id_jabatan = B.id_jabatan
		LEFT JOIN tbl_informasikaryawan D ON D.nik = A.username
    WHERE A.username = '".$user_id."' ");
			$count = mysqli_num_rows($query);
			if ($count){
					$response = new usr();
					$response->success = 1;
            		while ($row = mysqli_fetch_array($query)) {
            			// temp user array
            		    $json = array();
            		    $json['id']= $row['id'];
										$json['fullname']= $row['fullname'];
            		    $json['username']= $row['username'];
            		    $json['nik']= $row['no_KTP'];
										$json['nama_jabatan']= $row['nama_jabatan'];
										$json['status_kontrak']= $row['status_kontrak'];
            		}
					$response->message = "Data user profile.";
					$response->data = $json;
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
