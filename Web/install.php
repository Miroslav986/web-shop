<?php

// za kreiranje instalacionog fajla nam je potrebna konekcija sa bazom i zato je ukljucujemo 
// ukljucujemo i config da bi imali podesavanje za admina

$db = require "./db.inc.php";
$config = require "./config.inc.php";

// kreiramo upit za tabelu users
$q_createUserTable = $db->prepare("
	CREATE TABLE IF NOT EXISTS `users`
	(
	`id` int AUTO_INCREMENT,
	`email` varchar(100) UNIQUE,
	`password` varchar(50),
	`name` varchar(100),
	`last_name` varchar(100),
	`newsletter` boolean DEFAULT false,
	`address` varchar(100),
	`city` varchar(100),
	`country` varchar(100),
	`phone_number` varchar(50),
	`date_of_birth` date,
	`account_type` enum('admin','user') DEFAULT 'user',
	`registration_date` datetime DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
	)
	");
$q_createUserTable->execute();
// ovim upitom uzimamo sve korisnike iz tabele  users
$q_getUser = $db->prepare("
	SELECT *
	FROM `users`
	");
$q_getUser->execute();
$number_of_users = $q_getUser->rowCount();

// vrsimo proveru broja korisnika i ukoliko je broj korisnika 0, onda dodajemo administratora.
if($number_of_users == 0) {
	$q_insertAdministrator = $db->prepare("
		INSERT INTO `users`
		(`email`,`password`,`account_type`)
		VALUES
		(:email, :password, :account_type)
		");
	$q_insertAdministrator->bindParam(":email", $config['default_admin_email']);
	$q_insertAdministrator->bindParam(":password", $config['default_admin_password']);
	$q_insertAdministrator->bindParam(":account_type", $config['default_admin_account_type']);
	$q_insertAdministrator->execute();
}

$q_createCategoryTable = $db->prepare('
	CREATE TABLE IF NOT EXISTS `categories`
	(
	`id` int AUTO_INCREMENT,
	`title` varchar(255),
	PRIMARY KEY (`id`)
	)
	');
$q_createCategoryTable->execute();

$q_createProductTable = $db->prepare("
	CREATE TABLE IF NOT EXISTS `products`
	(
	`id` int AUTO_INCREMENT,
	`cat_id` int,
	`title` varchar(255),
	`description` text,
	`price` decimal(7,2),
	`image` varchar(255),
	PRIMARY KEY (`id`)
	)
	");
$q_createProductTable->execute();

$q_createCommentsTable = $db->prepare('
	CREATE TABLE IF NOT EXISTS `comments`
	(
	`id` int AUTO_INCREMENT,
	`user_id` int,
	`product_id` int,
	`comment` text,
	`created_ad` datetime DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
	)
	');
$q_createCommentsTable->execute();

$q_createCartsTable = $db->prepare("
	CREATE TABLE IF NOT EXISTS `carts`
	(
	`id` int AUTO_INCREMENT,
	`user_id` int,
	`product_id` int,
	`quantity` int,
	`created_at` datetime DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
	)
	");
$q_createCartsTable->execute();