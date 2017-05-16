@extends('admin.index')

@section('content')
<div class="row">
  <div class="col-md-6">
    <h1>Edit Shipment</h1>
    @include('admin.errors')

    {!! Form::open(array('action' => 'ShipmentController@store', 'class' => 'form', 'files'=>true)) !!}
    {!! Form::hidden('id', $obj->id) !!}

  </div>

</div>
@foreach(\App\Shipment::first()->getFillableEdit() as $field => $type)

<div class="form-group">
    {!! Form::label($field) !!}
    {!! Form::$type[0]($field, $obj->$field, 
        array('required', 
        $type[1]=>$type[1],
              'class'=>'form-control disabled', 
              'placeholder'=>$field)) !!}
</div>

@endforeach

<div class="form-group">
    {!! Form::submit('Ok', 
      array('class'=>'btn btn-primary')) !!}
</div>

{!! Form::close() !!}




@stop