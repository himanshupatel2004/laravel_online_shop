@if(Session::has('error'))
<div class="alert alert-danger alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
    <h4><i class="icom fa fa-ban"></i> Error!</h4> {{ Session::has('error') }}
</div>
@endif

@if(Session::has('success'))
<div class="alert alert-success alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
    <h4><i class="icom fa fa-check"></i> Success!</h4> {{ Session::has('success') }}
</div>
@endif
