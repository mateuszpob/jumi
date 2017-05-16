@extends('admin.index')

@section('content')
<div class="row">
  <div class="col-md-6">
    <h1>Wysyłka - {{$obj->name}}</h1>
    @include('admin.errors')

  </div>

</div>
@foreach(\App\Shipment::first()->getFillableEdit() as $field => $type)

<div class="form-group">
    {!! Form::label($field) !!}
    {!! Form::$type[0]($field, $obj->$field, 
        array('required', 
        'disabled' => 'disabled',
              'class'=>'form-control disabled', 
              )) !!}
</div>

@endforeach



@stop