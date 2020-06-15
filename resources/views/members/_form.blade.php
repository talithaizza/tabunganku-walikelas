<div class="box-body">
    <div class="form-group has-feedback{{ $errors->has('name') ? ' has-error' : '' }}">
        {!! Form::label('name', 'Nama') !!}

        {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Nama member']) !!}
        {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
    </div>

    <div class="form-group has-feedback{{ $errors->has('email') ? ' has-error' : '' }}">
        {!! Form::label('email', 'Email') !!}

        {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'Email member']) !!}
        {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<!-- /.box-body -->

<div class="box-footer">
    {!! Form::submit('Simpan', ['class' => 'btn btn-primary']) !!}
</div>
