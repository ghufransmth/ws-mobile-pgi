<?php
	include "koneksi.php";

	class usr{}

	$user_id    = $_POST["id"];
  $fullname   = $_POST["fullname"];
  $username   = $_POST["username"];
  $password   = md5(trim($_POST["password"]));
  $nik        = $_POST["nik"];
  $jabatan    = $_POST["jabatan"];
  $status     = $_POST["status"];
	if(empty($_FILES['image']['name'])){
		$image = '';
	}else{
		$image = $_FILES['image']['name'];
	}

	if ((empty($user_id))) {
		$response = new usr();
		$response->success = 0;
		$response->message = "Login terlebih dahulu";
		die(json_encode($response));
	} else {
		if (!empty($user_id)){

      if($image == '' || $image == 0){
        if($password == '' || $password == 0){
          header('Access-Control-Allow-Origin: *');
    			$query = mysqli_query($conn, "UPDATE user SET nama_user = '".$fullname."' WHERE username = '".$username."'");
        }else{
          header('Access-Control-Allow-Origin: *');
          $query = mysqli_query($conn, "UPDATE user SET nama_user = '".$fullname."' , password = '".$password."' WHERE username = '".$username."'");
        }
  			$response = new usr();
  			$response->success = 1;
				$response->result = TRUE;
  			$response->message = "Data Profile diupdate.";
  			die(json_encode($response));
      }else{
        $random = random_word(20);
        $path = "images/".$image;
        // sesuiakan ip address laptop/pc atau URL server
        $actualpath = $_SERVER["DOCUMENT_ROOT"]."/mobile_dev/$path";
        if($password == '' || $password == 0){
          header('Access-Control-Allow-Origin: *');
    			$query = mysqli_query($conn, "UPDATE user SET nama_user = '".$fullname."' WHERE username = '".$username."'");
        }else{
          header('Access-Control-Allow-Origin: *');
          $query = mysqli_query($conn, "UPDATE user SET nama_user = '".$fullname."' , password = '".$password."' WHERE username = '".$username."'");
        }
        header('Access-Control-Allow-Origin: *');
        $query2 = mysqli_query($conn, "UPDATE tbl_informasikaryawan SET foto = '".$image."' WHERE nik = '".$username."'");
        header('Access-Control-Allow-Origin: *');
        move_uploaded_file($_FILES['image']['tmp_name'], $actualpath);

  			$response = new usr();
  			$response->success = 1;
				$response->result = TRUE;
  			$response->message = "Data Profile diupdate.";
  			die(json_encode($response));
      }

// 			$stm = mysqli_affected_rows($query);

// 			if ($stm > 0){
// 					$response = new usr();
// 					$response->success = 1;
// 					$response->message = "Data History telah dimasukkan.";
// 					die(json_encode($response));

// 			} else {
// 					$response = new usr();
// 					$response->success = 0;
// 					$response->message = "Anda bukan user.";
// 					die(json_encode($response));
// 			}

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

	mysqli_close($con);
?>
