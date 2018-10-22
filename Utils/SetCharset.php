<?php
/**
 * Created by PhpStorm.
 * User: lukaszkrawczak
 * Date: 08.06.2018
 * Time: 07:51
 */


    require_once('../config.php');

    $db = $config['db'];

    $con = mysqli_connect($db['host'], $db['user'], $db['password'],$db['database']);


    if (!mysqli_set_charset($con, "utf8")) 
	{
	
	} 
	else 
	{
	    mysqli_character_set_name($con);
	}