<?php        // Will check if the file has already been included, and if so, not include (require) it again.    require_once('../config.php');    // Creating array whitch represents database connection info.    $db = $config['db'];    // This variable contains function whitch opens a new connection to the MySQL server.    $con = mysqli_connect($db['host'], $db['user'], $db['password'],$db['database']);       $name = $_POST["name"];    $username = $_POST["username"];    // $username = 'brus5';    $birthday = $_POST["birthday"];    $password = $_POST["password"];    $male = $_POST["male"];    $email = $_POST["email"];    $RESULT = "2000";    $date = "01.01.2018";    $id = '1';    $weight = '80';    $height = '180';    $somatotype = '700';    $proteinsratio = '40';    $fatsratio = '20';    $carbsratio = '40';    $date = "01.01.2018";    $sql = "SELECT * FROM user WHERE username='$username'";    $result = mysqli_query($con, $sql);    $response = array();    if ($stmt = mysqli_prepare($con, $sql)) {        mysqli_stmt_execute($stmt);        mysqli_stmt_store_result($stmt);    }    if (mysqli_stmt_num_rows($stmt) > 0) {        $response["userused"] = true;    }elseif (mysqli_stmt_num_rows($stmt) == 0)  {    $statement = mysqli_prepare($con, "INSERT INTO user (name, username, birthday, password, male, email) VALUES (?, ?, ?, ?, ?, ?)");    mysqli_stmt_bind_param($statement, "ssisss", $name, $username, $birthday, $password, $male, $email);    mysqli_stmt_execute($statement);     $statement = mysqli_prepare($con, "INSERT INTO user_result_calories_counted (id, username, RESULT, date) VALUES (?,?,?,?)");    mysqli_stmt_bind_param($statement, "isss", $id, $username, $RESULT, $date);    mysqli_stmt_execute($statement);    $statement = mysqli_prepare($con, "INSERT INTO user_infomations (id, username, weight, height, somatotype, proteinsratio, fatsratio,  carbsratio, date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");    mysqli_stmt_bind_param($statement, "isdddddds", $id, $username, $weight, $height, $somatotype, $proteinsratio, $fatsratio, $carbsratio, $date);    mysqli_stmt_execute($statement);    $response["success"] = true; }    echo json_encode($response);    ?>