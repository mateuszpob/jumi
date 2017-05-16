@extends('admin.index')

@section('content')
<h1>Add mail</h1>
@include('admin.errors')

{!! Form::open(array('action' => 'MailController@store', 'class' => 'form')) !!}


@foreach(\App\Mail::getFillableCreate() as $field => $type)

<div class="form-group">
    {!! Form::label($field) !!}
    {!! Form::$type($field, null, 
        array('class'=>'form-control', 
              'placeholder'=>$field)) !!}
</div>

@endforeach

<div class="form-group">
    {!! Form::submit('Ok', 
      array('class'=>'btn btn-primary')) !!}
</div>

{!! Form::close() !!}

@stop