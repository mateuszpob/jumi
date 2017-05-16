@extends('admin.index')

@section('content')
<h1>Add user</h1>
@include('admin.errors')

{!! Form::open(array('action' => 'UserController@store', 'class' => 'form')) !!}

@foreach(\App\User::first()->getFillable() as $field)

<div class="form-group">
    {!! Form::label($field) !!}
    {!! Form::text($field, null, 
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