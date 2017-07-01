<?php
use Parsclick\Sessions\PersistentSessionHandler;


$init = new \MyClasses\components\Controller;
$db = $init->db;


$handler = new PersistentSessionHandler($db);
session_set_save_handler($handler);
session_start();
$_SESSION['active'] = time();