<?php

    $con = mysqli_connect("mysql.hostinger.com", "u983372861_user", "Lukasz1989!", "u983372861_data");

    $username = $_POST["username"];
    $updateweight = $_POST["updateweight"];
    $updateheight = $_POST["updateheight"];
    $updatesomatotype = $_POST["updatesomatotype"];
    $date = $_POST["date"];
    // $username = 'brus5';
    $sql = "SELECT * FROM user_infomations WHERE username='$username' ORDER BY date DESC LIMIT 1";
    $sql1 = "SELECT * FROM user_infomations WHERE username='$username' AND date='$date'";
    $result = mysqli_query($con, $sql);
    $result1 = mysqli_query($con, $sql);
    $result2 = mysqli_query($con, $sql);

    $response = array();

    $row1 = mysqli_fetch_array($result1,MYSQLI_ASSOC);
    // printf("id: %s\n username: %s\n weight: %s\n height: %s\n somatotype: %s\n date: %s", $row1["id"], $row1["username"], $row1["weight"], $row1["height"], $row1["somatotype"], $row1["date"]);

    // $id = $row1["id"];
    // $height = $row1["height"];

    if ($stmt = mysqli_prepare($con, $sql1)) {
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
    }

    if (mysqli_stmt_num_rows($stmt) > 0) {
        $sql2 = "UPDATE user_infomations SET weight='$updateweight' WHERE username='$username' AND date='$date'";
        mysqli_query($con,$sql2);
    }
    elseif (mysqli_stmt_num_rows($stmt) == 0)  {
    $statement = mysqli_prepare($con, "INSERT INTO user_infomations (id, username, weight, height, somatotype, proteinsratio, fatsratio, carbsratio, date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($statement,"isdddddds", $row1["id"], $row1["username"], $updateweight, $row1["height"], $row1["somatotype"], $row1["proteinsratio"], $row1["fatsratio"], $row1["carbsratio"], $date);
    mysqli_stmt_execute($statement);
    }

    echo json_encode("good");
    mysqli_free_result($result);
    mysqli_close($con);
?>