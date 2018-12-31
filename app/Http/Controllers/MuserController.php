<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Muser;
use App\CommonResponse;
use App\Constants;
use Mail;
use View;

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
            $this->sendEmail($request);
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

    public function updateDp(Request $request) {
        $resp = new CommonResponse();
        try{
            Muser::findOrFail($request->input('loginid'))->update([
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

    public function aktifasiUser($loginid)
    {
        $resp = new CommonResponse();
        try{
            Muser::findOrFail($loginid)->update([
                'status_user'=>"A",
            ]);
            
        }catch (\Exception $e) {
            return "Terjadi Kesalahan". $e->getMessage();
        }
        return "<h1 style='color:green;'>Aktifasi Berhasil<h1>";
    }
    public function delete($loginid)
    {
        $muser = Muser::where('loginid',$loginid)->firstOrFail();
        $muser->delete();

        return 204;
    }

    public function sendEmail(Request $request){

        Mail::send([], [], function ($message) use ($request)
        {

            $message->from('idoctor_noreply@zoho.com', 'Konfirmasi Pendaftaran iDoctor');

            $message->to($request->input('email'))
             ->subject("Verifikasi Email") 
            ->setBody("Dear ".$request->input('username').
                ",\r\n Klik link berikut untuk verifikasi: ".Constants::ENDPOINT_URL."api/muser/verifikasi/".$request->input('loginid'));

        });


        return response()->json(['message' => 'Request completed']);
    }

    public function gantipassword($param){
        return View::make('gantipass')->with('loginid', $param)->with('errs','');
    }

    public function updatePass(Request $request){
        if($request->password == $request->confirmPass){
            try{
                Muser::findOrFail($request->loginid)->update([
                    'password'=>$request->password,
                ]);
                
            }catch (\Exception $e) {
                return "<h4 style='color:red;'>Terjadi Kesalahan Server: </h4>". $e->getMessage();
            }
            return "<h1 style='color:green;'> Password Berhasil Dirubah </h1>";
        }else{
            return View::make('gantipass')->with('loginid', $request->loginid)->with('errs','Password Tidak Sesuai');
        }
        
    }

    public function kirimEmailGantiPassword($email){
        $usr = Muser::where('email',$email)->first();

        $resp = new CommonResponse();

        if($usr == null){
            $resp -> respCode = Constants::RESP_DATA_NOTFOUND_CODE;
            $resp -> respDesc = Constants::RESP_DATA_NOTFOUND_DESC;
        }else{
            
            Mail::send([], [], function ($message) use ($usr)
            {

                $message->from('idoctor_noreply@zoho.com', 'Perubahan Password');

                $message->to($usr->email)
                ->subject("Rubah Passwordmu") 
                ->setBody("Dear ".$usr->username.
                    ",\r\n Klik link berikut untuk Ganti Password: ".Constants::ENDPOINT_URL."api/muser/ganpas/".$usr->loginid);

            });

            $resp -> respCode = Constants::RESP_SUCCESS_CODE;
            $resp -> respDesc = Constants::RESP_SUCCESS_DESC;
        }

        return response()->json($resp);
    }










    public function testSendEmail2(){
        
        Mail::send([], [], function ($message) {

            $message->from('idoctor_noreply@zoho.com', 'Test Title');

            $message->to("arif.purwandaru@gmail.com")
            ->subject("Rubah Passwordmu") 
            ->setBody("Dear Klik link berikut untuk Ganti Password: ");

        });
    }
}
