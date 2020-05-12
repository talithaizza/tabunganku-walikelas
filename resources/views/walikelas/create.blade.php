@extends('layouts.app')

@section('dashboard')
    Wali Kelas
    <small>Tambah Wali Kelas</small>
@endsection

@section('breadcrumb')
    <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="{{ url('/admin/books') }}">Wali Kelas</a></li>
    <li class="active">Tambah Wali Kelas</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Tambah Wali Kelas</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                {!! Form::open(['url' => route('walikelas.store'), 'method' => 'post', 'files' => 'true']) !!}
                    @include('walikelas._form')
                {!! Form::close() !!}
            </div>
            <!-- /.box -->
        </div>
    </div>
    <!-- /.row -->
@endsection
