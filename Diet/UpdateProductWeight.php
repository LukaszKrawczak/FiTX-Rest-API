<?php

    require_once('../config.php');

    $db = $config['db'];

    $con = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);

    $id = $_POST["id"];
    $user_id = $_POST["user_id"];
    $updateweight = $_POST["updateweight"];
    $date = $_POST["date"];
    
        $sql = "UPDATE user_diet SET weight = $updateweight WHERE id = $id AND user_id = $user_id AND date='$date'";
        mysqli_query($con,$sql);    

    $response = array();
    $response["success"] = true;
    echo json_encode($response);
    mysqli_close($con);
