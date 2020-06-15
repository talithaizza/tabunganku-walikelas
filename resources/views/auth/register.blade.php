<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="{{ asset("/admin-lte/bootstrap/css/bootstrap.min.css") }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset("/admin-lte/dist/css/AdminLTE.min.css") }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('admin-lte/plugins/iCheck/square/blue.css') }}">

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="hold-transition register-page">
    <div class="register-box">
        <div class="register-logo">
            <a href="{{ url('/') }}"><b>Lara</b>buk</a>
        </div>
        <!-- /.login-logo -->
        <a href=" ">Admin</a> <a>|</a> <a href=" ">Wali Kelas</a>
        <div class="login-box-body">
            @include('layouts._flash')

            <p class="login-box-msg">Registrasi Wali Kelas</p>

            {!! Form::open(['url' => route('register'), 'files' => 'true', 'method' => 'post']) !!}

                <div class="form-group has-feedback{{ $errors->has('name') ? ' has-error' : '' }}">
                    {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Nama']) !!}
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>

                    {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
                </div>

                <div class="form-group has-feedback{{ $errors->has('email') ? ' has-error' : '' }}">
                    {!! Form::email('email', null, ['class' => 'form-control', 'placeholder' => 'Email']) !!}
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>

                    {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
                </div>

                <div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }}">
                    {!! Form::password('password', ['class' => 'form-control', 'placeholder' => 'Password']) !!}
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>

                    {!! $errors->first('password', '<p class="help-block">:message</p>') !!}
                </div>

                <div class="form-group has-feedback{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                    {!! Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => 'Password Confirmation']) !!}
                    <span class="glyphicon glyphicon-log-in form-control-feedback"></span>

                    {!! $errors->first('password_confirmation', '<p class="help-block">:message</p>') !!}
                </div>

                <div class="form-group has-feedback{{ $errors->has('avatar') ? ' has-error' : '' }}">
                    {!! Form::file('avatar', ['class' => 'form-control']) !!}
                    <p class="help-block">(Opsional) Pilih Foto Profil</p>

                    {!! $errors->first('avatar', '<p class="help-block">:message</p>') !!}
                </div>

                <div class="form-group has-feedback{{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }}">
                    {!! app('captcha')->display() !!}

                    {!! $errors->first('g-recaptcha-response', '<p class="help-block">:message</p>') !!}
                </div>

                <div class="row">
                    <!-- .col -->
                    <!--
                    <div class="col-xs-8">
                        <div class="checkbox icheck">
                            <label>
                                <input type="checkbox" value="agree"> Saya setuju dengan kebijakan
                            </label>
                        </div>
                    </div>
                    -->

                    <div class="col-xs-12">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">Sign Up</button>
                    </div>
                    <!-- /.col -->
                </div>
            {!! Form::close() !!}

            <a href="{{ route('login') }}">Saya sudah punya akun</a>

        </div>
        <!-- /.form-box -->
    </div>
    <!-- /.register-box -->


    <!-- jQuery 2.2.3 -->
    <script src="{{ asset("/admin-lte/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>
    <!-- Bootstrap 3.3.6 -->
    <script src="{{ asset("/admin-lte/bootstrap/js/bootstrap.min.js") }}"></script>

    <!-- iCheck -->
    <script src="{{ asset('admin-lte/plugins/iCheck/icheck.min.js') }}"></script>
    <script>
      $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' // optional
        });
      });
    </script>
</body>
</html>
