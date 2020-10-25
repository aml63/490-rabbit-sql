#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
require_once('auth.php.inc');

function doLogin($username,$password)
{
    // lookup username in databas
    // check password
    $login = new loginDB();
    return $login->validateLogin($username,$password);
    //return false if not valid
}


function doRegister($username,$password)
{
	$register = new loginDB();
	return $register->newRegister($username,$password);
}

function setInfo($username,$newinfo,$type)
{
	$set = new loginDB();
	return $set->pushInfo($username,$newinfo,$type);
}

function getInfo($username,$type)
{
	$get = new loginDB();
	return $get->fetchInfo($username,$type);
}

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
    case "login":
      return doLogin($request['username'],$request['password']);
    case "register":
      return doRegister($request['username'],$request['password']);
    case "setbio":
      return setInfo($request['username'],$request['newBio'],$request['type']);
    case "getbio":
      return getInfo($request['username'],$request['type']);
    case "setcabinet":
      return setInfo($request['username'],$request['newCabinet'],$request['type']);
    case "getcabinet":
      return getInfo($request['username'],$request['type']);
    case "validate_session":
      return doValidate($request['sessionId']);
  }
  return array("returnCode" => '0', 'message'=>"Server received request and processed");
}

$server = new rabbitMQServer("testRabbitMQ.ini","testServer");

$server->process_requests('requestProcessor');
exit();
?>

