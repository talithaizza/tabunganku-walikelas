@extends('layouts.app')

@section('dashboard')
    Dashboard
    <small>Guru</small>
@endsection

@section('breadcrumb')
    <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Dashboard</li>
@endsection

@section('content')
    <!-- Welcome -->
    <div class="row">
        <div class="col-md-12">
            <div class="callout callout-success">
              <h4>Selamat Datang di TabunganKu</h4>

              <p>Sistem Tabungan Siswa Digital</p>
            </div>
        </div>
    </div>
	<!-- Info boxes -->
    <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="ion ion-ios-person-outline"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Total Siswa</span>
                    <span class="info-box-number">{{ $jumlah_data_siswa }}<small> </small></span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-red"><i class="fa fa-book"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Total Transaksi Tarik Hari ini</span>
                    <span class="info-box-number">{{ $total_tarik_harian }}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <!-- fix for small devices only -->
        <div class="clearfix visible-sm-block"></div>

        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-green"><i class="ion ion-ios-people-outline"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Total Transaksi Setor Hari Ini</span>
                    <span class="info-box-number">{{ $total_setor_harian }}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-yellow"><i class="ion ion-stats-bars"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Total Saldo Keseluruhan</span>
                    <span class="info-box-number">{{ $total_saldo_keseluruhan }}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection
