@extends('admin.index')

@section('content')
<div class="row">
    <div class="col-md-6">
        <h1>Add item</h1>
<!--        <p>
            Jeszcze wszystko super nie działa więc to jest krótka instrukcja:</br>
            Przy dodawaniu itemu trzeba wypełnic pola wymagane, czyli: <br>
            Name, Description, Price, Count, Weight.</br>
            Dla Was istotne są na razie name, description i price, reszta jest nei używana, ale wypełnić musicie, bo tak;
        </p>-->
        @include('admin.errors')

        {!! Form::open(array('action' => 'ItemController@store', 'class' => 'form',  'files'=>true)) !!}

        <div class="form-group">
        {!! Form::label('Category') !!}
        {!! Form::select('category_id[]', 
            \App\Category::getAllCategoryNamesWithIdes(true), 
            [],
            array('required', 
                  'multiple' => 'multiple',
                  'class'=>'form-control', 
                  'style'=>'height:300px', 
                  'placeholder'=>'category_id[]')) !!}
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
          <img id="item-image" class="upload-item" src="" />
        </div>
    </div>
</div>
@foreach(\App\Item::getFillableCreate() as $field => $type)

<div class="form-group">
    {!! Form::label($field) !!}
    @if($type[0] == 'checkbox')
        {!! Form::checkbox($field, 1,  $obj->$field=="1" ? true : false); !!}
    @else
    {!! Form::$type($field, null, 
        array('', 
              'class'=>'form-control', 
              'placeholder'=>$field)) !!}
    @endif
</div>

@endforeach

<div class="form-group">
    {!! Form::submit('Ok', 
      array('class'=>'btn btn-primary')) !!}
</div>

<div class="form-group ext-desc-list">
<div class="row ext-desc-list-panel" data-ext-desc-nr="0">
        <div class="col-md-12" data-number="0">
            <input class="form-control disabled ext-desc_name" placeholder="Nazwa w menu" data-number="0" data-ext-desc-nr="0" type="text" value="">
        </div>
        <div class="col-md-12" data-number="0">
            <textarea class="form-control disabled ext-desc_value" placeholder="Wartość opcji" data-number="0" data-ext-desc-nr="0" style="height:200px;"></textarea>
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