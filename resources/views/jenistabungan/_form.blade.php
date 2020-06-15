<div class="box-body">
	<div class="form-group has-feedback{{ $errors->has('nama') ? ' has-error' : '' }}">
        {!! Form::label('nama', 'Nama Tabungan') !!}

        {!! Form::text('nama', null, ['class' => 'form-control', 'placeholder' => 'Nama Tabungan']) !!}
        {!! $errors->first('nama', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="form-group has-feedback{{ $errors->has('deskripsi') ? ' has-error' : '' }}">
        {!! Form::label('deskripsi', 'Deskripsi') !!}

        {!! Form::text('deskripsi', null, ['class' => 'form-control', 'placeholder' => 'Deskripsi']) !!}
        {!! $errors->first('deksripsi', '<p class="help-block">:message</p>') !!}
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

    <button type="button" class = "btn btn-batal" onclick="window.location='{{ route('jenistabungan.index') }}'">Batal</button>
</div>
