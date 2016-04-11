<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$request_uri=$_SERVER['REQUEST_URI'];
$query_string = null;
$query_strings = null;
if(stristr($request_uri,'?')) {
	$query_array = explode("?", $request_uri);
	$query_string = $query_array[1];
	$query_string_array = explode("=", $query_string);
	if(sizeof($query_string_array) && $query_string_array[0]) {
		$query_strings[$query_string_array[0]] = $query_string_array[1];
	}
}
if($query_strings['verify'] == "9b55fda64956820da9becd83851563a6c14673501288b28e6ee2d7e1c3931724") {
	//echo "http 204";
	 header("HTTP/1.0 204 No Response");

} else {
	//echo "http 404";
	header("HTTP/1.0 404 Not Found");
}
