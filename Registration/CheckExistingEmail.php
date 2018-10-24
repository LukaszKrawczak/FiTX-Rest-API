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

$email = $_GET["email"];

$lengh = mb_strlen($email);

$duplicated_email = 0;

$sql = "SELECT COUNT(*) AS number FROM user WHERE email='$email'";

$response = array();

// Shows duplicated email: 1 - email already exist, 0 - email doesn't exist
$duplicated_email = 0;

if ($result = mysqli_query($con, $sql))
{
    while ($row = mysqli_fetch_assoc($result))
    {
        $duplicated_email = (int) $row["number"];
    }
}

if ( $duplicated_email > 0
    || $lengh < 5
    || $lengh > 320
    || !filter_var($email, FILTER_VALIDATE_EMAIL))
{
    $response["error"] = true;
}

else
{
    $response["error"] = false;
}

echo json_encode($response);
mysqli_close($con);