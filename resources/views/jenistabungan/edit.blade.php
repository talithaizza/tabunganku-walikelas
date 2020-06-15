@extends('layouts.app')

@section('dashboard')
Jenis Tabungan
    <small>Edit Jenis Tabungan</small>
@endsection

@section('breadcrumb')
    <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="{{ url('/admin/jenistabungan') }}">Jenis Tabungan</a></li>
    <li class="active">Edit Jenis Tabungan</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Edit Jenis Tabungan</h3>
                </div>
                <!-- /.box-header -->
                {!! Form::model($data, ['url' => route('jenistabungan.update', $data->id), 'method' => 'put']) !!}
                    @include('jenistabungan._editform')
                {!! Form::close() !!}
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection
