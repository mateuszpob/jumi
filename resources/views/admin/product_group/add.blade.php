@extends('admin.index')

@section('content')
<h1>Dodaj Grupę produktów</h1>
@include('admin.errors')

{!! Form::open(array('action' => 'ProductGroupController@store', 'class' => 'form', 'files'=>true)) !!}

<!--<div  class="form-group">
    {!! Form::label('Parent category') !!}
    {!! Form::select('id_upper', 
        \App\Category::getAllCategoryNamesWithIdes(true),
        [],
        array('required', 
              'class'=>'form-control', 
              'placeholder'=>'id_upper')) !!}
</div> -->



<div class="form-group">
    <label for="name">Name</label>
            <input required="required" class="form-control" placeholder="name" name="name" type="text" id="name">    
</div>
<div class="form-group">
    <label for="description">Description</label>
            <textarea class="form-control" placeholder="description" name="description" cols="50" rows="10" id="description"></textarea> 
</div>
<div class="form-group">
    <label for="name">Item IDes</label>
            <input class="form-control" placeholder="ID itemów, rozdzielone przecinkami (bez spacji)" name="item_ides" type="text" id="item_ides">    
</div>
<div class="row">
<div class="col-md-4">
<div class="form-group" style="width:200px;">
    <label for="promotion_percentage">Promotion Percentage</label>
            <input class="form-control" placeholder="promotion_percentage" name="promotion_percentage" type="text" id="promotion_percentage">          
</div>
<div class="form-group" style="width:200px;">
    <label for="promotion_start_date">Promotion Start Date</label>
            <input class="form-control" placeholder="promotion_start_date" name="promotion_start_date" type="datetime-local" id="promotion_start_date">             
</div>
<div class="form-group" style="width:200px;">
    <label for="promotion_end_date">Promotion End Date</label>
            <input class="form-control" placeholder="promotion_end_date" name="promotion_end_date" type="datetime-local" id="promotion_end_date">      
</div>
<div class="form-group">
    <label for="promotion_once">Promotion Once</label>
            <input name="promotion_once" type="checkbox" value="1" id="promotion_once">  
</div>
<div class="form-group">
    <label for="active">Active</label>
            <input name="active" type="checkbox" value="1" id="active">
             
</div>

<div class="form-group">
    {!! Form::submit('Ok', 
      array('class'=>'btn btn-primary')) !!}
</div>
</div>
<div class="col-md-8">
        <div class="col-md-2">
            <!--<input name="item_image" type='file' accept='image/*' onchange='openFile(event)'>-->
            {!! Form::file('item_image', array(
                'onchange' => 'openFile(event)',
                'accept' => 'image/gif, image/jpeg, image/png'
            )) !!}
        </div>
        <div class="col-md-10">
          <img id="item-image" class="upload-item" src="" />
        </div>
    </div>
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