@extends('layouts.app')

@section('content')
    @include('commons.navbar')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <img class="resize mb-2" src="https://mycloset-sakataran.s3-ap-northeast-1.amazonaws.com/{{ $cordinates->image }}">
                <div class="row mb-2">
                    <div class="mb-2">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-outline-color rounded-pill mr-2"><i class="fas fa-heart"></i></button><span>xxxスキ！</span>
                            <button type="button" class="btn btn-outline-dark rounded-pill mr-2"><i class="fas fa-paperclip"></i></button><span>xxxクリップ</span>
                        </div>
                    </div>
            </div>
                </div>
            <div class="col-md-6">
                <div class="row mb-2">
                    <div class="col-md-12">
                        <div class="d-flex  justify-content-between">
                            <div class="media">
                                <a class="link" href="{{ route('users.show', $cordinates->user->user_id) }}">
                                <img class="resize-circle-show mr-3" src="https://mycloset-sakataran.s3-ap-northeast-1.amazonaws.com/{{ $cordinates->user->image }}">
                                </a>
                                <div class="media-body">
                                    <h5 class="mt-0">{{ $cordinates->user->user_id }}</h5>
                                    {{ $cordinates->user->name }}
                                </div>
                            </div>
                            @if (Auth::id() == $cordinates->user_id)
                            <div class="dropdown">
                              <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="{{ route('cordinates.edit', $cordinates->id) }}">この投稿を編集する</a>
                                    <div class="dropdown-divider"></div>
                                    {!! Form::model($cordinates, ['route' => ['cordinates.destroy', $cordinates->id], 'method' => 'delete', 'onclick' => 'return confirm("本当に削除しますか？");']) !!}
                                        {!! Form::submit('この投稿を削除する', ['class' => 'dropdown-item']) !!}
                                    {!! Form::close() !!}
                                </div>
                            </div>
                            </div>
                            @endif
                            <hr>
                            <div class="mb-2">
                                {!! nl2br($cordinates->text) !!}
                                <div class="text-secondary">Posted by {{ $cordinates->created_at }}</div>
                            </div>
                            <div>
                                <button type="button" class="btn btn-outline-secondary btn-sm">#tag</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm">#tag</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm">#tag</button>
                            </div>
                        </div>
                    </div>    
                <div class="row bg-light">
                    <div class="col-md-12">
                        <h6>着用アイテム</h6>
                    </div>
                    <div class="col-md-12">
                        <span>大カテゴリ　＞　中カテゴリ　＞　小カテゴリ</span>
                        <p></p>
                        <span>ブランド名</span><span>サイズ</span>
                        <p></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <span>xxxコメント</span>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
@endsection