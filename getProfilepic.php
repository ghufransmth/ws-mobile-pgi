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

			$query = mysqli_query($conn, "select A.*, B.nm_Karyawan, B.foto FROM user A
		LEFT JOIN tbl_informasikaryawan B ON B.nik = A.username WHERE A.username='".$user_id."'");
			$count = mysqli_num_rows($query);

			if ($count){
					$response = new usr();
					$response->success = 1;
					$response->data = array();
            		while ($row = mysqli_fetch_array($query)) {
            			// temp user array
            			$json = array();
            		    $json['id']= $row['id'];
            		    $json['username']= $row['username'];
										$json['fullname']= $row['nama_user'];
            		  //  $json['first_name']= $row['first_name'];
            		  //  $json['email']= $row['email'];
            		  //  $json['phone']= $row['phone'];
            		  //  $json['nama_jabatan']= $row['nama_jabatan'];
            		  //  $json['atasan_1']= $row['atasan_1'];
            		  //  $json['atasan_2']= $row['atasan_2'];
            		    $path = "http://116.206.196.58:81/mobile_dev/images/".$row['foto']."";
            		    $json['profile_image']= $path;
            		    $json['user_level']= $row['level'];
            		    // push single puasa into final response array
			            array_push($response->data, $json);
            		}
					$response->message = "Data Users.";
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
