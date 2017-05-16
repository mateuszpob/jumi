@extends('admin.index')

@section('content')
<h1>Add Category</h1>
@include('admin.errors')

{!! Form::open(array('action' => 'CategoryController@store', 'class' => 'form')) !!}

<div class="form-group">
    {!! Form::label('Parent category') !!}
    {!! Form::select('id_upper', 
        \App\Category::getAllCategoryNamesWithIdes(true),
        [],
        array('required', 
              'class'=>'form-control', 
              'placeholder'=>'id_upper')) !!}
</div>


@foreach(\App\Category::getFillableCreate() as $field => $type)


<div class="form-group">
    {!! Form::label($field) !!}
    @if($type == 'checkbox')
        {!! Form::checkbox($field, 1,  false); !!}
    @else
        {!! Form::$type($field, null, 
            array('required', 
                  'class'=>'form-control', 
                  'placeholder'=>$field)) !!}
    @endif         
</div>

@endforeach

<div class="form-group">
    {!! Form::submit('Ok', 
      array('class'=>'btn btn-primary')) !!}
</div>

{!! Form::close() !!}

@stop