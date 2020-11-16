function edit(id){
  hideAlert();
  var osprovider = $('#osprovider-'+id).text(), osname = $('#osname-'+id).text(), 
    osversion = $('#osversion-'+id).text(), oosdate = $('#oosdate-'+id).text();

  $('#overlay-edit-open').click();
  $('#edit-id').val(id);
  $('#edit-osprovider').val(osprovider);
  $('#edit-osname').val(osname);
  $('#edit-osversion').val(osversion);
  $('#edit-oosdate').val(oosdate);
}

function appendLineTable(data){
  let line;
  line = '<tr id="row-id-'+data.ID+'">'+
            '<td>'+data.ID+'</td>'+
            '<td id="osprovider-'+data.ID+'">'+data.OSPROVIDER+'</td>'+
            '<td id="osname-'+data.ID+'">'+data.OSNAME+'</td>'+
            '<td id="osversion-'+data.ID+'">'+data.OSVERSION+'</td>'+
            '<td id="oosdate-'+data.ID+'">'+data.OOSDATE+'</td>'+
            '<td>'+
              '<button class="ds-icon-button-neutral ds-mar-l-1 ds-mar-r-1 ds-icon-edit" id="overlay-edit-open" onclick="edit(\''+data.ID+'\')" alt="Edit record" title="Edit record" aria-label="Edit record"></button>'+
              '<button class="ds-icon-button-neutral ds-mar-l-1 ds-mar-r-1 ds-icon-trash-can" id="overlay-delete-open" onclick="deleteRecord(\''+data.ID+'\')" alt="Delete record" title="Delete record" aria-label="Delete record"></button>'+
            '</td>'+
          '</tr>';
  return line;
}