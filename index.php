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
  if (isset($_REQUEST['date']) && isset($_REQUEST['time']) && is_array($_REQUEST['counts'])) {
    HandleSubmission();
  } //end if submission
elseif (isset($_REQUEST['submit-suma-importer'])) {
  //if submission not meeting all requirements
  print '<div class="alert" id="submission-response"><h3>Cannot Submit Data</h3><p>Submission requires at least a date, time, and one count. Please try again.</p>';
    } //end elseif bad submission
print "</div><!--id=content-->\n";

print '<div id="footer">';
include("license.php");
print "</div><!--id=footer-->\n";
print "</div><!--id=wrapper-->\n";

?>
</body>
</html>
