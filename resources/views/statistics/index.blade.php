@extends('layouts.app')

@section('dashboard')
   Peminjaman
   <small>Daftar Peminjaman</small>
@endsection

@section('breadcrumb')
   <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
   <li class="active">Peminjaman</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Peminjaman</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
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
    <script>
    $(function() {
        $('\
            <div id="filter_status" class="dataTables_length">\
                <label>Status \
                    <select size="1" name="filter_status" aria-controls="filter_status" \
                    class="form-control">\
                        <option value="all" selected="selected">Semua</option>\
                        <option value="returned">Sudah Dikembalikan</option>\
                        <option value="not-returned">Belum Dikembalikan</option>\
                    </select>\
                </label>\
            </div>\
        ').insertAfter('.dataTables_length');

        $("#dataTableBuilder").on('preXhr.dt', function(e, settings, data) {
            data.status = $('select[name="filter_status"]').val();
        });

        $('select[name="filter_status"]').change(function() {
            window.LaravelDataTables["dataTableBuilder"].ajax.reload();
        });
    });
    </script>
@endsection
