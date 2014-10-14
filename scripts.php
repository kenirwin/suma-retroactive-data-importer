<?php

  // if config $debug is true, turn it on
if ($debug) { Debug ($debug_level); } 

function Debug ($level = E_ALL) {
  error_reporting($level);
  ini_set("display_errors", true);
}


function GetActivities($initiative) {
  $activity_inputs = "";
  $q = "SELECT * FROM `activity_group` WHERE `fk_initiative` = '$initiative'";
  $r = mysql_query($q); 
  while ($ag_row = mysql_fetch_assoc($r)) {
    extract($ag_row);
    $ag_title = $title;
    if ($allowMulti == 1) { //allow selection of more than one answer
      $multi = "multiple";
    }
    else {
      $multi = "";
    }
    $activity_query = "SELECT * FROM `activity` WHERE `fk_activity_group` = $id and `enabled` = 1 ORDER BY `rank` ASC";
    $activity_r = mysql_query($activity_query);
    $opts = "";
    while ($myrow = mysql_fetch_assoc($activity_r)) {
      extract($myrow);
      $opts .= " <option value=\"$id\">$title</option>\n";
    }
    $activity_inputs .= "<h4>$ag_title</h4><select name=\"activities[]\" $multi>$opts\n</select>\n";
    
  } //end while looking up activity groups
  if (isset($activity_inputs)) {
    return($activity_inputs);
  }
} //end function GetActivities

function GetLocations($initiative) {
  $locs = array();
  $location_inputs = "";
  $q = "SELECT `location`.`id` as loc_id,`location`.`title` as loc_title from `location`,`initiative` where `location`.`enabled` = '1' and `location`.`fk_parent` = `initiative`.`fk_root_location` and `initiative`.`id` = '$initiative'";
  $r = mysql_query($q);
  $field_count = mysql_num_rows($r);
  while ($myrow = mysql_fetch_assoc($r)) {
    extract($myrow);
    $locs[$loc_id] = $loc_title;
    $location_inputs .= "<label for=\"counts[$loc_id]\">$loc_title</label>\n";
    $location_inputs .= "<input name=\"counts[$loc_id]\" type=\"text\" class=\"counts\"><br />\n";
  } //end while locations
  if ($field_count > 1) {
    $location_inputs .= '<div id="display-counts">Total Counts: <span id="sum-counts"></span></div>'; 
      }

  return($location_inputs);
} //end function GetLocations


function SelectInitiative() {
  $q="SELECT * FROM `initiative` where `enabled` = 1";
  $r=mysql_query($q);
  $opts = " <option value=\"\">Select an initiative</option>\n";
  while ($myrow=mysql_fetch_assoc($r)) {
    extract($myrow);
    $opts.=" <option value=\"$id\">$title</option>\n";
  } //end while 
  $select = "<label for=\"initiative\">Initiative</label> <select name=\"initiative\" id=\"initiative-selector\">\n$opts</select>\n";
  return ($select);
} //end function SelectInitiative

?>