<div class="box-body">
    <div class="form-group has-feedback{{ $errors->has('title') ? 'has-error' : '' }}">
        {!! Form::label('template', 'Gunakan template terbaru', ['class' => 'col-sm-4 control-label']) !!}

        <div class="col-sm-8">
            <a class="btn btn-success" href="{{ route('template.books') }}"><i class="fa fa-cloud-download"></i> Download</a>
        </div>
    </div>

    <div class="form-group has-feedback{{ $errors->has('excel') ? 'has-error' : '' }}">
        {!! Form::label('excel', 'Pilih file', ['class' => 'col-sm-4 control-label']) !!}

        <div class="col-sm-8">
            {!! Form::file('excel', ['class' => 'form-control']) !!}
            {!! $errors->first('excel', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>
<!-- /.box-body -->

<div class="box-footer">
    {!! Form::submit('Simpan', ['class' => 'btn btn-primary']) !!}
</div>
