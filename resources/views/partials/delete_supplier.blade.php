<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="Delete">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Delete: <?php echo $supplier->supplier_name; ?></h4>
      </div>
      <div class="modal-body">
        <p>Are you sure you wish to delete <strong><?php echo $supplier->supplier_name; ?></strong> from the database?</p>
        <p>This process cannot be undone.</p>
      </div>
      <div class="modal-footer">
        {!! Form::model($supplier, ['id' => 'deleteContact', 'route' => ['suppliers.destroy', $supplier->id], 'method' => 'delete']) !!}
            <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            <button type="button" class="btn btn-primary delete-object">Yes</button>
        {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>