CREATE DATABASE IF NOT EXISTS login;
CREATE TABLE IF NOT EXISTS login.users (
	screenname varchar(30),
	password varchar(30),
	bio varchar(5000),
	cabinet varchar(5000),
	likes varchar(5000)
);
