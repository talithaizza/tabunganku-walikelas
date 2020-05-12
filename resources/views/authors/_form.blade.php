<div class="box-body">
    <div class="form-group has-feedback{{ $errors->has('name') ? ' has-error' : '' }}">
     {!! Form::label('name', 'Nama') !!}

    {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Nama penulis']) !!}
    {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<!-- /.box-body -->

<div class="box-footer">
    {!! Form::submit('Simpan', ['class' => 'btn btn-primary']) !!}
</div>
