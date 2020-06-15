<div class="box-body">
    <div class="form-group has-feedback{{ $errors->has('nis') ? ' has-error' : '' }}">
        {!! Form::label('nis', 'NIS') !!}

        {!! Form::text('nis', null, ['class' => 'form-control', 'placeholder' => 'NIS']) !!}
        {!! $errors->first('nis', '<p class="help-block">:message</p>') !!}
    </div>

    <div class="form-group has-feedback{{ $errors->has('jenis_tabungan') ? ' has-error' : '' }}">
        {!! Form::label('jenis_tabungan', 'Jenis Tabungan') !!}

        <!-- {!! Form::text('jenis_tabungan', null, ['class' => 'form-control', 'placeholder' => 'Jenis Tabungan']) !!} -->
        {!! Form::select('jenis_tabungan', App\JenisTabungan::pluck('nama', 'nama')->all(), null, [
            'class' => 'form-control js-select2',
			'placeholder' => 'Jenis Tabungan'
        ]) !!}
        {!! $errors->first('jenis_tabungan', '<p class="help-block">:message</p>') !!}
    </div>

    <div class="form-group has-feedback{{ $errors->has('nominal') ? ' has-error' : '' }}">
        {!! Form::label('nominal', 'Nominal') !!}

        {!! Form::text('nominal', null, ['class' => 'form-control', 'placeholder' => 'Nominal, ex: 100000']) !!}
        {!! $errors->first('nominal', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<!-- /.box-body -->

<div class="box-footer">
    {!! Form::submit('Simpan', ['class' => 'btn btn-primary']) !!}

    <!-- {!! Form::submit('Reset', ['class' => 'btn btn-batal']) !!} -->
</div>
