@extends('admin.index')

@section('content')
<h1>Add Schema Category</h1>
@include('admin.errors')

{!! Form::open(array('action' => 'SchemaCategoriesController@store', 'class' => 'form')) !!}




@foreach(\App\SchemaCategories::getFillableCreate() as $field => $type)


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