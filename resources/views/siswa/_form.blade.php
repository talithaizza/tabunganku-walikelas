<div class="box-body">
	<link rel="stylesheet" href="{{ asset('/datepicker/dist/css/bootstrap-datepicker.min.css') }}">
	<div class="form-group has-feedback{{ $errors->has('nis') ? ' has-error' : '' }}">
        {!! Form::label('nis', 'NIS') !!}

        {!! Form::text('nis', null, ['class' => 'form-control', 'placeholder' => 'NIS']) !!}
        {!! $errors->first('nis', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="form-group has-feedback{{ $errors->has('nama_lengkap') ? ' has-error' : '' }}">
        {!! Form::label('nama_lengkap', 'Nama Lengkap') !!}

        {!! Form::text('nama_lengkap', null, ['class' => 'form-control', 'placeholder' => 'Nama Lengkap']) !!}
        {!! $errors->first('nama_lengkap', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="form-group has-feedback{!! $errors->has('kelas') ? 'has-error' : '' !!}">
        {!! Form::label('kelas', 'Kelas') !!}

        {!! Form::select('kelas', App\Kelas::pluck('kelas', 'kelas')->all(), null, [
            'class' => 'form-control js-select2',
			'placeholder' => 'Pilih Kelas'
        ]) !!}
        {!! $errors->first('kelas', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="form-group has-feedback{{ $errors->has('angkatan') ? ' has-error' : '' }}">
        {!! Form::label('angkatan', 'Angkatan') !!}

        {!! Form::text('angkatan', null, ['class' => 'form-control', 'placeholder' => 'Angkatan']) !!}
        {!! $errors->first('angkatan', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="form-group has-feedback{{ $errors->has('ttl') ? ' has-error' : '' }}">
        {!! Form::label('ttl', 'TTL') !!}
        <div class='input-group date' id='ttl'>
            {!! Form::text('ttl', null, ['class' => 'form-control', 'placeholder' => 'TTL']) !!}
			<span class="input-group-addon">
				<span class="glyphicon glyphicon-calendar"></span>
			</span>
        </div>
        {!! $errors->first('ttl', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="form-group has-feedback{{ $errors->has('telp_ortu') ? ' has-error' : '' }}">
        {!! Form::label('telp_ortu', 'Telp Ortu') !!}

        {!! Form::text('telp_ortu', null, ['class' => 'form-control', 'placeholder' => 'Telp Ortu']) !!}
        {!! $errors->first('telp_ortu', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="form-group has-feedback{{ $errors->has('email') ? ' has-error' : '' }}">
        {!! Form::label('email', 'Email') !!}

        {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'Email']) !!}
        {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="form-group has-feedback{{ $errors->has('nama_pengguna') ? ' has-error' : '' }}">
        {!! Form::label('nama_pengguna', 'Nama Pengguna') !!}

        {!! Form::text('nama_pengguna', null, ['class' => 'form-control', 'placeholder' => 'Nama Pengguna']) !!}
        {!! $errors->first('nama_pengguna', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="form-group has-feedback{{ $errors->has('katasandi') ? ' has-error' : '' }}">
        {!! Form::label('katasandi', 'Kata Sandi') !!}

        {!! Form::text('katasandi', null, ['class' => 'form-control', 'placeholder' => 'Kata Sandi']) !!}
        {!! $errors->first('katasandi', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="form-group has-feedback{{ $errors->has('avatar') ? ' has-error' : '' }}">
        {!! Form::label('avatar', 'Foto Profil') !!}
        {!! Form::file('avatar', ['class' => 'form-control']) !!}
        <!-- <p class="help-block">Pilih foto profil</p> -->
        @if (isset($data) && $data->avatar)
            <p> {!! Html::image(asset('img/'.$data->avatar), null, ['class' => 'img-rounded img-responsive']) !!} </p>
        @endif
        {!! $errors->first('avatar', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="form-group row">
        <div class="col-sm-1"><b>Aktif</b></div>
            <div class="col-sm-10">
                <input type="checkbox" name="aktif" value=1>
            </div>
        </div>
    </div>
	

    <!-- <div class="form-group has-feedback{{ $errors->has('aktif') ? ' has-error' : '' }}">
        {!! Form::label('aktif', 'Aktif') !!}

        {!! Form::text('aktif', null, ['class' => 'form-control', 'placeholder' => 'Aktif']) !!}
        {!! $errors->first('aktif', '<p class="help-block">:message</p>') !!}
    </div> -->
<!-- /.box-body -->

<div class="box-footer">
    {!! Form::submit('Simpan', ['class' => 'btn btn-primary']) !!}
    
    <button type="button" class = "btn btn-batal" onclick="window.location='{{ route('siswa.index') }}'">Batal</button>
    <!-- {!! Form::submit('Kembali', ['class' => 'btn btn-primary1']) !!} -->
</div>
