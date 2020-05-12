@extends('layouts.app')

@section('dashboard')
   Penulis
   <small>Ubah Penulis</small>
@endsection

@section('breadcrumb')
   <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
   <li><a href="{{ url('/admin/authors') }}">Penulis</a></li>
   <li class="active">Ubah Penulis</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Ubah Penulis</h3>
                </div>
                <!-- /.box-header -->
                {!! Form::model($author, ['url' => route('authors.update', $author->id), 'method' => 'put']) !!}
                    @include('authors._form')
                {!! Form::close() !!}
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection
