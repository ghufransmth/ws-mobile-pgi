<?php
	include "koneksi.php";

	class usr{}

	$email    = $_POST["email"];

	if ((empty($email))) {
		$response = new usr();
		$response->success = 0;
		$response->message = "Kolom email tidak boleh kosong";
		die(json_encode($response));
	} else {

    $userID = UserID($email);
		$token = generateRandomString();

		$sql = "INSERT INTO tbl_recovery_keys (nik, token, valid) VALUES ('$userID', '$token', '1')";
		$query2 = mysqli_query($conn, $sql);
		//$count = mysqli_num_rows($query);
    $send_mail = send_mail($email, $token);
    if($send_mail === 'success')
    {
      $response = new usr();
      $response->success = 1;
      $response->message = "A mail with recovery instruction has sent to your email.";
      die(json_encode($response));
    }else{
      $response = new usr();
      $response->success = 0;
      $response->message = "There is something wrong.";
      die(json_encode($response));
    }


	}



	function validateDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

  function send_mail($to, $token){
    require 'class/class.phpmailer.php';
		$mail = new PHPMailer;
		$mail->IsSMTP();								//Sets Mailer to send message using SMTP
		$mail->Host = 'mail.rapiertechnology.co.id';		//Sets the SMTP hosts of your Email hosting, this for Godaddy
		$mail->Port = '26';								//Sets the default SMTP server port
		$mail->SMTPAuth = true;							//Sets SMTP authentication. Utilizes the Username and Password variables
		$mail->Username = 'hrdpgi@rapiertechnology.co.id';					//Sets SMTP username
		$mail->Password = 'Rapier2020';					//Sets SMTP password
		$mail->SMTPSecure = '';							//Sets connection prefix. Options are "", "ssl" or "tls"
		$mail->From = 'hrdpgi@rapiertechnology.co.id';					//Sets the From email address for the message
		$mail->FromName = 'Pusat Gadai';				//Sets the From name of the message
		$mail->AddAddress($to, 'Pusat Gadai Mobile');		//Adds a "To" address
		//$mail->AddCC('franssmith7@gmail.com', 'ghufran');	//Adds a "Cc" address
		$mail->WordWrap = 50;							//Sets word wrapping on the body of the message to a given number of characters
		$mail->IsHTML(true);							//Sets message type to HTML
    $link = 'http://116.206.196.58:81/mobile_dev/getrecovery.php?email='.$to.'&token='.$token;
		$mail->Subject = 'Pusat Gadai Mobile: Password Recovery Instruction';				//Sets the Subject of the message
		$mail->Body = '<b>Hello</b><br><br>You have requested for your password recovery. <a href='.$link.'>Click here</a> to reset your password. If you are unable to click the link then copy the below link and paste in your browser to reset your password.<br><i>'.$link.'</i>';				//An HTML or plain text message body
    if(!$mail->Send()) {
  		return 'fail';
  	} else {
  		return 'success';
  	}
		$name = '';
		$email = '';
		$subject = '';
		$message = '';


  }

  function UserID($email)
  {
    require 'koneksi.php';
    $name = '';
  	$sql = "SELECT username FROM user WHERE email = '$email'";
  	$query = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_array($query)) {
        $name = $row['username'];
    }
  	//$row = mysqli_fetch_assoc($query);

  	return $name;
  }


  function generateRandomString($length = 20) {
  	// This function has taken from stackoverflow.com

  	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $charactersLength = strlen($characters);
      $randomString = '';
      for ($i = 0; $i < $length; $i++) {
          $randomString .= $characters[rand(0, $charactersLength - 1)];
      }
      return md5($randomString);
  }


	//mysqli_close($con);
?>
