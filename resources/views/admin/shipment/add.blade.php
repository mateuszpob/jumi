@extends('admin.index')

@section('content')
<h1>Add Shipment</h1>
@include('admin.errors')

{!! Form::open(array('action' => 'ShipmentController@store', 'class' => 'form')) !!}


@foreach(\App\Shipment::getFillableCreate() as $field => $type)

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

{!! Form::close() !!}

@stop