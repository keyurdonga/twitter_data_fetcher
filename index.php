<?php

$consumer_key = 'LH3oQv28gacLQfFIoMjj50QIB';
$consumer_secret = '2Z13ObQn6KszRBq8EQXsvwcDDWqTVKb69KMo0SeqSjmkRV7KWo';
$access_token = '2826939806-V7uMmKFADJgmBMdtBhjUmeXOIi07lt0oRBpRFf6';
$access_token_secret = 'MoTxGQXnRKCDm2eUPbkPtBbHoN9JwbeF0nNjBrBRkUYH3';

require "twitteroauth/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;

$connection = new TwitterOAuth($consumer_key, $consumer_secret, $access_token, $access_token_secret);
$content = $connection->get("account/verify_credentials");
//$feed1 = $connection->get("application/rate_limit_status");

$currencies = array("Bitcoin","Ethereum","Bitcoin Cash","Ripple","Litecoin");
$len = count($currencies);
$feed = array($len);
for($x = 0; $x < $len; $x++) {
	$arr = $connection->get("search/tweets",["q" => $currencies[$x], "count" => 100]);
	$feed[$x] = json_encode($arr);
}

?>
<script
  src="https://code.jquery.com/jquery-3.2.1.min.js"
  integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
  crossorigin="anonymous"></script>
  
<script type="text/javascript">
	// pass PHP variable declared above to JavaScript variable
	var feedLength = <?php echo $len; ?>;
	var feed = [];
	feed.push(<?php echo $feed[0]; ?>);
	feed.push(<?php echo $feed[1]; ?>);
	feed.push(<?php echo $feed[2]; ?>);
	feed.push(<?php echo $feed[3]; ?>);
	feed.push(<?php echo $feed[4]; ?>);
	//console.log(feed);
	console.log("");
	var fLen = feed.length;
	if(fLen != feedLength){
		alert("please update your feed array in javascript too.!!");
	}
	var sData = [];
	for(var i=0; i<fLen;i++){
		var sLen = feed[i].statuses.length;
		for(var j=0;j<sLen;j++){
			
			if(feed[i].statuses[j].lang == "en"){
				var elements = {};
				elements.id = feed[i].statuses[j].id;
				elements.text = feed[i].statuses[j].text;
				var hLen = feed[i].statuses[j].entities.hashtags.length;
				if(hLen > 0){
					var hashtags = " ";
					for(var k=0;k<hLen;k++){
						hashtags += feed[i].statuses[j].entities.hashtags[k].text + " ";
					}
					elements.hashtags = hashtags.trim();
				}else{
					elements.hashtags = "";
				}
				elements.user = {};
				elements.user.id = feed[i].statuses[j].user.id;
				elements.user.name = feed[i].statuses[j].user.name;
				elements.user.location = feed[i].statuses[j].user.location;
				elements.user.followers_count = feed[i].statuses[j].user.followers_count;
				elements.user.friends_count = feed[i].statuses[j].user.friends_count;
				elements.user.listed_count = feed[i].statuses[j].user.listed_count;
				elements.user.created_at = feed[i].statuses[j].user.created_at;
				elements.user.favourites_count = feed[i].statuses[j].user.favourites_count;
				elements.user.time_zone = feed[i].statuses[j].user.time_zone;
				elements.user.statuses_count = feed[i].statuses[j].user.statuses_count;
				elements.user.lang = feed[i].statuses[j].user.lang;
				elements.retweet_count = feed[i].statuses[j].retweet_count;
				elements.favorite_count = feed[i].statuses[j].favorite_count;
				elements.lang = feed[i].statuses[j].lang;
				sData.push(elements);
			}
		}
	}
	var fd = JSON.stringify(sData);
	
	$.ajax({
        type: 'post',
        url: 'http://localhost/twitter/database.php', //<--Note http
        data: {feed:fd},
        success: function( data ) {
			console.log( data );
        }
    });
	
</script>