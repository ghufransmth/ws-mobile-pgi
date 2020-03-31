<?php

  include "koneksi.php";
	class usr{}

  //PROSES POST DATA
  $user_id    = $_POST['user_id'];
	// $start      = $_POST['start'];
	$image      = $_FILES['image']['name'];
  // $image      = '';
  $lat        = $_POST['lat'];
	$lang       = $_POST['lang'];

	header('Access-Control-Allow-Origin: *');
	if (empty($user_id)) {
		$response = new usr();
		$response->success = 0;
		$response->message = "Login terlebih dahulu.";
		die(json_encode($response));
	} else if ((empty($lat))) {
		$response = new usr();
		$response->success = 0;
		$response->message = "Kolom lattitude tidak boleh kosong";
		die(json_encode($response));
	} else if ((empty($lang))) {
		$response = new usr();
		$response->success = 0;
		$response->message = "Kolom longitude tidak boleh kosong";
		die(json_encode($response));
	} else {
    $lokasi = '';
    //SELECT DATA USER DAN LAT LONG NYA
    header('Access-Control-Allow-Origin: *');
		$users = mysqli_query($conn, "select A.id, A.nama_user, A.username, A.level, NULL AS email, B.LokasiID, B.id_cabang, B.id_jabatan, B.Lokasi_Absen, C.Nm_Cabang FROM user A
		LEFT JOIN tbl_karyawan B ON B.nik = A.username
    LEFT JOIN tbl_cabang C ON C.kdCabang = B.id_cabang
    WHERE A.username = '".$user_id."'");

    while ($row = mysqli_fetch_array($users)) {
        $name = $row['username'];
        $kd_cabang = $row['id_cabang'];
        $lokasi_id = $row['LokasiID'];
        $nm_cabang = $row['Nm_Cabang'];
        $id_jabatan = $row['id_jabatan'];
        $lokasi = ($row['Lokasi_Absen'] != null) ? $row['Lokasi_Absen'] : '';
    }

    //SELECT JARAK DARI LOKASI
    $jarak = 1;
    $cari_jaraks = 0;
    $lokasiLat = substr($lokasi,0,9);
    $lokasiLong = substr($lokasi,11);
    header('Access-Control-Allow-Origin: *');
		$cari_jarak = mysqli_query($conn, "SELECT (6371 * acos(
                cos( radians($lokasiLat) )
              * cos( radians($lat) )
              * cos( radians($lang) - radians($lokasiLong) )
              + sin( radians($lokasiLat) )
              * sin( radians($lat) )
            ) ) as jarak");
    while ($rows = mysqli_fetch_array($cari_jarak)) {
        $cari_jaraks = $rows['jarak'];
    }

    //IMAGE SETTINGS
    $random = random_word(20);
    $temp = explode(".", $image);
    $newfilename = round(microtime(true)) . $user_id . '.' .end($temp);
    $path = "images/".$newfilename;
    // sesuiakan ip address laptop/pc atau URL server
    $actualpath = $_SERVER["DOCUMENT_ROOT"]."/mobile_dev/$path";

    if($lokasi != '' || $lokasi != null){

      $lokasis = str_replace(' ', '', $lokasi);
      //SELECT TIMEZONE LOKASI ABSEN
      $timezone = http_request("https://maps.googleapis.com/maps/api/timezone/json?location=$lokasis&timestamp=1331161200&key=AIzaSyDehSFW1fG9E6CMXy80LuFfVkn0Cu2s1A0");
      // ubah string JSON menjadi array
      $timezone = json_decode($timezone, TRUE);

      $num_rows = mysqli_num_rows($users);

      $allow_distance = 0.2;
  		/* echo json_encode($num_rows); */
      if(((float)$cari_jaraks) <= $allow_distance){
  			if ($num_rows > 0){
          //MENENTUKAN LOKASI ABSEN PUSAT ATAU CABANG
          if($lokasi_id == 1){
              date_default_timezone_set($timezone['timeZoneId']);
              $datenow    = date('Y-m-d');
              // $jam_masuk   = date('H:i:s',strtotime($start));
              // $hour_masuk   = date('H',strtotime($start));
              $jam_masuk   = date('H:i:s');
              $hour_masuk   = date('H');
              $jam_keluar  = date('H:i:s');
              $datestart    = date("Y-m-d H:i:s");

              $now = new DateTime($jam_masuk);
              //SHIFT 1
              $start_timen = new DateTime('01:00');
              $end_timen = new DateTime('07:50');
              $start_time1 = new DateTime('07:51');
              $end_time1 = new DateTime('08:01');
              $start_time2 = new DateTime('08:02');
              $end_time2 = new DateTime('08:30');
              $start_time3 = new DateTime('08:31');
              $end_time3 = new DateTime('09:00');
              $start_time4 = new DateTime('09:01');
              $end_time4 = new DateTime('10:00');
              $start_time5 = new DateTime('10:01');
              $end_time5 = new DateTime('10:44');
              $start_timex = new DateTime('10:45');
              $end_timex = new DateTime('11:50');
              //SHIFT 2
              $start_time6 = new DateTime('11:51');
              $end_time6 = new DateTime('12:01');
              $start_time7 = new DateTime('12:02');
              $end_time7 = new DateTime('12:30');
              $start_time8 = new DateTime('12:31');
              $end_time8 = new DateTime('13:00');
              $start_timex2 = new DateTime('13:01');
              $end_timex2 = new DateTime('16:59');
              //lEMBUR
              $lembur = 0;

              //SHIFT 1 03.00 - 07.51
              if($now >= $start_timen && $now < $start_time1){
                $shift = 1;
                $telat = 0;
			  }else if ($now >= $start_time1 && $now < $start_time2){
				  //07.51 - 08.02
                $shift = 1;
                $telat = 1;
              }else if($now >= $start_time2 && $now < $start_time3){
				  //08.02 - 08.31
                $shift = 1;
                $telat = 2;
              }else if($now >= $start_time3 && $now < $start_time4){
				  //08.31 - 09.01
                $shift = 1;
                $telat = 3;
              }else if($now >= $start_time4 && $now < $start_time5){
				  //09.01 - 10.01
                $shift = 1;
                $telat = 4;
              }else if($now >= $start_time5 && $now < $start_timex){
				  //10.01 - 10.45
                $shift = 1;
                $telat = 5;
              }else if($now >= $start_timex && $now < $start_time6){
				  //10.45 - 11.51
                $shift = 2;
                $telat = 0;
              }else if($now >= $start_time6 && $now < $start_time7){ //SHIFT 2
				  //11.51 - 12.02
                $shift = 2;
                $telat = 1;
              }else if($now >= $start_time7 && $now < $start_time8){
				  //12.02 - 12.31
                $shift = 2;
                $telat = 2;
              }else if($now >= $start_time8 && $now < $start_timex2){
				  //12.31 - 13.01
                $shift = 2;
                $telat = 3;
              }else{
				  //13.01 - xx
                $shift = 0;
                $telat = 0;
              }

          }else{
              date_default_timezone_set($timezone['timeZoneId']);
              $datenow    = date('Y-m-d');
              // $jam_masuk   = date('H:i:s',strtotime($start));
              // $hour_masuk   = date('H',strtotime($start));
              $jam_masuk   = date('H:i:s');
              $hour_masuk   = date('H');
              $jam_keluar  = date('H:i:s');
              $datestart    = date("Y-m-d H:i:s");

              $now = new DateTime($jam_masuk);
              //SHIFT 1
              $start_timen = new DateTime('01:00');
              $end_timen = new DateTime('07:50');
              $start_time1 = new DateTime('07:51');
              $end_time1 = new DateTime('08:01');
              $start_time2 = new DateTime('08:02');
              $end_time2 = new DateTime('08:30');
              $start_time3 = new DateTime('08:31');
              $end_time3 = new DateTime('09:00');
              $start_time4 = new DateTime('09:01');
              $end_time4 = new DateTime('10:00');
              $start_time5 = new DateTime('10:01');
              $end_time5 = new DateTime('10:44');
              $start_timex = new DateTime('10:45');
              $end_timex = new DateTime('11:50');
              //SHIFT 2
              $start_time6 = new DateTime('11:51');
              $end_time6 = new DateTime('12:01');
              $start_time7 = new DateTime('12:02');
              $end_time7 = new DateTime('12:30');
              $start_time8 = new DateTime('12:31');
              $end_time8 = new DateTime('13:00');
              $start_timex2 = new DateTime('13:01');
              $end_timex2 = new DateTime('16:59');
              //lEMBUR
              $start_lem1 = new DateTime('17:30');
              $end_lem1 = new DateTime('18:59');
              $start_lem2 = new DateTime('19:00');
              $end_lem2 = new DateTime('20:59');
              $start_lem3 = new DateTime('21:00');
              $end_lem3 = new DateTime('23:59');

              //SHIFT 1 03.00 - 07.51
                if($now >= $start_timen && $now < $start_time1){
                  $shift = 1;
                  $telat = 0;
  			  }else if ($now >= $start_time1 && $now < $start_time2){
  				  //07.51 - 08.02
                  $shift = 1;
                  $telat = 1;
                }else if($now >= $start_time2 && $now < $start_time3){
  				  //08.02 - 08.31
                  $shift = 1;
                  $telat = 2;
                }else if($now >= $start_time3 && $now < $start_time4){
  				  //08.31 - 09.01
                  $shift = 1;
                  $telat = 3;
                }else if($now >= $start_time4 && $now < $start_time5){
  				  //09.01 - 10.01
                  $shift = 1;
                  $telat = 4;
                }else if($now >= $start_time5 && $now < $start_timex){
  				  //10.01 - 10.45
                  $shift = 1;
                  $telat = 5;
                }else if($now >= $start_timex && $now < $start_time6){
  				  //10.45 - 11.51
                  $shift = 2;
                  $telat = 0;
                }else if($now >= $start_time6 && $now < $start_time7){ //SHIFT 2
  				  //11.51 - 12.02
                  $shift = 2;
                  $telat = 1;
                }else if($now >= $start_time7 && $now < $start_time8){
  				  //12.02 - 12.31
                  $shift = 2;
                  $telat = 2;
                }else if($now >= $start_time8 && $now < $start_timex2){
  				  //12.31 - 13.01
                  $shift = 2;
                  $telat = 3;
                }else{
  				  //13.01 - xx
                  $shift = 0;
                  $telat = 0;
                }

              if ($now >= $start_lem1 && $now <= $end_lem1)
              {
                if($shift == 2){
                  $lembur = 0;
                }else{
                  $lembur = 1;
                }
              }else if($now >= $start_lem2 && $now <= $end_lem2){
                if($shift == 2){
                  $lembur = 0;
                }else{
                  $lembur = 2;
                }
              }else if($now >= $start_lem3 && $now <= $end_lem3){
                if($shift == 2){
                  $lembur = 0;
                }else{
                  $lembur = 3;
                }
              }else{
                $lembur = 0;
              }
          }



              //KONDISI SHIFT 1 DAN BATASAN KETIKA MELEBIHI JAM 12 DIHITUNG ABSEN KELUAR
              if(((int) $hour_masuk) >= 12 && ((int) $hour_masuk) <= 21){

                  header('Access-Control-Allow-Origin: *');
                  $CekPresensi = mysqli_query($conn, "SELECT * FROM tbl_absensi WHERE nik ='".$user_id."' AND DATE(tanggal) = CURDATE() ORDER BY id");
                  $num_rows2 = mysqli_num_rows($CekPresensi);
                  if($num_rows2 > 0){
                    //CHECK ROLE CCTV
                    $add = 0;
                    $hitung = 0;
                    $id_free_role = mysqli_query($conn, "select * from tbl_free_role where id = 2 limit 1");
                    while ($row = mysqli_fetch_array($id_free_role)) {
                        $add = json_decode($row['id_free_role']);
                        $length = count($add);
                        $resultList = array();
                        for($i=0; $i < $length; $i++){
                          $hitung = $add[$i];
                        }
                    }

                    if($id_jabatan == $hitung){
                        header('Access-Control-Allow-Origin: *');
                        $query = mysqli_query($conn, "UPDATE tbl_absensi SET jam_keluar='".$jam_masuk."' WHERE nik='".$user_id."' AND DATE(tanggal) = CURDATE()");

                        $response = new usr();
                        $response->success = 1;
                        $response->message = "Berhasil Absen Pulang";
                        die(json_encode($response));
                    }else{
                      //KETIKA ABSEN MASUK JAM 13.00 - 16.59
                      if ($now >= $start_timex2 && $now <= $end_timex2){
                        $response = new usr();
                        $response->success = 1;
                        $response->message = "Tunggu Sampai Jam Pulang Ya :)";
                        die(json_encode($response));
                      }else{
                        header('Access-Control-Allow-Origin: *');
                        $query = mysqli_query($conn, "UPDATE tbl_absensi SET jam_keluar='".$jam_masuk."', lembur='".$lembur."' WHERE nik='".$user_id."' AND DATE(tanggal) = CURDATE()");

                        $response = new usr();
                        $response->success = 1;
                        $response->message = "Berhasil Absen Pulang";
                        die(json_encode($response));
                      }
                    }


                  }else{
                    //CHECK ROLE CCTV
                    $add = 0;
                    $hitung = 0;
                    $id_free_role = mysqli_query($conn, "select * from tbl_free_role where id = 2 limit 1");
                    while ($row = mysqli_fetch_array($id_free_role)) {
                        $add = json_decode($row['id_free_role']);
                        $length = count($add);
                        $resultList = array();
                        for($i=0; $i < $length; $i++){
                          $hitung = $add[$i];
                        }
                    }

                    if($id_jabatan == $hitung){

                        header('Access-Control-Allow-Origin: *');
                        $query = mysqli_query($conn, "INSERT INTO tbl_absensi (kd_cabang,nik,tanggal,jam_masuk,jam_keluar,telat,lembur,hari_libur,shift,lat,lang,image) VALUES ('$kd_cabang','$name','$datenow','$jam_masuk','','$telat','0','0','$shift','$lat','$lang','$newfilename')");
                        header('Access-Control-Allow-Origin: *');
                        compress($_FILES['image']['tmp_name'], $actualpath, 65);
                        $response = new usr();
                        $response->success = 1;
                        $response->message = "Berhasil Absen Masuk";
                        die(json_encode($response));
                    }else{
                      if($shift == 2){

                        header('Access-Control-Allow-Origin: *');
                        $query = mysqli_query($conn, "INSERT INTO tbl_absensi (kd_cabang,nik,tanggal,jam_masuk,jam_keluar,telat,lembur,hari_libur,shift,lat,lang,image) VALUES ('$kd_cabang','$name','$datenow','$jam_masuk','','$telat','0','0','$shift','$lat','$lang','$newfilename')");
                        header('Access-Control-Allow-Origin: *');
                        compress($_FILES['image']['tmp_name'], $actualpath, 65);
                        $response = new usr();
                        $response->success = 1;
                        $response->message = "Berhasil Absen Masuk";
                        die(json_encode($response));
                      }else{
                        header('Access-Control-Allow-Origin: *');
                        $query = mysqli_query($conn, "INSERT INTO tbl_absensi (kd_cabang,nik,tanggal,jam_masuk,jam_keluar,telat,lembur,hari_libur,shift,lat,lang,image) VALUES ('$kd_cabang','$name','$datenow','','$jam_masuk','1','1','0','0','$lat','$lang','$newfilename')");
                        header('Access-Control-Allow-Origin: *');
                        compress($_FILES['image']['tmp_name'], $actualpath, 65);
                        $response = new usr();
                        $response->success = 1;
                        $response->message = "Berhasil Absen Pulang";
                        die(json_encode($response));
                        // $response = new usr();
                        // $response->success = 1;
                        // $response->message = "Maaf Absen Sudah Ditutup :(";
                        // die(json_encode($response));
                      }
                    }

                    //KETIKA ABSEN MASUK JAM 13.00 - 16.59
                    // if ($now >= $start_timex2 && $now <= $end_timex2)
                    // {
                    //   $response = new usr();
                    //   $response->success = 1;
                    //   $response->message = "Maaf Absen Sudah Ditutup :(";
                    //   die(json_encode($response));
                    // }else{
                    //   if($shift == 2){
                    //     $random = random_word(20);
                		// 		$path = "images/".$image;
                		// 		// sesuiakan ip address laptop/pc atau URL server
                		// 		$actualpath = $_SERVER["DOCUMENT_ROOT"]."/mobile_dev/$path";
                    //
                    //     header('Access-Control-Allow-Origin: *');
                    //     $query = mysqli_query($conn, "INSERT INTO tbl_absensi (kd_cabang,nik,tanggal,jam_masuk,jam_keluar,telat,lembur,hari_libur,shift,lat,lang,image) VALUES ('$kd_cabang','$name','$datenow','$jam_masuk','','$telat','0','0','$shift','$lat','$lang','$image')");
                    //     header('Access-Control-Allow-Origin: *');
                    //     move_uploaded_file($_FILES['image']['tmp_name'], $actualpath);
                    //     $response = new usr();
                    //     $response->success = 1;
                    //     $response->message = "Berhasil Absen Masuk";
                    //     die(json_encode($response));
                    //   }else{
                    //     $response = new usr();
                    //     $response->success = 1;
                    //     $response->message = "Maaf Absen Sudah Ditutup :(";
                    //     die(json_encode($response));
                    //   }
                    //
                    // }

                  }

              }else if(((int) $hour_masuk) >= 22 && ((int) $hour_masuk) <= 23){
                $response = new usr();
                $response->success = 1;
                $response->message = "Maaf Absen Sudah Ditutup :(";
                die(json_encode($response));
              }else{
                header('Access-Control-Allow-Origin: *');
                $CekPresensi = mysqli_query($conn, "SELECT * FROM tbl_absensi WHERE nik ='".$user_id."' AND DATE(tanggal) = CURDATE() ORDER BY id");
                $num_rows2 = mysqli_num_rows($CekPresensi);
                if($num_rows2 > 0){
                  header('Access-Control-Allow-Origin: *');
                  $query = mysqli_query($conn, "UPDATE tbl_absensi SET jam_masuk='".$jam_masuk."',lembur='".$lembur."' WHERE nik='".$user_id."' AND DATE(tanggal) = CURDATE()");

                  $response = new usr();
                  $response->success = 1;
                  $response->message = "Berhasil Absen Masuk";
                  die(json_encode($response));
                }else{
                  //CHECK ROLE CCTV
                  $add = 0;
                  $hitung = 0;
                  $id_free_role = mysqli_query($conn, "select * from tbl_free_role where id = 2 limit 1");
                  while ($row = mysqli_fetch_array($id_free_role)) {
                      $add = json_decode($row['id_free_role']);
                      $length = count($add);
                      $resultList = array();
                      for($i=0; $i < $length; $i++){
                        $hitung = $add[$i];
                      }
                  }

                  if($id_jabatan == $hitung){
                    header('Access-Control-Allow-Origin: *');
                    $query = mysqli_query($conn, "UPDATE tbl_absensi SET jam_keluar='".$jam_masuk."' WHERE nik='".$user_id."' AND DATE(tanggal) = DATE_ADD(CURDATE(), INTERVAL -1 DAY)");

                    $response = new usr();
                    $response->success = 1;
                    $response->message = "Berhasil Absen Pulang";
                    die(json_encode($response));
                  }else{

                    header('Access-Control-Allow-Origin: *');
                    $query = mysqli_query($conn, "INSERT INTO tbl_absensi (kd_cabang,nik,tanggal,jam_masuk,jam_keluar,telat,lembur,hari_libur,shift,lat,lang,image) VALUES ('$kd_cabang','$name','$datenow','$jam_masuk','','$telat','0','0','$shift','$lat','$lang','$newfilename')");
                    header('Access-Control-Allow-Origin: *');
                    compress($_FILES['image']['tmp_name'], $actualpath, 65);
                    $response = new usr();
                    $response->success = 1;
                    $response->message = "Berhasil Absen Masuk";
                    die(json_encode($response));
                  }

                }

              }




  			} else {
  				$response = new usr();
  				$response->success = 0;
  				$response->message = "Anda bukan user";
  				die(json_encode($response));
  			}
      }else{

        //CHECK FREE ROLE
        $add = 0;
        $hitung = 0;
        $id_free_role = mysqli_query($conn, "select * from tbl_free_role where id = 1 limit 1");
        while ($row = mysqli_fetch_array($id_free_role)) {
            $add = json_decode($row['id_free_role']);
            $length = count($add);
            $resultList = array();
            for($i=0; $i < $length; $i++){
              $bap = array();
              if($id_jabatan == $add[$i]){
                $hitung += 1;
              }
            }
        }
        //KONDISI TERDAPAT USER YANG DIKECUALIKAN
        if($hitung == 1){
          //MENENTUKAN LOKASI ABSEN PUSAT ATAU CABANG
          if($lokasi_id == 1){
              date_default_timezone_set($timezone['timeZoneId']);
              $datenow    = date('Y-m-d');
              // $jam_masuk   = date('H:i:s',strtotime($start));
              // $hour_masuk   = date('H',strtotime($start));
              $jam_masuk   = date('H:i:s');
              $hour_masuk   = date('H');
              $jam_keluar  = date('H:i:s');
              $datestart    = date("Y-m-d H:i:s");

              $now = new DateTime($jam_masuk);
              //SHIFT 1
              $start_timen = new DateTime('01:00');
              $end_timen = new DateTime('07:50');
              $start_time1 = new DateTime('07:51');
              $end_time1 = new DateTime('08:01');
              $start_time2 = new DateTime('08:02');
              $end_time2 = new DateTime('08:30');
              $start_time3 = new DateTime('08:31');
              $end_time3 = new DateTime('09:00');
              $start_time4 = new DateTime('09:01');
              $end_time4 = new DateTime('10:00');
              $start_time5 = new DateTime('10:01');
              $end_time5 = new DateTime('10:44');
              $start_timex = new DateTime('10:45');
              $end_timex = new DateTime('11:50');
              //SHIFT 2
              $start_time6 = new DateTime('11:51');
              $end_time6 = new DateTime('12:01');
              $start_time7 = new DateTime('12:02');
              $end_time7 = new DateTime('12:30');
              $start_time8 = new DateTime('12:31');
              $end_time8 = new DateTime('13:00');
              $start_timex2 = new DateTime('13:01');
              $end_timex2 = new DateTime('16:59');
              //lEMBUR
              $lembur = 0;

              //SHIFT 1 03.00 - 07.51
              if($now >= $start_timen && $now < $start_time1){
                $shift = 1;
                $telat = 0;
			  }else if ($now >= $start_time1 && $now < $start_time2){
				  //07.51 - 08.02
                $shift = 1;
                $telat = 1;
              }else if($now >= $start_time2 && $now < $start_time3){
				  //08.02 - 08.31
                $shift = 1;
                $telat = 2;
              }else if($now >= $start_time3 && $now < $start_time4){
				  //08.31 - 09.01
                $shift = 1;
                $telat = 3;
              }else if($now >= $start_time4 && $now < $start_time5){
				  //09.01 - 10.01
                $shift = 1;
                $telat = 4;
              }else if($now >= $start_time5 && $now < $start_timex){
				  //10.01 - 10.45
                $shift = 1;
                $telat = 5;
              }else if($now >= $start_timex && $now < $start_time6){
				  //10.45 - 11.51
                $shift = 2;
                $telat = 0;
              }else if($now >= $start_time6 && $now < $start_time7){ //SHIFT 2
				  //11.51 - 12.02
                $shift = 2;
                $telat = 1;
              }else if($now >= $start_time7 && $now < $start_time8){
				  //12.02 - 12.31
                $shift = 2;
                $telat = 2;
              }else if($now >= $start_time8 && $now < $start_timex2){
				  //12.31 - 13.01
                $shift = 2;
                $telat = 3;
              }else{
				  //13.01 - xx
                $shift = 0;
                $telat = 0;
              }

          }else{
              date_default_timezone_set($timezone['timeZoneId']);
              $datenow    = date('Y-m-d');
              // $jam_masuk   = date('H:i:s',strtotime($start));
              // $hour_masuk   = date('H',strtotime($start));
              $jam_masuk   = date('H:i:s');
              $hour_masuk   = date('H');
              $jam_keluar  = date('H:i:s');
              $datestart    = date("Y-m-d H:i:s");

              $now = new DateTime($jam_masuk);
              //SHIFT 1
              $start_timen = new DateTime('01:00');
              $end_timen = new DateTime('07:50');
              $start_time1 = new DateTime('07:51');
              $end_time1 = new DateTime('08:01');
              $start_time2 = new DateTime('08:02');
              $end_time2 = new DateTime('08:30');
              $start_time3 = new DateTime('08:31');
              $end_time3 = new DateTime('09:00');
              $start_time4 = new DateTime('09:01');
              $end_time4 = new DateTime('10:00');
              $start_time5 = new DateTime('10:01');
              $end_time5 = new DateTime('10:44');
              $start_timex = new DateTime('10:45');
              $end_timex = new DateTime('11:50');
              //SHIFT 2
              $start_time6 = new DateTime('11:51');
              $end_time6 = new DateTime('12:01');
              $start_time7 = new DateTime('12:02');
              $end_time7 = new DateTime('12:30');
              $start_time8 = new DateTime('12:31');
              $end_time8 = new DateTime('13:00');
              $start_timex2 = new DateTime('13:01');
              $end_timex2 = new DateTime('16:59');
              //lEMBUR
              $start_lem1 = new DateTime('17:30');
              $end_lem1 = new DateTime('18:59');
              $start_lem2 = new DateTime('19:00');
              $end_lem2 = new DateTime('20:59');
              $start_lem3 = new DateTime('21:00');
              $end_lem3 = new DateTime('23:59');

              //SHIFT 1 03.00 - 07.51
              if($now >= $start_timen && $now < $start_time1){
                $shift = 1;
                $telat = 0;
			  }else if ($now >= $start_time1 && $now < $start_time2){
				  //07.51 - 08.02
                $shift = 1;
                $telat = 1;
              }else if($now >= $start_time2 && $now < $start_time3){
				  //08.02 - 08.31
                $shift = 1;
                $telat = 2;
              }else if($now >= $start_time3 && $now < $start_time4){
				  //08.31 - 09.01
                $shift = 1;
                $telat = 3;
              }else if($now >= $start_time4 && $now < $start_time5){
				  //09.01 - 10.01
                $shift = 1;
                $telat = 4;
              }else if($now >= $start_time5 && $now < $start_timex){
				  //10.01 - 10.45
                $shift = 1;
                $telat = 5;
              }else if($now >= $start_timex && $now < $start_time6){
				  //10.45 - 11.51
                $shift = 2;
                $telat = 0;
              }else if($now >= $start_time6 && $now < $start_time7){ //SHIFT 2
				  //11.51 - 12.02
                $shift = 2;
                $telat = 1;
              }else if($now >= $start_time7 && $now < $start_time8){
				  //12.02 - 12.31
                $shift = 2;
                $telat = 2;
              }else if($now >= $start_time8 && $now < $start_timex2){
				  //12.31 - 13.01
                $shift = 2;
                $telat = 3;
              }else{
				  //13.01 - xx
                $shift = 0;
                $telat = 0;
              }

              if ($now >= $start_lem1 && $now <= $end_lem1)
              {
                if($shift == 2){
                  $lembur = 0;
                }else{
                  $lembur = 1;
                }
              }else if($now >= $start_lem2 && $now <= $end_lem2){
                if($shift == 2){
                  $lembur = 0;
                }else{
                  $lembur = 2;
                }
              }else if($now >= $start_lem3 && $now <= $end_lem3){
                if($shift == 2){
                  $lembur = 0;
                }else{
                  $lembur = 3;
                }
              }else{
                $lembur = 0;
              }
          }



              //KONDISI SHIFT 1 DAN BATASAN KETIKA MELEBIHI JAM 12 DIHITUNG ABSEN KELUAR
              if(((int) $hour_masuk) >= 12 && ((int) $hour_masuk) <= 21){

                  header('Access-Control-Allow-Origin: *');
                  $CekPresensi = mysqli_query($conn, "SELECT * FROM tbl_absensi WHERE nik ='".$user_id."' AND DATE(tanggal) = CURDATE() ORDER BY id");
                  $num_rows2 = mysqli_num_rows($CekPresensi);
                  if($num_rows2 > 0){
                    //KETIKA ABSEN MASUK JAM 13.00 - 16.59
                    if ($now >= $start_timex2 && $now <= $end_timex2){
                      $response = new usr();
                      $response->success = 1;
                      $response->message = "Tunggu Sampai Jam Pulang Ya :)";
                      die(json_encode($response));
                    }else{
                      header('Access-Control-Allow-Origin: *');
                      $query = mysqli_query($conn, "UPDATE tbl_absensi SET jam_keluar='".$jam_masuk."', lembur='".$lembur."' WHERE nik='".$user_id."' AND DATE(tanggal) = CURDATE()");

                      $response = new usr();
                      $response->success = 1;
                      $response->message = "Berhasil Absen Pulang";
                      die(json_encode($response));
                    }

                  }else{
                    //JIKA ABSEN LEBIH DARI JAM 12 DAN TERMASUK SHIFT 2 ATAU TIDAK
                    if($shift == 2){

                      header('Access-Control-Allow-Origin: *');
                      $query = mysqli_query($conn, "INSERT INTO tbl_absensi (kd_cabang,nik,tanggal,jam_masuk,jam_keluar,telat,lembur,hari_libur,shift,lat,lang,image) VALUES ('$kd_cabang','$name','$datenow','$jam_masuk','','$telat','0','0','$shift','$lat','$lang','$newfilename')");
                      header('Access-Control-Allow-Origin: *');
                      compress($_FILES['image']['tmp_name'], $actualpath, 65);
                      $response = new usr();
                      $response->success = 1;
                      $response->message = "Berhasil Absen Masuk";
                      die(json_encode($response));
                    }else{

                      header('Access-Control-Allow-Origin: *');
                      $query = mysqli_query($conn, "INSERT INTO tbl_absensi (kd_cabang,nik,tanggal,jam_masuk,jam_keluar,telat,lembur,hari_libur,shift,lat,lang,image) VALUES ('$kd_cabang','$name','$datenow','','$jam_masuk','1','1','0','0','$lat','$lang','$newfilename')");
                      header('Access-Control-Allow-Origin: *');
                      compress($_FILES['image']['tmp_name'], $actualpath, 65);
                      $response = new usr();
                      $response->success = 1;
                      $response->message = "Berhasil Absen Pulang";
                      die(json_encode($response));
                    }

                  }

              }else if(((int) $hour_masuk) >= 22 && ((int) $hour_masuk) <= 23){
                $response = new usr();
                $response->success = 1;
                $response->message = "Maaf Absen Sudah Ditutup :(";
                die(json_encode($response));
              }else{
                header('Access-Control-Allow-Origin: *');
                $CekPresensi = mysqli_query($conn, "SELECT * FROM tbl_absensi WHERE nik ='".$user_id."' AND DATE(tanggal) = CURDATE() ORDER BY id");
                $num_rows2 = mysqli_num_rows($CekPresensi);
                if($num_rows2 > 0){
                  header('Access-Control-Allow-Origin: *');
                  $query = mysqli_query($conn, "UPDATE tbl_absensi SET jam_masuk='".$jam_masuk."',lembur='".$lembur."' WHERE nik='".$user_id."' AND DATE(tanggal) = CURDATE()");

                  $response = new usr();
                  $response->success = 1;
                  $response->message = "Berhasil Absen Masuk";
                  die(json_encode($response));
                }else{

                  header('Access-Control-Allow-Origin: *');
                  $query = mysqli_query($conn, "INSERT INTO tbl_absensi (kd_cabang,nik,tanggal,jam_masuk,jam_keluar,telat,lembur,hari_libur,shift,lat,lang,image) VALUES ('$kd_cabang','$name','$datenow','$jam_masuk','','$telat','0','0','$shift','$lat','$lang','$newfilename')");
                  header('Access-Control-Allow-Origin: *');
                  compress($_FILES['image']['tmp_name'], $actualpath, 65);
                  $response = new usr();
                  $response->success = 1;
                  $response->message = "Berhasil Absen Masuk";
                  die(json_encode($response));
                }

              }
        }else{
          $response = new usr();
          $response->success = 0;
          $response->message = "Lokasi Absen Terlalu Jauh :(";
          die(json_encode($response));
        }

      }
    }else{
      $response = new usr();
      $response->success = 0;
      $response->message = "Lokasi Tidak Ditemukan";
      die(json_encode($response));
    }



		/* $random = random_word(20);

		$path = "images/".$image."";

		// sesuiakan ip address laptop/pc atau URL server
		$actualpath = $_SERVER["DOCUMENT_ROOT"]."/mantap/$path";

		$query = mssql_query("INSERT INTO tb_geoatt (user_id,start_date,end_date,keterangan,image,lat,lang) VALUES ('$user_id','$start','','nope','$image','$lat','$lang')");

		if ($query){
// 			file_put_contents($path,base64_decode($image));
			move_uploaded_file($_FILES['image']['tmp_name'], $actualpath);
			$response = new usr();
			$response->success = 1;
			$response->message = "Successfully Uploaded";
			die(json_encode($response));
		} else{
			$response = new usr();
			$response->success = 0;
			$response->message = "Error Upload image";
			die(json_encode($response));
		} */
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

  function compress($source, $destination, $quality)
  {
      $info = getimagesize($source);
      if ($info['mime'] == 'image/jpeg') $image = imagecreatefromjpeg($source);
      elseif ($info['mime'] == 'image/gif') $image = imagecreatefromgif($source);
      elseif ($info['mime'] == 'image/png') $image = imagecreatefrompng($source);
      imagejpeg($image, $destination, $quality);
      return $destination;
  }

  function http_request($url){
      // persiapkan curl
      $ch = curl_init();

      // set url
      curl_setopt($ch, CURLOPT_URL, $url);

      // set user agent
      curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');

      // return the transfer as a string
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

      // $output contains the output string
      $output = curl_exec($ch);

      // tutup curl
      curl_close($ch);

      // mengembalikan hasil curl
      return $output;
  }

?>
