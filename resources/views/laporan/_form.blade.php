<div class="box-body">
    <div class="form-group has-feedback{{ $errors->has('tanggal_awal') ? ' has-error' : '' }}">
        {!! Form::label('tanggal_awal', 'Dari Tanggal') !!}

        <!-- {!! Form::text('tanggal_awal', null, ['class' => 'form-control', 'placeholder' => 'Dari Tanggal, ex: 2000-01-01']) !!} -->
        <div class='input-group date' id='ttl'>
            {!! Form::text('tanggal_awal', null, ['class' => 'form-control', 'placeholder' => 'Dari Tanggal, ex: 2000-01-01']) !!}
			<span class="input-group-addon">
				<span class="glyphicon glyphicon-calendar"></span>
			</span>
        </div>  
        {!! $errors->first('tanggal_awal', '<p class="help-block">:message</p>') !!}
    </div>

    <div class="form-group has-feedback{{ $errors->has('tanggal_akhir') ? ' has-error' : '' }}">
        {!! Form::label('tanggal_akhir', 'Sampai Tanggal') !!}

        <!-- {!! Form::text('tanggal_akhir', null, ['class' => 'form-control', 'placeholder' => 'Sampai Tanggal, ex: 2000-01-01']) !!} -->
        <div class='input-group date'>
        
            {!! Form::text('tanggal_akhir', null, ['class' => 'form-control', 'placeholder' => 'Sampai Tanggal, ex: 2000-01-01']) !!}
			<span class="input-group-addon">
				<span class="glyphicon glyphicon-calendar"></span>
			</span>
        </div>        
        {!! $errors->first('tanggal_akhir', '<p class="help-block">:message</p>') !!}
    </div>

    <div class="form-group has-feedback{{ $errors->has('jenistab') ? ' has-error' : '' }}">
        {!! Form::label('jenistab', 'Jenis Tabungan') !!}

        <!-- {!! Form::text('jenistab', null, ['class' => 'form-control', 'placeholder' => 'Jenis Tabungan']) !!} -->
        {!! Form::select('jenis_tabungan', App\JenisTabungan::pluck('nama', 'nama')->all(), null, [
            'class' => 'form-control js-select2',
			'placeholder' => 'Jenis Tabungan'
        ]) !!}
        {!! $errors->first('jenistab', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<!-- /.box-body -->

<div class="box-footer">
    {!! Form::submit('Tampil', ['class' => 'btn btn-primary']) !!}

    {!! Form::submit('Reset', ['class' => 'btn btn-batal']) !!}
</div>
