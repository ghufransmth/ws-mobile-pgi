<?php
	include "koneksi.php";

	class usr{}

	$user_id    = $_POST["user_id"];

	function cUrlGetData($url, $post_fields = null, $headers = null) {
	    $ch = curl_init();
	    $timeout = 5;
	    curl_setopt($ch, CURLOPT_URL, $url);
	    if ($post_fields && !empty($post_fields)) {
	        curl_setopt($ch, CURLOPT_POST, 1);
	        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
	    }
	    if ($headers && !empty($headers)) {
	        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	    }
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	    $data = curl_exec($ch);
	    if (curl_errno($ch)) {
	        echo 'Error:' . curl_error($ch);
	    }
	    curl_close($ch);
	    return $data;
	}

	// date_default_timezone_set('Asia/Jakarta');
	// $md5 = md5('S!janGk@r#'.date('dmY'));

	$url = "http://116.206.196.58:81/hris/testapi/index_get";
	$post_fields = 'user_id='.$user_id;
	$headers = ['Content-Type' => 'application/x-www-form-urlencoded', 'charset' => 'utf-8'];
	$dat = cUrlGetData($url, $post_fields, $headers);
	$responseJSON = json_decode($dat, true);

	//echo json_encode($responseJSON[0]['fix_gaji']);

	if ((empty($user_id))) {
		$response = new usr();
		$response->success = 0;
		$response->message = "Login terlebih dahulu";
		die(json_encode($response));
	} else {
		if (!empty($user_id)){

			if (!empty($responseJSON)){
					$response = new usr();
					$response->success = 1;
								// while ($row = mysqli_fetch_array($query)) {
								// 	// temp user array
								// 		$json = array();
								// 		$json['id']= $row['id'];
								// 		$json['nm_Karyawan']= $row['nm_Karyawan'];
								// 		$json['No_Rek']= $row['No_Rek'];
								// 		$json['fix_gaji']= rupiah($row['fix_gaji']);
								// }
					$json = array();
					$json['fix_gaji']= rupiah($responseJSON[0]['fix_gaji']);
					$response->message = "Data user payroll.";
					$response->data = $json;
					die(json_encode($response));

			} else {
					$response = new usr();
					$response->success = 0;
					$response->message = "Tidak ada data payroll.";
					die(json_encode($response));
			}



		}
	}

  function rupiah($angka){

  	$hasil_rupiah = "Rp " . number_format($angka,2,',','.');
  	return $hasil_rupiah;

  }

	mysqli_close($con);
?>
