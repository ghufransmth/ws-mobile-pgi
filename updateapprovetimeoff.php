<?php
	include "koneksi.php";

	require_once __DIR__ . '/firebase/firebase.php';
	require_once __DIR__ . '/firebase/push.php';

	class usr{}

	$id    			= $_POST["id"];
	$user_id    = $_POST["user_id"];

	$firebase = new Firebase();
	$push = new Push();

	// optional payload
	$payload = array();
	$payload['team'] = 'India';
	$payload['score'] = '5.6';

	if ((empty($id))) {
		$response = new usr();
		$response->success = 0;
		$response->message = "No Value Post";
		die(json_encode($response));
	} else {
		if (!empty($id)){

			header('Access-Control-Allow-Origin: *');
			$users = mysqli_query($conn, "select A.id, A.nama_user, A.username, A.level, NULL AS email, B.id_cabang, B.id_jabatan, B.Lokasi_Absen, C.Nm_Cabang FROM user A
			LEFT JOIN tbl_karyawan B ON B.nik = A.username
	    LEFT JOIN tbl_cabang C ON C.kdCabang = B.id_cabang
	    WHERE A.username = '".$user_id."'");

			while ($row = mysqli_fetch_array($users)) {
	        $name = $row['nama_user'];
	    }

			header('Access-Control-Allow-Origin: *');
			$query = mysqli_query($conn, "UPDATE tbl_timeoff SET status_approv = 1 , approve_by = '".$name."' WHERE id = '".$id."'");
			$stm = mysqli_affected_rows($conn);

			if ($stm > 0){
					$title = 'PUSAT GADAI';
					$message = 'Pengajuan Timeoff Disetujui';
					$push_type = 'individual';
					$include_image = FALSE;

					// notification title
					$title = isset($title) ? $title : '';

					// notification message
					$message = isset($message) ? $message : '';

					// push type - single user / topic
					$push_type = isset($push_type) ? $push_type : '';

					// whether to include to image or not
					$include_image = isset($include_image) ? TRUE : FALSE;


					$push->setTitle($title);
					$push->setMessage($message);
					if ($include_image) {
							$push->setImage('https://pbs.twimg.com/profile_images/915401561546698752/toQDeCEW_400x400.jpg');
					} else {
							$push->setImage('');
					}
					$push->setIsBackground(FALSE);
					$push->setPayload($payload);


					$json = '';
					$response = '';

					$query = mysqli_query($conn, "SELECT B.regId FROM tbl_timeoff A LEFT JOIN user B ON A.nik = B.username WHERE A.id = '".$id."'");
					$count = mysqli_num_rows($query);
					while ($row = mysqli_fetch_array($query)) {
							$regId = ($row['regId'] != null) ? $row['regId'] : '';
					}
					if ($count > 0){
							$json = $push->getPush();
							$response = $firebase->send($regId, $json);
					}

					$response = new usr();
					$response->success = 1;
					$response->message = "Data Timeoff telah diapprove.";
					die(json_encode($response));

			} else {
					$response = new usr();
					$response->success = 0;
					$response->message = "Gagal mengupdate.";
					die(json_encode($response));
			}



		}
	}

	mysqli_close($con);
?>
