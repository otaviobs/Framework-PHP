function edit(id){
  hideAlert();
  var name = $('#name-'+id).text(), shortcode = $('#shortcode-'+id).text(), 
    cdirid = $('#cdirid-'+id).text();

  $('#overlay-edit-open').click();
  $('#edit-id').val(id);
  $('#edit-name').val(name);
  $('#edit-shortcode').val(shortcode);
  $('#edit-cdirid').val(cdirid);
}

function appendLineTable(data){
  let line;
  line = '<tr id="row-id-'+data.ID+'">'+
            '<td>'+data.ID+'</td>'+
            '<td>'+data.MIN_CLEAN_SERVER+' ('+data.MIN_CLEAN_SERVER_RATIO+'% - '+data.TARGET_CLEAN_SERVER_RATIO+'%)</td>'+
            '<td>'+data.MIN_TICKET_LINKAGE+' ('+data.MIN_TICKET_LINKAGE_RATIO+'% - '+data.TARGET_TICKET_LINKAGE_RATIO+'%)</td>'+
            '<td>'+data.MIN_LABELED_PROBLEMATIC_SERVER+' ('+data.MIN_LABELED_PROBLEMATIC_SERVER_RATIO+'% - '+data.TARGET_LABELED_PROBLEMATIC_SERVER_RATIO+'%)</td>'+
			'<td>'+data.START_DATE+' </td>'+
            '<td>'+data.END_DATE+' </td>'+
          '</tr>';
		  
  return line;
}