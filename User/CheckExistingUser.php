<?php
/**
 * Created by PhpStorm.
 * User: Łukasz Krawczak
 * Date: 22/10/2018
 * Time: 08:13
 */

// Will check if the file has already been included, and if so, not include (require) it again.
require_once('../config.php');

// Creating array whitch represents database connection info.
$db = $config['db'];

// This variable contains function whitch opens a new connection to the MySQL server.
$con = mysqli_connect($db['host'], $db['user'], $db['password'],$db['database']);

$username = $_GET["username"];

$sql = "SELECT COUNT(*) AS number FROM user WHERE username='$username'";

$response = array();

// Shows duplicated user: 1 - user already exist, 0 - user doesn't exist
$duplicated_user = 0;

if ($result = mysqli_query($con, $sql))
{
    while ($row = mysqli_fetch_assoc($result))
    {
        $duplicated_user = $row["number"];
    }
}

if ($duplicated_user > 0)
{
    $response["user_exist"] = true;
}
else
{
    $response["user_exist"] = false;
}

echo json_encode($response);
mysqli_close($con);
