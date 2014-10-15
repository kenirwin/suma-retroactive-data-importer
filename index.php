<?php
include_once("config.php"); //handles mysql_connect session
include_once("scripts.php");
?>
<head>
<title>Suma Import Generator</title>
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/themes/smoothness/jquery-ui.css" />
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/jquery-ui.min.js"></script>
 <script>
$(document).ready(function() {
    $("select").change(function() {
	$("#json-output").hide();
	var initID = $(this).val();
	if (initID == "") {
	  $("#details-form").html("");
	}
	else {
	  $.get("load_fields.php", { initiative: initID })
	      .done(function(data) {
		  $("#details-form").html(data); // load form fields
		  $("#datepicker").datepicker(); //trigger datepicker

		  // for counts with more than one location, display 
		  // sum of all location counts
		  $(".counts").bind("keyup", function () {
		      var total = 0;
		      $(".counts").each(function(e) {
			  tmpVal = $(this).val();
			  total += Number(tmpVal);
			}); //end each count
		      $("#sum-counts").html(total);
		    }); //end keyup

		}); //end js-actions on successful AJAX load
	} //end else if there's an initiative ID
      }); //end on selection of initiative from pulldown
  }); //end document ready

</script>
<style>
body { height: 100%; margin: 0; padding: 0; }
#wrapper { min-height:100%; position: relative }
#content { padding: 10px; padding-bottom: 80px }
#footer { position: absolute; bottom: 10; left: 0; height: 80px; padding: 10px}
</style>
</head>

<body>
<div id="wrapper">
<div id="content">
<h1>Retroactive Suma Import Generator</h1>

<p><a href="https://github.com/cazzerson/Suma/issues/17">JSON Format to Emulate</a></p>

<?php
  print(SelectInitiative());
?>

<div id="details-form"></div>

<?php
  if (isset($_REQUEST['date']) && isset($_REQUEST['time']) && is_array($_REQUEST['counts'])) {
    $sessions_all = array();
    $date = $_REQUEST['date'];
    $time = $_REQUEST['time'];
    $start = strtotime("$date $time");
    $end = $start + (60*5); //add five minutes
    $counts = $_REQUEST['counts'];
    $initiative = intval($_REQUEST['initiative']);

    if (isset($_REQUEST['activities'])) {
      $activity_info = array();
      foreach ($_REQUEST['activities'] as $v) {
	array_push( $activity_info, intval($v));
      }
    }
    else { $activity_info = array ();} 

    $session_array = GenerateOneSession($initiative, $start, $end, $counts, $activity_info);
    
    array_push ($sessions_all, $session_array);

    print "<form><textarea id=\"json-output\" cols=\"80\" rows=\"25\">";
    print (GenerateJSON($sessions_all));
    print "</textarea></form>";
    print "<hr />\n";

  } //end if submission
print "</div><!--id=content-->\n";

print '<div id="footer">';
include("license.php");
print "</div><!--id=footer-->\n";
print "</div><!--id=wrapper-->\n";

?>
</body>

