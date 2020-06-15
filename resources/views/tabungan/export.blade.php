@extends('layouts.app')

@section('dashboard')
  Laporan
   <small>Export Laporan</small>
@endsection

@section('breadcrumb')
   <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
   <li><a href="{{ url('/admin/books') }}">Laporan</a></li>
   <li class="active">Export</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Form Export Laporan</h3>
              </div>
                <!-- /.box-header -->
                {!! Form::open(['url' => route('export.tabungan.post'), 'method' => 'post']) !!}
                    <div class="box-body">
                        <div class="form-group has-feedback{!! $errors->has('author_id') ? 'has-error' : '' !!}">
                            {!! Form::label('id_siswa', 'Nama Siswa') !!}

                            {!! Form::select('id_siswa[]', App\siswa::pluck('nama_lengkap', 'nis')->all(), null, [
                                'class' => 'form-control js-select2',
                                'multiple' => 'multiple',
                                'placeholder' => 'Semua Siswa'
                            ]) !!}
                            {!! $errors->first('id_siswa', '<p class="help-block">:message</p>') !!}
                        </div>
                        
                        <div class="form-group has-feedback{!! $errors->has('author_id') ? 'has-error' : '' !!}">
                            {!! Form::label('id_siswa', 'Kelas') !!}

                            {!! Form::select('id_siswa[]', App\siswa::pluck('nama_lengkap', 'nis')->all(), null, [
                                'class' => 'form-control js-select2',
                                'multiple' => 'multiple',
                            ]) !!}
                            {!! $errors->first('id_siswa', '<p class="help-block">:message</p>') !!}
                        </div>
                        <!-- <div class="form-group has-feedback{!! $errors->has('type') ? 'has-error' : '' !!}">
                            {!! Form::label('type', 'Pilih Output') !!}

                            <div class="radio">
                                <label>
                                    {{ Form::radio('type', 'xls', true) }} Excel
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    {{ Form::radio('type', 'pdf') }} PDF
                                </label>
                            </div>
                            {!! $errors->first('type', '<p class="help-block">:message</p>') !!}
                        </div> -->

                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        {!! Form::submit('Download', ['class' => 'btn btn-primary']) !!}
                    </div>
                {!! Form::close() !!}
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection
