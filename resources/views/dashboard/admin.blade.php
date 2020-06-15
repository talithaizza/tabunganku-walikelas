@extends('layouts.app')

@section('dashboard')
    Dashboard
    <small>Admin</small>
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
              <!-- <p>Sistem Informasi Tabungan Siswa Laravel 5.4 & AdminLTE</p>
              <p>Dibuat oleh <a href="https://www.instagram.com/melania.fitria" target="_blank">Melania Nur Fitriansa</a>, lihat respository <a href="#" target="_blank">GitHub</a></p> -->
            </div>
        </div>
    </div>

    <!-- Info boxes -->
    <div class="row">
		<div class="col-md-12">
            <div>
              <h4>Informasi Siswa</h4>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="ion ion-ios-people-outline"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Siswa Keseluruhan</span>
                    <span class="info-box-number">{{ $total_siswa }}<small> </small></span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-yellow"><i class="ion ion-ios-people-outline"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Siswa Aktif</span>
                    <span class="info-box-number">{{ $total_siswa_aktif }}</span>
                </div>
            </div>
        </div>
		<div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-red"><i class="ion ion-ios-people-outline"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Siswa Non-aktif</span>
                    <span class="info-box-number">{{ $total_siswa_nonaktif }}</span>
                </div>
            </div>
        </div>
	</div>

    <div class="row">
        <!-- fix for small devices only -->
        <div class="clearfix visible-sm-block"></div>
		<div class="col-md-12">
            <div>
              <h4>Informasi Wali Kelas</h4>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="ion ion-ios-person-outline"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Wali Kelas Keseluruhan</span>
                    <span class="info-box-number">{{ $total_walikelas }}<small> </small></span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-yellow"><i class="ion ion-ios-person-outline"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Wali Kelas Aktif</span>
                    <span class="info-box-number">{{ $total_walikelas_aktif }}</span>
                </div>
            </div>
        </div>
		<div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-red"><i class="ion ion-ios-person-outline"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Walikelas Non-aktif</span>
                    <span class="info-box-number">{{ $total_walikelas_nonaktif }}</span>
                </div>
            </div>
        </div>
	</div>
	
    <div class="row">
        <!-- fix for small devices only -->
        <div class="clearfix visible-sm-block"></div>
		<div class="col-md-12">
            <div>
              <h4>Informasi Kelas</h4>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="ion ion-ios-book-outline"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Kelas</span>
                    <span class="info-box-number">{{ $total_kelas }}</span>
                </div>
            </div>
        </div>
	</div>

    <div class="row">
        <!-- fix for small devices only -->
        <div class="clearfix visible-sm-block"></div>
		<div class="col-md-12">
            <div>
              <h4>Informasi Jenis Tabungan</h4>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="ion ion-ios-paper-outline"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Jenis Tabungan</span>
                    <span class="info-box-number">{{ $total_jenis_tabungan }}</span>
                </div>
            </div>
        </div>
	</div>

    <div class="row">
		<div class="col-md-12">
            <div>
              <h4>Informasi Tabungan</h4>
            </div>
        </div>
		
		<div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="ion ion-stats-bars"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Saldo Keseluruhan</span>
                    <span class="info-box-number">{{ $total_saldo_keseluruhan }}</span>
                </div>
            </div>
        </div>
		
		@foreach($total_saldo_pertabungan as $x => $x_value)
		<div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-green"><i class="ion ion-stats-bars"></i></span>
				
                <div class="info-box-content">
                    <span class="info-box-text">{{$x}}</span>
                    <span class="info-box-number">{{$x_value}}</span>
                </div>
            </div>
        </div>
		@endforeach
		
    </div>

    <!-- /.row -->

@endsection
