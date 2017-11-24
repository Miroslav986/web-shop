<?php  
//pozivamo configuracioni fajl i stavljamo ga u promenljivu $config,  i primenjujemo ga na povezivanje sa bazom

  $config = require './config.inc.php';

  try {
    $db = new PDO("mysql:host={$config['db_host']};dbname={$config['db_name']};charset=utf8;", $config['db_user'], $config['db_pass']);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
  
    return $db;
  } catch(Exception $e) {
    var_dump($e);
    die("Failed to connect to database.");
  }
