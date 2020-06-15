@extends('layouts.app')

@section('dashboard')
   Wali Kelas
   <small>Daftar Wali Kelas</small>
@endsection

@section('breadcrumb')
   <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
   <li class="active">Wali Kelas</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Daftar Wali Kelas</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <p>
                        <a class="btn btn-success" href="{{ url('/admin/walikelas/create') }}">Tambah</a>
                        <!-- <a class="btn btn-warning" href="{{ url('/admin/export/books') }}">Export</a> -->
                    </p>
                    {!! $html->table(['class' => 'table table-bordered table-striped']) !!}
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection

@section('scripts')
    {!! $html->scripts() !!}
@endsection
