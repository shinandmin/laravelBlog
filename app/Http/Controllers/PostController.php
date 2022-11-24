<?php

namespace App\Http\Controllers;

use App\Models\Post;  // 사용할 모델 파일 use 처리
use http\Env\Response;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index() {
        //Post 모델의 전체값 출력
        //return response()->json( Post::all());

        $posts = Post::orderBy( 'created_at', 'desc' )->get();
        return response()->json( $posts );  // Post 모델의 전체값 출력
    }

    // 입력 받을때는 request가 무조건 들어간다.
    public function create( Request $request ) {
        /*
        // 폼 검증
        $request->validate([
            'subject' => 'required',
            'content' => 'required',
        ]);
        */

        $subject = $request->input( 'subject' );
        $content = $request->input( 'content' );

        // orm 방식으로 insert 하는 방법
        $post = new Post();     // post 모델의 class화
        $post->subject = $subject;
        $post->content = $content;
        $post->save();          // 이렇게하면 insert 된다. (저장)

        return response()->json( $post );
    }

    public function read( $id ) {
        //$post = Post::where('id', $id)->get();  // 조건에 맞는 전체 데이터
        //$post = Post::where('id', $id)->first();  // 조건에 맞는 첫 번째 데이터
        $post = Post::find( $id ); // 조건에 맞는 첫 번째 데이터를 구하는 축약식
        return response()->json( $post );
    }

    public function update( Request $request, $id ) {
        // id값으로 post 가져오기
        $post = Post::find( $id );

        // id 값에 해당하는 $post가 없는 경우 예외 처리
        if ( !$post ) {
            return response()
                ->json( [ 'message' => '조회할 데이터가 없습니다.' ], 404 );
        }

        // request로 요청값 받기
        $subject = $request->input( 'subject' );
        $content = $request->input( 'content' );

        // 값이 넘어온 것만 업데이트 처리
        if ( $subject ) $post->subject = $subject;
        if ( $content ) $post->content = $content;

        // 데이터 저장
        $post->save();

        // 응답
        return response()->json( $post );
    }

    public function delete ( $id ) {
        // id값으로 post 가져오기
        $post = Post::find( $id );

        // id 값에 해당하는 $post가 없는 경우 예외 처리
        if ( !$post ) {
            return response()
                ->json( [ 'message' => '삭제할 데이터가 없습니다.' ], 404 );
        }

        $post->delete();

        return response()->json([ 'message' => '삭제되었습니다.']);
    }
}
