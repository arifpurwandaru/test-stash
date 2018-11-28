<?php
namespace App;

class Constants{
    const RESP_SUCCESS_CODE = "00";
    const RESP_SUCCESS_DESC = "success";

    const RESP_DATA_NOTFOUND_CODE = "01";
    const RESP_DATA_NOTFOUND_DESC = "Data not found";

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

}