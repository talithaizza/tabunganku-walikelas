<!-- /.box-body -->
<div class="box-body">
    <div class="form-group has-feedback{{ $errors->has('konfirmasi') ? ' has-error' : '' }}">
        {!! Form::label('konfirmasi', 'Ketik "Konfirmasi Ubah Status Siswa" untuk melakukan pengecekan atau pengubahan status siswa!') !!}

        {!! Form::text('konfirmasi', null, ['class' => 'form-control', 'placeholder' => 'Konfirmasi Ubah Status Siswa']) !!}
        {!! $errors->first('konfirmasi', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="col-sm-1"></div> 
</div>
<!-- /.box-body -->

<div class="box-footer">
    {!! Form::submit('Simpan', ['class' => 'btn btn-primary']) !!}
</div>
