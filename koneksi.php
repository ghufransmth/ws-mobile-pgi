<?php

    $server		= "localhost"; //sesuaikan dengan nama server
	$user		= "root"; //sesuaikan username
	$password	= ""; //sesuaikan password
	$database	= "hris"; //sesuaikan target databese


	// $serverName = "172.18.26.11"; //serverName\instanceName
// 	$serverName = "DESKTOP-HDETAC4\SQLEXPRESS01";
	// Since UID and PWD are not specified in the $connectionInfo array,
	// The connection will be attempted using Windows Authentication.
// 	$connectionInfo = array( "Database"=>"MOBATT");

    $conn = mysqli_connect($server, $user, $password,$database);
// 	$conn = sqlsrv_connect( $serverName, $connectionInfo);

	if( $conn ) {
	   //echo "Connection established.<br />";
	}else{
		 echo "Connection could not be established.<br />";
		 die( print_r( mysqli_errors(), true));
	}

?>
