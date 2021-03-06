@extends('layouts.app')

@section('content')
    @include('commons.navbar')
    <div class="container">
        @include('commons.error_messages')
        <div class="row">
            <div class ="col-md-12">
                <a class="link text-dark" href="{{ route('cordinates.show',$cordinates->id) }}"><i class="fas fa-arrow-left mr-2"></i>編集せずに戻る</a>
                <p></p>
                <label class="upload-label btn btn-outline-dark rounded-pill mt-2">
                    画像を変更する
                    {!! Form::file('image',['accept' => '.png, .jpeg, .jpg, .gif','class' => 'file', 'form' => 'edit-cordinate']) !!}
                    {{ csrf_field() }}
                </label>
                <div class="row text-center">
                    <div class="col-md-6">
                        <h6>現在の画像</h6>
                        <img width="200px" src="https://mycloset-sakataran.s3-ap-northeast-1.amazonaws.com/{{ $cordinates->image }}">
                    </div>
                    <div class="col-md-6">
                        <h6>選択中の画像</h6>
                        <img id="preview" width="200px">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        {!! Form::label('text', 'コーディネート説明', ['class' => 'col-form-label']) !!}
                        {!! Form::textarea('text', $cordinates->text, ['class' => 'form-control border border-dark', 'rows' => '3', 'form' => 'edit-cordinate']) !!}
                    </div>
                    
                    <div class="form-group">
                    {!! Form::label('tags', 'タグ', ['class' => 'col-form-label']) !!}
                    {!! Form::text('tags', $tags, ['class' => 'form-control border border-dark', 'form' => 'edit-cordinate']) !!}
                    </div>
                </div>
                <a class="btn btn-block btn-outline-dark rounded-pill mb-2" href="{{ route('items.create', $cordinates->id) }}">続けて着用アイテムを編集する</a>
            {!! Form::model($cordinates, ['id' => 'edit-cordinate','route' => ['cordinates.update', $cordinates->id], 'method' => 'put', 'enctype' => 'multipart/form-data', 'autocomplete'=> 'off']) !!}
                {!! Form::submit('変更を完了する', ['class' => 'btn btn-block btn-outline-color rounded-pill']) !!}
            {!! Form::close() !!}
            </div>
        </div>  
    </div>
@endsection