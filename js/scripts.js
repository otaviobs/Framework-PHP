var page = $('#search').data('page'), token = $('#search').find('input[name="stoken"]').val();

//Variable used to store information data on screen
var table_data;

if(typeof token == 'undefined'){
	token = $('#order').find('input[name="stoken"]').val();
}

if (typeof page === "undefined") {
    page = $('#order').data('page');
}

function showAlert(type, message, action){
  var alertType, alertNew = $('#alert-new'), alertEdit = $('#alert-edit'), alertDelete = $('#alert-delete'), btnClose = '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
  alertNew.removeClass('alert-success').removeClass('alert-warning').removeClass('alert-danger');
  alertEdit.removeClass('alert-success').removeClass('alert-warning').removeClass('alert-danger');
  alertDelete.removeClass('alert-success').removeClass('alert-warning').removeClass('alert-danger');

  if (type == 'success'){
    alertType = 'alert-success';
  }
  else if(type == 'warning'){
    alertType = 'alert-warning';
  }else{
    alertType = 'alert-danger';
  }
  switch(action){
    case 'new':
      alertNew.addClass(alertType).removeClass('d-none');
      alertNew.html('<p>'+message+'</p>'+btnClose);
      // $('html,body').animate({scrollTop:$('#alert-new').offset().top}, 800);
      break;
    case 'edit':
      alertEdit.addClass(alertType).removeClass('d-none');
      alertEdit.html('<p>'+message+'</p>'+btnClose);
      // $('html,body').animate({scrollTop:$('#alert-edit').offset().top}, 800);
      break;
    case 'delete':
      alertDelete.addClass(alertType).removeClass('d-none');
      alertDelete.html('<p>'+message+'</p>'+btnClose);
      // $('html,body').animate({scrollTop:$('#alert-delete').offset().top}, 800);
      break;

  }
}

function hideAlert(){
  var alertType, alertNew = $('#alert-new'), alertEdit = $('#alert-edit'), alertDelete = $('#alert-delete'), alertLoader = $('#alert-loader');
  if(!alertNew.hasClass('d-none')){
    alertNew.addClass('d-none');
  }
  if(!alertEdit.hasClass('d-none')){
    alertEdit.addClass('d-none');
  }
  if(!alertDelete.hasClass('d-none')){
    alertDelete.addClass('d-none');
  }
  if(!alertLoader.hasClass('d-none')){
    alertLoader.addClass('d-none');
  }
}

function cleanForm(form){
	$(form)[0].reset();
}

$('#btnNew').click(function(_event){
  var form_data = $('#newForm');
  
  if(!form_data[0].checkValidity()){
	$('<input type="submit">').hide().appendTo($( "#newForm" )).click().remove();
	return false;
  }
  
  //Validate the entire form
  if(!validateForm(form_data))
	return false;

  $.ajax({
    url: "controller.json.php?page="+page+"&action=new",
    type: "POST",
    data: form_data.serializeArray(),
	beforeSend: function(){
		disableAllButton();
	},
	complete: function(){

		enableAllButton();
		$("input").removeClass("alert-danger");

		//exception actions for account credits
		if(page == "account-credits"){

			//Deactiving the new credit button
			$('#btnNew').attr("disabled", "disabled").addClass("disabled");
			$("#order-column").children("option:selected").data("balance", 1);
			$("#balance").text(1);
			$('#overlay-new-open').attr("disabled", "disabled").addClass("disabled");
			
		}

	},
    success: function(data) {
      if(!data.status){
        location.reload();
      }
      if(data.status==200){
        showAlert('success', data.message, 'new');
        cleanForm('#newForm');		
        setTimeout(function(){
          loadDatas();
        }, 2000);
      }
      else if(data.status==500){
        showAlert('warning', data.message, 'new');
      }else{
        showAlert('danger', data.message, 'new');
      }
    },
    error: function(_xhr, _status, errorThrown) {
      showAlert('danger', errorThrown, 'new');
    }
  }); 
  
});


$('#btnEdit').click(function(_event){
  var form_data = $('#editForm');
  
  //Validate the entire form
  if(!validateForm(form_data))
	return false;

  $.ajax({
    url: "controller.json.php?page="+page+"&action=edit",
    type: "POST",
    data: form_data.serialize(),
	beforeSend: function(){
		disableAllButton();
	},
	complete: function(){
		enableAllButton();
		$("input").removeClass("ds-error");
	},
    success: function(data) {
      if(!data.status){
        location.reload();
      }
      if(data.status==200){
        showAlert('success', data.message, 'edit');
        setTimeout(function(){
          loadDatas();  
        }, 2000);
        $('button').removeAttr("disabled").removeClass("ds-disabled");
      }
      else if(data.status==500){
        showAlert('warning', data.message, 'edit');
      }
      else{
        showAlert('error', data.message, 'edit');
      }
    },
    error: function(_xhr, _status, errorThrown) {
      showAlert('error', errorThrown, 'edit');
    }
  });
  
});


$('#btnDelete').click(function(_event){

    $.ajax({
      url: "controller.json.php?page="+page+"&action=delete",
      type: "POST",
      data: {id: $('#btnDelete').data('action'), stoken: token},
	  complete: function(){
		  $('#btnDelete').attr("disabled", "disabled").addClass("ds-disabled");
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


$('#btnNext').click(function(_event){
  disablePagButton();
  pagination($('#btnPagination .ds-current').next().text());
  enablePagButton();
});

$('#btnPrevious').click(function(_event){
  var prev = $('#btnPagination .ds-current');
  disablePagButton();
  if(prev.text() > 0){
    pagination(prev.prev().text());
  }
  enablePagButton();
});

$('#search').submit(function(){
  var table = $("#table-data tbody"), value = $("input[name=search]").val(), 
        limit = $("#select-menu").val(), nPage = 1;
  
  showLoader($(".table-responsive"));

  setTimeout(function(){
    $('#pgSearch').val(value);
    $.ajax({
      url: "controller.json.php?page="+page+"&action=load",
      type: "POST",
      data: {search: value, limit: limit, nPage: nPage, stoken: token},
      async: false,
      success: function(data) {
        if(!data.status){
          location.reload();
        }
        if(data.status == 200){
          if(data.records.length === 0){
            showNoneData($(".table-responsive"));
          }
          else{
            table.empty();
		    
			//Setting the actual table information at a global javascript variable
		    table_data = data.records;
            
			managerPagination(nPage, data.endPage);
            $.each(data.records, function(key, json){
              table.append(appendLineTable(json));
            });
            hideLoader($(".table-responsive"));
          }
        }
      },
      error: function(_xhr, _status, errorThrown) {
        showAlert('warning', errorThrown, 'delete');
      }
    });
  }, 100);
  return false;
});

$("#select-menu").change(function() {
  pagination(1);
});

$("#order-column").change(function() {
	$( "#order" ).submit();
});

$('#order').submit(function(){
	
  var table = $("#table-data tbody"), group = $("#btnPagination"), order = $("#order-column").val(), 
        limit = $("#select-menu").val(), nPage = 1;

  group.addClass('d-none');

  showLoader($(".table-responsive"));

  setTimeout(function(){
    $.ajax({
      url: "controller.json.php?page="+page+"&action=ordenation",
      type: "POST",
      data: {order_column: order, limit: limit, nPage: nPage, stoken: token},
      async: false,
      success: function(data) {
		  
		//Error
        if(!data.status){
          location.reload();
        }
		//Success
        if(data.status == 200){

		  //Check if there is any data on return
		  if(data.records.length === 0){
            showNoneData($(".table-responsive"));
          }
          else{
			  table.empty();

			  //Setting the actual table information at a global javascript variable
			  table_data = data.records;
			  
			  managerPagination(nPage, data.endPage);
			  $.each(data.records, function(key, json){
				  table.append(appendLineTable(json));
			  });
			  
			  hideLoader($(".table-responsive"));
			  group.removeClass('d-none');

		  }
        }
      },
      error: function(_xhr, _status, errorThrown) {
        showAlert('warning', errorThrown, 'delete');
      }
    });
  }, 100);
  return false;
});

//Function to active when user will export data
$('#export').click(function(_event){	

/*    hideAlert();
  $('#overlay-loader-open').click(); */
  document.location.href = "csvexport.php?type="+$(this).data('report');
/*   setTimeout(function(){
	$('.ds-close-default').click();
  }, 2000);  */

	
});	

function validateForm(form_data){
	
  var controlForm = true, form_type;
	
  //Start cleaning the errors if existed
  $("input").removeClass("ds-error");
  
  $("textarea").removeClass("ds-error");
  
  //Check which type is to be used on alerts
  if(form_data.attr('id') == 'newForm')
	  form_type = 'new';
  else if(form_data.attr('id') == 'editForm')
	  form_type = 'edit';
  else if(form_data.attr('id') == 'deleteForm')
	  form_type = 'delete';
  
  //Validate the data
  $.each(form_data.serializeArray(), function(_qt, field){
    if(field.value == ''){

	  //Case the issue is at any input field
      $("input[name="+field.name+"]").addClass("ds-error").attr("placeholder","This field is required...").focus();
	  
	  //Case the issue is at any textarea field
	  $("textarea[name="+field.name+"]").addClass("ds-error").attr("placeholder","This field is required...").focus();
	  
      controlForm = false;
      return false;
    }else if(!validateSpecialCaracters(field.value)){
	  showAlert('error', 'This field doesn\'t match the requirements', form_type);
	  
	  //Case the issue is at any input field
	  $("input[name="+field.name+"]").addClass("ds-error");
	  
	  //Case the issue is at any textarea field
	  $("textarea[name="+field.name+"]").addClass("ds-error");
	  
	  controlForm = false;
      return false;
	}else if(!validateSpecialCondition(field.name, field.value)){
	  showAlert('error', 'This field doesn\'t match the requirements', form_type);
	  
	  //Case the issue is at any input field
	  $("input[name="+field.name+"]").addClass("ds-error");
	  
	  //Case the issue is at any textarea field
	  $("textarea[name="+field.name+"]").addClass("ds-error");
	  
	  controlForm = false;
      return false;
	}
  });
  if(!controlForm){
    form_data.find("button").removeAttr("disabled").removeClass("ds-disabled");
    return false;
  }
  
  return true;
	
}

function validateSpecialCaracters(val) {
    var n, strEvents = ['onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload'],
	patt = /^[a-zA-Z0-9- _/\.]*$/g, patt2 = /<(|\/|[^\/>][^>]+|\/[^>][^>]+)>/;
	
	if (patt.test(val) == false){
		return false;
	}else if(patt2.test(val)){
		return false;
	}else{
	  for(n = 0; n < strEvents.length; n++) {
		  patt3 = new RegExp(strEvents[n], 'i');
		  if (patt3.test(val)){
			    return false;
				break;
		  }
	  }
	}
	return true;
}

function validateSpecialCondition(field_name, field_value){

	var pattern;

	switch(field_name){
	
		/******************/
		/*****ACCOUNTS*****/
		/******************/
		
		//Condition for CDIR
		case 'newCdirid':
		case 'editCdirid':
		
			pattern = /^CDIR-[0-9]+$/g;
		
		break;
	
		//Condition for SHORTCODE
		case 'newShortcode':
		case 'editShortcode':
		
			pattern = /^[a-zA-Z0-9]+$/g;
		
		break;
	
		/******************/
		/*****OS CLASS*****/
		/******************/
		
		//Condition for VERSION
		case 'newOSVersion':
		case 'editOSVersion':
		
			pattern = /^[0-9\.]+$/g;	
		
		break;
		
		default:
		
			return true;
		
		break;
		
	}
	
	if (pattern.test(field_value) == false)
		return false;
	else
		return true;
	
}


function showLoader(el){
  let loader = el.find(".ds-loader-container"), table = $(el).find("table"), btnsPag = $('.ds-button-group-h'),
    noData = $("#no-records");

  loader.removeClass("d-none");
  noData.addClass("d-none");
  btnsPag.addClass("d-none");
  table.addClass("d-none");
}

function hideLoader(el){
  let loader = el.find('.ds-loader-container'), table = $(el).find("table"), btnsPag = $('.ds-button-group-h');
  loader.addClass("d-none");
  btnsPag.removeClass("d-none");
  table.removeClass("d-none");
}

function showNoneData(el){
  let loader = el.find('.ds-loader-container'), noData = $("#no-records");
  loader.addClass("d-none");
  noData.removeClass("d-none");
}

function disableAllButton(){
  $('button').attr("disabled", "disabled").addClass("disabled");
}

function enableAllButton(){
  $('button').removeAttr("disabled").removeClass("disabled");
}

function disablePagButton(){
  $('#btn-pag-group button').attr("disabled", "disabled").addClass("disabled");
}

function enablePagButton(){
  $('#btn-pag-group button').removeAttr("disabled").removeClass("disabled");
}

//Function used after add any record
function loadDatas(){
  pagination(
    $('#btnPagination .active').text(), 
    $('#btnPagination .active')
  );
}

function deleteRecord(id){
  $('#delete-message').text("Are you sure to delete this record?"); 
  $('#btnDelete').removeAttr("disabled").removeClass("disabled");
  hideAlert();
  $('#overlay-delete-open').click();
  $('#btnDelete').data('action',id);
}

function pagination(nPage) {
  var table = $("#table-data tbody"), limit = $("#select-menu").val(), 
      currentP = $('#btnPagination .active'), group = $("#btnPagination"), search = $('#pgSearch').val();
  
  if(typeof search == 'undefined'){
	  search = $("#order-column").children("option:selected").val();
  }
  
  group.addClass('d-none');

  showLoader($(".table-responsive"));
  
  setTimeout(function(){
    $.ajax({
      url: "controller.json.php?page="+page+"&action=pagination",
      type: "POST",
      data: {nPage: nPage, limit: limit, search: search, stoken: token},
      async: false,
      success: function(data) {
        if(!data.status){
          location.reload();
        }
        if(data.status == 200){
          table.empty();
		  
		  //Setting the actual table information at a global javascript variable
		  table_data = data.records;
          
		  managerPagination(nPage, data.endPage);
          $.each(data.records, function(key, json){
              table.append(appendLineTable(json));
          });
        }
        hideLoader($(".table-responsive"));
        group.removeClass('d-none');
      },
      error: function(_xhr, _status, errorThrown) {
        showAlert('warning', errorThrown, 'delete');
      }
    });
  }, 100);

}

function managerPagination(_currentP, endPage){
  var group = $("#btnPagination"), pgNext = 0, pgBack = 0, activeClass, btnPrev = $("#btnPrevious"), btnNext = $("#btnNext");
  
  _currentP = parseInt(_currentP);
  pgNext = _currentP + 2;
  pgBack = _currentP - 1;
  
  if(pgBack <= 0){
    btnPrev.addClass("disabled");
    btnPrev.children().attr("disabled", "disabled")
  }
  else{
    btnPrev.removeClass("disabled");
    btnNext.children().removeAttr("disabled", "disabled")
  }

  if(_currentP == endPage){
    btnNext.addClass("disabled");
    btnNext.children().attr("disabled", "disabled")
  }
  else{
    btnNext.removeClass("disabled");
    btnNext.children().removeAttr("disabled", "disabled")
  }

  group.empty();
  if(_currentP > 5){
    group.append(
      '<li class="page-item">'+
        '<button class="btn btn-default" onClick="pagination(1)" role="link">1</button>'+
      '</li>'+
      '<li class="page-item">'+
        '<div class="text-center" aria-hidden="true">...</div>'+
      '</li>'
    );
  }
  for (let index = pgBack; index < pgNext; index++) {
    if(index > 0){
      activeClass = _currentP==index?' active':'';
      evOnClick = _currentP==index?'':'onClick="pagination('+index+')"';
      group.append(
        '<li class="page-item'+activeClass+'">'+
          '<button class="btn btn-default" '+evOnClick+' role="link">'+index+'</button>'+
        '</li>'
      );
      if(index==endPage)
        break;
    }
  }
  if((_currentP + 2)  < endPage){
    group.append(
      '<li class="page-item">'+
        '<div class="text-center" aria-hidden="true">...</div>'+
      '</li>'
    );
  }
  if(_currentP < endPage && _currentP < (endPage-1)){
    group.append(
      '<li class="page-item'+activeClass+'">'+
      '<button class="btn btn-default" onClick="pagination('+endPage+')" role="link">'+endPage+'</button>'+
    '</li>'
    );
  }
}