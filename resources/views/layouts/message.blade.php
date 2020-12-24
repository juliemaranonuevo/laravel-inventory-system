@if ($errors->any())
<div class="form-group">
    <div id="roll" class="alert alert-danger alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <p>
            @foreach ($errors->all() as $error)
            {{ $error }}<br>
            @endforeach
        </p>
    </div>
</div>
@elseif(session()->has('success'))
<div class="form-group">
    <div id="roll" class="alert alert-success alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <p>
        {{ session()->get('success') }}<br>
        </p>
    </div>
</div>
@elseif(session()->has('custom_error_message'))
<div class="form-group">
    <div id="roll" class="alert alert-danger alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <p>
        {!! Session::get('custom_error_message') !!}<br>
        </p>
    </div>
</div>
@endif