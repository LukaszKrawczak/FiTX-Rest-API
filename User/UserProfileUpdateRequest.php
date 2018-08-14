<?php

    $con = mysqli_connect("mysql.hostinger.com", "u983372861_user", "Lukasz1989!", "u983372861_data");
    
    $updatename     =   $_POST["updatename"];
    $username       =   $_POST["username"];
    $updateusername =   $_POST["updateusername"];
    $updatebirthday =   $_POST["updatebirthday"];
    $updatepassword =   $_POST["updatepassword"];
    $updateemail    =   $_POST["updateemail"];
    $updateweight    =   $_POST["updateweight"];
    $updateheight    =   $_POST["updateheight"];
    $updatesomatotype=   $_POST["updatesomatotype"];
    $updateproteinsratio=   $_POST["updateproteinsratio"];
    $updatefatsratio=   $_POST["updatefatsratio"];
    $updatecarbsratio=   $_POST["updatecarbsratio"];
    $date           =   $_POST["date"];
    
    $sql = "SELECT * FROM user_infomations WHERE username='$username' ORDER BY date DESC LIMIT 1";
    $sql1 = "SELECT * FROM user_infomations WHERE username='$username' AND date='$date'";

    $result1 = mysqli_query($con, $sql);

    $statement = mysqli_prepare($con, "UPDATE user SET username=?, name=?, birthday=?, password=?, email=? WHERE username=?");
                 mysqli_stmt_bind_param($statement, "ssssss", $updateusername, $updatename, $updatebirthday, $updatepassword, $updateemail, $username);
                 mysqli_stmt_execute($statement);

    $statement = mysqli_prepare($con, "UPDATE user_result_calories_counted SET username=? WHERE username=?");
                 mysqli_stmt_bind_param($statement, "ss", $updateusername, $username);
                 mysqli_stmt_execute($statement);

    $row1 = mysqli_fetch_array($result1,MYSQLI_ASSOC);            

    if ($stmt = mysqli_prepare($con, $sql1)) {
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
    }

    if (mysqli_stmt_num_rows($stmt) > 0) {
        $sql2 = "UPDATE user_infomations SET weight='$updateweight', height='$updateheight', somatotype='$updatesomatotype', proteinsratio='$updateproteinsratio', fatsratio='$updatefatsratio', carbsratio='$updatecarbsratio' WHERE username='$username' AND date='$date'";
        mysqli_query($con,$sql2);
    }

    elseif (mysqli_stmt_num_rows($stmt) == 0)  {
    $statement = mysqli_prepare($con, "INSERT INTO user_infomations (id, username, weight, height, somatotype, proteinsratio, fatsratio, carbsratio, date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($statement,"isdddddds", $row1["id"], $row1["username"], $updateweight, $updateheight, $updatesomatotype, $updateproteinsratio, $updatefatsratio, $updatecarbsratio, $date);
    mysqli_stmt_execute($statement);
    }

    $response = array();
    $response["success"] = true;  
    
    echo json_encode($response);
?>