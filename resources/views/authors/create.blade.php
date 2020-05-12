@extends('layouts.app')

@section('dashboard')
   Penulis
   <small>Tambah Penulis</small>
@endsection

@section('breadcrumb')
   <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
   <li><a href="{{ url('/admin/authors') }}">Penulis</a></li>
   <li class="active">Tambah Penulis</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Tambah Penulis</h3>
                </div>
                <!-- /.box-header -->
                {!! Form::open(['url' => route('authors.store'), 'method' => 'post']) !!}
                    @include('authors._form')
                {!! Form::close() !!}
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection
