#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

// Write\append to log file
function makeLog($log)
{
	$logfile = fopen("log.txt", "w") or die ("Error opening log file");	
	$message = date("Y-m-d | h:i:sa")." | ".$log.PHP_EOL;
	echo $message;
	return fwrite($logfile, $message);
}

// Process request
function requestProcessor($request)
{
  echo "received request".PHP_EOL;
  var_dump($request);
  if(!isset($request['type']))
  {
    return "ERROR: unsupported message type";
  }
  switch ($request['type'])
  {
    case "log":
	  return makeLog($request['log']);
  }
  return array("returnCode" => '0', 'message'=>"Server received request and processed");
}

$server = new rabbitMQServer("testlog.ini","testlog");
$server->process_requests('requestProcessor');

exit();
?>

