
<?php

    require_once('../config.php');

    $db = $config['db'];

    $con = mysqli_connect($db['host'], $db['user'], $db['password'],$db['database']);

	
$username       = $_POST["username"];
$date           = $_POST["date"];
$user_id		= $_POST["user_id"];

$sql_dailyDiet = "SELECT * FROM user_diet WHERE user_id='$user_id' AND date LIKE '%$date%'";


$sql_marosRatio = "SELECT * FROM user_infomations WHERE username='$username' ORDER BY date DESC LIMIT 1";


$sql_caloriesLimit = "SELECT * FROM user_calories_limit WHERE id='$user_id' AND date LIKE '%$month%' ORDER BY date DESC LIMIT 1";


$response = array();
$response_weight = array();
$response_ratio = array();
$response_kcal_limit = array();


if ($result = mysqli_query($con, $sql_dailyDiet)) 
{
    // sprowadzenie wynikow by wyswietlic tablice asocjacynja
    while ($row_id = mysqli_fetch_assoc($result)) 
    {
        $product_id = $row_id["id"];

        $sql1 = "SELECT * FROM list_products WHERE product_id = $product_id";

        $result1 = mysqli_query($con, $sql1);

        while ($row = mysqli_fetch_array($result1)) 
        {
            array_push($response, 
                array(
                    "product_id"        =>$row[0],
                    "name"              =>$row[1],
                    "weight"            =>$row[2],
                    "proteins"          =>$row[3],
                    "fats"              =>$row[4],
                    "carbs"             =>$row[5],
                    "kcal"              =>$row[6],
                    "fats_saturated"    =>$row[7],
                    "fats_unsaturated"  =>$row[8],
                    "carbs_fiber"       =>$row[9],
                    "carbs_sugar"       =>$row[10],
                    "multiplier_piece"  =>$row[11],
                    "verified"          =>$row[12],
                    "date"              =>$row[13],
                    "username"          =>$row[14]
                    )
            );
        }
    }
}


$result2 = mysqli_query($con, $sql_dailyDiet);
while ($row = mysqli_fetch_array($result2))
{
    array_push($response_weight, 
        array(
            "weight"        =>$row[1],
            "date"          =>$row[3]
            )
    );
}

$result3 = mysqli_query($con,$sql_marosRatio);
while ($row = mysqli_fetch_array($result3))
{
    array_push($response_ratio, 
        array(
            "proteinsratio" =>$row[5],
            "fatsratio"     =>$row[6],
            "carbsratio"    =>$row[7]
            )
    );
}


$result4 = mysqli_query($con, $sql_caloriesLimit);
while ($row = mysqli_fetch_array($result4))
{
    array_push($response_kcal_limit, 
        array(
            "RESULT"=>$row[1]
            )
        );
}


echo json_encode(array
        (
        "response"              =>$response,
        "response_weight"       =>$response_weight,
        "response_ratio"        =>$response_ratio,
        "response_kcal_limit"   =>$response_kcal_limit
        )
    );
mysqli_close($con);