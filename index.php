<?
/*
  error_reporting(E_ALL);
  ini_set("display_errors", true);
*/
include("connect.php"); //handles mysql_connect session
include("scripts.php");
$device = "manual";
$version = "1.1.0";


?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/themes/smoothness/jquery-ui.css" />
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/jquery-ui.min.js"></script>
 <script>
   $(function() {
     });


$(document).ready(function() {
    $("select").change(function() {
	var initID = $(this).val();
	$.get("load_fields.php", { initiative: initID })
	      .done(function(data) {
		  $("#details-form").html(data); // load form fields
		  $( "#datepicker" ).datepicker(); //triger datepicker
		});
      }); //end on click delete-link
  }); //end document ready

</script>




<h1>Retroactive Suma Import Generator</h1>

<p><a href="https://github.com/cazzerson/Suma/issues/17">Format to Emulate</a></p>

<?
print(SelectInitiative($_REQUEST['initative']));
?>

<div id="details-form"></div>

<?
  if ($_REQUEST['date'] && $_REQUEST['time'] && is_array($_REQUEST['counts'])) {
    $date = $_REQUEST['date'];
    $time = $_REQUEST['time'];
    $counts = $_REQUEST['counts'];
    $start = strtotime("$date $time");
    $end = $start + (60*5); //add five minutes
    $temptime = $start; 
    $temp_array = $counts_array = array();

    foreach ($counts as $loc => $ct) {
      $temptime++;
      $temp_array = array ("timestamp" => $temptime,
			   "number" => intval($ct),
			   "location" => $loc, 
			   "activities" => array()
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

    
    print "<form><textarea cols=\"80\" rows=\"25\">";
    print (json_encode($return, JSON_PRETTY_PRINT));
    print "</textarea></form>";
    print "<hr />\n";

  } //end if submission


?>


