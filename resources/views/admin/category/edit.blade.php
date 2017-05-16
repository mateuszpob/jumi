@extends('admin.index')

@section('content')
<div class="row">
  <div class="col-md-6">
<h1>Edit Category</h1>
@include('admin.errors')

{!! Form::open(array('action' => 'CategoryController@store', 'class' => 'form')) !!}
{!! Form::hidden('id', $obj->id) !!}
<div class="form-group">
    {!! Form::label('Kategoria nadrzędna') !!}
    {!! Form::select('id_upper', 
        \App\Category::getAllCategoryNamesWithIdes(true, $obj->id), 
        $obj->id_upper,
        array('required', 
              'class'=>'form-control', 
              'placeholder'=>'id_upper')) !!}
</div>
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