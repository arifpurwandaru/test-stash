<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tpendaftaran;
use App\CommonResponse;
use App\Constants;
use App\AntrianInfo;
use DB;

class PendaftaranController extends Controller
{
    
    function index(){
        return Tpendaftaran::all();
    }

    function store(Request $request){
       $resp = new CommonResponse();
       try {
           $resp->respCode = Constants::RESP_SUCCESS_CODE;
           $resp->respDesc = Constants::RESP_SUCCESS_DESC;
           $resp->data = Tpendaftaran::create($request->all()); 
       } catch (\Illuminate\Database\QueryException $e) {
           $resp -> respCode = Constants::RESP_DB_ERROR_CODE;
           $resp -> respDesc = $e->getMessage();
       } catch (\Exception $e) {
           $resp -> respCode = Constants::RESP_GENERAL_ERROR_CODE;
           $resp -> respDesc = $e->getMessage();
       }
       return response()->json($resp);
    }


    function getCurrentAntrian($tgl,$sesi){
        
        $matchThese = ['jadwal_pendaftaran' => $tgl, 
                    'sesi'=>$sesi];

        $tpn = Tpendaftaran::where($matchThese)->max('no_urut');
                if($tpn==null){
                    $tpn = 0;
                }

        return $tpn+1; 
    }
    public function validateDuplicateAndSelectMaxNoUrut(Request $request)
    {
        $resp = new CommonResponse();

        try {
            $matchThese = ['jadwal_pendaftaran' => $request->jadwal_pendaftaran, 
                        'pasienid' => $request->pasienid,
                    'sesi'=>$request->sesi];

            $vald = Tpendaftaran::where($matchThese)->get();
            if(!$vald->isEmpty()){
                $resp->respCode = Constants::RESP_DATA_VALIDATE_FAIL_CODE;
                $resp->respDesc = Constants::RESP_DATA_VALIDATE_FAIL_DESC;
                return response()->json($resp);
            }else{
                $resp->respCode = Constants::RESP_SUCCESS_CODE;
                $resp->respDesc = Constants::RESP_SUCCESS_DESC;
                $resp->data = $this->getCurrentAntrian($request->jadwal_pendaftaran, $request->sesi);
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


    public function getAllPendaftarByLoginIdAndTgl(Request $request){
        $resp = new CommonResponse();
       try {
            $asdf = DB::select("select mp.nama,tp.no_urut,tp.status_antrian, tp.jadwal_pendaftaran,tp.id, tp.sesi 
                from tpendaftarans tp 
                inner join mpasiens mp on mp.pasienid = tp.pasienid
                where tp.jadwal_pendaftaran=? and tp.loginid=? and tp.sesi=?
                order by tp.no_urut asc",
                [$request->jadwal_pendaftaran, $request->loginid, $request->sesi]);

                if($asdf==null){
                    $resp->respCode = Constants::RESP_DATA_NOTFOUND_CODE;
                    $resp->respDesc = Constants::RESP_DATA_NOTFOUND_DESC;
                    return response()->json($resp);
                }else{
                    $resp->respCode = Constants::RESP_SUCCESS_CODE;
                    $resp->respDesc = Constants::RESP_SUCCESS_DESC;
                    $resp->data = $asdf; 
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

    public function retrieveStatusAntrean($tgl,$sesiid){
        $resp = new CommonResponse();
        try{
            $antInfo = new AntrianInfo();
            $matchThese = ['jadwal_pendaftaran' => $tgl, 
                        'sesi'=>$sesiid,
                        'status_antrian' => '0'];
            $tpn = Tpendaftaran::where($matchThese)->min('no_urut');
            
            $listwhere = ['jadwal_pendaftaran' => $tgl, 
                        'sesi'=>$sesiid];
            $tot = Tpendaftaran::where($listwhere)->max('no_urut');
            if($tpn!=null && $tot!=null){
                $antInfo->current = $tpn;
                $antInfo->total = $tot;
            }else if($tot!=null){
                $antInfo->current=$tot;
                $antInfo->total=$tot;
            }else{
                $resp->respCode=Constants::RESP_DATA_NOTFOUND_CODE;
                $resp->respDesc=Constants::RESP_DATA_NOTFOUND_DESC;
                return response()->json($resp);
            }
            $resp->data = $antInfo;
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

    public function getAllAntrianByDateAndStatus($tgl,$status,$sesi){
        $resp = new CommonResponse();
        try{
            $asdf = DB::select("select p.*, ps.nama, ps.imgLink, ps.imgLinkTemp, ps.alamatLengkap, ps.jenisKelamin, ps.golonganDarah, ps.nik 
                        from tpendaftarans p
                        inner join mpasiens ps on ps.pasienid = p.pasienid
                        where p.jadwal_pendaftaran=? and p.status_antrian=? and p.sesi=?
                        order by p.no_urut asc",
                        [$tgl,$status,$sesi]);
           /*  $matchThese = ['jadwal_pendaftaran' => $tgl, 
                        'status_antrian' => $status];
            $tpn = Tpendaftaran::where($matchThese)->get(); */
            if($asdf==null){
                $resp->respCode=Constants::RESP_DATA_NOTFOUND_CODE;
                $resp->respDesc=Constants::RESP_DATA_NOTFOUND_DESC;
                return response()->json($resp);
            }else{
                $resp->data = $asdf;
                $resp->respCode=Constants::RESP_SUCCESS_CODE;
                $resp->respDesc=Constants::RESP_SUCCESS_DESC;
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


    
    function updateDoneTrx(Request $request){
        $resp = new CommonResponse();
        try{
             Tpendaftaran::findOrFail($request->input('id'))->update([
                'catatan_medis'=>$request->input('catatan_medis'),
                'diagnosa_penyakit'=>$request->input('diagnosa_penyakit'),
                'obat'=>$request->input('obat'),
                'status_antrian'=>Constants::STATUS_DONE,
            ]);

            $resp -> respCode = Constants::RESP_SUCCESS_CODE;
            $resp -> respDesc = Constants::RESP_SUCCESS_DESC;
            $resp -> data = Tpendaftaran::where([
                ['jadwal_pendaftaran', '=', $request->input('jadwal_pendaftaran')],
                ['no_urut','<',$request->input('no_urut')],
                ['status_antrian','=',Constants::STATUS_OPEN]
                ])
            ->update(['status_antrian' => Constants::STATUS_SKIP]);
        }catch (\Exception $e) {
            $resp -> respCode = Constants::RESP_GENERAL_ERROR_CODE;
            $resp -> respDesc = $e->getMessage();
        }
        return response()->json($resp);
    }


    function resubmitPendaftaran(Request $request){
        $resp = new CommonResponse();
        try{
            $noUrut = $this->getCurrentAntrian($request->input('jadwal_pendaftaran'),$request->input('sesi'));
            Tpendaftaran::findOrFail($request->input('id'))->update([
               'status_antrian'=>Constants::STATUS_OPEN,
               'no_urut'=>$noUrut
           ]);

           $resp -> respCode = Constants::RESP_SUCCESS_CODE;
           $resp -> respDesc = Constants::RESP_SUCCESS_DESC;
       }catch (\Exception $e) {
           $resp -> respCode = Constants::RESP_GENERAL_ERROR_CODE;
           $resp -> respDesc = $e->getMessage();
       }
       return response()->json($resp);
    }



    public function getRiwayatKunjunganTerakhir($loginid){
        $resp = new CommonResponse();
        try{
            $asdf = DB::select("select trx.pasienid, p.nama, p.imgLink, p.imgLinkTemp, bridge.jadwal_pendaftaran
            from tpendaftarans trx
            inner join mpasiens p on p.pasienid = trx.pasienid
            inner join 
                (select pasienid, max(jadwal_pendaftaran) as jadwal_pendaftaran
                    from tpendaftarans 
                    where loginid=? 
                    and status_antrian=1
                    group by pasienid) bridge on bridge.pasienid = trx.pasienid
            group by trx.pasienid, p.nama, p.imgLink, p.imgLinkTemp, bridge.jadwal_pendaftaran",
                        [$loginid]);
          
            if($asdf==null){
                $resp->respCode=Constants::RESP_DATA_NOTFOUND_CODE;
                $resp->respDesc=Constants::RESP_DATA_NOTFOUND_DESC;
                return response()->json($resp);
            }else{
                $resp->data = $asdf;
                $resp->respCode=Constants::RESP_SUCCESS_CODE;
                $resp->respDesc=Constants::RESP_SUCCESS_DESC;
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

    
    public function getDetailRiwayatPemeriksaan($pasienid, $tgl){
        $resp = new CommonResponse();
        try{
            $asdf = DB::select("select p.nama, p.tglLahir, p.imgLink, p.imgLinkTemp, p.jenisKelamin, p.golonganDarah, trx.catatan_medis, trx.jadwal_pendaftaran, trx.keluhan
            from tpendaftarans trx
            inner join mpasiens p on p.pasienid = trx.pasienid
            where trx.pasienid=?
            and trx.jadwal_pendaftaran=?",
                        [$pasienid,$tgl]);
          
            if($asdf==null){
                $resp->respCode=Constants::RESP_DATA_NOTFOUND_CODE;
                $resp->respDesc=Constants::RESP_DATA_NOTFOUND_DESC;
                return response()->json($resp);
            }else{
                $resp->data = $asdf[0];
                $resp->respCode=Constants::RESP_SUCCESS_CODE;
                $resp->respDesc=Constants::RESP_SUCCESS_DESC;
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



    
    public function perbaharuiDonePasien(Request $request){
        $resp = new CommonResponse();
        try{
            $whatfield = $request->input('whatField');
            if($whatfield == 'Diagnosa'){
                Tpendaftaran::findOrFail($request->input('id'))->update([
                    'diagnosa_penyakit'=>$request->input('diagnosa_penyakit'),
                ]);
            }else if ($whatfield == 'Obat'){
                Tpendaftaran::findOrFail($request->input('id'))->update([
                    'obat'=>$request->input('obat'),
                ]);
            }
             

            $asdf = DB::select("select p.*, ps.nama, ps.imgLink, ps.imgLinkTemp, ps.alamatLengkap, ps.jenisKelamin, ps.golonganDarah, ps.nik 
                        from tpendaftarans p
                        inner join mpasiens ps on ps.pasienid = p.pasienid
                        where p.id=?",
                        [$request->input('id')]);

            $resp -> respCode = Constants::RESP_SUCCESS_CODE;
            $resp -> respDesc = Constants::RESP_SUCCESS_DESC;
            $resp -> data = $asdf[0];
        }catch (\Exception $e) {
            $resp -> respCode = Constants::RESP_GENERAL_ERROR_CODE;
            $resp -> respDesc = $e->getMessage();
        }
        return response()->json($resp);
    }



    
    public function aktifkanKembali($id){
        $resp = new CommonResponse();
        try{
             Tpendaftaran::findOrFail($id)->update([
                'status_antrian'=>Constants::STATUS_OPEN,
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