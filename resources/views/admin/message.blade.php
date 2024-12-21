{{-- @if(Session::has('success'))
<div class="alert alert-success alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
    <h4><i class="icom fa fa-check"></i> Success!</h4> {{ Session::has('success') }}
</div>
@endif --}}
@if(session('error'))
<div class="alert alert-success alert-dismissible" role="alert">
    <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close"></button>
    <h4><i class="icom fa fa-check"></i> Error!</h4> {{ session('error') }}
</div>
@endif

@if(session('success'))
<div class="alert alert-success alert-dismissible" role="alert">
    <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close"></button>
    <h4><i class="icom fa fa-check"></i> Success!</h4> {{ session('success') }}
</div>
@endif
