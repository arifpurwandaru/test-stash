<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mjadwal;
use App\CommonResponse;
use App\Constants;
use App\MjadwalParent;

class MjadwalController extends Controller
{
    function index(){
        $resp = new CommonResponse();
        try{
            $resp->respCode = Constants::RESP_SUCCESS_CODE;
            $resp->respDesc = Constants::RESP_SUCCESS_DESC;
            $resp->data = MjadwalParent::all();
        }catch (\Illuminate\Database\QueryException $e) {
            $resp -> respCode = Constants::RESP_DB_ERROR_CODE;
            $resp -> respDesc = $e->getMessage();
        } catch (\Exception $e) {
            $resp -> respCode = Constants::RESP_GENERAL_ERROR_CODE;
            $resp -> respDesc = $e->getMessage();
        }
        
        return response()->json($resp);
    }

    function getOneWithChildren($parentId){
        $resp = new CommonResponse();
        try{
            $parent = MjadwalParent::with('jadwals')->where('id',$parentId)->first();
            $resp->data = $parent;
            $resp->respCode = Constants::RESP_SUCCESS_CODE;
        }catch (\Illuminate\Database\QueryException $e) {
            $resp -> respCode = Constants::RESP_DB_ERROR_CODE;
            $resp -> respDesc = $e->getMessage();
        } catch (\Exception $e) {
            $resp -> respCode = Constants::RESP_GENERAL_ERROR_CODE;
            $resp -> respDesc = $e->getMessage();
        }
        return response()->json($resp);
    }

    function deleteAndGenocide($parentId){
        $resp = new CommonResponse();
        try{
            $parent = MjadwalParent::with('jadwals')->where('id',$parentId)->first();
            foreach ($parent->jadwals as $jadwal) {
                $jadwal->delete();
            }
            $parent->delete(); 
            $resp->respCode = Constants::RESP_SUCCESS_CODE;
            $resp->respDesc = Constants::RESP_SUCCESS_DESC;
            $resp->data = MjadwalParent::all();
        }catch (\Illuminate\Database\QueryException $e) {
            $resp -> respCode = Constants::RESP_DB_ERROR_CODE;
            $resp -> respDesc = $e->getMessage();
        } catch (\Exception $e) {
            $resp -> respCode = Constants::RESP_GENERAL_ERROR_CODE;
            $resp -> respDesc = $e->getMessage();
        }
        return response()->json($resp);
    }

    function store(Request $request){
       return Mjadwal::create($request->all()); 
    }

    function saveParent(Request $request){
        $resp = new CommonResponse();
        $resp->data = $request->data;
        try{
            $resp->respCode = Constants::RESP_SUCCESS_CODE;
            $resp->respDesc = Constants::RESP_SUCCESS_DESC;

            $parent = $request->data;
            $savedParent = MjadwalParent::create($parent);
            $jadwals = [];
            foreach ($request->data['jadwals'] as $jdw) {
                $jadwals[] = new Mjadwal($jdw);
            }
            $savedParent->jadwals()->saveMany($jadwals);
            $savedParent->jadwals = $jadwals;
            $resp->data = $savedParent;
        }catch (\Illuminate\Database\QueryException $e) {
            $resp -> respCode = Constants::RESP_DB_ERROR_CODE;
            $resp -> respDesc = $e->getMessage();
        } catch (\Exception $e) {
            $resp -> respCode = Constants::RESP_GENERAL_ERROR_CODE;
            $resp -> respDesc = $e->getMessage();
        }
        return response()->json($resp);
    }


    
    function getAllMjadwal(){
        $resp = new CommonResponse();
        try{
            $resp->respCode = Constants::RESP_SUCCESS_CODE;
            $resp->respDesc = Constants::RESP_SUCCESS_DESC;
            $resp->data = Mjadwal::all();
        }catch (\Illuminate\Database\QueryException $e) {
            $resp -> respCode = Constants::RESP_DB_ERROR_CODE;
            $resp -> respDesc = $e->getMessage();
        } catch (\Exception $e) {
            $resp -> respCode = Constants::RESP_GENERAL_ERROR_CODE;
            $resp -> respDesc = $e->getMessage();
        }
        
        return response()->json($resp);
    }

    function getByTgl($tgl){
        $resp = new CommonResponse();
        try{
            $mj = Mjadwal::where('tanggal',$tgl)->get();
            if($mj->isEmpty()){
                $resp->respCode = Constants::RESP_DATA_NOTFOUND_CODE;
                $resp->respDesc = Constants::RESP_DATA_NOTFOUND_DESC;
            }else{
                $resp->respCode = Constants::RESP_SUCCESS_CODE;
                $resp->respDesc = Constants::RESP_SUCCESS_DESC;
            }
        }catch (\Illuminate\Database\QueryException $e) {
            $resp -> respCode = Constants::RESP_DB_ERROR_CODE;
            $resp -> respDesc = $e->getMessage();
        } catch (\Exception $e) {
            $resp -> respCode = Constants::RESP_GENERAL_ERROR_CODE;
            $resp -> respDesc = $e->getMessage();
        }
        return response()->json($resp);
    }
}
