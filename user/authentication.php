<?php

session_start();
if(!isset($_SESSION['email'])) {
    header("Location: ../assets/login.php");
    exit();
}

if(!isset($_SESSION['authv2'])){
	$_SESSION['authv2'] = rand(1000, 9999);
}
echo $_SESSION['authv2'];
send();
function send(){
	error_reporting(E_ALL);
	ini_set('display_errors','On');
	echo "<script>function Init(){
		document.getElementById('mail').setAttribute('readonly', true);
		document.getElementById('mail').value = '".$_SESSION['email']."';
			}</script>";
	require_once __DIR__.'/../vendor/autoload.php';
	$transport = new Swift_SmtpTransport('smtp.gmail.com', 465,'ssl');
	$transport->setUsername('semasljivovica@gmail.com');
	$transport->setPassword('isamxvxuoqczpnzh');
	
	$mailer = new Swift_Mailer($transport);
	
	$message = new Swift_Message('Mein Betreff v1');
	$message->setFrom(['semasljivovica@gmail.com'=>'Administrator']);
	$message->setTo([$_SESSION['email']=>'User']);
	$message->setBody('
	Hallo this is an Authentication code that is going to expire in 15 Minutes.	\n
	');
	$message->addPart('
	<!DOCTYPE html>
	<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
	</head>
	<body style="color:white;display:grid;align-items:center;justify-content: center;overflow: hidden;font-family: "Franklin Gothic Medium", "Arial Narrow", Arial, sans-serif;color: white;">
		<div class="container" style="color:white;display:grid;align-items: center;justify-content: center;width:80vw;height:70vh;background-color: rgba(137, 43, 226, 0.721);position:relative;top: 13vh;">
				<div class="title" style="font-size: 4vw;color:white;">
					Your Authentication code:
				</div>
				<div class="code" style="width: 10vw;height:10vh;background-color:rgba(0, 0, 0, 0.772);font-size:4vw;margin: 2vh 2vw;padding: 2vh 2vw;justify-self: center;">
					<div class="code-inn" style="color:white;display:flex;align-items: center;justify-self: center;line-height: 10vh;">
						'.$_SESSION['authv2'].'
					</div>
				</div>
			<div class="info" style="color:white;font-size: 2vh;position: absolute;bottom: 2vh;display: flex; align-items: center;justify-self: center;">
				This is an Authentication code that is going to expire in 15 Minutes.
			</div>
		</div>
	</body>
	</html>','text/html');
	
	
	$result = $mailer->send($message);


	}
	if(isset($_COOKIE['authid'])){
		echo "<script>function Init(){
			document.getElementById('mail').setAttribute('readonly', true);
			document.getElementById('mail').value = '".$_SESSION['email']."';
				}</script>";
	}
    if(isset($_POST['submit']) ){
        if($_POST['code'] == $_SESSION['authv2']){
        setcookie("email", $_SESSION['email'], strtotime("+30 days"));
        header("Location: dashboard.php");
    }
    }
?>   

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Authentication V1</title>
	<meta charset="UTF-8">
    <link rel="stylesheet" href="../style.css"/>
</head>
<body onload="Init()" >
<form class="form" method="post" name="login">
        <h1 class="login-title">Enter your Authentication code:</h1>
        <input type="text" class="login-input" name="email" id="mail"/>
        <input type="password" class="login-input" name="code" placeholder="Code"/>
        <input type="submit" value="Authenticate" name="submit" class="login-button"/>
  </form>
</body>
</html>