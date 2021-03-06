<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FavoritesController extends Controller
{
    /**
     * 投稿をクリップするアクション。
     *
     * @param  $id  投稿のid
     * @return \Illuminate\Http\Response
     */
    public function store($id)
    {
        // 認証済みユーザ（閲覧者）が、 idの投稿をクリップする
        \Auth::user()->favorite($id);
        // 前のURLへリダイレクトさせる
        return back();
    }

    /**
     * 投稿のクリップを外すアクション。
     *
     * @param  $id  投稿のid
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // 認証済みユーザ（閲覧者）が、 idの投稿のクリップを外す
        \Auth::user()->unfavorite($id);
        // 前のURLへリダイレクトさせる
        return back();
    }
}
