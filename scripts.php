<?php

  /*
list of functions:

function Debug ($level = E_ALL)
function DisplayJSONOutput ($sessions_all)
function GenerateJSON($sessions_all, $pretty=true)
function GenerateOneSession($initiative,$start,$end,$counts,$activity_info=array())
function GetFormFields ($init)
function GetLocationInputs($init)
function GetActivityInputs($init)
function HandleSubmission ()
function SelectInitiative()
function SubmitJSON ($sessions_all)
function ValidateSubmission()
  */


  // if config $debug is true, turn it on
if ($debug) { Debug ($debug_level); } 

function Debug ($level = E_ALL) {
  error_reporting($level);
  ini_set("display_errors", true);
}

function DisplayJSONOutput ($sessions_all) {
  global $sumaserver_url;
  //  print "<form action=\"$sumaserver_url/sync\" method=\"POST\">";
  $alert = "<div class=\"alert\"><h3>JSON-Only Output</h3><p>This copy of Suma Import Generator is not configured to submit directly into Suma. Instead, it will only display the correct JSON format, which you can submit through Suma's admininstrative interface. You can set Suma Import Generator to submit directly into your Suma instance by changing the <strong>&dollar;allow_direct_submit</strong> variable to <strong>true</strong> in <strong>config.php</strong></p></div>";
  print $alert;
  print "<textarea name=\"json\" id=\"json-output\" cols=\"80\" rows=\"25\">";
  print (GenerateJSON($sessions_all));
  print "</textarea><br />\n";
//  print "<input type=\"submit\" value=\"Submit data to Suma\"></form>";
  print "<hr />\n";
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


function GetFormFields ($init) {
  global $sumaserver_url;
  $url = $sumaserver_url . "/query/initiatives";
  $response = json_decode(file_get_contents($url));

  foreach ($response as $initiative) { //look at all initiatives
    if ($initiative->id == $init) { //only get locations and activities from the right id
      $fields = array();
      $fields['locations'] = GetLocationInputs($initiative);
      $fields['activities'] = GetActivityInputs($initiative);
      return $fields;
    } //end if correct initiative
  } //end foreach initiative
} //end function GetFormFields

function GetLocationInputs($init) {
  $location_inputs = '<div id="counts-block">' . PHP_EOL;
  $field_count = 0;
  // Get location info
  foreach ($init->dictionary->locations as $loc) { 
    $location_inputs .= '<label for="counts[' . $loc->id .']">'. $loc->title .'</label>' . PHP_EOL;
    $location_inputs .= '<input name="counts[' . $loc->id .']" type="text" class="counts"><br />' . PHP_EOL;
    $field_count++;
  } //end foreach location
  if ($field_count > 1) {
    $location_inputs .= '<div id="display-counts">Total Counts: <span id="sum-counts"></span></div>'. PHP_EOL;
  } //end if more than one location field
  $location_inputs .= '</div><!-- id=counts-block -->' . PHP_EOL;
  return $location_inputs;
}

function GetActivityInputs($init) {
  $activity_inputs = "";
  $activityGroupNames = array(); 

  // Get activity info by group
  foreach ($init->dictionary->activityGroups as $ag) {
    if ($ag->id > 0) { // negative numbers for non-activity
      $domID = strtolower(preg_replace("/[^a-zA-Z0-9]+/","_",$ag->title));
      if (isset($ag->allowMulti)) {
	if ($ag->allowMulti == 1) { //allow selection of more than one answer
	  $multi = "multiple";
	  $multiNote = " - <em>(Ctrl-click to select multiple answers)</em>";
	} 
	else {
	  $multi = $multiNote = "";
	} //end if allow multiples
      } 
      else {
	$multi = $multiNote = "";
      }
      if (isset($ag->required)) {
	if ($ag->required == 1) {
	  $require_text = 'class="required"';
	  $require_field = 'class="required-field"';
	}
	else { $require_text = ''; }
      }
      else { $require_text = ''; }

      // Get activity options for this group
      $opts = "";
      
      foreach ($init->dictionary->activities as $act) {
	if ($act->activityGroup == $ag->id) {//end if associated with this group
	  $opts .= ' <option value="' . $act->id . '">' . $act->title . '</option>' . PHP_EOL;
	    }
      } //end foreach activity
      $activity_inputs .= '<h4 '.$require_text .'>'.$ag->title. ' ' . $multiNote . '</h4><select name="'.$domID.'[]" id="'.$domID.'" '. $multi .' '. $require_field .'>' . $opts . '</select>' . PHP_EOL;
      array_push($activityGroupNames, $domID);
    } //end if activity group is a positive number
  } //end foreach activity group
  if (sizeof($activityGroupNames)>0) { $activity_inputs .= '<input type="hidden" name="activity_group_names" value="'.implode(';', $activityGroupNames).'" />'; }

  return ($activity_inputs);
} //end function GetActivityInputs


function HandleSubmission () {
  global $allow_direct_submit;
  $sessions_all = array();
  $counts = $_REQUEST['counts'];
  $date = $_REQUEST['date'];
  $time = $_REQUEST['time'];
  $start = strtotime("$date $time");
  $end = $start + array_sum($counts) + 60; //one second per count, plus one minute
  $initiative = intval($_REQUEST['initiative']);

  if (isset($_REQUEST['activity_group_names'])) {
    $activity_info = array();
    $agNames = preg_split("/;/", $_REQUEST['activity_group_names']);
    foreach ($agNames as $group) {
      foreach($_REQUEST[$group] as $k=>$v) {
	array_push($activity_info, intval($v));
      } //end foreach activity value in group
    } //end foreach activity group
  }//end if isset activityGroupNames
  else { $activity_info = array ();} 
  
  $session_array = GenerateOneSession($initiative, $start, $end, $counts, $activity_info);
  
  array_push ($sessions_all, $session_array);
  
  print '<div id="submission-response">';
  if ($allow_direct_submit) { 
    SubmitJSON ($sessions_all);
  }
  else {
    DisplayJSONOutput ($sessions_all);
  }
  print '</div><!-- id=submission-response -->';
} //end function HandleSubmission

function PostJSON ($url, $json) {
  if (function_exists('curl_version')) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
					       'Content-Type: application/json', 
					       'Content-Length: ' . strlen($json),
					       'User-Agent: Suma-Import-Generator',
					       )
		); 
    $result = curl_exec($ch);
    return $result;
  } //end if curl_version exists
  else {
    return "CURL is not available in this PHP installation";
  }
} //end function PostJSON

function RenderMarkdown ($text) {
  if (function_exists('curl_version')) {
    $api="https://api.github.com/markdown";
    $array = array ( "mode" => "markdown",
		     "text" => $text
		     );
    $json = json_encode($array);
    $html = PostJSON($api, $json);
    return $html;
  }
  else { return "<pre>$text</pre>"; }
}

function SelectInitiative() {
  global $sumaserver_url; 
  $url = $sumaserver_url . "/clientinit";
  $response = json_decode(file_get_contents($url));
  
  $opts = " <option value=\"\">Select an initiative</option>\n";
  foreach ($response as $init) {
    $opts.=' <option value="'. $init->initiativeId .'">'. $init->initiativeTitle .'</option>\n';
  }
  $select = "<label for=\"initiative\">Initiative</label> <select name=\"initiative\" id=\"initiative-selector\">\n$opts</select>\n";
  
  return ($select);
} //end function SelectInitiative

function SubmitJSON ($sessions_all) {
  global $sumaserver_url;
  $url = "$sumaserver_url/sync";
  $json = GenerateJSON($sessions_all,false);
  $data = array ("json" => $json);
  $options = array(
		   'http' => array(
				   'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
				   'method'  => 'POST',
				   'content' => http_build_query($data),
				   ),
		   );
  $context  = stream_context_create($options);
  $result = file_get_contents($url, false, $context);
  
  if (preg_match("/Transaction Complete/", $result)) { 
    print "<h4>Submission Successful</h4>\n";
  }
  else { 
    print "<h4>Submission Failed</h4>\n";
    print "<p>Details: $result</p>\n";
  }
} //end function SubmitJSON

function ValidateSubmission(){
  $submit_errors = false;
  $display_errors = "";
  if (! isset($_REQUEST['initiative'])) { 
    $submit_errors = true;
    $display_errors .= "<li>missing <strong>initiative</strong></li>\n";
  }
  elseif (! is_numeric($_REQUEST['initiative'])) { 
    $submit_errors = true;
    $display_errors .= "<li><strong>initiative</strong> is not a number</li>\n";
  }
  if (! isset($_REQUEST['date'])) { 
    $submit_errors = true;
    $display_errors .= "<li>missing <strong>date</strong></li>\n";
  }
  elseif (($timestamp = strtotime($_REQUEST['date'])) === false || (($timestamp = strtotime($_REQUEST['date'])) === -1)) {
    $submit_errors = true;
    $display_errors .= "<li><strong>date</strong> doesn't look like a valid date</li>\n";
  }
  if (! isset($_REQUEST['time'])) {
    $submit_errors = true;
    $display_errors .= "<li>missing <strong>time</strong></li>\n";
  }
  // validate time by concatenating "$time + yesterday" -- if that converts to a valid timestamp, it's probably ok
  elseif (($timestamp = strtotime($_REQUEST['time'] . " yesterday")) === false || (($timestamp = strtotime($_REQUEST['time'] . " yesterday")) === -1)) {
    $submit_errors = true; 
    $display_errors .= "<li><strong>time</strong> doesn't look like a valid time</li>\n";
  }
  if (! is_array($_REQUEST['counts'])) {
    $submit_errors = true;
    $display_errors .= "<li>missing <strong>locations counts</strong></li>\n";
  }

  if ($submit_errors) {
    print '<div class="alert" id="submission-response"><h3>Cannot Submit Data - Missing or Invalid Data</h3><ul id="submission-errors">' . $display_errors . '</ul>' . PHP_EOL;
    return false;
  }
  else {
    return true;
  }
} //end function ValidateSubmission

?>