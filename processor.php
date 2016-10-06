<?php

    define('DOCROOT', realpath(dirname(__FILE__)). '/');
	include( DOCROOT .'activationAndNotifications.php');
    
    session_start();

    $name = $_POST['name'];
    $email = $_POST['mail'];

    $connection = mysqli_connect("localhost","root","password","userStats") or die("Error " . mysqli_error($connection));
    
    $sendMail = new notification();

    $sql = "select * from keyRequesters where email = '".$email."';";

    $result = mysqli_query($connection, $sql) or die("Error in selecting data" . mysqli_error($connection));

    if($row = mysqli_fetch_assoc($result)) {
        $_SESSION['status'] = 0;
    } else {

        $key = generateKey();

        $body = "<h2> Use the following API Key to access Viewer's List: </h2> <br/> <p> ".$key." </p>";

        $status = $sendMail->email("mailtosecureyou@gmail.com", "Viewer's List", "mailtosecureyou", "mailstodeliver", $email, "Viewer's List: API Key", $body);

        if($status == 1) {

            $sql = "insert into keyRequesters(name, email, api_key, count) values('".$name."', '".$email."', '".$key."', 0);";

            $result = mysqli_query($connection, $sql) or die("Error in selecting data" . mysqli_error($connection));
            $_SESSION['status'] = 1;
        } else {
            $_SESSION['status'] = 2;
        }
    }

    function generateKey() {
        $key_size = 20;
        $key = "";
        $num = array("1","2","3","4","5","6","7","8","9","0");
        $small = array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z");
        $caps = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
        $spec = array('!','@','#','$','%','^','&','*','(',')','-','_','+','=');
        
        for($i=0;$i<$key_size;$i++) {
            if(mt_rand()%3) {
                $key .= $num[mt_rand(0,9)];
            } else if(mt_rand()%7) {
                $key .= $small[mt_rand(0,25)];
            } else if(mt_rand()%5) {
                $key .= $caps[mt_rand(0,25)];
            } else {
                $key .= $spec[mt_rand(0,13)];
            }
        }
        
        return $key;
    }

    echo "<meta http-equiv='refresh' content='0; url=index.php'>";
?>