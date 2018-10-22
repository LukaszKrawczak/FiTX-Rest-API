
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





	$response = array();
	




	$reps;
    $sql = "SELECT reps FROM user_exercise WHERE user_id = '$user_id' AND DATE(`date`) > (NOW() - INTERVAL 7 DAY)";
	$sql_query_reps = mysqli_query($con, $sql);


	while ($row = mysqli_fetch_assoc($sql_query_reps)) 
	{ 
        // Merge Strings 
		$reps .= $row["reps"];
    }

    // Converting String to Array
    $reps = str_replace('.', ' ', $reps);
    
    // Converting String value to Integer Array
    $interegArray = array_map('intval', explode(' ', $reps));
    
    // Sum all integerArray elements
    $reps_sum = array_sum($interegArray);
    
    // Average rep time is 3 seconds, so all reps times by 3 seconds
    $reps_time = $reps_sum * 3 / 60;
    
    
    // Calories burned under phisical activity (TEA)
    // Trening siłowy – 4 × 60 min x 8 = 1920 kcal
    // Wynik z punktu 2 dzielimy przez 7 (dni tygodnia) = 274 kcal
    $TEA = ($reps_time * 8) / 7;





    $TEA_AREO;
    $sql_tea_areo = "SELECT `time` FROM `user_cardio` WHERE `user_id` = 5 AND DATE(`date`) > (NOW() - INTERVAL 7 DAY)";
    $sql_query_tea_areo = mysqli_query($con, $sql_tea_areo);

    while ($row = mysqli_fetch_assoc($sql_query_tea_areo)) 
    {
        $TEA_AREO += $row['time'] * 5;
    }

    // Divided by 7 days
    $TEA_AREO /= 7;

    // Adding $TEA_AREO to $areo variable
    $TEA = $TEA + $TEA_AREO;

    $weight;
    // Selecting the last date row from user_weight
    $sql_weight = "SELECT RESULT FROM `user_weight` WHERE id = '$user_id' ORDER BY date DESC LIMIT 1";
    $sql_query_weight = mysqli_query($con, $sql_weight);

    while ($row = mysqli_fetch_assoc($sql_query_weight)) 
    {
        $weight = doubleval($row['RESULT']);
    }





    $height;
    // Selecting the last date row from user_height
    $sql_height = "SELECT RESULT FROM `user_height` WHERE id = '$user_id' ORDER BY date DESC LIMIT 1";
    $sql_query_height = mysqli_query($con, $sql_height);

    while ($row = mysqli_fetch_assoc($sql_query_height)) 
    {
        $height = intval($row['RESULT']);
    }





    $birthday;
    $sql_birth = "SELECT birthday FROM `user` WHERE user_id = '$user_id' ";
    $sql_query_birth = mysqli_query($con, $sql_birth);

    while ($row = mysqli_fetch_assoc($sql_query_birth)) 
    {
        //date in dd/mm/yyyy format; or it can be in other formats as well
        $birthday = $row['birthday'];
    }

    // Explode the date to get month, day and year
    $birthday = explode(".", $birthday);
    
    // Get age from date or birthdate
    $age = (date("md", date("U", mktime(0, 0, 0, $birthday[0], $birthday[1], $birthday[2])    )) > date("md")
      ? ((date("Y") - $birthday[2]) - 1)
      : (date("Y") - $birthday[2]));





    $sex;
    $sql_sex = "SELECT male FROM `user` WHERE user_id = '$user_id' ";
    $sql_query_sex = mysqli_query($con, $sql_sex);

    while ($row = mysqli_fetch_assoc($sql_query_sex)) 
    {
        $sex = $row['male'];
    }





    $somatotype;
    $sql_somatotype = "SELECT RESULT FROM `user_somatotype` WHERE id = '$user_id' ";
    $sql_query_somatotype = mysqli_query($con, $sql_somatotype);

    while ($row = mysqli_fetch_assoc($sql_query_somatotype)) 
    {
        $somatotype = $row['RESULT'];
    }






    // If user is man
    if (strpos($sex, 'm') !== false) 
    {
        $BMR = (9.99 * $weight) + (6.25 * $height) - (4.92 * $age) + 5;
    }
    else
    {
        // If user is woman
        $BMR = (9.99 * $weight) + (6.25 * $height) - (4.92 * $age) - 161;
    }


/*
Przykład
Mężczyzna, mezomorfik, 18 lat, 80 kg, 178 cm wzrostu, 4 intensywne treningi siłowe w tygodniu po 60 min, oraz dodatkowo po każdym treningu 20 min interwałów.


Podstawową przemianę materii (BMR):
BMR = (9,99 × 80 (kg)) + (6,25 × 178 (cm)) – (4,92 × 18) + 5 = 1828,14 kcal

 

Kalorie spalone podczas aktywności fizycznej (TEA):
Trening siłowy – 4 × 60 min x 8 = 1920 kcal

Wynik z punktu 2 dzielimy przez 7 (dni tygodnia) = 274 kcal

 

Sumujemy wyniki z punktu 1 i 3:

(BMR) 1828,14 kcal + (TEA)274 kcal = 2102,14 kcal

 

Dodajemy NEAT:
2102,14 kcal + 500 kcal = 2602,14 kcal

 

Doliczamy efekt termiczny pożywienia (TEF)
TDEE = 2602,14 kcal + (0,1 x 2602,14) ≈ 2862 kcal
*/

    $TDEE = $BMR + $TEA + $somatotype;

    // Adding Thermic effect of food (TEF)
    $TDEE = $TDEE + (0.1 * $TDEE);







    $diet_goal;
    $sql_diet_goal = "SELECT diet_goal FROM user_settings WHERE id = '$user_id' ";
    $sql_query_diet_goal = mysqli_query($con, $sql_diet_goal);

    while ($row = mysqli_fetch_assoc($sql_query_diet_goal)) 
    {
        $diet_goal = $row['diet_goal'];
    }

    // Increase by 10% of calories to TDEE if "mass" selected
    if ($diet_goal == 0) 
    {
        $TDEE = $TDEE + ($TDEE * 0.1);
    }

    // Balanced value is saved as '1'. 
    // So we don't need to do nothing with that.


    // Decrease by 10% of calories to TDEE if "reduction" selected
    if ($diet_goal == 2) 
    {
        $TDEE = $TDEE - ($TDEE * 0.1);
    }








    $duplicated = 0;
    // Checking if table has already entered record with date
    $sql_date_check = "SELECT COUNT(*) date FROM user_calories_limit WHERE date = '$date' AND id = '$user_id'";
    $sql_query_date_check = mysqli_query($con, $sql_date_check);

    while ($row = mysqli_fetch_assoc($sql_query_date_check))
    {
        $duplicated = intval($row['date']);
    }

    $val;

    if ($duplicated < 1)
    {
        $statement = mysqli_prepare($con, "INSERT INTO user_calories_limit (id, RESULT, date) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($statement, "iis", $user_id, intval($TDEE), $date);
        mysqli_stmt_execute($statement);
        $val = "if (duplicated < 1)";
    }

    if ($duplicated > 0) 
    {
        $sql_update_kcal_limit = "UPDATE `user_calories_limit` SET `RESULT` = $TDEE WHERE `id`='$user_id' AND `date`='$date' ";
        mysqli_query($con, $sql_update_kcal_limit);        
        $val = "if (duplicated  > 0)";
        // $statement = mysqli_prepare($con, "UPDATE user_calories_limit (id, RESULT, date) VALUES (?,?,?)") ;
        // mysqli_stmt_bind_param($statement, "iis", $user_id, intval($TDEE), $date);
        // mysqli_stmt_execute($statement);
    }




    







echo json_encode(array
        (
        // "reps_time"           =>intval($reps_time),
        // "value"               =>$reps,
        // "TEA"                 =>intval($TEA),
        // "TEA_AREO"            =>intval($TEA_AREO),
        // "weight"              =>intval($weight),
        // "height"              =>intval($height),
        // "age"                 =>$age,
        // "sex"                 =>$sex,
        // "BMR"                 =>intval($BMR),
        // "diet_goal"           =>intval($diet_goal),
        "duplicated" => $duplicated,
        "val" => $val,
        "RESULT"                =>intval($TDEE)
        )
    );
	
mysqli_close($con);