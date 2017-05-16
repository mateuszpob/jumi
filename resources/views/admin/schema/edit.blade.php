@extends('admin.index')

@section('content')
<div class="row">
  <div class="col-md-6">
<h1>Edit Schema Categories</h1>
@include('admin.errors')

{!! Form::open(array('action' => 'SchemaCategoriesController@store', 'class' => 'form')) !!}
{!! Form::hidden('id', $obj->id) !!}


@foreach($obj->getFillableEdit() as $field => $type)

<div class="form-group">
    {!! Form::label($field) !!}
    @if($type[0] == 'checkbox')
        {!! Form::checkbox($field, 1,  $obj->$field=="1" ? true : false); !!}
    @else
        {!! Form::$type[0]($field, $obj->$field, 
            array('', 
            $type[1]=>$type[1],
                  'class'=>'form-control disabled', 
                  'placeholder'=>$field, false)) !!}
    @endif
</div>


@endforeach

<div class="form-group">
    {!! Form::submit('Ok', 
      array('class'=>'btn btn-primary')) !!}
</div>

{!! Form::close() !!}

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