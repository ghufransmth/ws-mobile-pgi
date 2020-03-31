<?php
	include "koneksi.php";

	class usr{}

	$user_id    = $_POST["user_id"];
	$start_date = $_POST["start_date"];
	$end_date   = $_POST["end_date"];
	$reason     = $_POST["reason"];
	$keterangan = $_POST["keterangan"];
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
						$reason = mysqli_query($conn, "SELECT * FROM tbl_alasan WHERE id = '".$reason."'");
					  while ($row = mysqli_fetch_array($reason)) {
					    $id_reason = $row['id'];
					    $reason_desc = $row['nama'];
					  }

						header('Access-Control-Allow-Origin: *');
						$query = mysqli_query($conn, "SELECT * FROM user WHERE username='".$user_id."'");
        		$num_rows = mysqli_num_rows($query);

        		if ($num_rows > 0){

								$random = random_word(20);
								$temp = explode(".", $image);
        				$newfilename = round(microtime(true)) . $user_id . '.' .end($temp);
								$path = "images_timeoff/".$newfilename;
								// sesuiakan ip address laptop/pc atau URL server
								$actualpath = $_SERVER["DOCUMENT_ROOT"]."/mobile_dev/$path";

								// $expbanner = explode('.',basename($_FILES['image']['name']));
								// $bannerexptype = $expbanner[1];
								// date_default_timezone_set('Asia/Jakarta');
								// $date = date('m/d/Yh:i:sa', time());
								// $rand = rand(10000,99999);
								// $encname = $date.$rand;
								// $bannername = md5($encname).'.'.$bannerexptype;
								// $actualpath = $_SERVER["DOCUMENT_ROOT"]."/mobile_dev/images_timeoff/".$bannername;

								$hitung = 0;
								$earlier = new DateTime($start_date);
								$later = new DateTime($end_date);
								$diff = $later->diff($earlier)->format("%a");
								$hitung = $diff+1;
								$sql = "INSERT INTO tbl_timeoff (nik, reason, status_approv, approve_by, jumlah_hari, potong_cuti, start_date, end_date, keterangan, image) VALUES('".$user_id."','".$reason_desc."','0','','".$hitung."','0','".$start_date."','".$end_date."','".$keterangan."','".$newfilename."')";
		        		$query2 = mysqli_query($conn, $sql);
								header('Access-Control-Allow-Origin: *');
								//panggil fungsi compress,
							  compress($_FILES['image']['tmp_name'], $actualpath, 65);
								//move_uploaded_file($_FILES['image']['tmp_name'], $actualpath);
								//echo $sql;
								//echo json_encode($num_rows2);

								$response = new usr();
        				$response->success = 1;
        				$response->message = "Data timeoff telah dimasukkan.";
        				die(json_encode($response));


        			} else {
        				$response = new usr();
        				$response->success = 0;
        				$response->message = $user_id;
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

	function compress($source, $destination, $quality)
  {
      $info = getimagesize($source);
      if ($info['mime'] == 'image/jpeg') $image = imagecreatefromjpeg($source);
      elseif ($info['mime'] == 'image/gif') $image = imagecreatefromgif($source);
      elseif ($info['mime'] == 'image/png') $image = imagecreatefrompng($source);
      imagejpeg($image, $destination, $quality);
      return $destination;
  }


	//mysqli_close($con);
?>
