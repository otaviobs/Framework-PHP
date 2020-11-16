<div class="col-12">
  <div class="table-responsive text-center">
    <div class="ds-loader-container d-none">
      <div class="spinner-grow text-dark" role="status">
        <span class="sr-only">Launching...</span>
      </div>
    </div>
    <div id="no-records" class="col-12 text-align-center d-none">
      <p>No records found.</p>
    </div>
    <table id="table-data" class="table table-striped table-sm">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Price</th>
          <th>Date</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>
  <nav id="btn-pag-group" class="col-12" aria-label="Pagination of records">
    <input type="hidden" id="pgSearch">
    <ul class="pagination">
      <li id="btnPrevious" class="page-item">
        <button class="btn btn-default disabled" disabled="disabled" aria-label="Previous">Prev</button>
      </li>
      <li id="btnPagination" class="page-item active">
        <button class="btn btn-default"></button>
      </li>
      <li id="btnNext" class="page-item">
        <button class="btn btn-default" aria-label="Next">Next</button>
      </li>
    </ul>
  </div>
</nav>

<?php
// Put this function in the manager.php 
  function crudModal(){
?>
<!-- Modals -->
  <!-- NEW -->
<div class="modal fade" id="overlay-new" tabindex="-1" aria-labelledby="overlay-newLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="overlay-newLabel">New Record</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="newForm">
          <div id="alert-new" class="col-12 alert alert-dismissible fade show d-none"></div>
          <div class="form-group">
            <label for="new-name">Name</label>
            <input type="text" name="newName" class="form-control" id="new-name" data-error="form-validation-name" maxlength="50" />
          </div>
          <div class="form-group">
            <label for="new-price">Price</label>
            <input type="number" name="newPrice" class="form-control" id="new-price" min="0.00" step="0.01" />
          </div>
          <input type="hidden" name="stoken" value="<?php echo $_SESSION['user']->getTokenCSFR();?>">
        </form>
      </div>
      <div class="modal-footer">
        <button id="btnNew" class="btn btn-primary">Save</button>
        <button class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

  <!-- VIEW -->
<div class="modal fade" id="overlay-view" tabindex="-1" aria-labelledby="overlay-viewLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">View Record</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="col-12 form-group">
          <label for="view-name">Name</label>
          <input type="text" name="viewName" class="form-control" id="view-name" disabled/>
        </div>
        <div class="form-group">
          <div class="col-4 float-left">
            <label for="view-price">Price</label>
            <input type="text" name="viewPrice" class="form-control" id="view-price" disabled/>
          </div>
          <div class="col-4 float-left">
            <label for="view-created">Created Date</label>
            <input type="text" name="viewCreated" class="form-control" id="view-created" disabled/>
          </div>
          <div class="col-4 float-left">
            <label for="view-modified">Modified Date</label>
            <input type="text" name="viewModified" class="form-control" id="view-modified" disabled/>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

  <!-- EDIT -->
<div class="modal fade" id="overlay-edit" tabindex="-1" aria-labelledby="overlay-editLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Record</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="editForm">
          <input type="hidden" name="stoken" value="<?php echo $_SESSION['user']->getTokenCSFR();?>">
          <div id="alert-edit" class="col-12 alert alert-dismissible fade show d-none"></div>
          <input type="hidden" name="editId" class="form-control" id="edit-id"/>
          <div class="col-12 form-group">
            <label for="edit-name">Name</label>
            <input type="text" name="editName" class="form-control" id="edit-name" maxlength="50" />
          </div>
          <div class="col-12 form-group">
            <label for="edit-price">Price</label>
            <input type="text" name="editPrice" class="form-control" id="edit-price"  min="0.00" step="0.01" />
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button id="btnEdit" class="btn btn-primary">Save</button>
        <button class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

  <!-- DELETE -->
<div class="modal fade" id="overlay-delete" tabindex="-1" aria-labelledby="overlay-deleteLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Delete Record</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div id="alert-delete" class="col-12 alert alert-dismissible fade show d-none"></div>
      <div class="modal-body">
        <p id="delete-message">Are you sure to delete this field?</p>
      </div>
      <div class="modal-footer">
        <button id="btnDelete" class="btn btn-danger">Delete</button>
        <button class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<?php
}
?>
