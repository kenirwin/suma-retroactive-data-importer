<?
include("connect.php"); //handles mysql_connect session
$initiative = "1";
$device = "manual";
$version = "1.1.0";
?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/themes/smoothness/jquery-ui.css" />
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/jquery-ui.min.js"></script>
 <script>
   $(function() {
       $( "#datepicker" ).datepicker();
     });
</script>

<?
$locs = array();
$location_inputs = "";
$q = "SELECT * from `location` where enabled = '1' and `fk_parent` = '$initiative'";
$r = mysql_query($q);
while ($myrow = mysql_fetch_assoc($r)) {
  extract($myrow);
  $locs[$id] = $title;
  $location_inputs .= "<label for=\"counts[$id]\">$title</label>\n";
  $location_inputs .= "<input name=\"counts[$id]\" type=\"text\"><br />\n";
}

?>

<h1>Retroactive Suma Import Generator</h1>

<p><a href="https://github.com/cazzerson/Suma/issues/17">Format to Emulate</a></p>

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


<form action="index.php" method="get">
<h4>Time and Date of Data Collection</h4>
   <label for="date">Data collection date</label>
   <input name="date" type="text" id="datepicker" /><br />
  <label for="time">Data collection time (e.g. "9:30 pm")</label>
   <input name="time" type="text" /><br />
<h4>Counts by location</h4>
   <?=$location_inputs;?>
   <input type="submit" />
</form>
