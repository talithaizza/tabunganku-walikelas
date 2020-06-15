@extends('layouts.app')

@section('dashboard')
    Password
    <small>Edit Password</small>
@endsection

@section('breadcrumb')
    <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Ubah Password</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Edit Password</h3>
                </div>
                <!-- /.box-header -->
                {!! Form::open(['url' => url('/settings/password'), 'method' => 'post']) !!}
                    <div class="box-body">

                        <div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }}">
                            {!! Form::label('password', 'Password Lama') !!}

                            {!! Form::password('password', ['class' => 'form-control', 'placeholder' => 'Password lama']) !!}
                            {!! $errors->first('password', '<p class="help-block">:message</p>') !!}
                        </div>

                        <div class="form-group has-feedback{{ $errors->has('new_password') ? ' has-error' : '' }}">
                            {!! Form::label('new_password', 'Password Baru') !!}

                            {!! Form::password('new_password', ['class' => 'form-control', 'placeholder' => 'Password baru']) !!}
                            {!! $errors->first('new_password', '<p class="help-block">:message</p>') !!}
                        </div>

                        <div class="form-group has-feedback{{ $errors->has('new_password_confirmation') ? ' has-error' : '' }}">
                            {!! Form::label('new_password_confirmation', 'Konfirmasi Password Baru') !!}

                            {!! Form::password('new_password_confirmation', ['class' => 'form-control', 'placeholder' => 'Konfirmasi password baru']) !!}
                            {!! $errors->first('new_password_confirmation', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        {!! Form::submit('Simpan', ['class' => 'btn btn-primary']) !!}

                        <!-- {!! Form::submit('Reset', ['class' => 'btn btn-batal']) !!} -->
                    </div>
                {!! Form::close() !!}
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection
