@extends('layouts.app')

@section('dashboard')
   Data Siswa
   <small>Edit Siswa</small>
@endsection

@section('breadcrumb')
   <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
   <li><a href="{{ url('/admin/siswa') }}">Siswa</a></li>
   <li class="active">Edit Siswa</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Edit Siswa</h3>
                </div>
                <!-- /.box-header -->
                {!! Form::model($data, ['url' => route('siswa.update', $data->id), 'method' => 'put']) !!}
                    @include('siswa._form')
                {!! Form::close() !!}
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-6">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Upload</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                {!! Form::model($data, ['url' => route('siswa.upload', $data->id), 'method' => 'put', 'files' => 'true']) !!}
                    @include('siswa._formUpload')
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <!-- /.row -->
@endsection
