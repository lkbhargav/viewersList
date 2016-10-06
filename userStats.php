<?php
    // required headers for CORS compability
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials: true ");
    header("Access-Control-Allow-Methods: OPTIONS, GET, POST");
    header("Access-Control-Allow-Headers: Content-Type, Depth, User-Agent, X-File-Size, 
    X-Requested-With, If-Modified-Since, X-File-Name, Cache-Control");
    header("Access-Control-Allow-Headers: X-Requested-With, content-type, access-control-allow-origin, access-control-allow-methods, access-control-allow-headers");
    header("Content-Type: application/json");

    $idArray = array();

    // check for preflight
    $ajax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
        $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';

    // DB connection object
	$connection = mysqli_connect("localhost","root","password","userStats"); 

    // Getting values from GET request
	$key = $_GET["key"];	

	$id = $_GET["id"];

    $source = $_GET["source"];


    // Condition to see if API Key is set
    if(isset($key)) {
        
        // Query to verify if the key exists in records
        $sql = "select id from keyRequesters where api_key='" . $key . "';";
        
        $result = mysqli_query($connection, $sql);
        
        if($row = mysqli_fetch_assoc($result)) {	
            
            // Query to update use count
            $sql = "update keyRequesters set count = count + 1 where id=" . $row['id'];
            
            $result = mysqli_query($connection, $sql);
            
            // very important for json_encode to work with MySql databases
            mysqli_set_charset($connection, 'utf8');

            // Condition to decide on query to execute accordingly
            if(isset($id))
                $sql = "select * from guests where id=" . $id;
            else if(isset($source))
                $sql = "select * from guests where source='" . $source . "';";
            else
                $sql = "select * from guests";

            $result = mysqli_query($connection, $sql);

            // Adding values to idArray array, that to be printed
            while($row = mysqli_fetch_assoc($result))
            {
                $idArray[] = $row;
            }

            // way to encode the object
            if(isset($id) || isset($source))
                echo json_encode((object)$idArray, JSON_PRETTY_PRINT);
            else
                echo json_encode((object)$idArray, JSON_PRETTY_PRINT);
	   } else {
            // Message to print when invalid API Key is entered.
            echo "Invalid key";
        }
        
    } else {
        // Query to select all the viewer's
        $sql = "select * from guests";
        
        $result = mysqli_query($connection, $sql);
        ?>
        <!DOCTYPE html>
        <html>
            <head> 
                <title> Viewers </title>
            </head> 
            
            <body> 
                
                <center> 
                <h2>List of Viewer's</h2>
                <table> 
                    <tr> 
                        <th> ID </th> 
                        <th> Name </th> 
                        <th> IP Address </th> 
                        <th> First Viewed </th> 
                        <th> Source </th> 
                        <th> View Count </th> 
                        <th> Last Viewed </th> 
                        <th> Co-ordinates </th> 
                        <th> City </th> 
                    </tr>
        <?php
        // outputting all the viewer's and data in HTML table format
        while($row = mysqli_fetch_assoc($result))
        {
            ?><tr> <td> <?php echo $row["id"]; ?> </td> <td> <?php echo $row["name"]; ?> </td> <td> <?php echo $row["ipAddress"]; ?> </td> <td> <?php echo $row["watch"]; ?> </td> <td> <?php echo $row["source"]; ?> </td> <td> <?php echo $row["viewCount"]; ?> </td> <td> <?php echo $row["lastViewed"]; ?> </td> <td> <?php echo $row["location"]; ?> </td> <td> <?php echo $row["city"]; ?> </td> </tr> <?php
        } ?>
        
                </table> </center>
            </body> 
        </html> 
    <?php
    } 
	
    // closing the DB connection
	mysqli_close($connection);
?>