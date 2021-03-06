@if (count($cordinates) > 0 )
<section class="scroll_area"
  data-infinite-scroll='{
    "path": ".pagination a[rel=next]",
    "append": ".post"
    }'
>
<div class="d-flex row post">
    @foreach ($cordinates as $cordinate)
    <div class="col-md-4 p-2 text-wrap">
        <div class="card">
            <div class="card-header media">
                @if($cordinate->user->image == null || $cordinate->user->image == '')
                    <div class="m-1">
                        <a class="link text-dark" href="{{ route('users.show', $cordinate->user->user_id) }}">
                        <i class="fas fa-user-circle fa-3x"></i>
                        </a>
                    </div>
                @else
                    <div class="m-1">
                        <a class="link" href="{{ route('users.show', $cordinate->user->user_id) }}">
                            <img class="resize-circle-index" src="https://mycloset-sakataran.s3-ap-northeast-1.amazonaws.com/{{ $cordinate->user->image }}">
                        </a>
                    </div>
                @endif
                <div class="media-body">
                    <div class="d-flex justify-content-between">
                        <div class="">
                                <h6 class="font-weigth-bold mt-2 mb-0">
                                {{ $cordinate->user->name}}
                                </h6>
                            @if ($cordinate->user->height_hidden != 1 && $cordinate->user->height !== null)
                                <small class="text-secondary d-flex align-items-start">{{ $cordinate->user->height }}cm</small>
                            @endif
                        </div>
                        {{-- フォローボタンの表示 --}}
                        <div class="col text-right mt-2">
                            @include('commons.follow_button')
                        </div>
                    </div>
                </div>
            </div>
                <a class="link" href="{{ route('cordinates.show', $cordinate->id) }}">
                    <img class="card-img resize" src="https://mycloset-sakataran.s3-ap-northeast-1.amazonaws.com/{{ $cordinate->image }}">
                </a>
            <div class="card-footer p-1">
                {{-- いいねボタンの表示 --}}
                @include('commons.nice_button')
                {{-- クリップボタンの表示 --}}
                @include('commons.clip_button')
            </div>
        </div>
    </div>
    @endforeach
</div>
</section>
<div class="hidden">
{{ $cordinates->appends(request()->input())->links() }}
</div>
@else
<div class="col-12">
    <div class="alert alert-secondary text-center" role="alert">
        <i class="far fa-sticky-note fa-5x"></i>
        <h2>該当する投稿はありません</h2>
    </div>
</div>
@endif