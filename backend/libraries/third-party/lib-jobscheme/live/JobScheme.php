<?php
require_once "Config.php";

function getUserId($username)
{

    $db = mysql_connect(JOBS_MYSQL_HOST, JOBS_MYSQL_USER, JOBS_MYSQL_PASSWORD)
          or die("Could not connect : " . mysql_error());

    mysql_select_db(JOBS_MYSQL_DB);

    $user_uid = '';

    $sql = "SELECT uid FROM users WHERE username = '$username'";
    $res = mysql_query($sql,$db) or die(mysql_error());
    if(mysql_num_rows($res)) 
    {
		$row = mysql_fetch_assoc($res);
		if(isset($row['uid'])) 
		{
			return $row['uid'];
		}
		else
		{
			return 0;
		}
    }
    else
    {
		return 0;
    }
}
