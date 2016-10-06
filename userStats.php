<?php
/*
    header("Access-Control-Allow-Origin: *");   

    header("Content-Type: application/json");
  */

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials: true ");
    header("Access-Control-Allow-Methods: OPTIONS, GET, POST");
    header("Access-Control-Allow-Headers: Content-Type, Depth, User-Agent, X-File-Size, 
    X-Requested-With, If-Modified-Since, X-File-Name, Cache-Control");
    header("Access-Control-Allow-Headers: X-Requested-With, content-type, access-control-allow-origin, access-control-allow-methods, access-control-allow-headers");
    header("Content-Type: application/json");

    $ajax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
        $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
/*
    if($ajax) {
            
    }

    $age = array("Peter"=>"35", "Ben"=>"37", "Joe"=>"43");

        echo json_encode($age); */

/*
    header("Access-Control-Allow-Credentials: true ");
    header("Access-Control-Allow-Methods: OPTIONS, GET, POST");
    header("Access-Control-Allow-Headers: Content-Type, Depth, User-Agent, X-File-Size, 
        X-Requested-With, If-Modified-Since, X-File-Name, Cache-Control");
    header("Content-Type: application/json");
    
    header("Access-Control-Allow-Origin: *");
    
    header("Access-Control-Allow-Headers: X-Requested-With, content-type, access-control-allow-origin, access-control-allow-methods, access-control-allow-headers");

    header('Access-Control-Allow-Methods: GET, POST');

    header("Content-Type: application/json"); 

    header('Access-Control-Allow-Origin: *');

    header('Access-Control-Allow-Methods: GET, POST');

    header("Access-Control-Allow-Headers: X-Requested-With"); */

	$connection = mysqli_connect("localhost","root","password","userStats"); 

	$key = $_GET["key"];	

	$id = $_GET["id"];

    $source = $_GET["source"];

    if(isset($key)) {
        
        $sql = "select id from keyRequesters where api_key='" . $key . "';";
        
        $result = mysqli_query($connection, $sql);
        
        if($row = mysqli_fetch_assoc($result)) {	
            
            $sql = "update keyRequesters set count = count + 1 where id=" . $row['id'];
            
            $result = mysqli_query($connection, $sql);
            
            // very important
            mysqli_set_charset($connection, 'utf8');

            if(isset($id))
                $sql = "select * from guests where id=" . $id;
            else if(isset($source))
                $sql = "select * from guests where source='" . $source . "';";
            else
                $sql = "select * from guests";

            $result = mysqli_query($connection, $sql);

            $idArray = array();
            while($row = mysqli_fetch_assoc($result))
            {
                $idArray[] = $row;
            }

            if(isset($id) || isset($source))
                echo json_encode((var)$idArray, JSON_PRETTY_PRINT);
            else
                echo json_encode((object)$idArray, JSON_PRETTY_PRINT);
	}
        else
            echo "Invalid key";
    } else {
        
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
        while($row = mysqli_fetch_assoc($result))
        {
            ?><tr> <td> <?php echo $row["id"]; ?> </td> <td> <?php echo $row["name"]; ?> </td> <td> <?php echo $row["ipAddress"]; ?> </td> <td> <?php echo $row["watch"]; ?> </td> <td> <?php echo $row["source"]; ?> </td> <td> <?php echo $row["viewCount"]; ?> </td> <td> <?php echo $row["lastViewed"]; ?> </td> <td> <?php echo $row["location"]; ?> </td> <td> <?php echo $row["city"]; ?> </td> </tr> <?php
        } ?>
        
                </table> </center>
            </body> 
        </html> 
    <?php
    } 
	
	mysqli_close($connection); 
?>