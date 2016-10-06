<?php
	define('DOCROOT', realpath(dirname(__FILE__)). '/');
	include( DOCROOT .'PHPMailer_5.2.4/class.phpmailer.php');
	class notification
	{
		function email($from,$fromName,$username,$password,$to,$subject,$body)
		{
			$mail = new PHPMailer();
        
			$mail->IsSMTP();
			$mail->From = $from;
			$mail->FromName = $fromName;
			$mail->Host = "smtp.gmail.com";
			$mail->SMTPSecure = "ssl";
			$mail->Port = 465;
			$mail->SMTPAuth = true;
			$mail->Username = $username;
			$mail->Password = $password;
			$mail->AddAddress($to);
			$mail->WordWrap = 50;
        
			$mail->IsHTML(true);
			$mail->Subject = $subject;
			$mail->Body = $body;
        
			if($mail->Send())
			{
				return 1;
			}
			else
			{
				return 0;
				echo "<script>alert('Unsuccessfull Delivery');</script>";
			}
		}	
	}
?>
