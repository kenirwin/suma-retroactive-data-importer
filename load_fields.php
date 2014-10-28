<?php
include_once("config.php");
include_once("scripts.php");
$initiative = $_REQUEST['initiative'];
$fields = GetFormFields($initiative);
$location_inputs = $fields['locations'];
$activity_inputs = $fields['activities'];;
?>


<form action="index.php" method="get">
<h4>Time and Date of Data Collection</h4>
  <input type="hidden" name="initiative" value="<?php echo($initiative) ;?>" />
   <label for="date">Data collection date</label>
   <input name="date" type="text" id="datepicker" /><br />
  <label for="time">Data collection time (e.g. "9:30 pm")</label>
   <input name="time" type="text" /><br />
<h4>Counts by location</h4>
    <?php echo($location_inputs); ?>
<?php echo($activity_inputs);?>
<br />   <input type="submit" />
</form>
