<!-- Modal Dialog -->
<div class="modal fade confirmationDialog" id="confirmDeleteAction" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body">
        <div class="modal-message"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">{{trans('backend.subscription.cancel_delete') }}</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal" id="confirm">{{trans('backend.subscription.delete') }}</button>
      </div>
    </div>
  </div>
</div>
