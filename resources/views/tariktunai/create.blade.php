@extends('layouts.app')

@section('dashboard')
    Tarik Tunai
    <small>Transaksi Penarikan Tunai</small>
@endsection

@section('breadcrumb')
    <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Tarik Tunai</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Formulir Penarikan Tunai</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                {!! Form::open(['url' => route('tariktunai.store'), 'method' => 'post']) !!}
                    @include('tariktunai._form')
                {!! Form::close() !!}
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col (left) -->
        <!-- /.col (right)-->
    </div>
    <!-- /.row -->
@endsection
