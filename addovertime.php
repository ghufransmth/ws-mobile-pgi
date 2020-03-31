<?php
	include "koneksi.php";
	
	class usr{}

	$user_id    = $_POST["user_id"];
	$date       = $_POST["date"];
	$start_hour = $_POST["start_hour"];
	$end_hour   = $_POST["end_hour"];
	$keterangan = $_POST["keterangan"];
    $created_on = $_POST["created_on"];

	if ((empty($user_id))) {
		$response = new usr();
		$response->success = 0;
		$response->message = "Login terlebih dahulu";
		die(json_encode($response));
	} else if ((empty($date))) {
		$response = new usr();
		$response->success = 0;
		$response->message = "Kolom tanggal tidak boleh kosong";
		die(json_encode($response));
	} else if ((empty($start_hour))) {
		$response = new usr();
		$response->success = 0;
		$response->message = "Kolom awal jam tidak boleh kosong";
		die(json_encode($response));
	} else if ((empty($end_hour))) {
		$response = new usr();
		$response->success = 0;
		$response->message = "Kolom akhir jam tidak boleh kosong";
		die(json_encode($response));
	} else if ((empty($keterangan))) {
		$response = new usr();
		$response->success = 0;
		$response->message = "Kolom Keterangan tidak boleh kosong";
		die(json_encode($response));
	} else {
		if (!empty($user_id) && !empty($date) && !empty($start_hour) && !empty($end_hour) && !empty($keterangan)){
		    
		    if(validateDate($date) == true){
		        
		        if(validateTime($start_hour) == true){
		            
    		            if(validateTime($end_hour) == true){
							
							$sql = "SELECT first_name FROM tb_users WHERE id='".$user_id."'";
    		                $users = mysqli_query($conn, $sql);
                		    while ($row = mysqli_fetch_array($users)) {
                                $name = $row['first_name'];
                            }
                			$stmt = mysqli_num_rows($users);
							
							if ($stmt > 0){
								$sql = "INSERT INTO tb_overtime (user_id, date, start_hour, end_hour,created_on, keterangan) VALUES('".$user_id."','".$date."','".$start_hour."','".$end_hour."','".$created_on."','".$keterangan."')";
                				$queries = mysqli_query($conn, $sql);
								// $stm = mysqli_num_rows($queries);
								//echo $sql;
								//echo json_encode($stm);

                                    /* if($query){
                                        $response = new usr();
                                		$response->success = 1;
                                		$response->data = array();
                                        while ($row = sqlsrv_fetch_array($query)) {
                                            $json = $push->getPush();
                                            $regId = $row['regId'];
                                            $response = $firebase->send($regId, $json);
                       
                                        }
                                        die(json_encode($response));
                                    }else{
                                        $response = new usr();
                                		$response->success = 0;
                                		$response->message = "Gagal Send Notif.";
                                		die(json_encode($response));
                                    }*/
                            
                					$response = new usr();
                					$response->success = 1;
                					$response->message = "Data overtime telah dimasukkan.";
                					die(json_encode($response)); 
                
                				
                			} else {
                				$response = new usr();
                				$response->success = 0;
                				$response->message = "Anda bukan user";
                				die(json_encode($response));
                			}
							
                			/* $num_rows = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tb_users WHERE id='".$user_id."'"));
                
                			if ($num_rows > 0){
                				$query = mysqli_query($con, "INSERT INTO tb_overtime (user_id, date, start_hour, end_hour) VALUES('".$user_id."','".$date."', '".$start_hour."', '".$end_hour."')");
                
                				if ($query){
                					$response = new usr();
                					$response->success = 1;
                					$response->message = "Data overtime telah dimasukkan.";
                					die(json_encode($response));
                
                				} else {
                					$response = new usr();
                					$response->success = 0;
                					$response->message = "Gagal memasukkan data overtime.";
                					die(json_encode($response));
                				}
                			} else {
                				$response = new usr();
                				$response->success = 0;
                				$response->message = "Anda bukan user";
                				die(json_encode($response));
                			} */
        			
    		        } else {
    		            $response = new usr();
            			$response->success = 0;
            			$response->message = "Jam tidak valid";
            			die(json_encode($response));
    		        }
    			
		        } else {
		            $response = new usr();
        			$response->success = 0;
        			$response->message = "Jam tidak valid";
        			die(json_encode($response));
		        }
			
		    } else {
		        $response = new usr();
    			$response->success = 0;
    			$response->message = "Tanggal tidak valid";
    			die(json_encode($response));
		    }
		}
	}
	
	function validateDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
    
    function validateTime($hour, $format = 'H:i')
    {
        $d = DateTime::createFromFormat($format, $hour);
        return $d && $d->format($format) == $hour;
    }

	//mysqli_close($con);
?>