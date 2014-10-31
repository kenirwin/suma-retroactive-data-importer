<?php

/* Suma variables -- these will appear in the `transaction` table */
$device = "Suma-Retro-Data-Importer";
$version = "1.1.0"; // Suma Client Version (not Suma v) -- ignore this variable

$allow_direct_submit = true; // when this variable is set to true and the $sumaserver_url is set, the script will submit data directly into Suma. If the url is not set or this variable is set to false, the script will display the JSON data to submit, but will not perform the submission

$sumaserver_url = ""; // full url with no trailing slash, e.g. http://www.example.com/sumaserver, see note about sumaserver security in README.md file

/* Debugging variables */
$debug = false; //false; // set to true to get PHP's native error messages
$debug_level = E_ALL; // you can change the error reporting level

?>