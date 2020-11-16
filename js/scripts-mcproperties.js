var page = $('#search').data('page'), token = $('#search').find('input[name="stoken"]').val();

function edit(id){
  hideAlert();
  var machine_class = $('#machine-'+id).text(), tad = $('#tad-'+id).text(), architecture = $('#architecture-'+id).text();
  $('#overlay-edit-open').click();
  $('#edit-id').val(id);
  $('#edit-machine-class').val(machine_class);
  $('#edit-tad').val(tad);
  $('#edit-architecture').val(architecture);
  
}

function appendLineTable(data){
  let line;
  line = '<tr id="row-id-'+data.ID+'">'+
            '<td>'+data.ID+'</td>'+
            '<td id="machine-'+data.ID+'">'+data.MACHINECLASS+'</td>'+
            '<td id="tad-'+data.ID+'">'+data.TAD+'</td>'+
            '<td id="architecture-'+data.ID+'">'+data.ARCHITECTURE+'</td>'+
            '<td>'+
              '<button class="ds-icon-button-neutral ds-mar-l-1 ds-mar-r-1 ds-icon-edit" id="overlay-edit-open" onclick="edit(\''+data.ID+'\')" alt="Edit record" title="Edit record" aria-label="Edit record"></button>'+
              '<button class="ds-icon-button-neutral ds-mar-l-1 ds-mar-r-1 ds-icon-trash-can" id="overlay-delete-open" onclick="deleteRecord(\''+data.ID+'\')" alt="Delete record" title="Delete record" aria-label="Delete record"></button>'+
            '</td>'+
          '</tr>';
  return line;
}