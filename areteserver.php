#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
require_once('auth.php.inc');

// Helper functions -> auth.php.inc

// Check user credentials against the database
function doLogin($username,$password) 
{
    // lookup username in databas
    // check password
    $login = new loginDB();
    return $login->validateLogin($username,$password);
    //return false if not valid
}

// Put a new user in the database
function doRegister($username,$password) 
{
	$register = new loginDB();
	return $register->newRegister($username,$password);
}

// Set some info for a user
function setInfo($username,$newinfo,$type) 
{
	$set = new loginDB();
	return $set->pushInfo($username,$newinfo,$type);
}

// Get some info for a user
function getInfo($username,$type) // username or id
{
	$get = new loginDB();
	return $get->fetchInfo($username,$type);
}

// Determine what functions are used to handle a request
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
    case "addlike":
      return setInfo($request['username'],$request['addLike'],$request['type']);
    case "getlikes": // this returns a user's likes, not the # of likes for a drink
      return getInfo($request['username'],$request['type']);
    case "getlikestats":
      return getInfo($request['id'],$request['type']);
    case "setcomm":
      return setInfo($request['username'],$request['newComm'],$request['type']);
    case "getcomm":
      return getInfo($request['username'],$request['type']);
    case "validate_session":
      return doValidate($request['sessionId']);
  }
  return array("returnCode" => '0', 'message'=>"Server received request and processed");
}

$server = new rabbitMQServer("testRabbitMQ.ini","testServer"); // Construct a new server/listening when executing this code

$server->process_requests('requestProcessor');	// Designate the function that handles received requests
exit();
?>

