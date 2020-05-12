@extends('layouts.app')

@section('dashboard')
    Setor Tunai
    <small>Transaksi Penyetoran Tunai</small>
@endsection

@section('breadcrumb')
    <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="{{ url('/admin/siswa') }}">Setor Tunai</a></li>
    <li class="active">Setor Tunai</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Formulir Penyetoran Tunai</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                {!! Form::open(['url' => route('setortunai.store'), 'method' => 'post']) !!}
                    @include('setortunai._form')
                {!! Form::close() !!}
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col (left) -->
        <!-- /.col (right)-->
    </div>
    <!-- /.row -->
@endsection
