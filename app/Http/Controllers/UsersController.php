<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// 以下追加
use App\User;
use App\Cordinate;
use \Storage; 
use \InterventionImage;

class UsersController extends Controller
{
    // ゲストユーザの設定
    private const GUEST_USER_ID = 1;
    
    public function show($id)
    {
        // user_idの値でユーザを検索して取得
        $user = User::where('user_id',$id)->first();
        if(empty($user)) {
            return \App::abort(404);
        }
        
        // 関係するモデルの件数をロード
        $user->loadRelationshipCounts();

        // ユーザの投稿一覧を作成日時の降順で取得
        $cordinates = $user->cordinates()->orderBy('created_at', 'desc')->paginate(3);

        // ユーザ詳細ビューでそれを表示
        return view('users.show', compact( 'user', 'cordinates',));
    }
    
    public function edit($id)
    {
        // idの値でユーザを検索して取得
        $user = User::where('user_id',$id)->first();
        if(empty($user)) {
            return \App::abort(404);
        }
        
        //  認証済みユーザ（閲覧者）がそのユーザである場合は、編集画面を表示
        if (\Auth::id() === $user->id) {
            return view('users.edit', [
            'user' => $user,
            ]);
        } else { // 違う場合はindexへリダイレクト
            return redirect ('/');
        }
    }

    public function update(Request $request, $id)
    {
        // idの値でユーザを検索して取得
        $user = User::findOrFail($id);
        
        //アップデート時のバリデーション
        // ゲストユーザーログイン時は、ニックネームとプロフィール画像をバリデーションにかけない
        if(\Auth::id() == self::GUEST_USER_ID) {
            $request->validate([
                'bio' => ['string', 'max:200', 'nullable'],
                'height' => ['integer', 'digits:3'],
                'height_hidden' => ['boolean'],
                'age' => ['integer', 'digits_between:0,100'],
                'age_hidden' => ['boolean'],
                'sex' => ['string'],
                'sex_hidden' => ['boolean'],
                'homepage' => ['string', 'nullable'],
                'instagram' => ['string', 'nullable'],
                'twitter' => ['string', 'nullable'],
                'facebook' => ['string', 'nullable'],
            ]);
        // ゲストユーザー以外がログインしている時は、全てのユーザー情報をバリデーションにかける
        } else { 
            $request->validate([
                'name' => ['required','string', 'max:100'],
                'bio' => ['string', 'max:200', 'nullable'],
                'image' => ['file', 'mimes:jpeg,png,jpg,gif', 'max:2048', 'nullable'],
                'height' => ['integer', 'digits:3'],
                'height_hidden' => ['boolean'],
                'age' => ['integer', 'digits_between:0,100'],
                'age_hidden' => ['boolean'],
                'sex' => ['string'],
                'sex_hidden' => ['boolean'],
                'homepage' => ['string', 'nullable'],
                'instagram' => ['string', 'nullable'],
                'twitter' => ['string', 'nullable'],
                'facebook' => ['string', 'nullable'],
             ]);
            // プロフィール画像のアップロード
            $image = $user->image;
            
            if(!is_null($request->file('image'))) {
                
                $image = $request->file('image');
                $disk = Storage::disk('s3');
    
                // 画像の名前をユーザ名＋元ファイル拡張子にする
                $imageName = $user->user_id.'.'.$image->getClientOriginalExtension();
                // 画像をバケットのprofileフォルダに保存する
                $path = $disk->putFileAs('profile', $image, $imageName, 'public');
    
                $user->image = $path;
                $user->save();
            }
            $user->name = $request->name;
        }

        // ユーザを更新
        $user->bio = $request->bio;
        
        $user->height = $request->height;
        if($request->height_hidden !== null){
            $user->height_hidden = 1;
        } else {
            $user->height_hidden = 0;
        }
        
        $user->age = $request->age;
        if($request->age_hidden !== null){
            $user->age_hidden = 1;
        } else {
            $user->age_hidden = 0;
        }
        
        if($request->sex == 0){
            $user->sex = 0;
        }elseif($request->sex == 1){
            $user->sex = 1;   
        }
        if($request->sex_hidden !== null){
            $user->sex_hidden = 1;
        } else {
            $user->sex_hidden = 0;
        }
        
        $user->homepage = $request->homepage;
        $user->instagram = $request->instagram;
        $user->twitter = $request->twitter;
        $user->facebook = $request->facebook;
        $user->save();

        // 前画面へ戻る
        return redirect('users/'.$user->user_id);
    }
    
    public function destroy($id)
    {
        // idの値でユーザを検索して取得
        $user = User::findOrFail($id);
        
        // ユーザの投稿を検索して取得
        $userPosts = \App\Cordinate::where('user_id',$id)->get();
        if($userPosts !== null ){
            //ユーザの投稿を展開
            foreach($userPosts as $userPost) {
                //ユーザの投稿の画像をS3から削除
                $userPostImage = $userPost->image;
                Storage::disk('s3')->delete($userPostImage);
            }
        }
        
        //プロフィール画像をidの値で検索して取得
        $userImage = User::where('id',$id)->value('image');
        $userName = User::where('id',$id)->value('user_id');
        
        //プロフィール画像を削除
        if($userImage !== null) {
            //配列からファイル名を取得してS3から削除
            Storage::disk('s3')->delete('profile/'.$userName);
        }
        
        // 取得したユーザを削除
        $user->delete();
        
        // トップページへリダイレクトさせる
        return redirect('/')->with('deleted_message', 'アカウントの削除が完了しました');
    }
    
    /**
     * ユーザのフォロー一覧ページを表示するアクション。
     *
     * @param  $id  ユーザのid
     * @return \Illuminate\Http\Response
     */
    public function followings($id)
    {
        // user_idの値でユーザを検索して取得
        $user = User::where('user_id',$id)->first();
        if(empty($user)) {
            return \App::abort(404);
        }
        
        // 関係するモデルの件数をロード
        $user->loadRelationshipCounts();

        // ユーザのフォロー一覧を取得
        $followings = $user->followings()->paginate(10);

        // フォロー一覧ビューでそれらを表示
        return view('users.followings', [
            'user' => $user,
            'users' => $followings,
        ]);
    }

    /**
     * ユーザのフォロワー一覧ページを表示するアクション。
     *
     * @param  $id  ユーザのid
     * @return \Illuminate\Http\Response
     */
    public function followers($id)
    {
        // user_idの値でユーザを検索して取得
        $user = User::where('user_id',$id)->first();
        if(empty($user)) {
            return \App::abort(404);
        }
        
        // 関係するモデルの件数をロード
        $user->loadRelationshipCounts();

        // ユーザのフォロワー一覧を取得
        $followers = $user->followers()->paginate(10);

        // フォロワー一覧ビューでそれらを表示
        return view('users.followers', [
            'user' => $user,
            'users' => $followers,
        ]);
    }
    
    /**
     * ユーザのクリップ一覧ページを表示するアクション。
     *
     * @param  $id  ユーザのid
     * @return \Illuminate\Http\Response
     */
     public function favorites($id)
    {
        // user_idの値でユーザを検索して取得
        $user = User::where('user_id',$id)->first();
        if(empty($user)) {
            return \App::abort(404);
        }
        
        // 関係するモデルの件数をロード
        $user->loadRelationshipCounts();

        // ユーザのクリップ一覧を投稿作成日時の降順で取得
        $favorites = $user->favorites()->orderBy('created_at', 'desc')->paginate(3);

        // クリップ一覧ビューでそれらを表示
        return view('users.favorites', compact('user', 'favorites'));
    }
}
