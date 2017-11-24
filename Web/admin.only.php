<?php

require_once './User.class.php';

if(!User::isAdmin()) {
	header('Location: ./index.php');
	die('You have to be administrator to access this page.');
}