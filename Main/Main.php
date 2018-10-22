
<?php
    // Will check if the file has already been included, and if so, not include (require) it again.
    require_once('../config.php');

    // Creating array whitch represents database connection info.
    $db = $config['db'];

    // This variable contains function whitch opens a new connection to the MySQL server.
    $con = mysqli_connect($db['host'], $db['user'], $db['password'],$db['database']);
	
	// Variables from fetched hashmap.
	$user_id		= $_GET['user_id'];
	$date           = $_GET['date'];

	$sql_kcal = "SELECT * FROM user_calories WHERE id = '$user_id' AND date LIKE '$date' ";

	$sql_kcal_limit = "SELECT * FROM user_calories_limit WHERE id = $user_id ORDER BY date DESC LIMIT 1 ";

	$sql_exercise = "SELECT * FROM user_exercise WHERE user_id = $user_id AND date LIKE '$date%' ";

	$sql_cardio_time = "SELECT * FROM user_cardio WHERE user_id = $user_id AND date LIKE '$date%' ";

	$sql_cardio_value = "SELECT * FROM list_cardio WHERE user_id = $user_id AND date LIKE '$date%' ";

	$sql_user_weight = "SELECT RESULT FROM user_weight WHERE id = $user_id AND date = '$date%' ";
	$result_user_weight = mysqli_query($con, $sql_user_weight);

	$lista = array();

	$response = array();

	$response_lifted = array();

	$lifted;

	$repetitions;

	$rest;

	$seconds;

	$cardio_time;

	$zero = 0;

	$value = 0;

	$value1 = 0;

	$cardio_counted;

	// cardio burned calories per 1 hour from list_cardio
	$cardio_burned = array(); 
	
	// cardio times from user_cardio
	$cardio_times = array();  

	// user weight from user_weight
	$user_weight = array();  

		$result = mysqli_query($con, $sql_kcal);

		if ($result->num_rows === 0) 
		{
			array_push($response,
				array(
					"kcal"			=>$zero
				)
			);
		}



        while ($row = mysqli_fetch_array($result)) 
        {		
            array_push($response, 
                array(
                    "kcal"	        =>$row[1]
                    )
            );
        }
		
		

		$result_limit = mysqli_query($con, $sql_kcal_limit);
	
        while ($row = mysqli_fetch_array($result_limit)) 
        {		
            array_push($response, 
                array(
                    "kcal_limit"	  =>$row[1]
                    )
            );
        }
		
		
if ($result = mysqli_query($con, $sql_exercise))
{
        while ($row_main = mysqli_fetch_assoc($result)) 
        {		
			$weight = $row_main["weight"];
			$lifted .= $weight;

			$reps = $row_main["reps"];
			$repetitions .= $reps;




			$rest = $row_main["rest"];
			
			$sql_rest = "SELECT * FROM user_exercise WHERE rest = '$rest' AND date LIKE '$date%'";
			
			$reps_rest = mysqli_query($con, $sql_rest);




			$reps = $row_main["reps"];
			
			$sql_reps = "SELECT * FROM user_exercise WHERE reps = '$reps' AND date LIKE '$date%'";
			
			$reps = mysqli_query($con, $sql_reps);




			while ($row = mysqli_fetch_array($reps)) 
			{
				// The string is showing how many dots are in this String.
				// Dot represents 1 serie of exercise
				$divider = substr_count($row[3], '.');		
				
				// $value1 = $divider;
				$value++;
				

					while ($row1 = mysqli_fetch_array($reps_rest)) 
					{		
						// $seconds = $divider * ($row1[2] / 1000);
						$value1++;

						$val = $divider * $row1[2] / 1000;

					}
						


			}

			$seconds += $val;

			$rest_minutes = $seconds;
		}
		
		// divided by 60 seconds to get minutes
		$rest_minutes /= 60; 

		// rest time measured in minutes from whole training
		$rest = $rest_minutes; 

}



if ($result = mysqli_query($con, $sql_cardio_time))
{				

        while ($row_id = mysqli_fetch_assoc($result)) 
        {
			$id = $row_id["id"];
			
			$sql_calories_burned = "SELECT * FROM list_cardio WHERE id = '$id' ";

			$kcal_res = mysqli_query($con, $sql_calories_burned);




			while ($row = mysqli_fetch_array($kcal_res)) 
			{		
            	array_push($cardio_burned, $row[2]);
			}
        }
}



if ($result = mysqli_query($con, $sql_cardio_time))
{	
	while ($row = mysqli_fetch_array($result)) 
	{		
        array_push($cardio_info, array(
            "id"        =>$row4[0], 
            "done"      =>$row4[1], 
            "time"      =>$row4[2], 
            "date"      =>$row4[5]
            )
        );

      	array_push($cardio_times, $row[2]);
	}
}


if ($result = mysqli_query($con, $sql_cardio_time))
{

	$arr_lengh = count($cardio_burned);

	for ($i=0; $i < $arr_lengh ; $i++) 
	{ 
		$cardio_counted += $cardio_burned[$i] * $cardio_times[$i];

		$cardio_time += $cardio_times[$i];
	}

}




while ($row = mysqli_fetch_array($result_user_weight)) 
{
	// // array_push($response, array(
	// 	"RESULT"=>$row[1]
	// 	)
	// );	

	// array_push($user_weight, $row['RESULT']);
	// $user_weight =  doubleval($row['RESULT']);

	array_push($user_weight, array(
		"weight"	=> doubleval($row['RESULT']),
		"date"		=> $date
		)
	);



}




			if (is_null($repetitions)) 
			{
				$repetitions = 0;
			}



			if (is_null($lifted)) 
			{
				$lifted = 0;
			}



			if (is_null($rest)) 
			{
				$rest = 0;
			}



			if (is_null($cardio_time)) 
			{
				$cardio_time = 0;
			}



			if (is_null($cardio_counted)) 
			{
				$cardio_counted = 0;
			}



			array_push($response,
				array(
					"repetitions" => $repetitions
				)
			);
			
			
			
			array_push($response,
				array(
					"lifted" => $lifted
				)
			);



			array_push($response,
				array(
					"rest" => intval($rest)
				)
			);



			array_push($response,
				array(
					"cardio_counted" => intval($cardio_counted)
				)
			);	



			array_push($response,
				array(
					"cardio_time" => intval($cardio_time)
				)
			);	



			array_push($response,
				array(
					"cardio_burned" => $cardio_burned
				)
			);						



			
			array_push($response,
				array(
					"cardio_times" => $cardio_times
				)
			);




			array_push($response,
				array(
					"weight" => $user_weight
				)
			);



			array_push($response,
				array(
					"value" => $value,
					"value1" => $value1
				)
			);









echo json_encode(array
        (
        "response"              =>$response
        )
    );
	
mysqli_close($con);