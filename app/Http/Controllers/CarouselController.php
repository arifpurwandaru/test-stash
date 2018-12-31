<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Carousel;
use App\CommonResponse;
use App\Constants;
class CarouselController extends Controller {
    function getAll(){
        $resp = new CommonResponse();
        $resp -> data = Carousel::all();
        return response()->json($resp);
    }

    
    public function update(Request $request)
    {
        $resp = new CommonResponse();
        try{
            Carousel::findOrFail($request->input('id'))->update([
                'imgLink'=>$request->input('imgLink'),
                'imgLinkTemp'=>$request->input('imgLinkTemp'),
            ]);
            $resp -> respCode = Constants::RESP_SUCCESS_CODE;
            $resp -> respDesc = Constants::RESP_SUCCESS_DESC;
        }catch (\Exception $e) {
            $resp -> respCode = Constants::RESP_GENERAL_ERROR_CODE;
            $resp -> respDesc = $e->getMessage();
        }
        return response()->json($resp);
    }

}
