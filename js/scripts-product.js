//Trigger change the select menu in order to fill the variable with the variable from javascript about the account
$("#order-column").change();

function view(id){
  hideAlert();
  
  //Go though all elements in order to search which data refers to view one
  $.each(table_data, function(key, column){
		if(id == column.ID){	
			$('#view-id').val(column.ID);
			$('#view-name').val(column.NAME);
			$('#view-price').val(column.PRICE);
			$('#view-created').val(column.CREATEDDATE);
			$('#view-modified').val(column.MODIFIEDDATE);
			return false;
		}
	});
}

function edit(id){
	hideAlert();
	$.each(table_data, function(key, column){
		if(id == column.ID){
			$('#edit-id').val(column.ID);
			$('#edit-name').val(column.NAME);
			$('#edit-price').val(column.PRICE);		
		}
	});
}

function appendLineTable(data){
  let line;

  //Get only first name of created by
	line = '<tr id="row-id-'+data.ID+'">'+
			'<td>'+data.ID+'</td>'+
			'<td>'+data.NAME+'</td>'+
			'<td>'+data.PRICE.replace("/\$/g","")+'</td>'+
			'<td>'+data.CREATEDDATE+'</td>'+
			'<td>'+
				'<button class="btn btn-default" onClick="view(\''+data.ID+'\')" alt="View record" title="View record" aria-label="View record" data-toggle="modal" data-target="#overlay-view"><i class="fas fa-eye"></i></button>'+
				'<button class="btn btn-default" id="overlay-edit-open" onclick="edit(\''+data.ID+'\')" alt="Edit record" title="Edit record" aria-label="Edit record" data-toggle="modal" data-target="#overlay-edit"><i class="fas fa-edit"></i></button>'+
				'<button class="btn btn-default" onClick="deleteRecord(\''+data.ID+'\')" alt="Cancel record" title="Cancel record" aria-label="Cancel record" data-toggle="modal" data-target="#overlay-delete"><i class="fas fa-trash"></i></button>'+
			'</td>'+
			'</tr>';

  return line;
}
/*
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
*/