<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mpasien;
use App\CommonResponse;
use App\Constants;

class MpasienController extends Controller{

    
    public function getByLoginId($loginid){
        $resp = new CommonResponse();
        try{
            $resp->respCode = Constants::RESP_SUCCESS_CODE;
            $resp->respDesc = Constants::RESP_SUCCESS_DESC;
            $resp->data = Mpasien::where('loginid',$loginid)->get();
            return response()->json($resp);
        }catch (\Illuminate\Database\QueryException $e) {
            $resp -> respCode = Constants::RESP_DB_ERROR_CODE;
            $resp -> respDesc = $e->getMessage();
            return response()->json($resp);
        } catch (\Exception $e) {
            $resp -> respCode = Constants::RESP_GENERAL_ERROR_CODE;
            $resp -> respDesc = $e->getMessage();
            return response()->json($resp);
        }
    }

    function getOne($pasienid){
        $resp = new CommonResponse();
        try{
            $resp->data = Mpasien::where('pasienid',$pasienid)->first();
            $resp->respCode = Constants::RESP_SUCCESS_CODE;
            $resp->respDesc = Constants::RESP_SUCCESS_DESC;
        }catch(\Illuminate\Database\QueryException $e){
            $resp->respCode = Constants::RESP_DB_ERROR_CODE;
            $resp->respDesc = $e->getMessage();
        }catch(\Exception $e){
            $resp->respCode = Constants::RESP_GENERAL_ERROR_CODE;
            $resp->respDesc = $e->getMessage();
        }
        return response()->json($resp);
    }

    function store(Request $request){
        $resp = new CommonResponse();
        try {
            $resp->respCode = Constants::RESP_SUCCESS_CODE;
            $resp->respDesc = Constants::RESP_SUCCESS_DESC;
            Mpasien::create($request->all()); 
            return response()->json($resp);
        } catch (\Illuminate\Database\QueryException $e) {
            $resp -> respCode = Constants::RESP_DB_ERROR_CODE;
            $resp -> respDesc = $e->getMessage();
            return response()->json($resp);
        } catch (\Exception $e) {
            $resp -> respCode = Constants::RESP_GENERAL_ERROR_CODE;
            $resp -> respDesc = $e->getMessage();
            return response()->json($resp);
        }

    }

    function update(Request $request){
        $resp = new CommonResponse();
        try{
            Mpasien::findOrFail($request->input('pasienid'))->update([
                'nama'=>$request->input('nama'),
                'jenisKelamin'=>$request->input('jenisKelamin'),
                'nik'=>$request->input('nik'),
                'email'=>$request->input('email'),
                'tglLahir'=>$request->input('tglLahir'),
                'golonganDarah'=>$request->input('golonganDarah'),
                'alamatLengkap'=>$request->input('alamatLengkap'),
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


    
    function updateDp(Request $request){
        $resp = new CommonResponse();
        try{
            Mpasien::findOrFail($request->input('pasienid'))->update([
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
