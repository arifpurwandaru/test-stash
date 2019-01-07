<?php
namespace App;

class Constants{
    //const ENDPOINT_URL = "https://idoctor.masjidjabirsorowajan.com/";

    
    const ENDPOINT_URL = "http://192.168.1.9:8000/";

    const RESP_SUCCESS_CODE = "00";
    const RESP_SUCCESS_DESC = "success";

    const RESP_DATA_NOTFOUND_CODE = "01";
    const RESP_DATA_NOTFOUND_DESC = "Data not found";

    const RESP_SESI_EXIST_CODE = "21";
    const RESP_SESI_EXIST_DESC = "Sesi pada jam tersebut sudah ada/saling beririsan dengan yang lain";

    const RESP_SESI_JAMKEBALIK_CODE = "22";
    const RESP_SESI_JAMKEBALIK_DESC = "Jam mulai tidak boleh lebih besar atau sama dg Jam selesai";

    const RESP_SESI_JAMNGAWUR_CODE = "23";
    const RESP_SESI_JAMNGAWUR_DESC = "Format penulisan jam tidak sesuai/salah";

    const RESP_DATA_VALIDATE_FAIL_CODE = "02";
    const RESP_DATA_VALIDATE_FAIL_DESC = "Validation Failed";

    const RESP_DB_ERROR_CODE = "DBERROR";
    const RESP_DB_ERROR_DESC = "DB Error";

    const RESP_GENERAL_ERROR_CODE = "GENERAL_ERROR";
    const RESP_GENERAL_ERROR_DESC = "General Error";

    //status antrean
    const STATUS_OPEN="0";
    const STATUS_DONE="1";
    const STATUS_SKIP="2";

    //status pembayaran
    const BLM_DIBAYAR="0";
    const SDH_DIBAYAR="1";

    //sys config
    const FEE_PASIEN="FEE_PASIEN";

}