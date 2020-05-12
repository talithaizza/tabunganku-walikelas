@extends('layouts.app')

@section('dashboard')
   Wali Kelas
   <small>Edit Wali Kelas</small>
@endsection

@section('breadcrumb')
   <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
   <li><a href="{{ url('/admin/books') }}">Wali Kelas</a></li>
   <li class="active">Edit Wali Kelas</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Edit Wali Kelas</h3>
                </div>
                <!-- /.box-header -->
                {!! Form::model($data, ['url' => route('walikelas.update', $data->id), 'method' => 'put', 'files' => 'true']) !!}
                    @include('walikelas._form')
                {!! Form::close() !!}
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection
