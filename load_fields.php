<?
require("connect.php");
$locs = array();
$location_inputs = "";
$initiative = $_REQUEST['initiative'];
$q = "SELECT * from `location` where enabled = '1' and `fk_parent` = '$initiative'";
$r = mysql_query($q);
while ($myrow = mysql_fetch_assoc($r)) {
  extract($myrow);
  $locs[$id] = $title;
  $location_inputs .= "<label for=\"counts[$id]\">$title</label>\n";
  $location_inputs .= "<input name=\"counts[$id]\" type=\"text\"><br />\n";
}
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