<?php

//Route::group( ['middleware' => 'guest'] , function() {
Route::get('/', 'IndexController@index');
Route::get('/post/{id}', 'IndexController@pengumuman');
Auth::routes();
Route::get('/log-in', function () {
    return view('auth.login');
});
Route::get('/presensi-berangkat', 'IndexController@presensiberangkat');
Route::get('/presensi-pulang', 'IndexController@presensipulang');
//Route::get('/cekdata', 'IndexController@cekdata');
Route::post('/simpan-presensi-berangkat', 'IndexController@simpanpresensiberangkat');
Route::post('/simpan-presensi-pulang/{id}', 'IndexController@simpanpresensipulang');
Route::get('/panduan', 'IndexController@panduan');
Route::post('/simpan-data-pegawai', 'IndexController@simpandatapegawai');
Route::post('/simpan-register', 'IndexController@simpanregister');
//Route::get('/input-izin', 'IndexController@inputizin');
Route::post('/simpan-izin-pegawai', 'IndexController@simpanizin');
Route::get('/data-pegawai', 'IndexController@datapegawai');
Route::get('/kehadiran-hari-ini', 'IndexController@kehadiran');
//});


//Route::get('/registrasi', 'AdminController@registrasi');
//Route::post('/simpan-registrasi', 'AdminController@simpanregistrasi');

Route::group(['middleware' => 'admin'], function () {
    Route::get('/dashboard', 'AdminController@dashboard');

    //Presensi
    Route::get('/presensi-harian', 'AdminController@presensi');
    Route::post('/presensi-cari-bulan', 'AdminController@caribulan');

    //Data presensi
    Route::get('/semua-presensi', 'AdminController@semuapresensi');
    Route::get('/laporan-presensi', 'AdminController@laporanpresensi');
    Route::get('/laporan', 'AdminController@laporan');
    Route::post('update-presensi/{id}', 'AdminController@updatepresensi');
    Route::get('downloadLaporanExcel/{type}', 'AdminController@downloadpresensi');

    //Lokasi Maps
    Route::get('/maps-location', 'AdminController@mapslocation');

    //Master cuti
    Route::get('/jenis-cuti', 'AdminController@jeniscuti');
    Route::post('/simpan-jenis-cuti', 'AdminController@simpanjeniscuti');
    Route::get('/{id}/delete-jenis-cuti', 'AdminController@deletejeniscuti');
    Route::post('update-jenis-cuti/{id}', 'AdminController@updatejeniscuti');
    Route::get('/manajemen-jatah-cuti', 'AdminController@manajemenjatahcuti');
    Route::post('/simpan-jatah-cuti', 'AdminController@simpanjatahcuti');
    Route::get('/{id}/delete-jatah-cuti', 'AdminController@deletejatahcuti');
    Route::post('update-jatah-cuti/{id}', 'AdminController@updatejatahcuti');

    //Cuti
    //Route::get('{id}/konfirmasi-cuti/', 'AdminController@konfirmasicuti');
    Route::get('/konfirmasi-cuti', 'AdminController@konfirmasicuti');
    Route::get('{id}/konfirmasi-tolak-cuti/', 'AdminController@tolakcuti');
    Route::get('{id}/konfirmasi-izinkan-cuti/', 'AdminController@izinkancuti');

    //Route::post('update-izin/{id}', 'AdminController@updateizin');
    //Route::get('/{id}/edit-izin', 'AdminController@editizin');
    //Route::post('/hapus-izin/{id}', 'AdminController@hapusizin');

    //Izin
    Route::get('/konfirmasi-izin', 'AdminController@konfirmasiizin');
    Route::get('{id}/konfirmasi-tolak-izin/', 'AdminController@tolakizin');
    Route::get('{id}/konfirmasi-izinkan-izin/', 'AdminController@izinkanizin');


    //Route::get('/jumlah-kehadiran', 'AdminController@jumlahkehadiran');

    Route::get('/jam-masuk', 'AdminController@jammasuk');
    Route::post('/simpan-jam', 'AdminController@simpanjam');
    Route::post('update-jam/{id}', 'AdminController@updatejam');
    Route::get('/{id}/edit-jam', 'AdminController@editjam');
    Route::post('/hapus-jam/{id}', 'AdminController@hapusjam');

    //Users
    Route::get('/manajemen-user', 'AdminController@manajemenuser');
    Route::get('/tambah-user', 'AdminController@tambahuser');
    Route::post('/simpan-user', 'AdminController@simpanuser');
    Route::get('/arsip-user', 'AdminController@arsipuser');

    //Pengumuman
    Route::get('/manajemen-pengumuman', 'AdminController@manajemenpengumuman');
    Route::get('/{id}/delete-manajemen-pengumuman', 'AdminController@deletemanajemenpengumuman');
    Route::post('/update-manajemen-pengumuman/{id}', 'AdminController@updatemanajemenpengumuman');
    Route::post('/simpan-manajemen-pengumuman', 'AdminController@simpanmanajemenpengumuman');

    //Atur Header
    Route::get('/atur-header', 'AdminController@header');
    Route::post('/update-header/{id}', 'AdminController@updateheader');

    //Biodata
    Route::get('/biodata', 'AdminController@biodata');
    Route::get('/{id}/edit-biodata', 'AdminController@editbiodata');
    Route::get('/{id}/detail-biodata', 'AdminController@detailbiodata');
    Route::get('/{id}/edit-data', 'AdminController@editdata');
    Route::post('/update-data/{id}', 'AdminController@updatedata');
    Route::post('/hapus-data/{id}', 'AdminController@hapusdata');

    //Radius
    Route::get('/atur-radius', 'AdminController@aturradius');
    Route::post('/update-radius/{id}', 'AdminController@updateradius');

    //Route untuk User biasa
    Route::post('/update-biodata', 'AdminController@updatebiodata');
    Route::get('/rekap-presensi', 'AdminController@rekappresensi');
    Route::get('/pengajuan-cuti', 'AdminController@pengajuancuti');
    Route::get('/daftar-cuti', 'AdminController@daftarcuti');
    Route::post('/simpan-pengajuan-cuti', 'AdminController@simpanpengajuancuti');
    Route::post('/batal-pengajuan-cuti/{id}', 'AdminController@batalpengajuancuti');
    Route::get('/daftar-izin', 'AdminController@daftarizin');
    Route::get('/pengajuan-izin', 'AdminController@pengajuanizin');
    Route::post('/simpan-pengajuan-izin', 'AdminController@simpanpengajuanizin');
    Route::post('/batal-pengajuan-izin/{id}', 'AdminController@batalpengajuanizin');
    Route::get('/jatah-cuti', 'AdminController@lihatcuti');
});
