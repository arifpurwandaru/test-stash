<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mjadwal;
use App\CommonResponse;
use App\Constants;
use App\MjadwalParent;
use App\SesiPemeriksaan;
use Illuminate\Support\Facades\Log;
use \Datetime;
class MjadwalController extends Controller
{
    function simpanJadwal(Request $request){
        $resp = new CommonResponse();
        $resp->respCode = Constants::RESP_SUCCESS_CODE;
        $resp->respDesc = Constants::RESP_SUCCESS_DESC;
        try{
            //Validasi
            $datas = $request->datas;
            foreach($datas as $ramane){
                $sesip = $ramane['sesis'][0];
                try{
                    $setar = new DateTime($sesip['mulai']);
                    $eeen = new DateTime($sesip['selesai']);
                    if($setar >= $eeen){
                        $resp->respCode = Constants::RESP_SESI_JAMKEBALIK_CODE;
                        $resp->respDesc = Constants::RESP_SESI_JAMKEBALIK_DESC;
                        return response()->json($resp);
                    }
                }catch(\Exception $e){
                    $resp->respCode = Constants::RESP_SESI_JAMNGAWUR_CODE;
                    $resp->respDesc = Constants::RESP_SESI_JAMNGAWUR_DESC;
                    return response()->json($resp);
                }
                    
                    $cil = SesiPemeriksaan::where("mjadwal_parent_id",$ramane['id'])->get();
                    if($cil !=null && !$cil->isEmpty()){
                        foreach($cil as $sesi){
                            if($this->checkHourBetween($sesi->mulai,$sesi->selesai,$sesip['mulai'])
                            || $this->checkHourBetween($sesi->mulai,$sesi->selesai,$sesip['selesai'])
                            || $this->checkHourBetween($sesip['mulai'],$sesip['selesai'], $sesi->mulai)
                            || $this->checkHourBetween($sesip['mulai'],$sesip['selesai'], $sesi->selesai)
                            || $sesip['mulai'] == $sesi->mulai || $sesip['selesai']==$sesi->selesai
                            ){
                                $resp->respCode = Constants::RESP_SESI_EXIST_CODE;
                                $resp->respDesc = Constants::RESP_SESI_EXIST_DESC;
                                //$resp->data = $cil;
                                return response()->json($resp);
                            } 
                        }
                    }
            }

            //Lolos Validasi
            foreach($datas as $jangkrikbos){
                $jamb = MjadwalParent::where("id",$jangkrikbos['id'])->first();
                if($jamb == null){
                    MjadwalParent::create($jangkrikbos);
                }
                $bledug = $jangkrikbos['sesis'][0];
                SesiPemeriksaan::create($bledug);
            }

        }catch (\Illuminate\Database\QueryException $e) {
            $resp -> respCode = Constants::RESP_DB_ERROR_CODE;
            $resp -> respDesc = $e->getMessage();
            Log::error($e);
        } catch (\Exception $e) {
            $resp -> respCode = Constants::RESP_GENERAL_ERROR_CODE;
            $resp -> respDesc = $e->getMessage();
            Log::error($e);
        }
        
        return response()->json($resp);
    }
     function checkHourBetween($start,$end,$tryme){
        $startDt = new DateTime($start);
        $endDt = new DateTime($end);
        $trymeDt = new DateTime($tryme);
        if($trymeDt > $startDt && $trymeDt < $endDt){
            return true;
        }else{
            return false;
        }
     }

     function getOneWithChildren($parentId){
        $resp = new CommonResponse();
        try{
            $result =  MjadwalParent::with(['sesis' => function ($q) {
                $sekarang = new DateTime();
                $q->orderBy('mulai', 'asc');
              }])->where('id',$parentId)->first();
            $resp->respCode = Constants::RESP_SUCCESS_CODE;
            $resp->data = $result;
            if($result == null){
                $resp->respCode = Constants::RESP_DATA_NOTFOUND_CODE;
                $resp->respDesc = Constants::RESP_DATA_NOTFOUND_DESC;
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

    function getSesiByParentFilterByJam($parentId){
        $resp = new CommonResponse();
        try{
            $result =  MjadwalParent::with(['sesis' => function ($q) {
                $sekarang = new DateTime();
                $q->where('selesai','>',$sekarang->format('H:i'));
                $q->orderBy('mulai', 'asc');
              }])->where('id',$parentId)->first();
            $resp->respCode = Constants::RESP_SUCCESS_CODE;
            $resp->data = $result;
            if($result == null){
                $resp->respCode = Constants::RESP_DATA_NOTFOUND_CODE;
                $resp->respDesc = Constants::RESP_DATA_NOTFOUND_DESC;
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

    function index(){
        $resp = new CommonResponse();
        try{
            $resp->respCode = Constants::RESP_SUCCESS_CODE;
            $resp->respDesc = Constants::RESP_SUCCESS_DESC;
            /* foreach($listparent as $rmn){
                $rmn->sesis = SesiPemeriksaan::where('mjadwal_parent_id',$rmn->id)->get();
            } */
            $resp->data = MjadwalParent::with('sesis')->get();
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
            $parent = MjadwalParent::with('jadwals','sesis')->where('id',$parentId)->first();
            foreach ($parent->jadwals as $jadwal) {
                $jadwal->delete();
            }
            foreach($parent->sesis as $sesip){
                $sesip->delete();
            }
            
            $parent->delete(); 
            $resp->respCode = Constants::RESP_SUCCESS_CODE;
            $resp->respDesc = Constants::RESP_SUCCESS_DESC;
            $resp->data = MjadwalParent::with('sesis')->get();
        }catch (\Illuminate\Database\QueryException $e) {
            $resp -> respCode = Constants::RESP_DB_ERROR_CODE;
            $resp -> respDesc = $e->getMessage();
        } catch (\Exception $e) {
            $resp -> respCode = Constants::RESP_GENERAL_ERROR_CODE;
            $resp -> respDesc = $e->getMessage();
        }
        return response()->json($resp);
    }

    
    function deleteSesi($sesiId){
        $resp = new CommonResponse();
        try{
            $zesi = SesiPemeriksaan::where('id',$sesiId)->first();
            $parentId = $zesi->mjadwal_parent_id;
            $zesi->delete(); 
            $resp->respCode = Constants::RESP_SUCCESS_CODE;
            $resp->respDesc = Constants::RESP_SUCCESS_DESC;
            $resp->data = MjadwalParent::with('sesis')->where('id',$parentId)->first();
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
