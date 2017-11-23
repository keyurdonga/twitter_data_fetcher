<?php
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';

$feed = $_POST['feed'];
$d = json_decode($feed);
echo count($d);
for ($x = 0; $x < count($d); $x++) {
	$conn = mysql_connect($dbhost, $dbuser, $dbpass);

	if (!$conn)
	{
		print_r("\n");
		die('Could not connect: ' . mysql_error());
	}

	$tId = $d[$x]->id;
	$text = $d[$x]->text;
	$_text = mysql_real_escape_string($text);
	$hashtags = $d[$x]->hashtags;
	$_hashtags = mysql_real_escape_string($hashtags);
	$userId = $d[$x]->user->id;
	$userName = $d[$x]->user->name;
	$_userName = mysql_real_escape_string($userName);
	$userLocation = $d[$x]->user->location;
	$_userLocation = mysql_real_escape_string($userLocation);
	$userFollowersCount = $d[$x]->user->followers_count;
	$userFriendsCount = $d[$x]->user->friends_count;
	$userListedCount = $d[$x]->user->listed_count;
	$userCreatedAt = $d[$x]->user->created_at;
	$userFavouritesCount = $d[$x]->user->favourites_count;
	$userTimeZone = $d[$x]->user->time_zone;
	$userStatusesCount = $d[$x]->user->statuses_count;
	$userLang = $d[$x]->user->lang;
	$_userLang = $userLang;
	$retweetCount = $d[$x]->retweet_count;
	$favoriteCount = $d[$x]->favorite_count;
	$lang = $d[$x]->lang;
		
	mysql_select_db('tweets');
	$sql1 = "SELECT * FROM `tweet-info` WHERE `tweet_id` = '$tId';";
	$retval1 = mysql_query($sql1, $conn);
	if (!$retval1)
	{
		print_r("\n Error SQL1 - ");
		print_r($sql1);
		print_r("\n");
		die('Could not enter data1: ' . mysql_error());
	}
	$num_rows = mysql_num_rows($retval1);
	
	if($num_rows == 0){
			$sql2 = "INSERT INTO `tweet-info` (`tweet_id`, `tweet`, `user_id`, `retweet_count`, `favorite_count`, `language`) 
			VALUES ('$tId', '$_text', '$userId', '$retweetCount', '$favoriteCount', '$lang');";
			$retval2 = mysql_query($sql2, $conn);
			if (!$retval2)
			{
				print_r("\n Error SQL2 - ");
				print_r($sql2);
				print_r("\n");
				die('Could not enter data2: ' . mysql_error());
			}
			
			$sql3 = "INSERT INTO `tweet-hashtags` (`tweet_id`, `tweet_hashtags`) VALUES ('$tId', '$_hashtags');";
			$retval3 = mysql_query($sql3, $conn);
			if (!$retval3)
			{
				print_r("\n Error SQL3 - ");
				print_r($sql3);
				print_r("\n");
				die('Could not enter data3: ' . mysql_error());
			}
			
			$sql4 = "SELECT * FROM `user-info` WHERE `user_id` = '$userId';";
			$retval4 = mysql_query($sql4, $conn);
			if (!$retval4)
			{
				print_r("\n Error SQL4 - ");
				print_r($sql4);
				print_r("\n");
				die('Could not enter data4: ' . mysql_error());
			}
			$num_rows1 = mysql_num_rows($retval4);
			
			if($num_rows1 == 0){
				$sql5 = "INSERT INTO `user-info` (`user_id`, `name`, `location`, `followers_count`, `friends_count`, `listed_count`, `created_at`, `favourites_count`, `time_zone`, `statuses_count`, `lang`)
				VALUES ('$userId', '$_userName', '$_userLocation', '$userFollowersCount', '$userFriendsCount', '$userListedCount', '$userCreatedAt', '$userFavouritesCount', '$userTimeZone', '$userStatusesCount', '$userLang');";
				$retval5 = mysql_query($sql5, $conn);
				if (!$retval5)
				{
					print_r("\n Error SQL5 - ");
					print_r($sql5);
					print_r("\n");
					die('Could not enter new data5: ' . mysql_error());
				}
				print_r("\n New User");
			}else{
				$sql6 = "UPDATE `user-info` SET `name` = '$userName',
												`location` = '$_userLocation',
												`followers_count` = '$userFollowersCount',
												`friends_count` = '$userFriendsCount',
												`listed_count` = '$userListedCount',
												`favourites_count` = '$userFavouritesCount',
												`time_zone` = '$userTimeZone',
												`statuses_count` = '$userStatusesCount',
												`lang` = '$userLang'
											WHERE `user-info`.`user_id` = '$userId'";				
				
				$retval6 = mysql_query($sql6, $conn);
				if (!$retval6)
				{
					print_r("\n Error SQL6 - ");
					print_r($sql6);
					print_r("\n");
					die('Could not enter update data6: ' . mysql_error());
				}
				print_r("\n Updated User");
			}
	}
	echo "\n Entered data successfully \n";
	mysql_close($conn);
} 


function mysql_escape_mimic($inp) { 
    if(is_array($inp)) 
        return array_map(__METHOD__, $inp); 

    if(!empty($inp) && is_string($inp)) { 
        return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $inp); 
    } 

    return $inp; 
}

</script>