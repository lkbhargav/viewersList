<?php

    // Defines file path as DOCROOT, that needs to be included
    define('DOCROOT', realpath(dirname(__FILE__)). '/');
    
    // Concatenating defined DOCROOT with the file to be included
    include( DOCROOT .'activationAndNotifications.php');
    
    // Staring a session
    session_start();

    // Getting data from HTML form with method set to POST
    $name = $_POST['name'];
    $email = $_POST['mail'];

    // DB connection string
    $connection = mysqli_connect("localhost","root","password","userStats");
    
    // Defining $sendMail object of class notification 
    $sendMail = new notification();

    // Query to see if email already exists
    $sql = "select id from keyRequesters where email = '".$email."';";

    $result = mysqli_query($connection, $sql);

    // Important condition, that decides to generate API Key or not
    if($row = mysqli_fetch_assoc($result)) {
        // Setting a session variable called status to 0, if email already exists
        $_SESSION['status'] = 0;
    } else {
        
        // Call to generateKey() method
        $key = generateKey();

        // Declaring email body
        $body = "<h2> Use the following API Key to access Viewer's List: </h2> <br/> <h5> API KEY: ".$key." </h5>";

        // Sending an email using $sendMail object
        $status = $sendMail->email("mailtosecureyou@gmail.com", "Viewer's List", "mailtosecureyou", "mailstodeliver", $email, "Viewer's List: API Key", $body);

        // Condition to see if the mail has been sent successfully or not
        if($status == 1) {
            
            // Inserting data about the user and key related to him
            $sql = "insert into keyRequesters(name, email, api_key, count) values('".$name."', '".$email."', '".$key."', 0);";

            $result = mysqli_query($connection, $sql);
            
            // Setting a session variable called status to 1, if account created successfully
            $_SESSION['status'] = 1;
        } else {
            
            // Setting a session variable called status to 2, if it was an invalid email address
            $_SESSION['status'] = 2;
        }
    }


    // Function to generate unique API Key
    function generateKey() {
        
        // API Key length
        $key_size = 20;
        
        // Empty key declared
        $key = "";
        
        // Number array
        $num = array("1","2","3","4","5","6","7","8","9","0");
        
        // Small Alphabets array
        $small = array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z");
        
        // Capital Alphabets array
        $caps = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
        
        // Special Charecter's array
        $spec = array('!','@','#','$','%','^','&','*','(',')','-','_','+','=');
        
        // Simple loop to traverse through $key_size length and generate key randomly using random numbers.
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
        
        // Query to search if generated API Key already exists.
        $sql = "select id from keyRequesters where api_key = '".$key."';";
        
        $result = mysqli_query($connection, $sql);
        
        // Simple condition to regenerate the API Key if the generated key already exists.
        if($row = mysqli_fetch_assoc($result)) {
            generateKey();
        } else {
            return $key;    
        }
    }

    // closing the DB connection
	mysqli_close($connection);

    // Simple page redirection using HTML syntax
    echo "<meta http-equiv='refresh' content='0; url=index.php'>";
?>