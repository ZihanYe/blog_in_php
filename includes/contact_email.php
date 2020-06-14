<?php
	require(ROOT_PATH . '/vendor/phpmailer/phpmailer/src/PHPMailer.php');
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;

	// Load Composer's autoloader
	require (ROOT_PATH . '/vendor/autoload.php');

	$name = "";
	$email = "";
	$subject = "";
	$content = "";
	$message = "";
	$type = "";

	if(isset($_POST["send"])) {
		// receive all input values from the form
		$name = esc($_POST['userName']);
		$email = esc($_POST['userEmail']);
		$subject = esc($_POST['subject']);
		$content = esc($_POST['content']);

		$mailHeaders = "From: " . $name . "<". $email .">\r\n";

		$mail = new PHPMailer();
		// $mail->SMTPDebug = SMTP::DEBUG_SERVER; 
		$mail->IsSMTP();
		$mail->SMTPDebug = 0;
		$mail->SMTPAuth = TRUE;
		$mail->SMTPSecure = "PHPMailer::ENCRYPTION_STARTTLS";
		$mail->Port     = 25;  
		$mail->Username = "yzh9933@126.com";
		$mail->Password = "*****";
		$mail->Host     = "smtp.126.com"; // "smtp.live.com";
		//$mail->Mailer   = "smtp";

		$mail->SetFrom("yzh9933@126.com", "clover");

		$mail->AddReplyTo($email, "Information");
		$mail->AddAddress("zihan.ye@stcatz.ox.ac.uk");
		$mail->Subject = "IdeaStorm | User Message";
		$mail->Body = "<b>$mailHeaders</b>" . $mail->MsgHTML($content);
		$mail->IsHTML(true);
		if($mail->send()) {
			$message = "Your contact information is received successfully.";
	    	$type = "success";
	    } else {
			$message = "Fail to send your contact information.";
	    	$type = "error";
		}

		mysqli_query($conn, "INSERT INTO contacts (name, email,subject, content) VALUES ('" . $name. "', '" . $email. "','" . $subject. "','" . $content. "')");
		$insert_id = mysqli_insert_id($conn);
		if(empty($insert_id) and $type === "error") {
	    	$message = "Fail to send and save your contact information.";
			$type = "error";
	    } else {
	    	$message = "Your contact information is received successfully.";
	    }
	}

	// escape value from form
	function esc(String $value)
	{	
		// bring the global db connect object into function
		global $conn;

		$val = trim($value); // remove empty space sorrounding string
		$val = mysqli_real_escape_string($conn, $value);

		return $val;
	}
?>