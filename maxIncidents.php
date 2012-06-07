<?php
define("ZDAPIKEY", "");
define("ZDUSER", "");
define("ZDURL", "https://subdomain.zendesk.com/api/v2");

/* Note: the ZDURL needs to not have a trailing slash after v2 */

function curlWrap($url, $json, $action)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_MAXREDIRS, 10 );
	curl_setopt($ch, CURLOPT_URL, ZDURL.$url);
	curl_setopt($ch, CURLOPT_USERPWD, ZDUSER."/token:".ZDAPIKEY);
	switch($action){
		case "POST":
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
			break;
		case "GET":
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
			break;
		case "PUT":
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
		default:
			break;
	}
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
	curl_setopt($ch, CURLOPT_USERAGENT, "MozillaXYZ/1.0");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	$output = curl_exec($ch);
	curl_close($ch);
	$decoded = json_decode($output);
	return $decoded;
}


//Get Incident Data
$id = $_GET[id];
$data = curlWrap("/tickets/".$id.".json", null, "GET");

//Find problem ticket and count incidents
$source = $data->ticket->problem_id;
$num = curlWrap("/tickets/".$source."/incidents.json", null, "GET");
$num = count($num->tickets);

//Get tags on existing problem ticket
$pr_tags = curlWrap("/tickets/".$source.".json", null, "GET");
$tag_arr = $pr_tags->ticket->tags;

//Remove old tag and add new tag
foreach($tag_arr as $key => $value){
	if(preg_match('/linked_[0-9]+/i',$value)){
		unset($tag_arr[$key]);
	}
}
array_push($tag_arr, "linked_".$num);
$tag_arr = array_values($tag_arr);

// Create JSON and put it up
$update = json_encode(array( "ticket" => array("tags" => $tag_arr)));
$data = curlWrap("/tickets/".$source.".json", $update, "PUT");
?>