<header class="mb-4">
    <nav class="navbar navbar-expand-sm navbar-light bg-light fixed-top ">
        {{-- トップページへのリンク --}}
        <a class="logo navbar-brand" href="/"><i class="fas fa-hashtag color mr-2"></i>My closet</a>
        {{-- 検索フォーム --}}
        <div class="none">
            <form class="form-inline" action="{{ url('/search')}}" method="post">
                {{ csrf_field()}}
                {{method_field('get')}}
                <div class="form-group">
                <input type="text" class="form-control bg-light" name="keyword"  value="{{request('keyword')}}" placeholder="キーワードやタグで検索">
                </div>
                <button type="submit" class="btn"><i class="fas fa-search"></i></button>
            </form>
        </div>
        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#nav-bar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="nav-bar">
            <ul class="navbar-nav mr-auto"></ul>
            <ul class="navbar-nav">
                <div class="appear">
                <li class="nav-item p-2">
                    <form class="form-inline" action="{{ url('/search')}}" method="post">
                        {{ csrf_field()}}
                        {{method_field('get')}}
                        <div class="form-group">
                        <input type="text" class="form-control bg-light" name="keyword"  value="{{request('keyword')}}" placeholder="キーワードで検索">
                        </div>
                        <button type="submit" class="btn"><i class="fas fa-search"></i></button>
                    </form>
                </li>
                </div>
                @if(Auth::check())
                {{-- マイページへのリンク --}}
                <li class="nav-item p-2">{!! link_to_route('users.show', 'マイページ', ['user' => Auth::user()->user_id ], ['class' => 'link text-dark']) !!}</li>
                {{-- ログアウト --}}
                <li class="nav-item p-2">{!! link_to_route('logout.get', 'ログアウト',[], ['class' => 'link text-dark']) !!}</li>
                @else
                @endif
            </ul>
        </div>
    </nav>
</header>