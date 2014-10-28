<?php

  // if config $debug is true, turn it on
if ($debug) { Debug ($debug_level); } 

function Debug ($level = E_ALL) {
  error_reporting($level);
  ini_set("display_errors", true);
}

function GenerateJSON($sessions_all, $pretty=true) {
  global $device, $version;
  $return = array("device" => $device,
		  "version" => $version,
		  "sessions" => $sessions_all
		  );
  
  if ($pretty == true) {
    return(json_encode($return, JSON_PRETTY_PRINT));
  }
  else {
    return(json_encode($return));
  }
} //end function GenerateJSON

    


function GenerateOneSession($initiative,$start,$end,$counts,$activity_info=array()) {
  $temptime = $start; 
  $temp_array = $counts_array = array();
  
  foreach ($counts as $loc => $ct) {
    $temptime++;
    $temp_array = array ("timestamp" => $temptime,
			 "number" => intval($ct),
			 "location" => $loc, 
			 "activities" => $activity_info
			 );
    
    array_push($counts_array, $temp_array);
  } //end foreach $counts

  $session_array = array ("initiativeID" => $initiative,
			  "startTime" => $start,
			  "endTime" => $end,
			  "counts" => $counts_array
				 );



  return $session_array;
} //end function GenerateOneSession


function GetFormFields ($id) {
  global $sumaserver_url;
  $url = $sumaserver_url . "/query/initiatives";
  $response = json_decode(file_get_contents($url));

  foreach ($response as $init) { //look at all initiatives
    if ($init->id == $id) { //only get locations and activities from the right id
      $fields = array();
      $fields['locations'] = GetLocationInputs($init);
      $fields['activities'] = GetActivityInputs($init);
      return $fields;
    } //end if correct initiative
  } //end foreach initiative
} //end function GetFormFields

function GetLocationInputs($init) {
  $location_inputs = "";
  // Get location info
  foreach ($init->dictionary->locations as $loc) { 
    $location_inputs .= '<label for="counts[' . $loc->id .']">'. $loc->title .'</label>' . PHP_EOL;
    $location_inputs .= '<input name="counts[' . $loc->id .']" type="text" class="counts"><br />' . PHP_EOL;
  } //end foreach location
  return $location_inputs;
}

function GetActivityInputs($init) {
  $activity_inputs = "";
  // Get activity info by group
  foreach ($init->dictionary->activityGroups as $ag) {
    if ($ag->id > 0) { // negative numbers for non-activity
      if (isset($ag->allowMulti)) {
	if ($ag->allowMulti == 1) { //allow selection of more than one answer
	  $multi = "multiple";
	} 
	else {
	  $multi = "";
	} //end if allow multiples
      } 
      else {
	$multi = "";
      }
      
      // Get activity options for this group
      $opts = "";
      
      foreach ($init->dictionary->activities as $act) {
	if ($act->activityGroup == $ag->id) {//end if associated with this group
	  $opts .= ' <option value="' . $act->id . '">' . $act->title . '</option>' . PHP_EOL;
	    }
      } //end foreach activity
      $activity_inputs .= '<h4>'.$ag->title.'</h4><select name="activities[]" '. $multi .'>' . $opts . '</select>' . PHP_EOL;
    } //end if activity group is a positive number
  } //end foreach activity group
  return ($activity_inputs);
} //end function GetActivityInputs




function SelectInitiative() {
  global $sumaserver_url; 
  $url = $sumaserver_url . "/query/initiatives";
  $response = json_decode(file_get_contents($url));
  
  $opts = " <option value=\"\">Select an initiative</option>\n";
  foreach ($response as $init) {
    $opts.=' <option value="'. $init->id .'">'. $init->title .'</option>\n';
  }
  $select = "<label for=\"initiative\">Initiative</label> <select name=\"initiative\" id=\"initiative-selector\">\n$opts</select>\n";
  
  return ($select);
} //end function SelectInitiative

?>