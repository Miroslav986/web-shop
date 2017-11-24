<?php

require_once "./User.class.php";

$u = new User();
$u->logout();

header('Location: ./index.php');