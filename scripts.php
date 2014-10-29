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
  $location_inputs = "";
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
      $activity_inputs .= '<h4 '.$require_text .'>'.$ag->title.'</h4><select name="activities[]" '. $multi .' '. $require_field .'>' . $opts . '</select>' . PHP_EOL;
    } //end if activity group is a positive number
  } //end foreach activity group
  return ($activity_inputs);
} //end function GetActivityInputs


function HandleSubmission () {
  global $allow_direct_submit;
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
  $url = $sumaserver_url . "/query/initiatives";
  $response = json_decode(file_get_contents($url));
  
  $opts = " <option value=\"\">Select an initiative</option>\n";
  foreach ($response as $init) {
    $opts.=' <option value="'. $init->id .'">'. $init->title .'</option>\n';
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



?>