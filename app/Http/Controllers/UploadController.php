<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function upload(Request $request) {
        $file = $request->file( 'file' );

        //logger( $file );
        //logger( $file->isValid() );
        //logger( $file->path() );
        //logger( $file->extension() );
        //logger( $file->getClientOriginalName() );
        //logger( $file->getClientMimeType() );

        $dir = date('y/m'); //20201212 날짜로 폴더 경로 생성
        // storage->app->public->upload 폴더에 저장됨
        $path = $file->store( 'uploads/' . $dir, 'public' );

        return response()->json( [ 'path' => $path ] );
    }
}
