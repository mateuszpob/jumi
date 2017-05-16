
<!DOCTYPE html>
<html>
    <head>
        <!-- Bootstrap 3.3.4 -->
        <link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- Main page styles -->
        <link href="/css/main.css" type="text/css" rel="stylesheet" />
        <!-- Mimity page styles -->
        <link href="/css/mimity.css" type="text/css" rel="stylesheet" />
        <!-- jQuery Colorbox style -->
        <link rel="stylesheet" href="/css/colorbox.css" />
        <!-- jQuery 2.2.0 -->
        <script src="/js/jquery-2.2.0.min.js" type="text/javascript"></script>
        <!-- jQuery UI 1.11.2 -->
        <script src="/js/jquery-ui.min.js" type="text/javascript"></script>
        <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
        <script>
          $.widget.bridge('uibutton', $.ui.button);
        </script>
        <!-- Bootstrap 3.3.2 JS -->
        <script src="/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="/js/main.js" type="text/javascript"></script> 
        <script src="/js/wookmark.js" type="text/javascript"></script> 
        <!-- jQuery Colorbox -->
        <script src="/js/jquery.colorbox-min.js" type="text/javascript"></script> 
    </head>
    <body class="login-body">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-2 col-md-offset-1" style="margin-top: 18%;">
					<div class="panel panel-default hive-login-panel">
						<div class="panel-heading">Zaloguj się</div>
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

							<form class="form-horizontal" role="form" method="POST" action="{{ url('/auth/login') }}">
								<input type="hidden" name="_token" value="{{ Session::token() }}">

									<label style="text-align:left" class="col-md-12 control-label">E-Mail</label>
								<div class="form-group">
									<div class="col-md-12">
		                                                            <input type="text" class="form-control" name="email" value="{{ old('email') }}">
									</div>
								</div>

									<label style="text-align:left" class="col-md-12 control-label">Hasło</label>
								<div class="form-group">
									<div class="col-md-12">
										<input type="password" class="form-control" name="password">
									</div>
								</div>

								<!-- <div class="form-group">
									<div class="col-md-6 col-md-offset-4">
										<div class="checkbox">
											<label>
												<input type="checkbox" name="remember"> Remember Me </input>
											</label>
										</div>
									</div>
								</div> -->

								<div class="form-group">
									<div class="col-md-6 col-md-offset-4">
										<button type="submit" class="btn btn-primary">Zaloguj</button>
		                                                                <!-- <a href="{!! url('auth/facebook/authorize') !!}" class="btn btn-primary">Facebook</a> -->
										<!-- <a class="btn btn-link" href="{{ url('/password/email') }}">Forgot Your Password?</a> -->
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
    </body>
</html>
