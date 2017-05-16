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
    @if($type[0] == 'checkbox')
        {!! Form::checkbox($field, 1,  $obj->$field=="1" ? true : false); !!}
    @else
        {!! Form::$type[0]($field, $obj->$field, 
            array('', 
            $type[1]=>$type[1],
                  'class'=>'form-control disabled', 
                  'placeholder'=>$field, false)) !!}
    @endif
</div>

@endforeach

<div class="form-group">
    {!! Form::submit('Ok', 
      array('class'=>'btn btn-primary')) !!}
</div>

{!! Form::close() !!}




@stop