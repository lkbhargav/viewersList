<!DOCTYPE html>
<htmL>
    <head>
        <title> Viewers List </title>
    </head>
    
    <body>
        <form method="post" action="processor.php">
            <label> Name: </label>
            <input type="text" name="name" required/>
            <br/>
            <label> Email: </label>
            <input type="email" name="mail" required/>
            <br/>
            <input type="submit" value="Generate Key"/>
        </form>
        <?php
        
            // Staring a session
            session_start();
        
            // Condition to display HTML acording to the session variable status
            if(isset($_SESSION['status'])) {
                if($_SESSION['status'] == 0) {
                    echo "<p style='color:red'> Email already exists. </p>";
                } else if($_SESSION['status'] == 1) {
                    echo "<p style='color:green'> Email has been sent with the api_key as you requested. </p>";
                } else if($_SESSION['status'] == 2) {
                    echo "<p style='color:red'> Invalid Email. </p>";
                }
                
                // Unsetting the status session
                unset($_SESSION['status']);
            }
        ?>
        
        <a href="32.208.103.170/userStats.php">List View</a>
    </body>
</htmL>