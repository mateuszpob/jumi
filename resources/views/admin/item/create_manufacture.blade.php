@extends('admin.index')

@section('content')
<h1>Add Producer</h1>
@include('admin.errors')

{!! Form::open(array('action' => 'ItemController@storeManufacture', 'class' => 'form')) !!}


@foreach(\App\Producer::getFillableCreate() as $field => $type)

<div class="form-group">
    {!! Form::label($field) !!}
    {!! Form::$type($field, null, 
        array('required', 
              'class'=>'form-control', 
              'placeholder'=>$field)) !!}
</div>

@endforeach

<div class="form-group">
    {!! Form::submit('Ok', 
      array('class'=>'btn btn-primary')) !!}
</div>

{!! Form::close() !!}

@stop