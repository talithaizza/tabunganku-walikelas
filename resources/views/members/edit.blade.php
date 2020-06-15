@extends('layouts.app')

@section('dashboard')
   Member
   <small>Ubah Member</small>
@endsection

@section('breadcrumb')
   <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
   <li><a href="{{ url('/admin/members') }}">Member</a></li>
   <li class="active">Ubah Member</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Ubah Member</h3>
                </div>
                <!-- /.box-header -->
                {!! Form::model($member, ['url' => route('members.update', $member->id), 'method' => 'put', 'files' => 'true']) !!}
                    @include('members._form')
                {!! Form::close() !!}
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection
