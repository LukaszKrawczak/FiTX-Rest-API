<?php    // Will check if the file has already been included, and if so, not include (require) it again.    require_once('../config.php');    // Creating array whitch represents database connection info.    $db = $config['db'];    // This variable contains function whitch opens a new connection to the MySQL server.    $con = mysqli_connect($db['host'], $db['user'], $db['password'],$db['database']);    // Variables from fetched hashmap.    $id = $_GET["id"];    $user_id = $_GET["user_id"];    $date = $_GET["date"];    // Selecting the row where user_id, training_id (id) and date    $sql = "SELECT * FROM user_exercise WHERE user_id = $user_id AND id = '$id' AND date LIKE '%$date%'";        // Creating SQL query for getting row where training ID matches with     // SQL id informations    $sql_get_training = "SELECT * FROM list_excercise WHERE id = $id";    $response = array();    $trainings_info = array();    // Getting training informations. This will send informations     // no matter if client sended user_id and date.    $result2 = mysqli_query($con, $sql_get_training);        while ($row = mysqli_fetch_array($result2))         {            array_push($response, array(                "id"=>$row['id'],                "name"=>$row['name'],                 "target"=>$row['target']            )        );        }    // Fetching array with id, done, rest, reps, weight    // user_id, notepad, date    $result2 = mysqli_query($con, $sql);        while ($row2 = mysqli_fetch_array($result2))		{                        array_push($trainings_info, array							(                            "id"=>$row2[0],                             "done"=>$row2[1],                             "rest"=>$row2[2],                             "reps"=>$row2[3],                             "weight"=>$row2[4],                             "user_id"=>$row2[5],                             "notepad"=>$row2[6],                            "date"=>$row2[7]							)						);         }    // Generating JSON response whitch will contain     echo json_encode(            array					(						"server_response"=>$response, 						"trainings_info"=>$trainings_info					                    )				);				mysqli_close($con);