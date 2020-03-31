<?php
    $server		= "localhost"; //sesuaikan dengan nama server
	$user		= "perhubun_sipal"; //sesuaikan username
	$password	= "P@ssword123"; //sesuaikan password
	$database	= "perhubun_sipalpal"; //sesuaikan target databese
	
	$con = mysqli_connect($server, $user, $password,$database);
	
	class usr{}
	
	$bulan = date('m',strtotime('-1 month'));
	$bulanNow = date('m');
	$tahun = date('Y',strtotime('-1 month'));
	$tahunNow = date('Y');
	$numberRomawi = ["I", "II", "III", "IV", "V", "VI", "VII", "VIII","IX", "X", "XI", "XII"];
	
	header('Access-Control-Allow-Origin: *');
	$query = mysqli_query($con, "SELECT companies.id,COUNT(activities_jpts.id) AS bulan FROM companies LEFT JOIN activities_jpts ON companies.id = activities_jpts.id_perusahaan AND DATE_FORMAT(activities_jpts.bulan, '%m') = $bulan AND DATE_FORMAT(activities_jpts.bulan, '%Y') = $tahun GROUP BY companies.id");
	
	header('Access-Control-Allow-Origin: *');
    $response = new usr();
    $response->data = array();
	while ($row = mysqli_fetch_array($query)) {
	    if($row['bulan'] == 0){
	        $id = $row['id'];
	        header('Access-Control-Allow-Origin: *');
	        $query2 = mysqli_query($con, "SELECT status_peringatan FROM companies WHERE id=$id LIMIT 1");
	        $select_number = mysqli_query($con, "SELECT id FROM peringatan ORDER BY id DESC LIMIT 1");
	        while ($row2 = mysqli_fetch_array($query2)) {
	            if($row2['status_peringatan'] == 0){
	                while($row3 = mysqli_fetch_array($select_number)){
	                    $makeID = $row3['id'] + 1;
	                    $tgl_peringatan = $makeID.'/SP1/'.$numberRomawi[$bulan].'/'.$tahunNow;
	                    array_push($response->data, $tgl_peringatan);
	                }
	                
	                
	               // header('Access-Control-Allow-Origin: *');
        	       // $query3 = mysqli_query($con, "INSERT INTO peringatan (id_perusahaan,status_peringatan,tgl_peringatan,nomor_peringatan,ket_peringatan,deleted) VALUES ('$id','1','$tgl_peringatan','$jam_buka','$lat','$lang')");
	            }
	            
	           // die(json_encode($row2['status_peringatan']));
	        }
	        
	       // header('Access-Control-Allow-Origin: *');
	       // $select_number = mysqli_query($con, "SELECT id FROM peringatan ORDER BY id DESC");
	       // while ($row2 = mysqli_fetch_array($query2)) {
	       //     die(json_encode($row2['status_peringatan']));
	       // }
	    }
    }
    
    die(json_encode($response));
    
// 	header('Access-Control-Allow-Origin: *');
// 	$response = new usr();
// 	$response->data = array();
//     while ($row = mysqli_fetch_array($query)) {
//     // push single puasa into final response array
//     //   $json = array();
//     //   $json['nama']= $row['nama'];
// 		array_push($response->data, $row);
//     }
// 	die(json_encode($response));
//     $response = new usr();
// 	$response->success = 0;
// 	$response->message = "Nice";
// 	die(json_encode($query));
    
    

?>