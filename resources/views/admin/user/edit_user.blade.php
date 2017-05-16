@extends('admin.index')


@section('content')
<h1> {{$user->name}} </h1>
    {!! \Form::open(array('action' => 'UserController@store', 'class' => 'form')) !!}
    {!! Form::hidden('user_id', $user->id) !!}
    <div class="form-group">
        {!! Form::label('Nick') !!}
        {!! Form::text('nick', $nick, 
            array('class'=>'form-control', 
                  'placeholder'=>'nick')) !!}
    </div>
    
    <div class="form-group">
        {!! Form::label('Email') !!}
        {!! Form::text('email', $email, 
            array('class'=>'form-control', 
                  'placeholder'=>'Email')) !!}
    </div>
    
    <div class="form-group">
        {!! Form::label('Role') !!}
        <select multiple name="roles[]" class="form-control select">
            @foreach(App\Role::all() as $role)
            <option value="{{$role->id}}" 
                    @if ($user->roles->contains($role->id))
                        selected
                    @endif
                    >{{$role->name}}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        {!! Form::submit('Ok', 
          array('class'=>'btn btn-primary')) !!}
    </div>

    {!! Form::close() !!}
    
    
    

    
@stop






