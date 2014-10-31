<html>
<?php
include_once("config.php"); //handles mysql_connect session
include_once("scripts.php"); //main functions driving Suma Retroactive Data Importer
?>
<head>
<title>Suma Retroactive Data Importer</title>
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js" type="text/javascript"></script>
<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/themes/smoothness/jquery-ui.css" type="text/css"/>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/jquery-ui.min.js" type="text/javascript"></script>
<script src="onload.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="style.css" type="text/css" />
</head>

<body>
<div id="wrapper">
<div id="content">
<h1>Suma Retroactive Data Importer</h1>
  <p><a href="documentation.php" class="button">Documentation</a></p>
<?php
  // show initiative pulldown selector (always)

  if (! is_readable("config.php")) {
    print '<div class="alert"><h3>Config file not readable</h3><p>The file <strong>config.php</strong> is not present or not readable. Please copy the file <strong>config-sample.php</strong> to <strong>config.php</strong> and add your local Suma Server URL to activate this service.</p></div>';
  }
elseif (! isset($sumaserver_url) || ($sumaserver_url == "")){
  print '<div class="alert"><h3>$sumaserver_url not set</h3><p>The <strong>$sumaserver_url</strong> variable in <strong>config.php</strong> is not set. Please set this variable in order to use the service.</p></div>.';
}
else {
  print(SelectInitiative());
}
?>

<div id="details-form"></div>

  <?php
  //if submission, display JSON or submit to suma
if (isset($_REQUEST['submit-suma-importer'])) {
  if (ValidateSubmission()) {
    HandleSubmission();
  }  
} //end if data submitted

print "</div><!--id=content-->\n";

print '<div id="footer">';
include("license.php");
print "</div><!--id=footer-->\n";
print "</div><!--id=wrapper-->\n";

?>
</body>
</html>
