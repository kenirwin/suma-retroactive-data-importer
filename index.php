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
		  $( "#datepicker" ).datepicker(); //trigger datepicker
		});
	}
      }); //end on click delete-link
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
print(SelectInitiative($_REQUEST['initative']));
?>

<div id="details-form"></div>

<?php
  if ($_REQUEST['date'] && $_REQUEST['time'] && is_array($_REQUEST['counts'])) {
    $date = $_REQUEST['date'];
    $time = $_REQUEST['time'];
    $counts = $_REQUEST['counts'];
    $initiative = intval($_REQUEST['initiative']);
    $start = strtotime("$date $time");
    $end = $start + (60*5); //add five minutes
    $temptime = $start; 
    $temp_array = $counts_array = array();

    if ($_REQUEST['activities']) {
      $activity_info = array();
      foreach ($_REQUEST['activities'] as $v) {
	array_push( $activity_info, intval($v));
      }
    }
    else { $activity_info = array ();} 

    foreach ($counts as $loc => $ct) {
      $temptime++;
      $temp_array = array ("timestamp" => $temptime,
			   "number" => intval($ct),
			   "location" => $loc, 
			   "activities" => $activity_info
			   );
      
      array_push($counts_array, $temp_array);
    }
    $sessions_array = array ("initiativeID" => $initiative,
				 "startTime" => $start,
				 "endTime" => $end,
				 "counts" => $counts_array
				 );
    $sessions_meta = array ($sessions_array);//wrap in what will be an object

    $return = array("device" => $device,
		    "version" => $version,
		    "sessions" => $sessions_meta
		    );

    
    print "<form><textarea id=\"json-output\" cols=\"80\" rows=\"25\">";
    print (json_encode($return, JSON_PRETTY_PRINT));
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

