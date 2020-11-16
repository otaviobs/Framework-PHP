//Trigger change the select menu in order to fill the variable with the variable from javascript about the account
$("#order-column").change();


function viewCredits(id){
  hideAlert();
    
  $('#overlay-view-open').click();
  
  //Go though all elements in order to search which data refers to view one
  $.each(table_data, function(key, column){
	 
	if(id == column.ID){
		
	  $('#view-id').val(column.ID);
	  $('#view-account-name').val(column.ACCOUNT_NAME);
	  $('#view-created-by').val(column.CREATED_BY);
	  $('#view-created-date').val(column.CREATED_DATE);
	  $('#view-expiration-date').val(column.EXPIRATION_DATE);
	  $('#view-analysis-id').val(column.ANALYSIS_ID);
	  $('#view-status').val(column.STATUS);
	  $('#view-cancel-by').val(column.CANCEL_BY);
	  $('#view-cancel-date').val(column.CANCEL_DATE);
	  $('#view-comment').val(column.COMMENTS);
	  return false;
	  
	}
		 
  });

}

function deleteCredits(id){
  $('#btnCancelCredits').removeAttr("disabled").removeClass("ds-disabled");
  hideAlert();
  $('#overlay-delete-open').click();
  $('#btnCancelCredits').data('action',id);
}

$('#btnCancelCredits').click(function(_event){

    var form_data = $('#deleteForm');
  
    //Validate the entire form
	if(!validateForm(form_data))
		return false;

    $.ajax({
      url: "controller.json.php?page="+page+"&action=delete",
      type: "POST",
      data: {id: $('#btnCancelCredits').data('action'), deleteComment: $('#delete-comment').val(), stoken: token},
	  complete: function(){
		  $('#btnCancelCredits').attr("disabled", "disabled").addClass("ds-disabled");
	  },
      success: function(data) {
        if(!data.status){
          location.reload();
        }  
        if(data.status == '200'){
		  $('#delete-message').text(""); 
          showAlert('success', data.message, 'delete');
          
          setTimeout(function(){
            loadDatas();
			
			//Reactiving the new credit button
			$('#btnNew').prop("disabled", false).removeClass("ds-disabled");
			$("#order-column").children("option:selected").data("balance", 0);
		    $("#balance").text(0);
			$('#overlay-new-open').prop("disabled", false).removeClass("ds-disabled");
			
          }, 2000);
          
		  
        }
        else if(data.status == '500'){
			$('#delete-message').text("");
          showAlert('warning', data.message, 'delete');
        }else{
          showAlert('error', data.message, 'delete');
        }

      },
      error: function(_xhr, _status, errorThrown) {
        showAlert('warning', errorThrown, 'delete');
      }
    });
  
});

function appendLineTable(data){
  let line;

  //Get only first name of created by
  let first_name = data.CREATED_BY.split(" ");
  first_name = first_name[0];

  //If it's cancelled or expired, then it won't display the cancel button at action
  if(data.STATUS == 'CANCELLED' || data.STATUS == 'EXPIRED'){
	  
	  line = '<tr id="row-id-'+data.ID+'">'+
				'<td>'+data.ID+'</td>'+
				'<td>'+data.CREATED_DATE+'</td>'+
				'<td>'+first_name+'</td>'+
				'<td>'+data.EXPIRATION_DATE+'</td>'+
				'<td>'+data.ANALYSIS_ID+'</td>'+
				'<td>'+data.STATUS+'</td>'+
				'<td>'+
				  '<button class="ds-icon-button-neutral ds-mar-l-1 ds-mar-r-1 ds-icon-view" onClick="viewCredits(\''+data.ID+'\')" alt="View record" title="View record" aria-label="View record"></button>'+
				'</td>'+
			  '</tr>';
	  
  }else{
	  
	  line = '<tr id="row-id-'+data.ID+'">'+
				'<td>'+data.ID+'</td>'+
				'<td>'+data.CREATED_DATE+'</td>'+
				'<td>'+first_name+'</td>'+
				'<td>'+data.EXPIRATION_DATE+'</td>'+
				'<td>'+data.ANALYSIS_ID+'</td>'+
				'<td>'+data.STATUS+'</td>'+
				'<td>'+
				  '<button class="ds-icon-button-neutral ds-mar-l-1 ds-mar-r-1 ds-icon-close" onClick="deleteCredits(\''+data.ID+'\')" alt="Cancel record" title="Cancel record" aria-label="Cancel record"></button>'+
				  '<button class="ds-icon-button-neutral ds-mar-l-1 ds-mar-r-1 ds-icon-view" onClick="viewCredits(\''+data.ID+'\')" alt="View record" title="View record" aria-label="View record"></button>'+
				'</td>'+
			  '</tr>';
	  
  }
		  
  return line;
}

$("#order-column").change(function() {

	let balance = $("#order-column").children("option:selected").data("balance");
	$("#balance").text(balance);
	
	//Check if there is any balance, if so, the button to add credits will be disabled
	if(balance >= 1){
		
		$('#btnNew').attr("disabled", "disabled").addClass("ds-disabled");
		$('#overlay-new-open').attr("disabled", "disabled").addClass("ds-disabled");
		
	}else{
	
		$('#btnNew').prop("disabled", false).removeClass("ds-disabled");
		$('#overlay-new-open').prop("disabled", false).removeClass("ds-disabled");
		
	}
	
});

$("#overlay-new-open").click(function() {
	let selected_account_id = $("#order-column").children("option:selected").val();
	let selected_account_text = $("#order-column").children("option:selected").text();
	
	$("#new-account-name").val(selected_account_text);
	$("#new-account-name-hidden").val(selected_account_id);
	$('#new-expiration-date').val(getExpirationDate());
	
	return false;
});

$("#overlay-delete-open").click(function() {
    cleanForm('#deleteForm');
	let selected_account_id = $("#order-column").children("option:selected").val();
	let selected_account_text = $("#order-column").children("option:selected").text();
	
	$("#delete-account-name").val(selected_account_text);
	$("#delete-account-name-hidden").val(selected_account_id);
});


//Expiration date is one month after today
function getExpirationDate(){
	
	let current_time = new Date();
	let expiration_date = new Date(current_time.setMonth(current_time.getMonth()+1));
	let ed_formatted = expiration_date;
	let dd = String(ed_formatted.getDate()).padStart(2, '0');
	let mm = String(ed_formatted.getMonth() + 1).padStart(2, '0');
	let yyyy = ed_formatted.getFullYear();

	ed_formatted = yyyy + '-' + mm + '-' + dd;
	
	return ed_formatted;
}