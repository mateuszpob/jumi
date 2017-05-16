@extends('admin.index')

@section('content')
<div class="row">
  <div class="col-md-6">
    <h1>Edit producer</h1>
    @include('admin.errors')

    {!! Form::open(array('action' => 'ItemController@storeManufacture', 'class' => 'form', 'files'=>true)) !!}
    {!! Form::hidden('id', $obj->id) !!}
    
  </div>
  <div class="col-md-6">
    <div class="col-md-2">
        <!--<input name="item_image" type='file' accept='image/*' onchange='openFile(event)'>-->
        {!! Form::file('item_image', array(
            'onchange' => 'openFile(event)',
            'accept' => 'image/gif, image/jpeg, image/png'
        )) !!}
    </div>
    <div class="col-md-10">
      <img id="item-image" class="upload-item" src="{{ url('image/' . $obj->image_path . '?w=300') }}" />
    </div>
  </div>
</div>
@foreach(\App\Producer::first()->getFillableEdit() as $field => $type)

<div class="form-group">
    {!! Form::label($field) !!}
    {!! Form::$type[0]($field, $obj->$field, 
        array('required', 
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