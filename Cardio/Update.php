<?php
    require_once('../config.php');

    $db = $config['db'];

    $con = mysqli_connect($db['host'], $db['user'], $db['password'],$db['database']);

    $id = $_POST["id"];
    $done = $_POST["done"];
    $time = $_POST["time"];
    $user_id = $_POST["user_id"];
    $notepad = $_POST["notepad"];
    $date = $_POST["date"];
    
        $sql = "UPDATE user_cardio SET done='$done' WHERE id='$id' AND user_id='$user_id' AND date='$date'";
        mysqli_query($con,$sql);

        $sql1 = "UPDATE user_cardio SET time='$time' WHERE id='$id' AND user_id='$user_id' AND date='$date'";
        mysqli_query($con,$sql1);

        $sql4 = "UPDATE user_cardio SET notepad='$notepad' WHERE id='$id' AND user_id='$user_id' AND date='$date'";
        mysqli_query($con,$sql4);
    
    echo json_encode("good");
    mysqli_free_result($result);
    mysqli_close($con);