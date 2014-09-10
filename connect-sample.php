<?
$host = "localhost";
$user = "suma_user";
$password = "password";
$database = "suma_live";

$db = mysql_pconnect ($host, $user, $password) || die ("failed to connect to MySQL");
mysql_select_db ($database) || die ("failed to connect to Suma database");
?>