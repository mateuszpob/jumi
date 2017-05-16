@extends('admin.index')

@section('content')
<div class="row">
  <div class="col-md-12">
    <h1>Edit Mail</h1>
    @include('admin.errors')

    {!! Form::open(array('action' => 'MailController@store', 'class' => 'form', 'files'=>true)) !!}
    {!! Form::hidden('id', $obj->id) !!}

  </div>
</div>
@foreach(\App\Mail::first()->getFillableEdit() as $field => $type)

<div class="form-group">
    {!! Form::label($field) !!}
    {!! Form::$type[0]($field, $obj->$field, 
        array(
        $type[1]=>$type[1],
              'class'=>'form-control disabled', 
              'placeholder'=>$field)) !!}
</div>

@endforeach

<div class="form-group">
    {!! Form::submit('Ok', 
      array('class'=>'btn btn-primary')) !!}
</div>

{!! Form::close() !!}

@if(isset($view_html))
<iframe style="width:600px;height:500px;" srcdoc="{!! $view_html !!}">
    
</iframe>
    
@endif

<script>
  var openFile = function(event) {
    var input = event.target;

    var reader = new FileReader();
    reader.onload = function(){
      var dataURL = reader.result;
      var output = document.getElementById('item-image');
      output.src = dataURL;
    };
    reader.readAsDataURL(input.files[0]);

    // $('#save-image').on('click', function(){
    //   $.ajax({
    //     url: ''
    //   })
    // });

  };
</script>


@stop