<?php

	include "koneksi.php";

	class usr{}

	$username = $_POST["username"];
	$password = $_POST["password"];
	$encyrpt  = md5(trim($password));

	if ((empty($username)) || (empty($password))) {
		$response = new usr();
		$response->success = 0;
		$response->message = "Kolom tidak boleh kosong";
		die(json_encode($response));
	}else{
		$sql1 = "SELECT id FROM user WHERE username='".$username."'";
		$query1 = mysqli_query($conn,$sql1);
		$count1 = mysqli_num_rows($query1);
		if($count1){
			header('Access-Control-Allow-Origin: *');
			//$query = mysqli_query($conn, "SELECT id,username, password, email, kode_jabatan, user_level FROM tb_users WHERE username='".$username."' AND password='".$encyrpt."'");
			$query = mysqli_query($conn, "select A.id, A.nama_user AS fullname, A.username, A.level, NULL AS email, B.status_kontrak, B.id_jabatan, C.nama_jabatan FROM user A
		LEFT JOIN tbl_karyawan B ON B.nik = A.username
		LEFT JOIN tbl_jabatan C ON C.id_jabatan = B.id_jabatan
    WHERE A.username = '$username' AND A.password = '$encyrpt'");

			$row = mysqli_fetch_array($query);
			$count2 = mysqli_num_rows($query);

			if ($count2 > 0){

				// $query2 = mysqli_query($conn, "UPDATE tb_users_ptt SET attempt=0 WHERE nik_ptt='".$username."'");

				$response = new usr();
				$response->success = 1;
				$response->message = "Selamat datang ".$row['username'];
				$response->data = $row;
				die(json_encode($response));

			} else {

				// $sql3 = "SELECT attempt FROM tb_users WHERE username='".$username."' AND attempt <= 3";
				// $query3 = mysqli_query($conn,$sql3);
				// $count3 = mysqli_num_rows($query3);

				// if ($count3){

			 //   	$query2 = mysqli_query($conn, "UPDATE tb_users SET attempt=attempt + 1 WHERE username='".$username."'");

			 //   	if($query2){

			 //   	$sql4 = "SELECT attempt FROM tb_users WHERE username='".$username."' AND attempt <= 4";
				// 	$query4 = mysqli_query($conn,$sql4);
				// 	$row4 = mysqli_fetch_array($query4);

				// 		if($row4['attempt'] == 4){

				// 			$query5 = mysqli_query($conn, "UPDATE tb_users SET blocked=0 WHERE username='".$username."'");

				// 			$response = new usr();
				// 			$response->success = 0;
				// 			$response->message = "Please Contact Call Center.";
				// 			die(json_encode($response));

				// 		}else{
				// 			$response = new usr();
				// 			$response->success = 0;
		  //          		$response->message = "Usaha login sudah :".$row4['attempt'];
		  //          		// $response->data = $json;
				// 			die(json_encode($response));
				// 		}


				// 	}

				// }else{
				// 		$response = new usr();
				// 		$response->success = 0;
				// 		$response->message = "Please Contact Call Center.";
				// 		die(json_encode($response));
				// }

				$response = new usr();
			    $response->success = 0;
			    $response->message = "Silahkan Coba Lagi";
			    die(json_encode($response));


			}

		}else{
			$response = new usr();
			$response->success = 0;
			$response->message = "Username atau password salah"; //tidak terdaftar
			die(json_encode($response));
		}
	}




	mysqli_close($conn);
?>
