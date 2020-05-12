<div class="box-body">
    <div class="form-group has-feedback{{ $errors->has('avatar') ? ' has-error' : '' }}">
        {!! Form::label('avatar', 'Foto Profil') !!}
        {!! Form::file('avatar', ['class' => 'form-control']) !!}
        <p class="help-block">Pilih foto profil</p>
        @if (isset($data) && $data->avatar)
            <p> {!! Html::image(asset('img/'.$data->avatar), null, ['class' => 'img-rounded img-responsive']) !!} </p>
        @endif
        {!! $errors->first('avatar', '<p class="help-block">:message</p>') !!}
    </div>

    <!-- <div class="form-group has-feedback{{ $errors->has('aktif') ? ' has-error' : '' }}">
        {!! Form::label('aktif', 'Aktif') !!}

        {!! Form::text('aktif', null, ['class' => 'form-control', 'placeholder' => 'Aktif']) !!}
        {!! $errors->first('aktif', '<p class="help-block">:message</p>') !!}
    </div> -->
<!-- /.box-body -->


    <div class="box-footer">
        {!! Form::submit('Simpan', ['class' => 'btn btn-primary']) !!}
    </div>
</div>