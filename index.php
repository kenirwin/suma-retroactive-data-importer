<html>
<?php
include_once("config.php"); //handles mysql_connect session
include_once("scripts.php"); //main functions driving Suma Import Generator
?>
<head>
<title>Suma Import Generator</title>
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js" type="text/javascript"></script>
<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/themes/smoothness/jquery-ui.css" type="text/css"/>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/jquery-ui.min.js" type="text/javascript"></script>
<script src="onload.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="style.css" type="text/css" />
</head>

<body>
<div id="wrapper">
<div id="content">
<h1>Suma Import Generator</h1>
  <p><a href="documentation.php" class="button">Documentation</a></p>
<?php
  // show initiative pulldown selector (always)
  print(SelectInitiative());
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
