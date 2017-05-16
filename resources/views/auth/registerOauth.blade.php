<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Laravel</title>
        <script src="{{ elixir('js/main.js') }}"></script>
        <link href="{{ asset('/css/app.css') }}" rel="stylesheet">

        <!-- Fonts -->
        <link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
                <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
                <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">Laravel</a>
                </div>

                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li><a href="{{ url('/') }}">Home</a></li>
                    </ul>

                </div>
            </div>
        </nav>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">Register</div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-4 col-md-offset-4 ">
                                    <h1>Additional info</h1>
                                    <h4>Please choose your Displate username and password.</h4>
                                </div>
                            </div>
                            <form class="form-horizontal" role="form" method="POST" action="">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                <div class="form-group">
                                    <label class="col-md-4 control-label">Name</label>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" name="nick" value="{{ old('name') }}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 control-label">Password</label>
                                    <div class="col-md-4">
                                        <input type="password" class="form-control" name="password">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-4 control-label">Confirm Password</label>
                                    <div class="col-md-4">
                                        <input type="password" class="form-control" name="password_confirmation">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-4"></div>
                                    <div class="col-md-4 checkbox">
                                        <label>
                                            <input type="checkbox" name="privacy" required="required" />
                                            I accept the <a href="/regulations-open">Terms of Use</a> and <a href="/policy-open">Privacy policy</a>
                                        </label>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <div class="col-md-4 col-md-offset-4">
                                        <button type="submit" class="btn btn-primary">
                                            Register
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scripts -->
        <script type="text/javascript">

$('input, select, textarea, checkbox, radio').on('click keypress change', function () {

    $('input, select, textarea, checkbox, radio').tooltip('destroy');
    $('input, select, textarea, checkbox, radio').parents('.form-group').removeClass('has-error').removeClass('has-feedback');
    $('span.form-control-feedback').remove();
    typewatch(function (a) {
        $.post($('form').attr('action'), $('form').serialize()).done(function (data) {
            console.log(data);
        }).fail(function (data) {
            $.each(data.responseJSON, function (field, elem) {
                if ($('form [name="' + field + '"]').val() != '' && $('form [name="' + field + '"]').val() != "on") {
                    $('form [name="' + field + '"]').tooltip({placement: "bottom", html: true, title: elem.join('<br>')})
                    $('form [name="' + field + '"]').parent().append('<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>');
                    $('form [name="' + field + '"]').parents('.form-group').addClass('has-error').addClass('has-feedback');
                }
                console.log(field, elem);
            });
        });
    }, 500)
});

$('input[type="password"]').keypress(function (e) {
    //console.log(e);
    var s = String.fromCharCode(e.which);
    if (s.toUpperCase() === s && s.toLowerCase() !== s && !e.shiftKey) {
        $(this).parents('.form-group').addClass('has-warning').addClass('has-feedback')
        $(this).parent().append('<span class="glyphicon glyphicon-warning-sign form-control-feedback" aria-hidden="true"></span>')
        $(this).tooltip({placement: "bottom", title: 'Caps Lock is on'}).tooltip('show');
    }else{
        $(this).tooltip('destroy');
        $(this).parents('.form-group').removeClass('has-warning').removeClass('has-feedback');
        $('span.glyphicon-warning-sign.form-control-feedback').remove();
    }
});
        </script>
    </body>
</html>