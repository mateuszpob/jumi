@extends('admin.index')

@section('content')
<h1>Edit role</h1>
@include('admin.errors')

{!! Form::open(array('action' => 'RoleController@store', 'class' => 'form')) !!}

<div class="form-group">
    {!! Form::label('Role name') !!}
    {!! Form::hidden('id', $role->id) !!}
    {!! Form::text('role_name', $role->name, 
        array('required', 
              'class'=>'form-control', 
              'placeholder'=>'Role name')) !!}
</div>
<style type="text/css">
    .selectpicker span.text {
        font-family: 'Courier New', Courier, monospace
    }
</style>

<div class="form-group">
        {!! Form::label('Actions') !!}
        <select multiple name="roles[]" class="form-control select " data-live-search="true" style="height: 400px;" >
                <optgroup class="monospace-font">
                @if ($role->definitions()->where('action','=','*')->first())
                
                    <option value="*" title="Wszystkie" selected>*</option>
                @endif
                @foreach($routes as $key => $route)

                <option class="monospace-font" value="{{$key}}" title="{{$key}}"
                        @if ($role->definitions()->where('action','=',$key)->first())
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

<script type="text/javascript" defer="defer" >
    $('form.form').on('submit',function(){
        $('select[name="roles[]"]').prop('disabled', false);
    })
</script>

@stop

