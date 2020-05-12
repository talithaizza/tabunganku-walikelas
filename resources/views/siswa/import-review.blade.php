@extends('layouts.app')

@section('dashboard')
   Buku
   <small>Review Buku</small>
@endsection

@section('breadcrumb')
   <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
   <li><a href="{{ url('/admin/books') }}">Buku</a></li>
   <li><a href="{{ url('/admin/books/create') }}">Tambah Buku</a></li>
   <li class="active">Review Buku</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Review Buku</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <p>
                        <a class="btn btn-success" href="{{ url('/admin/books') }}">Selesai</a>
                    </p>

                    <div class="responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Judul</th>
                                    <th>Penulis</th>
                                    <th>Jumlah</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($books as $book)
                                    <tr>
                                        <td>{{ $book->title }}</td>
                                        <td>{{ $book->author_name }}</td>
                                        <td>{{ $book->amount }}</td>
                                        <td>
                                            {!! Form::open(['url' => route('books.destroy', $book->id), 'id' => 'form-' . $book->id, 'method' => 'delete', 'data-confirm' => 'Yakin menghapus ' . $book->title . '?', 'class' => 'js-review-delete']) !!}

                                                {!! Form::submit('Hapus', ['class' => 'btn btn-danger']) !!}

                                            {!! Form::close() !!}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <p>
                        <a class="btn btn-success" href="{{ url('/admin/books') }}">Selesai</a>
                    </p>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection
