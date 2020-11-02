CREATE DATABASE IF NOT EXISTS login;
CREATE TABLE IF NOT EXISTS login.users (
	screenname varchar(30),
	password varchar(30),
	bio varchar(5000),
	cabinet varchar(5000),
	likes varchar(5000)
);
CREATE USER IF NOT EXISTS 'testuser'@'localhost' IDENTIFIED BY '12345';
GRANT ALL PRIVILEGES ON login.* TO 'testuser'@'localhost';
