<?php
/* Suma variables -- these will appear in the `transaction` table */
$device = "manual";
$version = "1.1.0";

/* Debugging variables */
$debug = false; //false; // set to true to get PHP's native error messages
$debug_level = E_ALL; // you can change the error reporting level

/* MySQL connect variables */
$host = "localhost";
$user = "suma_user";
$password = "password";
$database = "suma_live";

/* Possible future variables */
//future versions might let you set preselected default value for input
$activity_defaults = array(); 
$count_defaults = array();


/* connect to MySQL */
$db = mysql_pconnect ($host, $user, $password) || die ("failed to connect to MySQL");
mysql_select_db ($database) || die ("failed to connect to Suma database");
?>