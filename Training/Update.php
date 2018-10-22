<?php
    require_once('../config.php');

    $db = $config['db'];

    $con = mysqli_connect($db['host'], $db['user'], $db['password'],$db['database']);

    $id = $_POST["id"];
    $done = $_POST["done"];
    $rest = $_POST["rest"];
    $reps = $_POST["reps"];
    $weight = $_POST["weight"];
    $user_id = $_POST["user_id"];
    $notepad = $_POST["notepad"];
    $date = $_POST["date"];
    
        $sql = "UPDATE user_exercise SET done='$done' WHERE id='$id' AND user_id='$user_id' AND date='$date'";
        mysqli_query($con,$sql);

        $sql1 = "UPDATE user_exercise SET rest='$rest' WHERE id='$id' AND user_id='$user_id' AND date='$date'";
        mysqli_query($con,$sql1);

        $sql2 = "UPDATE user_exercise SET reps='$reps' WHERE id='$id' AND user_id='$user_id' AND date='$date'";
        mysqli_query($con,$sql2);
        
        $sql3 = "UPDATE user_exercise SET weight='$weight' WHERE id='$id' AND user_id='$user_id' AND date='$date'";
        mysqli_query($con,$sql3);

        $sql4 = "UPDATE user_exercise SET notepad='$notepad' WHERE id='$id' AND user_id='$user_id' AND date='$date'";
        mysqli_query($con,$sql4);
    
    echo json_encode("good");
    mysqli_free_result($result);
    mysqli_close($con);