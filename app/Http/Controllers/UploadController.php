<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function upload(Request $request) {
        $file = $request->file( 'file' );

        logger( $file );
        logger( $file->isValid() );
        logger( $file->path() );
        logger( $file->extension() );

        return response()->json( [ 'message'=>'ok' ] );
    }
}
