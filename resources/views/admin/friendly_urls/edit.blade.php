@extends('admin.index')

@section('content')
<h1>Edit address</h1>
@include('admin.errors')

{!! Form::open(array('action' => 'FriendlyUrlController@store', 'class' => 'form')) !!}

{!! Form::hidden('id', $id) !!}

<div class="form-group">
    {!! Form::label('URL') !!}
    {!! Form::text('url', $url, 
        array('required', 
              'class'=>'form-control', 
              'placeholder'=>'url')) !!}
</div>
<style type="text/css">
    .selectpicker span.text {
        font-family: 'Courier New', Courier, monospace
    }
</style>

<div class="form-group">
        {!! Form::label('Action') !!}
        <select name="action" class="form-control select " data-live-search="true" >
                <optgroup class="monospace-font">
                @foreach($routes as $key => $route)
                    <option class="monospace-font" value="{{$key}}" title="{{$key}}"
                        @if ($action == $key)
                            selected
                        @endif
                        >{{$route}}</option>
                @endforeach
                </optgroup>
        </select>
</div>


<div class="form-group">
    {!! Form::submit('Ok', 
      array('class'=>'btn btn-primary')) !!}
</div>

{!! Form::close() !!}

@stop