<?php

$action = $_POST['action'];
$id = $_POST['id'];

header("Content-type: aplication/json");

$db = require './db.inc.php';

if ($action = 'delete_from_cart') {
	$q_removeFromCart = $db->prepare("
		DELETE
		FROM `carts`
		WHERE `id` = :id
		");
	$q_removeFromCart->bindParam(':id',$id);
	$res = $q_removeFromCart->execute();

	if($res) {
		echo json_encode(['success' => true]);
	} else {
		echo json_encode(['success' => false]);
	}
}