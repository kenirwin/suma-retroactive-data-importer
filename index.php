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
	$("#submission-response").hide();
	var initID = $(this).val();
	if (initID == "") {
	  $("#details-form").html("");
	}
	else {
	  $.get("load_fields.php", { initiative: initID })
	      .done(function(data) {
		  $("#details-form").html(data); // load form fields
		  $("#datepicker").datepicker(); //trigger datepicker

		  // for counts with more than one location, display 
		  // sum of all location counts
		  $(".counts").bind("keyup", function () {
		      var total = 0;
		      $(".counts").each(function(e) {
			  tmpVal = $(this).val();
			  total += Number(tmpVal);
			}); //end each count
		      $("#sum-counts").html(total);
		    }); //end keyup
		  
		  $("form").submit(function(e) {
		      var errors = false;
		      $(".required-field").each(function() {
			  if ($(this).val() == "" || $(this).val() == null) {
			    errors = true;
			    $(this).addClass('highlight-field');
			    e.preventDefault();
			  } //end if no value in required field
			});
		      
		      if (errors) {
			alert ("Some required fields are empty/unselected!");
		      }
		      
		    });
		}); //end js-actions on successful AJAX load
	} //end else if there's an initiative ID
      }); //end on selection of initiative from pulldown
  }); //end document ready

</script>
<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
<div id="wrapper">
<div id="content">
<h1>Suma Import Generator</h1>
  <p><a href="documentation.php" class="button">Documentation</a></p>
<?php
  print(SelectInitiative());
?>

<div id="details-form"></div>

<?php
  if (isset($_REQUEST['date']) && isset($_REQUEST['time']) && is_array($_REQUEST['counts'])) {
    HandleSubmission();
  } //end if submission
print "</div><!--id=content-->\n";

print '<div id="footer">';
include("license.php");
print "</div><!--id=footer-->\n";
print "</div><!--id=wrapper-->\n";

?>
</body>

