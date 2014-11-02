$(document).ready(function() {
    $("select").bind("mouseup change", function() {
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
		  
		  // validate required fields on submission
		  $("form").submit(function(e) { 
		      var errors = false;
		      var msgSpecs = "";
		      $(".required-field").each(function() {
			  if ($(this).val() == "" || $(this).val() == null) {
			    errors = true;
			    msgSpecs += "\r\n * Missing field: " + $(this).attr('name');
			    $(this).addClass('highlight-field');
			  } //end if no value in required field
			});
		      
		      // #counts-block must have at least one value
		      var atLeastOneCount = false;
		      var allCountsNumeric = true;
		      $(".counts").each(function() {
			  var countVal = $(this).val();
			  if (countVal != "" && countVal != null) {
			      atLeastOneCount = true;
			  } 
			  if (countVal.search("[^0-9]") >= 0) {
			      allCountsNumeric = false;
			      $(this).addClass('highlight-field');
			  } //end if found a non-numeric count
			});
		      if (atLeastOneCount == false) {
			$("#counts-block").addClass('highlight-field');
			errors = true;
			msgSpecs += "\r\n * You must enter at least one count";
		      }
		      if (allCountsNumeric == false) {
			  errors = true;
			  msgSpecs += "\r\n * Location counts may only contain numbers (no spaces or other characters)";
		      }

		      // if errors, display message and prevent submission
		      if (errors) {
			e.preventDefault();
			var msg = "Some required fields are empty/invalid/unselected!" + msgSpecs;
			alert (msg);
		      }
		      
		    });
		}); //end js-actions on successful AJAX load
	} //end else if there's an initiative ID
      }); //end on selection of initiative from pulldown
  }); //end document ready

