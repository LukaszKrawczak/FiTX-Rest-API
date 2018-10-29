<?php
/**
 * Created by PhpStorm.
 * User: Łukasz Krawczak
 * Date: 29/10/2018
 * Time: 12:25
 */

// Will check if the file has already been included, and if so, not include (require) it again.
require_once('../config.php');

// Creating array whitch represents database connection info.
$db = $config['db'];

// This variable contains function whitch opens a new connection to the MySQL server.
$con = mysqli_connect($db['host'], $db['user'], $db['password'],$db['database']);


// format yyyy-mm-dd
$date_today = date('Y-m-d', time());

$user_id = -1;

$name = $_GET["name"];
$username = $_GET["username"];
$birthday = $_GET["birthday"];
$email = $_GET["email"];
$password = $_GET["password"];
$sex = $_GET["sex"];

$user_height = $_GET["height"];
$user_weight = $_GET["weight"];


/** Default value for calories limit */
$user_calories_limit = 2000;

/** Default values */
$proteins = 40;
$fats = 20;
$carbs = 40;

/** Default value */
$user_somatotype = 300;

/** 1 - on | 0 - off */
$auto_calories = 1;
/** 0 - mass |  1 - balanced | 2 - reduction */
$diet_goal = 1;


/** Register new user */
$statement_register = mysqli_prepare($con, "INSERT INTO user (name, username, birthday, password, email, male) VALUES (?, ?, ?, ?, ?, ?)");
mysqli_stmt_bind_param($statement_register, "ssssss", $name, $username, $birthday, $password, $email, $sex);
mysqli_stmt_execute($statement_register);

/** Selecting user_id */
$sql_user_id = "SELECT `user_id` FROM `user` WHERE `username` = '$username'";

if ($result = mysqli_query($con, $sql_user_id))
{
    while ($row = mysqli_fetch_assoc($result))
    {
        $user_id = (int) $row["user_id"];
    }
}

/**
 * Entering standard user_diet_ratio
 * which is proteins: 40% - fats: 20% - carbs: 40%
 */
$statement_ratio = mysqli_prepare($con, "INSERT INTO user_diet_ratio (id, proteins, fats, carbs, date) VALUES (?, ?, ?, ?, ?)");
mysqli_stmt_bind_param($statement_ratio, "iiiis", $user_id, $proteins, $fats, $carbs, $date_today);
mysqli_stmt_execute($statement_ratio);


/** Register user's height */
$statement_height = mysqli_prepare($con, "INSERT INTO user_height (id, RESULT, date) VALUES (?, ?, ?)");
mysqli_stmt_bind_param($statement_height, "iss", $user_id,$user_height, $date_today);
mysqli_stmt_execute($statement_height);

/** Register user's weight */
$statement_weight = mysqli_prepare($con, "INSERT INTO user_weight (id, RESULT, date) VALUES (?, ?, ?)");
mysqli_stmt_bind_param($statement_weight, "iss", $user_id,$user_weight, $date_today);
mysqli_stmt_execute($statement_weight);

/** Register user's somatotype */
$statement_somatotype = mysqli_prepare($con, "INSERT INTO user_somatotype (id, RESULT, date) VALUES (?, ?, ?)");
mysqli_stmt_bind_param($statement_somatotype, "iss", $user_id,$user_somatotype, $date_today);
mysqli_stmt_execute($statement_somatotype);

/** Register user's settings */
$statement_settings = mysqli_prepare($con, "INSERT INTO user_settings (id, auto_calories, diet_goal) VALUES (?, ?, ?)");
mysqli_stmt_bind_param($statement_settings, "iii", $user_id,$auto_calories, $diet_goal);
mysqli_stmt_execute($statement_settings);

/** Register user's calories_limit */
$statement_calories_limit = mysqli_prepare($con, "INSERT INTO user_calories_limit (id, RESULT, date) VALUES (?, ?, ?)");
mysqli_stmt_bind_param($statement_calories_limit, "iss", $user_id,$user_calories_limit, $date_today);
mysqli_stmt_execute($statement_calories_limit);


$response = array();
$response["success"] = true;
echo json_encode($response);
mysqli_close($con);
