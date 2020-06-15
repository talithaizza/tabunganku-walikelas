<div class="box-body">
    <div class="form-group has-feedback{{ $errors->has('kelas') ? ' has-error' : '' }}">
        {!! Form::label('kelas', 'Kelas') !!}

        {!! Form::text('kelas', null, ['class' => 'form-control', 'placeholder' => 'Kelas']) !!}
        {!! $errors->first('kelas', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="form-group has-feedback{{ $errors->has('wali_kelas') ? ' has-error' : '' }}">
        {!! Form::label('wali_kelas', 'Wali Kelas') !!}

        {!! Form::text('wali_kelas', null, ['class' => 'form-control', 'placeholder' => 'Wali Kelas']) !!}
        {!! $errors->first('wali_kelas', '<p class="help-block">:message</p>') !!}
    </div>   
</div>
    
<!-- /.box-body -->

<div class="box-footer">
    {!! Form::submit('Simpan', ['class' => 'btn btn-primary']) !!}

    <button type="button" class = "btn btn-batal" onclick="window.location='{{ route('kelas.index') }}'">Batal</button>
</div>
