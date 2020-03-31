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
		  header('Access-Control-Allow-Origin: *');
			$query = mysqli_query($conn, "SELECT created_on,start_date,end_date, b.reason_desc as reason ,status_approv FROM tbl_timeoff as a LEFT JOIN tbl_reason_timeoff_picklist as b ON (a.reason = b.id) WHERE a.nik='".$user_id."' ORDER BY a.created_on DESC");
			$count = mysqli_num_rows($query);

			if ($count > 0){
					$response = new usr();
					$response->success = 1;
					$response->data = array();
            		while ($row = mysqli_fetch_array($query)) {
            			// temp user array
            			$json = array();
            		    $json['created_on']= date("F j, Y", strtotime($row['created_on']));
            		    $json['time']= date("g:i a", strtotime($row['created_on']));
            		    $json['start_date']= date("F j, Y", strtotime($row['start_date']));
            		    $json['end_date']= date("F j, Y", strtotime($row['end_date']));
            		    $json['reason']= $row['reason'];

            		    if($row['status_approv'] == 1)
            		    $row['status_approv'] = 'Approved';
            		    else if($row['status_approv'] == 2)
            		    $row['status_approv'] = 'Decline submission';
            		    else
            		    $row['status_approv'] = 'Not Yet Approved';
            		    $json['status']= $row['status_approv'];

            		    // push single puasa into final response array
			            array_push($response->data, $json);
            		}
					$response->message = "Data Report.";
					die(json_encode($response));

			} else {
					$response = new usr();
					$response->success = 0;
					$response->message = "Tidak Ada Data Izin.";
					die(json_encode($response));
			}



		}
	}

	mysqli_close($con);
?>
