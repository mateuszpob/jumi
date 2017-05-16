@extends('auth.app')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">Register</div>
				<div class="panel-body">
					@if (count($errors) > 0)
						<div class="alert alert-danger">
							<strong>Whoops!</strong> There were some problems with your input.<br><br>
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif

					<form class="form-horizontal" role="form" method="POST" action="{{ url('/auth/register') }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">

						<div class="form-group">
							<label class="col-md-4 control-label">Imię</label>
							<div class="col-md-6">
								<input type="text" required="required" class="form-control" name="first_name" value="{{ old('first_name') }}">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 control-label">Nazwisko</label>
							<div class="col-md-6">
								<input type="text" required="required" class="form-control" name="last_name" value="{{ old('last_name') }}">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 control-label">Miasto</label>
							<div class="col-md-6">
								<input type="text" required="required" class="form-control" name="city" value="{{ old('city') }}">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 control-label">Kod pocztowy</label>
							<div class="col-md-6">
								<input type="text" required="required" class="form-control" name="postcode" value="{{ old('postcode') }}">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 control-label">Adres</label>
							<div class="col-md-6">
								<input type="text" required="required" class="form-control" name="address" value="{{ old('address') }}">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 control-label">Telefon</label>
							<div class="col-md-6">
                                                            <input type="text" required="required" class="form-control" name="telephone" value="{{ old('telephone') }}">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">E-Mail</label>
							<div class="col-md-6">
								<input type="email" required="required" class="form-control" name="email" value="{{ old('email') }}">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Hasło</label>
							<div class="col-md-6">
								<input type="password" class="form-control" name="password">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Powtórz Hasło</label>
							<div class="col-md-6">
								<input type="password" class="form-control" name="password_confirmation">
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<button type="submit" class="btn btn-primary">
									Zarejestruj
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection