<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tpendaftaran;
use App\Tagihan;
use App\Sysconfig;
use App\CommonResponse;
use App\Constants;
use \Datetime;
use DB;

class TagihanController extends Controller
{
    function inquiry(){ 
        $resp = new CommonResponse();
        try{
            $sysconfig = Sysconfig::where('configKey','FEE_PASIEN')->first();
            $date = new Datetime('now');
            $periodes = [
                $this->cekTagihan($date->modify('-1 month')->format('Y-m'),$sysconfig->configValue),
                $this->cekTagihan($date->modify('-1 month')->format('Y-m'),$sysconfig->configValue),
                $this->cekTagihan($date->modify('-1 month')->format('Y-m'),$sysconfig->configValue),
                $this->cekTagihan($date->modify('-1 month')->format('Y-m'),$sysconfig->configValue)
            ];
            $resp->data = $periodes;
            
            $resp->respCode=Constants::RESP_SUCCESS_CODE;
            $resp->respDesc=Constants::RESP_SUCCESS_DESC;
        } catch (\Illuminate\Database\QueryException $e) {
            $resp -> respCode = Constants::RESP_DB_ERROR_CODE;
            $resp -> respDesc = $e->getMessage();
        } catch (\Exception $e) {
            $resp -> respCode = Constants::RESP_GENERAL_ERROR_CODE;
            $resp -> respDesc = $e->getMessage();
        }   

       return response()->json($resp);
    }

    function cekTagihan($periode,$fee){
        $tagihan = Tagihan::where('periode',$periode)->first();
        if($tagihan == null){
            
            $cts =  DB::select("select count(*) as jmlPasien from tpendaftarans 
                                where jadwal_pendaftaran like ? and status_antrian='1'",
                    [$periode."%"])[0];
            
            $tagihan = new Tagihan();
            $tagihan->periode = $periode;
            $tagihan->jmlPasien=$cts->jmlPasien;
            $tagihan->jmlTagihan = $cts->jmlPasien * $fee;
            $tagihan->statusPembayaran = "0";
            $tagihan->trxFee = $fee;
            return Tagihan::create([
                'periode'=>$tagihan->periode,
                'jmlPasien'=>$tagihan->jmlPasien,
                'jmlTagihan'=>$tagihan->jmlTagihan,
                'statusPembayaran'=>$tagihan->statusPembayaran,
                'trxFee'=>$tagihan->trxFee
            ]);

        }else{
            return $tagihan;
        }
    }
}
