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
            '<td id="name-'+data.ID+'">'+data.NAME+'</td>'+
            '<td id="shortcode-'+data.ID+'">'+data.SHORTCODE+'</td>'+
            '<td id="cdirid-'+data.ID+'">'+data.CDIRID+'</td>'+
            '<td>'+
              '<button class="ds-icon-button-neutral ds-mar-l-1 ds-mar-r-1 ds-icon-edit" id="overlay-edit-open" onclick="edit(\''+data.ID+'\')" alt="Edit record" title="Edit record" aria-label="Edit record"></button>'+
              '<button class="ds-icon-button-neutral ds-mar-l-1 ds-mar-r-1 ds-icon-trash-can" id="overlay-delete-open" onclick="deleteRecord(\''+data.ID+'\')" alt="Delete record" title="Delete record" aria-label="Delete record"></button>'+
            '</td>'+
          '</tr>';
  return line;
}