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
   <label for="date" class="required">Data collection date</label>
   <input name="date" type="text" id="datepicker" class="required-field" /><br />
  <label for="time" class="required">Data collection time (e.g. "9:30 pm")</label>
   <input name="time" type="text" class="required-field" /><br />
<h4 class="required">Counts by location</h4>
    <?php echo($location_inputs); ?>
<?php echo($activity_inputs);?>
<br />   <input type="submit" />
</form>
