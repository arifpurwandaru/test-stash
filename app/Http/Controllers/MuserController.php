<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Muser;
use App\CommonResponse;
use App\Constants;

class MuserController extends Controller{
    
    public function index(){
        return Muser::all();
    }

    public function login(Request $request){
        $email = $request->input('email');
        $password = $request -> input('password');

        $rsp = new CommonResponse();
        $rsp->respCode = "01";
        $rsp->respDesc = "User/Password Salah";
        
        $user = Muser::where('email',$email)->first();
        if(!empty($user)){
            if($user->password == $password){
                $rspn = new CommonResponse();
                $rspn->data = $user;
                $rspn->respCode = "00";
                $rspn->respDesc = "success";
                return response()->json($rspn);
            }else{
                return response()->json($rsp);
            }
        }else{
            return response()->json($rsp);
        }

    }
 
    public function show($loginid){
        $resp = new CommonResponse();
        try {
            $resp->respCode = Constants::RESP_SUCCESS_CODE;
            $resp->respDesc = Constants::RESP_SUCCESS_DESC;
            $resp->data = Muser::where('loginid',$loginid)->first();
            if($resp->data == null){
                $resp->respCode = Constants::RESP_DATA_NOTFOUND_CODE;
                $resp->respDesc = Constants::RESP_DATA_NOTFOUND_DESC;
            }
        } catch (\Illuminate\Database\QueryException $e) {
            $resp -> respCode = Constants::RESP_DB_ERROR_CODE;
            $resp -> respDesc = $e->getMessage();
        } catch (\Exception $e) {
            $resp -> respCode = Constants::RESP_GENERAL_ERROR_CODE;
            $resp -> respDesc = $e->getMessage();
        }
        return response()->json($resp);
    }

    public function store(Request $request)
    {
        $resp = new CommonResponse();
        try {
            $resp->respCode = Constants::RESP_SUCCESS_CODE;
            $resp->respDesc = Constants::RESP_SUCCESS_DESC;
            $resp->data = Muser::create($request->all());
        } catch (\Illuminate\Database\QueryException $e) {
            $resp -> respCode = Constants::RESP_DB_ERROR_CODE;
            $resp -> respDesc = $e->getMessage();
        } catch (\Exception $e) {
            $resp -> respCode = Constants::RESP_GENERAL_ERROR_CODE;
            $resp -> respDesc = $e->getMessage();
        }
        return response()->json($resp);
    }

    public function update(Request $request)
    {
        $resp = new CommonResponse();
        try{
            Muser::findOrFail($request->input('loginid'))->update([
                'username'=>$request->input('username'),
                'email'=>$request->input('email'),
                'alamat'=>$request->input('alamat'),
                'imgLink'=>$request->input('imgLink'),
            ]);
            $resp -> respCode = Constants::RESP_SUCCESS_CODE;
            $resp -> respDesc = Constants::RESP_SUCCESS_DESC;
        }catch (\Exception $e) {
            $resp -> respCode = Constants::RESP_GENERAL_ERROR_CODE;
            $resp -> respDesc = $e->getMessage();
        }
        return response()->json($resp);
    }

    public function delete($loginid)
    {
        $muser = Muser::where('loginid',$loginid)->firstOrFail();
        $muser->delete();

        return 204;
    }
}
