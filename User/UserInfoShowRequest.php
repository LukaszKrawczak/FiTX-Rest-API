<?php

    require_once('../config.php');

    $db = $config['db'];

    $con = mysqli_connect($db['host'], $db['user'], $db['password'],$db['database']);

    $username = $_POST["username"];
    // $username = 'brus5';
    $sql = "SELECT * FROM user WHERE username='$username'";
    $sql1 = "SELECT * FROM user_infomations WHERE username='$username' ORDER BY date DESC LIMIT 1";

    $result = mysqli_query($con, $sql);
    $result1 = mysqli_query($con, $sql1);
    $response = array();

    $row1 = mysqli_fetch_array($result1,MYSQLI_ASSOC);
    // printf("id: %s\n username: %s\n weight: %s\n height: %s\n somatotype: %s\n date: %s", $row1["id"], $row1["username"], $row1["weight"], $row1["height"], $row1["somatotype"], $row1["date"]);


    while ($row = mysqli_fetch_array($result)) {
        array_push($response, array(
            "user_id"=>$row[0], 
            "name"=>$row[1], 
            "username"=>$row[2],
            "birthday"=>$row[3], 
            "password"=>$row[4], 
            "email"=>$row[5], 
            "male"=>$row[6], 
            "height"=>$row1["height"], 
            "weight"=>$row1["weight"], 
            "somatotype"=>$row1["somatotype"], 
            "proteinsratio"=>$row1["proteinsratio"], 
            "fatsratio"=>$row1["fatsratio"], 
            "carbsratio"=>$row1["carbsratio"]));
    }

    echo json_encode(array("server_response"=>$response));

    mysqli_close($con);
?>