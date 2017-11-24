<?php

require_once './User.class.php';

if (!User::userId()) {
	header('Location: ./login.php');
	 die('You have to be logged in to access this page.');
}

