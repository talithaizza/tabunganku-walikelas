@extends('layouts.app')

@section('dashboard')
    Member
    <small>Detail {{ $member->name }}</small>
@endsection

@section('breadcrumb')
    <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href=" {{ url('admin/members') }}">Member</a></li>
    <li class="active">Detail {{ $member->name }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Buku yang sedang dipinjam</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>Judul</th>
                            <th>Tanggal Peminjaman</th>
                        </tr>
                        <tr>
                            @forelse ($member->borrowLogs()->borrowed()->get() as $log)
                                <tr>
                                    <td>{{ $log->book->title }}</td>
                                    <td>{{ $log->created_at }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tr>
                    </table>
                </div>
                <!-- /.box-body -->
                <div class="box-footer clearfix">

                </div>
            </div>
            <!-- /.box -->
        </div>

        <div class="col-md-6">

            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Buku yang sudah dikembalikan</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>Judul</th>
                            <th>Tanggal Kembali</th>
                        </tr>
                        <tr>
                            @forelse ($member->borrowLogs()->borrowed()->get() as $log)
                                <tr>
                                    <td>{{ $log->book->title }}</td>
                                    <td>{{ $log->created_at }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tr>
                    </table>
                </div>
                <!-- /.box-body -->
                <div class="box-footer clearfix">

                </div>
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection
