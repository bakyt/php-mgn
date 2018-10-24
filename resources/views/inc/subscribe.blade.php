<div class="modal fade" id="modal-subscribe">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">{{ trans('rent.subscribe') }}</h4>
            </div>
            <div class="modal-body">
                <p>{{ trans('rent.subscribe_description') }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">{{ trans('rent.close') }}</button>
                <button type="button" data-toggle="modal" data-target="#modal-subscribe" onclick="subscribe()" class="btn btn-primary">{{ trans('rent.subscribe') }}</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<button id="subscribe" type="button" class="btn btn-default hidden" data-toggle="modal" data-target="#modal-subscribe">
</button>