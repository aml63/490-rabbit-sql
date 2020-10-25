<?php

class loginDB
{
	private $logindb;

	public function __construct()
	{	
		// Connect to database host
		$this->logindb = new mysqli("127.0.0.1","testuser","12345","login");

		if ($this->logindb->connect_errno != 0)
		{
			echo "Error connecting to database: ".$this->logindb->connect_error.PHP_EOL;
			exit(1);
		}
		echo "correctly connected to database".PHP_EOL;
	}

	public function validateLogin($username,$password)
	{
		$un = $this->logindb->real_escape_string($username);
		$pw = $this->logindb->real_escape_string($password);
		$statement = "select * from users where screenname = '$un'";
		$response = $this->logindb->query($statement);

		while ($row = $response->fetch_assoc())
		{
			echo "checking password for $username".PHP_EOL;
			if ($row["password"] == $pw)
			{
				echo "passwords match for $username".PHP_EOL;
				return 1;// password match
			}
			echo "passwords did not match for $username".PHP_EOL;
		}
		return 0;//no users matched username
	}
	

	public function newRegister($username,$password)
	{
		$un = $this->logindb->real_escape_string($username);
		$pw = $this->logindb->real_escape_string($password);
	
		$statement = "select * from users where screenname = '$un'";
		$response = $this->logindb->query($statement);
	
		if ($response->fetch_assoc())
		{
			echo "User already found in database! Registration prevented.";
			return 0; // user already exists
		}
		else
		{			
			$statement = "INSERT INTO users (screenname, password) VALUES('$username', '$password')";
			$response = $this->logindb->query($statement);
			
			return 1; // registered?
		}
	}

	public function pushInfo($username,$newinfo,$type)
	{
		$un = $this->logindb->real_escape_string($username);
		$in = $this->logindb->real_escape_string($newinfo);
		
		// determine statement
		if ($type == "setbio")
			$statement = "UPDATE users SET bio='$in' where screenname='$username'";
		if ($type == "setcabinet")
			$statement = "UPDATE users SET cabinet='$in' where screenname='$username'";
			
		$response = $this->logindb->query($statement);
		
		return 1; // info set
	}
	
	public function fetchInfo($username,$type)
	{
		$un = $this->logindb->real_escape_string($username);
		
		if ($type=="getbio")
			$statement = "SELECT bio FROM users WHERE screenname='$username'";
		else if ($type=="getcabinet")
			$statement = "SELECT cabinet FROM users WHERE screenname='$username'";
		
		$response = $this->logindb->query($statement);
				
		return $response->fetch_assoc(); // return bio for user
	}
}
?>
