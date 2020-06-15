@extends('layouts.app')

@section('dashboard')
    Manual Status
    <small>Konfirmasi Status</small>
@endsection

@section('breadcrumb')
    <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Manual Status</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Konfirmasi Status</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                {!! Form::open(['url' => route('siswa.perbarui'), 'method' => 'post']) !!}
                    @include('siswa.formkonfirm')
                {!! Form::close() !!}
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col (left) -->
        <!-- /.col (right)-->
    </div>
    <!-- /.row -->
@endsection
