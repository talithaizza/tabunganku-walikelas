@extends('layouts.app')

@section('dashboard')
    Kelas
    <small>Tambah Kelas</small>
@endsection

@section('breadcrumb')
    <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="{{ url('/admin/books') }}">Kelas</a></li>
    <li class="active">Tambah Kelas</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Tambah Kelas</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                {!! Form::open(['url' => route('kelas.store'), 'method' => 'post']) !!}
                    @include('kelas._form')
                {!! Form::close() !!}
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col (left) -->
    </div>
    <!-- /.row -->
@endsection
