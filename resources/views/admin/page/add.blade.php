@extends('admin.index')

@section('content')
<h1>Add role</h1>
@include('admin.errors')

{!! Form::open(array('action' => 'RoleController@index', 'class' => 'form')) !!}

<div class="form-group">
    {!! Form::label('Role name') !!}
    {!! Form::text('role_name', null, 
        array('required', 
              'class'=>'form-control', 
              'placeholder'=>'Role name')) !!}
</div>

<div class="form-group">
    {!! Form::submit('Ok', 
      array('class'=>'btn btn-primary')) !!}
</div>

{!! Form::close() !!}

@stop