<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    //
    public function signUp(Request $request) {
        $params = $request->only([ 'name', 'email', 'password' ]);

        // 패스워드 암호화
        $params[ 'password' ] = bcrypt( $params[ 'password' ] );
        // $name = $request->input( 'name' );
        // $name = $request->input( 'email' );
        // $name = $request->input( 'password' );

        $user = User::create($params);
        return response()->json($user);

    }

    public function signIn(Request $request) {
        $params = $request->only([ 'email', 'password' ]);

        // Auth::attempt( $params )  :: DB에 해당 값이 있는지 체크해 줌
        // 1. 이메일과 일치하는 사용자 찾기
        // 2. 비밀번호 암호화 확인
        // 3. 로그인된 사용자 정보 반환
        if( Auth::attempt( $params ) ) {
            $user = User::where( 'email', $params[ 'email' ] )->first();
            // 앱키를 이용해 토큰 생성
            $token = $user->createToken(env('APP_KEY'));
            //logger($token);
            return response()->json( [
                'user' => $user,
                'token' => $token->plainTextToken,
                ] );
            return response()->json( $user );
        } else {
            return response()->json(['massage' => '로그인 정보를 확인하세요'], 400 );
        }
    }
}
