<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('muser', 'MuserController@store');
Route::get('muser', 'MuserController@index');
Route::get('muser/{userId}', 'MuserController@show');
Route::post('muser', 'MuserController@store');
Route::post('muser/update', 'MuserController@update');
Route::post('muser/updateDp', 'MuserController@updateDp');
Route::delete('muser/{userId}', 'MuserController@delete');
Route::post('login','MuserController@login');
Route::post('muser/sendEmail', 'MuserController@sendEmail');
Route::post('muser/okgantipas','MuserController@updatePass');
Route::get('muser/verifikasi/{loginid}','MuserController@aktifasiUser');
Route::get('muser/ganpas/{param}','MuserController@gantipassword');
Route::get('muser/kirimEmailGantiPassword/{email}','MuserController@kirimEmailGantiPassword');

Route::get('muser/jangkrik/testSendEmail2','MuserController@testSendEmail2');


Route::get('caro/getAll','CarouselController@getAll');
Route::post('caro/update', 'CarouselController@update');


Route::post('mpasien','MpasienController@store');
Route::get('mpasien/{loginid}','MpasienController@getByLoginId');
Route::get('mpasien/getOne/{pasienid}','MpasienController@getOne');
Route::post('mpasien/update','MpasienController@update');
Route::post('mpasien/updateDp','MpasienController@updateDp');

Route::post('mjadwal/simpanJadwal','MjadwalController@simpanJadwal');
Route::get('mjadwal','MjadwalController@index');
Route::get('mjadwal/getAllMjadwal','MjadwalController@getAllMjadwal');
Route::get('mjadwal/getOne/{parentId}','MjadwalController@getOneWithChildren');
Route::get('mjadwal/genocide/{parentId}','MjadwalController@deleteAndGenocide');
Route::get('mjadwal/deleteSesi/{sesiId}','MjadwalController@deleteSesi');
Route::get('mjadwal/getByTgl/{tgl}','MjadwalController@getByTgl');

Route::post('tpendaftaran','PendaftaranController@store');
Route::post('tpendaftaran/validateDuplicate','PendaftaranController@validateDuplicateAndSelectMaxNoUrut');
Route::post('tpendaftaran/getAllPendaftarByLoginIdAndTgl','PendaftaranController@getAllPendaftarByLoginIdAndTgl');
Route::post('tpendaftaran/updateDoneTrx','PendaftaranController@updateDoneTrx');
Route::post('tpendaftaran/resubmitPendaftaran','PendaftaranController@resubmitPendaftaran');
Route::post('tpendaftaran/perbaharuiDonePasien','PendaftaranController@perbaharuiDonePasien');
Route::get('tpendaftaran/retrieveStatusAntrean/{tgl}/{sesiid}','PendaftaranController@retrieveStatusAntrean');
Route::get('tpendaftaran','PendaftaranController@index');
Route::get('tpendaftaran/getAllAntrianByDateAndStatus/{tgl}/{status}/{sesi}','PendaftaranController@getAllAntrianByDateAndStatus');
Route::get('tpendaftaran/getRiwayatKunjunganTerakhir/{loginid}','PendaftaranController@getRiwayatKunjunganTerakhir');
Route::get('tpendaftaran/getDetailRiwayatPemeriksaan/{loginid}/{tgl}','PendaftaranController@getDetailRiwayatPemeriksaan');
Route::get('tpendaftaran/aktifkanKembali/{id}','PendaftaranController@aktifkanKembali');

