<?php

namespace App\Http\Controllers;

use App\Models\Post;  // 사용할 모델 파일 use 처리

use http\Env\Response;
use Illuminate\Http\Request;
use App\Http\Requests\PostRequest;

class PostController extends Controller
{
    public function index() {
        //Post 모델의 전체값 출력
        //return response()->json( Post::all());

        $posts = Post::orderBy( 'created_at', 'desc' )
            ->with(['user', 'categories', 'comments', 'comments.user'])
            ->paginate(10);

        return response()->json( $posts ); // Post 모델의 전체값 출력
    }

    // 입력 받을때는 request가 무조건 들어간다.
    public function create( Request $request ) {

        // 상단의 use App\Http\Requests\PostRequest; 를 통해 폼 검증이 진행된다.

//        $subject = $request->input( 'subject' );
//        $content = $request->input( 'content' );
        $params = $request->only( [ 'subject', 'content' ] );
        $params[ 'user_id' ] = $request->user()->id;
        $ids = $request->input( 'category_ids' );

//        // orm 방식으로 insert 하는 방법 1
//        $post = new Post();     // post 모델의 class화
//        $post->subject = $subject;
//        $post->content = $content;Z
//        $post->save();          // 이렇게하면 insert 된다. (저장)

        // orm 방식으로 insert 하는 방법 2
//        $post = new Post();
//        Post::create([
//            'subject' => $subject,
//            'content' => $content,
//        ]);

        // orm 방식으로 insert 하는 방법 3
        $post = Post::create($params);
        // 카테고리와 포스트를 연관 지어준다
        // attach, detach, syn
        $post->categories()->sync($ids);
        $result = Post::where( 'id', $post->id )
            ->with( [ 'user', 'categories' ] )
            ->first();
        return response()->json( $result );
    }

    public function read( $id ) {
        //$post = Post::where('id', $id)->get();  // 조건에 맞는 전체 데이터
        $post = Post::where('id', $id)
            ->with( 'comments', 'comments.user', 'user' )
            ->first();  // 조건에 맞는 첫 번째 데이터
        //$post = Post::find( $id ); // 조건에 맞는 첫 번째 데이터를 구하는 축약식
        return response()->json( $post );
    }

    public function update( Request $request, $id ) {
        // id값으로 post 가져오기
        $post = Post::find( $id );
        $ids = $request->input( 'category_ids' );

        // id 값에 해당하는 $post가 없는 경우 예외 처리
        if ( !$post ) {
            return response()
                ->json( [ 'message' => '조회할 데이터가 없습니다.' ], 404 );
        }

        // 인증 확인
        $user = $request->user();
        if( $user->id !== $post->user_id ) {
            return response()
                -> json( [ 'message' => '권한이 없습니다.' ], 403 );
        }

        // request로 요청값 받기
        $subject = $request->input( 'subject' );
        $content = $request->input( 'content' );

        // 값이 넘어온 것만 업데이트 처리
        if ( $subject ) $post->subject = $subject;
        if ( $content ) $post->content = $content;

        // 데이터 저장
        $post->save();
        $post->categories()->sync($ids);

        // 응답 json
        return response()->json( $post );
    }

    public function delete (Request $request, $id ) {
        // Post::where( 'id', $id )->delete();
        // id값으로 post 가져오기
        $post = Post::find( $id );

        // id 값에 해당하는 $post가 없는 경우 예외 처리
        if ( !$post ) {
            return response()
                ->json( [ 'message' => '삭제할 데이터가 없습니다.' ], 404 );
        }

        // 인증 확인
        $user = $request->user();
        if( $user->id !== $post->user_id ) {
            return response()
                -> json( [ 'message' => '권한이 없습니다.' ], 403 );
        }

        $post->delete();

        return response()->json([ 'message' => '삭제되었습니다.']);
    }
}
