<?php
use Parsclick\Sessions\PersistentSessionHandler;

$init = new \MyClasses\components\Controller;
$db_connect = $init->db;
$handler = new PersistentSessionHandler($db_connect);
session_set_save_handler($handler);
session_start();
$_SESSION['active'] = time();