@extends('layouts.app')

@section('dashboard')
   Buku
   <small>Ubah Buku</small>
@endsection

@section('breadcrumb')
   <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
   <li><a href="{{ url('/admin/books') }}">Buku</a></li>
   <li class="active">Ubah Buku</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Ubah Buku</h3>
                </div>
                <!-- /.box-header -->
                {!! Form::model($book, ['url' => route('books.update', $book->id), 'method' => 'put', 'files' => 'true']) !!}
                    @include('books._form')
                {!! Form::close() !!}
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection
