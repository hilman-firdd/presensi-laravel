<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Detailuser;
use App\Alamat;
use App\Kontak;
use App\Kepegawaian;
use App\Presensi;
use App\Pengajuanizin;
use DB;
use Session;
use Redirect;
use Image;
use Input;
use Larinfo;

class IndexController extends Controller
{
    public function index()
    {
        //$data = DB::Select('select tb_pengumuman.*, users.nama from tb_pengumuman JOIN users ON tb_pengumuman.id_user = users.nik order by created_at DESC');
        $nama = DB::select('select * from users');
        return view('home.index', compact('nama'));
    }

    public function pengumuman($id)
    {
        date_default_timezone_set('Asia/Jakarta');
        $data = DB::Select('select tb_pengumuman.*, users.nama from tb_pengumuman JOIN users ON tb_pengumuman.id_user = users.nik where tb_pengumuman.url = "' . $id . '"');
        foreach ($data as $key => $value) {
            $view = $value->view + 1;
        }
        DB::update('update tb_pengumuman set view = "' . $view . '" where url = "' . $id . '"');
        return view('home/pengumuman', compact('data'));
    }

    public function presensiberangkat()
    {
        error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
        $nik = $_GET['nik'];
        $data = DB::select('select * from users where nik = "' . $nik . '"');
        return view('home.presensi-berangkat', compact('data'));
    }

    public function presensipulang()
    {
        date_default_timezone_set('Asia/Jakarta');
        error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
        $id = User::find($id);
        $nik = $_GET['nik'];
        $data = DB::select('select * from users where nik = "' . $nik . '"');
        //$time = date("H");
        // if ($time < "16") {
        //Session::flash('gagal', 'Masih belum jam 4 sore, yakin tetap mau presensi ?');
        //} else {
        # code...
        //}
        return view('home.presensi-pulang', compact('data'));
    }

    public function simpanpresensiberangkat(Request $request)
    {
        if (empty($request->lokasi)) {
            Session::flash('gagal', 'Lokasi anda tidak di ketahui');
            return back();
        }

        $id_user = $_POST['id_user'];
        // $berangkat  = date("h:i:s || d-m-Y");
        date_default_timezone_set('Asia/Jakarta');
        $berangkat  = date("H:i:s");
        $tanggal  = date("Y-m-d");

        $agent = new \Jenssegers\Agent\Agent;
        if ($result = $agent->isDesktop()) {
            $hardware = "Laptop";
        } elseif ($result = $agent->isMobile()) {
            $hardware = "Handphone";
        } elseif ($result = $agent->isTablet()) {
            $hardware = "Tablet";
        }

        //data lokasi
        $data = $request->lokasi;
        $data_long = substr($data, strpos($data, ",") + 1);
        $data_lat = substr($request->lokasi, 0, strpos($request->lokasi, ","));

        $radius = DB::select('select * from tb_radius');
        foreach ($radius as $key => $value) {
            $lat_bawah = $value->lat_bawah;
            $lat_atas = $value->lat_atas;
            $long_bawah = $value->long_bawah;
            $long_atas = $value->long_atas;
        }

        /* if (($data_lat >= $lat_bawah && $data_lat <= $lat_atas) && ($data_long >= $long_bawah && $data_long <= $long_atas)) {

        } else {
             Session::flash('gagal', 'Lokasi anda diluar radius, silahkan aktifkan ulang lokasi');
             return back();
        } */

        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if (isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';

        //cek wfo
        $cekwf = DB::select('select * from tb_jammasuk where id_user =  "' . $id_user . '"');
        foreach ($cekwf as $key => $value) {

            $wf1 = $value->wf1;
            $wf2 = $value->wf2;
            $wf3 = $value->wf3;
            $wf4 = $value->wf4;
            $wf5 = $value->wf5;
            $wf6 = $value->wf6;
            $wf7 = $value->wf7;
        }

        $idsession = Session::getId() . "/" . date('Y-m-d') . "/" . "datang";
        if (date('D') == 'Mon') {
            if (!isset($wf1) || $wf1 == 'OFF') {
                Session::flash('gagal', 'Jam Kerja anda belum diatur atau terjadwal Off, hubungi Administrator Sistem');
                return back();
            } else {
                if ($wf1 == 'WFO') {
                    if (($data_lat >= $lat_bawah && $data_lat <= $lat_atas) || ($data_long >= $long_bawah && $data_long <= $long_atas)) {
                        $this->validate($request, [
                            'gambar' => 'required|mimes:jpg,png,jpeg',
                        ]);

                        $image = Input::file('gambar');
                        $extension = $image->getClientOriginalExtension();
                        $fileName = rand(11111, 99999) . '.' . $extension;
                        $input['gambar'] = $fileName;

                        $presensi = new Presensi();
                        $presensi->id_user = $id_user;
                        $presensi->berangkat = $berangkat;
                        $presensi->lokasi_berangkat = $request->lokasi;
                        $presensi->tanggal = $tanggal;
                        $presensi->hardware = $hardware;
                        $presensi->keterangan_kerja = $wf1;
                        $presensi->swafoto1 = $input['gambar'];
                        $presensi->ip = $ipaddress;
                        $presensi->id_session = $idsession;
                        $tumbukan =
                            DB::select('select id_user from tb_presensi where id_user = "' . $id_user . '" and  tanggal="' . date('Y-m-d') . '"');
                        if ($tumbukan) {
                            Session::flash('gagal', 'Anda sudah melakukan presensi berangkat');
                        } else {
                            $path = public_path('img/swafoto1/' . $fileName);
                            $img = Image::make($image->getRealPath())->save($path);

                            $presensi->save();
                            Session::flash('sukses', 'Anda berhasil melakukan presensi berangkat');
                            return Redirect::to('/');
                        }
                        return back();
                    } else {
                        Session::flash('gagal', 'Lokasi anda diluar radius, silahkan aktifkan ulang lokasi');
                        return back();
                    }
                } else {
                }
            }
        } else if (date('D') == 'Tue') {
            if (!isset($wf2) || $wf2 == 'OFF') {
                Session::flash('gagal', 'Jam Kerja anda belum diatur atau terjadwal Off, hubungi Administrator Sistem');
                return back();
            } else {
                if ($wf2 == 'WFO') {
                    if (($data_lat >= $lat_bawah && $data_lat <= $lat_atas) || ($data_long >= $long_bawah && $data_long <= $long_atas)) {
                        $this->validate($request, [
                            'gambar' => 'required|mimes:jpg,png,jpeg',
                        ]);

                        $image = Input::file('gambar');
                        $extension = $image->getClientOriginalExtension();
                        $fileName = rand(11111, 99999) . '.' . $extension;
                        $input['gambar'] = $fileName;

                        $presensi = new Presensi();
                        $presensi->id_user = $id_user;
                        $presensi->berangkat = $berangkat;
                        $presensi->lokasi_berangkat = $request->lokasi;
                        $presensi->tanggal = $tanggal;
                        $presensi->hardware = $hardware;
                        $presensi->keterangan_kerja = $wf2;
                        $presensi->swafoto1 = $input['gambar'];
                        $presensi->ip = $ipaddress;
                        $presensi->id_session = $idsession;
                        $tumbukan =
                            DB::select('select id_user from tb_presensi where id_user = "' . $id_user . '" and  tanggal="' . date('Y-m-d') . '"');
                        if ($tumbukan) {
                            Session::flash('gagal', 'Anda sudah melakukan presensi berangkat');
                        } else {
                            $path = public_path('img/swafoto1/' . $fileName);
                            $img = Image::make($image->getRealPath())->save($path);

                            $presensi->save();
                            Session::flash('sukses', 'Anda berhasil melakukan presensi berangkat');
                            return Redirect::to('/');
                        }
                        return back();
                    } else {
                        Session::flash('gagal', 'Lokasi anda diluar radius, silahkan aktifkan ulang lokasi');
                        return back();
                    }
                } else {
                }
            }
        } else if (date('D') == 'Wed') {
            if (!isset($wf3) || $wf3 == 'OFF') {
                Session::flash('gagal', 'Jam Kerja anda belum diatur atau terjadwal Off, hubungi Administrator Sistem');
                return back();
            } else {
                if ($wf3 == 'WFO') {
                    if (($data_lat >= $lat_bawah && $data_lat <= $lat_atas) || ($data_long >= $long_bawah && $data_long <= $long_atas)) {
                        $this->validate($request, [
                            'gambar' => 'required|mimes:jpg,png,jpeg',
                        ]);

                        $image = Input::file('gambar');
                        $extension = $image->getClientOriginalExtension();
                        $fileName = rand(11111, 99999) . '.' . $extension;
                        $input['gambar'] = $fileName;

                        $presensi = new Presensi();
                        $presensi->id_user = $id_user;
                        $presensi->berangkat = $berangkat;
                        $presensi->lokasi_berangkat = $request->lokasi;
                        $presensi->tanggal = $tanggal;
                        $presensi->hardware = $hardware;
                        $presensi->keterangan_kerja = $wf3;
                        $presensi->swafoto1 = $input['gambar'];
                        $presensi->ip = $ipaddress;
                        $presensi->id_session = $idsession;
                        $tumbukan =
                            DB::select('select id_user from tb_presensi where id_user = "' . $id_user . '" and  tanggal="' . date('Y-m-d') . '"');
                        if ($tumbukan) {
                            Session::flash('gagal', 'Anda sudah melakukan presensi berangkat');
                        } else {
                            $path = public_path('img/swafoto1/' . $fileName);
                            $img = Image::make($image->getRealPath())->save($path);

                            $presensi->save();
                            Session::flash('sukses', 'Anda berhasil melakukan presensi berangkat');
                            return Redirect::to('/');
                        }
                        return back();
                    } else {
                        Session::flash('gagal', 'Lokasi anda diluar radius, silahkan aktifkan ulang lokasi');
                        return back();
                    }
                } else {
                }
            }
        } else if (date('D') == 'Thu') {
            if (!isset($wf4) || $wf4 == 'OFF') {
                Session::flash('gagal', 'Jam Kerja anda belum diatur atau terjadwal Off, hubungi Administrator Sistem');
                return back();
            } else {
                if ($wf4 == 'WFO') {
                    if (($data_lat >= $lat_bawah && $data_lat <= $lat_atas) || ($data_long >= $long_bawah && $data_long <= $long_atas)) {
                        $this->validate($request, [
                            'gambar' => 'required|mimes:jpg,png,jpeg',
                        ]);

                        $image = Input::file('gambar');
                        $extension = $image->getClientOriginalExtension();
                        $fileName = rand(11111, 99999) . '.' . $extension;
                        $input['gambar'] = $fileName;

                        $presensi = new Presensi();
                        $presensi->id_user = $id_user;
                        $presensi->berangkat = $berangkat;
                        $presensi->lokasi_berangkat = $request->lokasi;
                        $presensi->tanggal = $tanggal;
                        $presensi->hardware = $hardware;
                        $presensi->keterangan_kerja = $wf4;
                        $presensi->swafoto1 = $input['gambar'];
                        $presensi->ip = $ipaddress;
                        $presensi->id_session = $idsession;
                        $tumbukan =
                            DB::select('select id_user from tb_presensi where id_user = "' . $id_user . '" and  tanggal="' . date('Y-m-d') . '"');
                        if ($tumbukan) {
                            Session::flash('gagal', 'Anda sudah melakukan presensi berangkat');
                        } else {
                            $path = public_path('img/swafoto1/' . $fileName);
                            $img = Image::make($image->getRealPath())->save($path);

                            $presensi->save();
                            Session::flash('sukses', 'Anda berhasil melakukan presensi berangkat');
                            return Redirect::to('/');
                        }
                        return back();
                    } else {
                        Session::flash('gagal', 'Lokasi anda diluar radius, silahkan aktifkan ulang lokasi');
                        return back();
                    }
                } else {
                }
            }
        } else if (date('D') == 'Fri') {
            if (!isset($wf5) || $wf5 == 'OFF') {
                Session::flash('gagal', 'Jam Kerja anda belum diatur atau terjadwal Off, hubungi Administrator Sistem');
                return back();
            } else {
                if ($wf5 == 'WFO') {
                    if (($data_lat >= $lat_bawah && $data_lat <= $lat_atas) || ($data_long >= $long_bawah && $data_long <= $long_atas)) {
                        $this->validate($request, [
                            'gambar' => 'required|mimes:jpg,png,jpeg',
                        ]);

                        $image = Input::file('gambar');
                        $extension = $image->getClientOriginalExtension();
                        $fileName = rand(11111, 99999) . '.' . $extension;
                        $input['gambar'] = $fileName;

                        $presensi = new Presensi();
                        $presensi->id_user = $id_user;
                        $presensi->berangkat = $berangkat;
                        $presensi->lokasi_berangkat = $request->lokasi;
                        $presensi->tanggal = $tanggal;
                        $presensi->hardware = $hardware;
                        $presensi->keterangan_kerja = $wf5;
                        $presensi->swafoto1 = $input['gambar'];
                        $presensi->ip = $ipaddress;
                        $presensi->id_session = $idsession;
                        $tumbukan =
                            DB::select('select id_user from tb_presensi where id_user = "' . $id_user . '" and  tanggal="' . date('Y-m-d') . '"');
                        if ($tumbukan) {
                            Session::flash('gagal', 'Anda sudah melakukan presensi berangkat');
                        } else {
                            $path = public_path('img/swafoto1/' . $fileName);
                            $img = Image::make($image->getRealPath())->save($path);

                            $presensi->save();
                            Session::flash('sukses', 'Anda berhasil melakukan presensi berangkat');
                            return Redirect::to('/');
                        }
                        return back();
                    } else {
                        Session::flash('gagal', 'Lokasi anda diluar radius, silahkan aktifkan ulang lokasi');
                        return back();
                    }
                } else {
                }
            }
        } else if (date('D') == 'Sat') {
            if (!isset($wf6) || $wf6 == 'OFF') {
                Session::flash('gagal', 'Jam Kerja anda belum diatur atau terjadwal Off, hubungi Administrator Sistem');
                return back();
            } else {
                if ($wf6 == 'WFO') {
                    if (($data_lat >= $lat_bawah && $data_lat <= $lat_atas) || ($data_long >= $long_bawah && $data_long <= $long_atas)) {
                        $this->validate($request, [
                            'gambar' => 'required|mimes:jpg,png,jpeg',
                        ]);

                        $image = Input::file('gambar');
                        $extension = $image->getClientOriginalExtension();
                        $fileName = rand(11111, 99999) . '.' . $extension;
                        $input['gambar'] = $fileName;

                        $presensi = new Presensi();
                        $presensi->id_user = $id_user;
                        $presensi->berangkat = $berangkat;
                        $presensi->lokasi_berangkat = $request->lokasi;
                        $presensi->tanggal = $tanggal;
                        $presensi->hardware = $hardware;
                        $presensi->keterangan_kerja = $wf6;
                        $presensi->swafoto1 = $input['gambar'];
                        $presensi->ip = $ipaddress;
                        $presensi->id_session = $idsession;
                        $tumbukan =
                            DB::select('select id_user from tb_presensi where id_user = "' . $id_user . '" and  tanggal="' . date('Y-m-d') . '"');
                        if ($tumbukan) {
                            Session::flash('gagal', 'Anda sudah melakukan presensi berangkat');
                        } else {
                            $path = public_path('img/swafoto1/' . $fileName);
                            $img = Image::make($image->getRealPath())->save($path);

                            $presensi->save();
                            Session::flash('sukses', 'Anda berhasil melakukan presensi berangkat');
                            return Redirect::to('/');
                        }
                        return back();
                    } else {
                        Session::flash('gagal', 'Lokasi anda diluar radius, silahkan aktifkan ulang lokasi');
                        return back();
                    }
                } else {
                }
            }
        } else if (date('D') == 'Sun') {
            if (!isset($wf7) || $wf7 == 'OFF') {
                Session::flash('gagal', 'Jam Kerja anda belum diatur atau terjadwal Off, hubungi Administrator Sistem');
                return back();
            } else {
                if ($wf7 == 'WFO') {
                    if (($data_lat >= $lat_bawah && $data_lat <= $lat_atas) || ($data_long >= $long_bawah && $data_long <= $long_atas)) {
                        $this->validate($request, [
                            'gambar' => 'required|mimes:jpg,png,jpeg',
                        ]);

                        $image = Input::file('gambar');
                        $extension = $image->getClientOriginalExtension();
                        $fileName = rand(11111, 99999) . '.' . $extension;
                        $input['gambar'] = $fileName;

                        $presensi = new Presensi();
                        $presensi->id_user = $id_user;
                        $presensi->berangkat = $berangkat;
                        $presensi->lokasi_berangkat = $request->lokasi;
                        $presensi->tanggal = $tanggal;
                        $presensi->hardware = $hardware;
                        $presensi->keterangan_kerja = $wf7;
                        $presensi->swafoto1 = $input['gambar'];
                        $presensi->ip = $ipaddress;
                        $presensi->id_session = $idsession;
                        $tumbukan =
                            DB::select('select id_user from tb_presensi where id_user = "' . $id_user . '" and  tanggal="' . date('Y-m-d') . '"');
                        if ($tumbukan) {
                            Session::flash('gagal', 'Anda sudah melakukan presensi berangkat');
                        } else {
                            $path = public_path('img/swafoto1/' . $fileName);
                            $img = Image::make($image->getRealPath())->save($path);

                            $presensi->save();
                            Session::flash('sukses', 'Anda berhasil melakukan presensi berangkat');
                            return Redirect::to('/');
                        }
                        return back();
                    } else {
                        Session::flash('gagal', 'Lokasi anda diluar radius, silahkan aktifkan ulang lokasi');
                        return back();
                    }
                } else {
                }
            }
        } else {
            Session::flash('gagal', 'Hari ini Tanggal ' . date('d-m-Y'));
            return back();
        }
    }

    public function simpanpresensipulang(Request $request, $id)
    {

        if (empty($request->lokasi)) {
            Session::flash('gagal', 'Lokasi anda tidak di ketahui');
            return back();
        }

        $cekpulang = DB::select('select * from tb_presensi where id_user = "' . $id . '" and  tanggal="' . date('Y-m-d') . '"');
        foreach ($cekpulang as $key => $value) {
            $pulanga = $value->pulang;
        }

        if (empty($cekpulang)) {
            # code...
        } else {
            if ($pulanga == '00:00:00') {
                # code...
            } else {
                Session::flash('gagal', 'Anda sudah melakukan presensi pulang');
                return back();
            }
        }

        //data lokasi
        $data = $request->lokasi;
        $data_long = substr($data, strpos($data, ",") + 1);
        $data_lat = substr($request->lokasi, 0, strpos($request->lokasi, ","));

        $radius = DB::select('select * from tb_radius');
        foreach ($radius as $key => $value) {
            $lat_bawah = $value->lat_bawah;
            $lat_atas = $value->lat_atas;
            $long_bawah = $value->long_bawah;
            $long_atas = $value->long_atas;
        }

        //cek wfo
        $cekwf = DB::select('select * from tb_jammasuk where id_user =  "' . $id . '"');
        foreach ($cekwf as $key => $value) {

            $wf1 = $value->wf1;
            $wf2 = $value->wf2;
            $wf3 = $value->wf3;
            $wf4 = $value->wf4;
            $wf5 = $value->wf5;
            $wf6 = $value->wf6;
            $wf7 = $value->wf7;
        }

        $idsession = Session::getId() . "/" . date('Y-m-d') . "/" . "pulang";
        if (date('D') == 'Mon') {
            if (!isset($wf1) || $wf1 == 'OFF') {
                Session::flash('gagal', 'Jam Kerja anda belum diatur atau terjadwal Off, hubungi Administrator Sistem');
                return back();
            } else {
                if ($wf1 == 'WFO') {
                    if (($data_lat >= $lat_bawah && $data_lat <= $lat_atas) || ($data_long >= $long_bawah && $data_long <= $long_atas)) {
                        date_default_timezone_set('Asia/Jakarta');
                        $this->validate($request, [
                            'gambar' => 'required|mimes:jpg,png,jpeg',
                        ]);

                        $image = Input::file('gambar');
                        $extension = $image->getClientOriginalExtension();
                        $fileName = rand(11111, 99999) . '.' . $extension;
                        $input['gambar'] = $fileName;

                        $pulang  = date("H:i:s");

                        $cek =
                            DB::select('select * from tb_presensi where id_user = "' . $id . '" and  tanggal="' . date('Y-m-d') . '"');

                        if (empty($cek)) {
                            Session::flash('gagal', 'Anda belum melakukan presensi berangkat');
                        } else {
                            $path = public_path('img/swafoto2/' . $fileName);
                            $img = Image::make($image->getRealPath())->save($path);

                            $ubah = DB::update("Update tb_presensi SET pulang = '" . $pulang . "', lokasi_pulang = '" . $request->lokasi . "', id_session_pulang = '" . $idsession . "', swafoto2 = '" . $input['gambar'] . "', laporan_wfo = '" . $request->laporan_wfo . "' where id_user = '" . $id . "' order by id DESC limit 1");
                            Session::flash('sukses', 'Anda berhasil melakukan presensi pulang');
                            return Redirect::to('/');
                        }
                        return back();
                    } else {
                        Session::flash('gagal', 'Lokasi anda diluar radius, silahkan aktifkan ulang lokasi');
                        return back();
                    }
                } else {
                }
            }
        } else if (date('D') == 'Tue') {
            if (!isset($wf2) || $wf2 == 'OFF') {
                Session::flash('gagal', 'Jam Kerja anda belum diatur atau terjadwal Off, hubungi Administrator Sistem');
                return back();
            } else {
                if ($wf2 == 'WFO') {
                    if (($data_lat >= $lat_bawah && $data_lat <= $lat_atas) || ($data_long >= $long_bawah && $data_long <= $long_atas)) {
                        date_default_timezone_set('Asia/Jakarta');
                        $this->validate($request, [
                            'gambar' => 'required|mimes:jpg,png,jpeg',
                        ]);

                        $image = Input::file('gambar');
                        $extension = $image->getClientOriginalExtension();
                        $fileName = rand(11111, 99999) . '.' . $extension;
                        $input['gambar'] = $fileName;

                        $pulang  = date("H:i:s");

                        $cek =
                            DB::select('select * from tb_presensi where id_user = "' . $id . '" and  tanggal="' . date('Y-m-d') . '"');

                        if (empty($cek)) {
                            Session::flash('gagal', 'Anda belum melakukan presensi berangkat');
                        } else {
                            $path = public_path('img/swafoto2/' . $fileName);
                            $img = Image::make($image->getRealPath())->save($path);

                            $ubah = DB::update("Update tb_presensi SET pulang = '" . $pulang . "', lokasi_pulang = '" . $request->lokasi . "', id_session_pulang = '" . $idsession . "', swafoto2 = '" . $input['gambar'] . "', laporan_wfo = '" . $request->laporan_wfo . "' where id_user = '" . $id . "' order by id DESC limit 1");
                            Session::flash('sukses', 'Anda berhasil melakukan presensi pulang');
                            return Redirect::to('/');
                        }
                        return back();
                    } else {
                        Session::flash('gagal', 'Lokasi anda diluar radius, silahkan aktifkan ulang lokasi');
                        return back();
                    }
                } else {
                }
            }
        } else if (date('D') == 'Wed') {
            if (!isset($wf3) || $wf3 == 'OFF') {
                Session::flash('gagal', 'Jam Kerja anda belum diatur atau terjadwal Off, hubungi Administrator Sistem');
                return back();
            } else {
                if ($wf3 == 'WFO') {
                    if (($data_lat >= $lat_bawah && $data_lat <= $lat_atas) || ($data_long >= $long_bawah && $data_long <= $long_atas)) {
                        date_default_timezone_set('Asia/Jakarta');
                        $this->validate($request, [
                            'gambar' => 'required|mimes:jpg,png,jpeg',
                        ]);

                        $image = Input::file('gambar');
                        $extension = $image->getClientOriginalExtension();
                        $fileName = rand(11111, 99999) . '.' . $extension;
                        $input['gambar'] = $fileName;

                        $pulang  = date("H:i:s");

                        $cek =
                            DB::select('select * from tb_presensi where id_user = "' . $id . '" and  tanggal="' . date('Y-m-d') . '"');

                        if (empty($cek)) {
                            Session::flash('gagal', 'Anda belum melakukan presensi berangkat');
                        } else {
                            $path = public_path('img/swafoto2/' . $fileName);
                            $img = Image::make($image->getRealPath())->save($path);

                            $ubah = DB::update("Update tb_presensi SET pulang = '" . $pulang . "', lokasi_pulang = '" . $request->lokasi . "', id_session_pulang = '" . $idsession . "', swafoto2 = '" . $input['gambar'] . "', laporan_wfo = '" . $request->laporan_wfo . "' where id_user = '" . $id . "' order by id DESC limit 1");
                            Session::flash('sukses', 'Anda berhasil melakukan presensi pulang');
                            return Redirect::to('/');
                        }
                        return back();
                    } else {
                        Session::flash('gagal', 'Lokasi anda diluar radius, silahkan aktifkan ulang lokasi');
                        return back();
                    }
                } else {
                }
            }
        } else if (date('D') == 'Thu') {
            if (!isset($wf4) || $wf4 == 'OFF') {
                Session::flash('gagal', 'Jam Kerja anda belum diatur atau terjadwal Off, hubungi Administrator Sistem');
                return back();
            } else {
                if ($wf4 == 'WFO') {
                    if (($data_lat >= $lat_bawah && $data_lat <= $lat_atas) || ($data_long >= $long_bawah && $data_long <= $long_atas)) {
                        date_default_timezone_set('Asia/Jakarta');
                        $this->validate($request, [
                            'gambar' => 'required|mimes:jpg,png,jpeg',
                        ]);

                        $image = Input::file('gambar');
                        $extension = $image->getClientOriginalExtension();
                        $fileName = rand(11111, 99999) . '.' . $extension;
                        $input['gambar'] = $fileName;

                        $pulang  = date("H:i:s");

                        $cek =
                            DB::select('select * from tb_presensi where id_user = "' . $id . '" and  tanggal="' . date('Y-m-d') . '"');

                        if (empty($cek)) {
                            Session::flash('gagal', 'Anda belum melakukan presensi berangkat');
                        } else {
                            $path = public_path('img/swafoto2/' . $fileName);
                            $img = Image::make($image->getRealPath())->save($path);

                            $ubah = DB::update("Update tb_presensi SET pulang = '" . $pulang . "', lokasi_pulang = '" . $request->lokasi . "', id_session_pulang = '" . $idsession . "', swafoto2 = '" . $input['gambar'] . "', laporan_wfo = '" . $request->laporan_wfo . "' where id_user = '" . $id . "' order by id DESC limit 1");
                            Session::flash('sukses', 'Anda berhasil melakukan presensi pulang');
                            return Redirect::to('/');
                        }
                        return back();
                    } else {
                        Session::flash('gagal', 'Lokasi anda diluar radius, silahkan aktifkan ulang lokasi');
                        return back();
                    }
                } else {
                }
            }
        } else if (date('D') == 'Fri') {
            if (!isset($wf5) || $wf5 == 'OFF') {
                Session::flash('gagal', 'Jam Kerja anda belum diatur atau terjadwal Off, hubungi Administrator Sistem');
                return back();
            } else {
                if ($wf5 == 'WFO') {
                    if (($data_lat >= $lat_bawah && $data_lat <= $lat_atas) || ($data_long >= $long_bawah && $data_long <= $long_atas)) {
                        date_default_timezone_set('Asia/Jakarta');
                        $this->validate($request, [
                            'gambar' => 'required|mimes:jpg,png,jpeg',
                        ]);

                        $image = Input::file('gambar');
                        $extension = $image->getClientOriginalExtension();
                        $fileName = rand(11111, 99999) . '.' . $extension;
                        $input['gambar'] = $fileName;

                        $pulang  = date("H:i:s");

                        $cek =
                            DB::select('select * from tb_presensi where id_user = "' . $id . '" and  tanggal="' . date('Y-m-d') . '"');

                        if (empty($cek)) {
                            Session::flash('gagal', 'Anda belum melakukan presensi berangkat');
                        } else {
                            $path = public_path('img/swafoto2/' . $fileName);
                            $img = Image::make($image->getRealPath())->save($path);

                            $ubah = DB::update("Update tb_presensi SET pulang = '" . $pulang . "', lokasi_pulang = '" . $request->lokasi . "', id_session_pulang = '" . $idsession . "', swafoto2 = '" . $input['gambar'] . "', laporan_wfo = '" . $request->laporan_wfo . "' where id_user = '" . $id . "' order by id DESC limit 1");
                            Session::flash('sukses', 'Anda berhasil melakukan presensi pulang');
                            return Redirect::to('/');
                        }
                        return back();
                    } else {
                        Session::flash('gagal', 'Lokasi anda diluar radius, silahkan aktifkan ulang lokasi');
                        return back();
                    }
                } else {
                }
            }
        } else if (date('D') == 'Sat') {
            if (!isset($wf6) || $wf6 == 'OFF') {
                Session::flash('gagal', 'Jam Kerja anda belum diatur atau terjadwal Off, hubungi Administrator Sistem');
                return back();
            } else {
                if ($wf6 == 'WFO') {
                    if (($data_lat >= $lat_bawah && $data_lat <= $lat_atas) || ($data_long >= $long_bawah && $data_long <= $long_atas)) {
                        date_default_timezone_set('Asia/Jakarta');
                        $this->validate($request, [
                            'gambar' => 'required|mimes:jpg,png,jpeg',
                        ]);

                        $image = Input::file('gambar');
                        $extension = $image->getClientOriginalExtension();
                        $fileName = rand(11111, 99999) . '.' . $extension;
                        $input['gambar'] = $fileName;

                        $pulang  = date("H:i:s");

                        $cek =
                            DB::select('select * from tb_presensi where id_user = "' . $id . '" and  tanggal="' . date('Y-m-d') . '"');

                        if (empty($cek)) {
                            Session::flash('gagal', 'Anda belum melakukan presensi berangkat');
                        } else {
                            $path = public_path('img/swafoto2/' . $fileName);
                            $img = Image::make($image->getRealPath())->save($path);

                            $ubah = DB::update("Update tb_presensi SET pulang = '" . $pulang . "', lokasi_pulang = '" . $request->lokasi . "', id_session_pulang = '" . $idsession . "', swafoto2 = '" . $input['gambar'] . "', laporan_wfo = '" . $request->laporan_wfo . "' where id_user = '" . $id . "' order by id DESC limit 1");
                            Session::flash('sukses', 'Anda berhasil melakukan presensi pulang');
                            return Redirect::to('/');
                        }
                        return back();
                    } else {
                        Session::flash('gagal', 'Lokasi anda diluar radius, silahkan aktifkan ulang lokasi');
                        return back();
                    }
                } else {
                }
            }
        } else if (date('D') == 'Sun') {
            if (!isset($wf7) || $wf7 == 'OFF') {
                Session::flash('gagal', 'Jam Kerja anda belum diatur atau terjadwal Off, hubungi Administrator Sistem');
                return back();
            } else {
                if ($wf7 == 'WFO') {
                    if (($data_lat >= $lat_bawah && $data_lat <= $lat_atas) || ($data_long >= $long_bawah && $data_long <= $long_atas)) {
                        date_default_timezone_set('Asia/Jakarta');
                        $this->validate($request, [
                            'gambar' => 'required|mimes:jpg,png,jpeg',
                        ]);

                        $image = Input::file('gambar');
                        $extension = $image->getClientOriginalExtension();
                        $fileName = rand(11111, 99999) . '.' . $extension;
                        $input['gambar'] = $fileName;

                        $pulang  = date("H:i:s");

                        $cek =
                            DB::select('select * from tb_presensi where id_user = "' . $id . '" and  tanggal="' . date('Y-m-d') . '"');

                        if (empty($cek)) {
                            Session::flash('gagal', 'Anda belum melakukan presensi berangkat');
                        } else {
                            $path = public_path('img/swafoto2/' . $fileName);
                            $img = Image::make($image->getRealPath())->save($path);

                            $ubah = DB::update("Update tb_presensi SET pulang = '" . $pulang . "', lokasi_pulang = '" . $request->lokasi . "', id_session_pulang = '" . $idsession . "', swafoto2 = '" . $input['gambar'] . "', laporan_wfo = '" . $request->laporan_wfo . "' where id_user = '" . $id . "' order by id DESC limit 1");
                            Session::flash('sukses', 'Anda berhasil melakukan presensi pulang');
                            return Redirect::to('/');
                        }
                        return back();
                    } else {
                        Session::flash('gagal', 'Lokasi anda diluar radius, silahkan aktifkan ulang lokasi');
                        return back();
                    }
                } else {
                }
            }
        } else {
            Session::flash('gagal', 'Hari ini Tanggal ' . date('d-m-Y'));
            return back();
        }
    }

    /*public function inputizin()
    {
        date_default_timezone_set('Asia/Jakarta');
        $jam  = date("H:i:s");
        $nama = DB::select('select * from users');
        $tanggal  = date("Y-m-d");
        $data = DB::select('Select users.nama, tb_jammasuk.masuk_senin, tb_jammasuk.masuk_selasa, tb_jammasuk.masuk_rabu, tb_jammasuk.masuk_kamis, tb_jammasuk.masuk_jumat, tb_jammasuk.masuk_sabtu, tb_jammasuk.masuk_minggu, tb_presensi.berangkat, tb_presensi.keterangan, tb_presensi.keterangan_rinci, tb_presensi.pulang, tb_presensi.tanggal, DATE_FORMAT(tb_presensi.created_at, "%Y/%m/%d") as created_at 
            from users
            left join tb_presensi ON users.id = tb_presensi.id_user 
            left join tb_jammasuk ON users.id = tb_jammasuk.id_user
            where tb_presensi.tanggal = "'.$tanggal.'" and NOT tb_presensi.keterangan = "" group by users.id
            order by tb_presensi.created_at ASC');
        return view('home.input-izin', compact('data','nama','tanggal','jam'));
    }*/

    public function simpanizin(Request $request)
    {
        error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
        date_default_timezone_set('Asia/Jakarta');
        $this->validate($request, [
            'nik' => 'required',
            'gambar' => 'required|image|mimes:jpg,png,jpeg',
        ]);

        $extension = Input::file('gambar')->getClientOriginalExtension();
        $fileName = rand(11111, 99999) . '.' . $extension;
        $green = Input::file('gambar');
        $img = Image::make($green)->save('img/izin/' . $fileName);
        $input['gambar'] = $fileName;

        $nik = $request->nik;
        $data = DB::Select('select id from users WHERE nik = "' . $nik . '"');
        foreach ($data as $key => $value) {
            $id_user = $value->id;
        }

        $izin = new Pengajuanizin();
        $izin->id_user = $id_user;
        $izin->tanggal = $_POST['tanggal'];
        $izin->alasan = $_POST['alasan'];
        $izin->bukti_pendukung = $input['gambar'];
        $izin->jenis_izin = $_POST['jenis_izin'];
        $izin->status = 'pengajuan';

        //if ($request->tanggal) {
        //$tgl=$request->tanggal;
        //$xp=explode("-",$tgl);
        //$rr=array($xp[2],$xp[1],$xp[0]);
        //$tanggal=implode("-",$rr);
        //$presensi->created_at = $tanggal;
        //}

        $tumbukan =
            DB::select('select id_user from tb_pengajuan_izin where id_user = "' . $id_user . '" and  tanggal="' . date('Y-m-d') . '"');
        if ($tumbukan) {
            Session::flash('gagal', 'Sudah mengisi izin hari ini');
        } else {
            $izin->save();
            Session::flash('sukses', 'Data berhasil disimpan');
            return back();
        }
        return back();
    }

    public function kehadiran()
    {
        error_reporting(E_ERROR | E_WARNING | E_PARSE);
        date_default_timezone_set('Asia/Jakarta');
        $tanggal  = date("Y-m-d");
        $data = DB::select('Select users.nama, tb_jammasuk.jammasuk, tb_jammasuk.sabtu, tb_jammasuk.minggu, tb_presensi.berangkat, tb_presensi.pulang, tb_presensi.tanggal, DATE_FORMAT(tb_presensi.created_at, "%Y/%m/%d") as created_at 
            from users
            left join tb_presensi ON users.id = tb_presensi.id_user 
            left join tb_jammasuk ON users.id = tb_jammasuk.id_user
            where tb_presensi.tanggal = "' . $tanggal . '" group by users.id
            order by tb_presensi.created_at ASC');
        foreach ($data as $key => $value) {
            $gettanggal = $value->created_at;
        }

        //  $daftar_hari = array(
        //     'Sunday' => 'Minggu',
        //     'Monday' => 'Senin',
        //     'Tuesday' => 'Selasa',
        //     'Wednesday' => 'Rabu',
        //     'Thursday' => 'Kamis',
        //     'Friday' => 'Jumat',
        //     'Saturday' => 'Sabtu'
        // );

        $namahari = date('l', strtotime($gettanggal));
        return view('home.kehadiran', compact('data', 'tanggal', 'namahari'));
    }

    public function simpanregister(Request $request)
    {
        error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
        $acak = rand(11111, 99999);
        $id_t = str_shuffle($acak);

        if (Input::hasFile('foto')) {
            $file = array('foto' => Input::file('foto'));
            if (Input::file('foto')->isValid()) {
                $destinationPath = 'images/presensi';
                $extension = Input::file('foto')->getClientOriginalExtension(); // getting image extension
                $fileName = rand(11111, 99999) . '.' . $extension; // renaming image
                $green = Input::file('foto');
                $img = Image::make($green)->resize('211', '255')->save(public_path().'/images/presensi/' . $fileName);
                $input['foto'] = $destinationPath . '/' . $fileName;

                $pegawai = new Detailuser();
                $pegawai->id_user = $id_t;
                $pegawai->nm_lengkap = $_POST['nm_lengkap'];
                $pegawai->jk = $_POST['jk'];
                $pegawai->nik = $_POST['nik'];
                $pegawai->tempat = $_POST['tempat'];
                $pegawai->tgl_lahir = $_POST['tgl_lahir'];
                $pegawai->agama = $_POST['agama'];
                $pegawai->status = $_POST['status'];
                $pegawai->kewarganegaraan = $_POST['kewarganegaraan'];
                $pegawai->nm_ibu = $_POST['nm_ibu'];
                Session::flash('sukses', 'Data berhasil ditambahkan');
                $pegawai->save();

                $nik = $_POST['nik'];

                $user = new User();
                $user->id = $id_t;
                $user->foto = $input['foto'];
                $user->name = $_POST['nm_lengkap'];
                $user->email = $_POST['email'];
                $user->username = $_POST['username'];
                $user->password = bcrypt($_POST['password']);
                $user->level = $_POST['level'];
                $user->status = 'Aktif';
                $user->unit = $_POST['unit'];
                $user->nik = $_POST['nik'];
                Session::flash('sukses', 'Data berhasil ditambahkan');
                $user->save();

                $alamat = new Alamat();
                $alamat->id_user = $id_t;
                $alamat->jl = $_POST['jl'];
                $alamat->rt = $_POST['rt'];
                $alamat->rw = $_POST['rw'];
                $alamat->dusun = $_POST['dusun'];
                $alamat->desa = $_POST['desa'];
                $alamat->kecamatan = $_POST['kecamatan'];
                $alamat->kode_pos = $_POST['kode_pos'];
                Session::flash('sukses', 'Data berhasil ditambahkan');
                $alamat->save();

                $kontak = new Kontak();
                $kontak->id_user = $id_t;
                $kontak->no_telp = $_POST['no_telp'];
                $kontak->no_hp = $_POST['no_hp'];
                $kontak->email = $_POST['email'];
                Session::flash('sukses', 'Data berhasil ditambahkan');
                $kontak->save();

                $kepegawaian = new Kepegawaian();
                $kepegawaian->id_user = $id_t;
                $kepegawaian->status_kepegawaian = $_POST['status_kepegawaian'];
                $kepegawaian->nik = $_POST['nik'];
                $kepegawaian->niy_nikk = $_POST['niy_nikk'];
                $kepegawaian->nuptk = $_POST['nuptk'];
                $kepegawaian->sk_pengangkatan = $_POST['sk_pengangkatan'];
                Session::flash('sukses', 'Data berhasil ditambahkan');
                $kepegawaian->save();

                Session::flash('sukses', 'Data berhasil disimpan');
                return back();
            }
        } else {
            $pegawai = new Detailuser();
            $pegawai->id_user = $id_t;
            $pegawai->nm_lengkap = $_POST['nm_lengkap'];
            $pegawai->jk = $_POST['jk'];
            $pegawai->nik = $_POST['nik'];
            $pegawai->tempat = $_POST['tempat'];
            $pegawai->tgl_lahir = $_POST['tgl_lahir'];
            $pegawai->agama = $_POST['agama'];
            $pegawai->status = $_POST['status'];
            $pegawai->kewarganegaraan = $_POST['kewarganegaraan'];
            $pegawai->nm_ibu = $_POST['nm_ibu'];
            Session::flash('sukses', 'Data berhasil ditambahkan');
            $pegawai->save();

            $user = new User();
            $user->id = $id_t;
            $user->foto = $input['foto'];
            $user->name = $_POST['nm_lengkap'];
            $user->email = $request->email;
            $user->username = $request->username;
            $user->password = bcrypt($_POST['password']);
            $user->level = $request->level;
            $user->unit = $request->unit;
            $user->status = 'Aktif';
            $user->nik = $_POST['nik'];
            Session::flash('sukses', 'Data berhasil ditambahkan');
            $user->save();

            $alamat = new Alamat();
            $alamat->id_user = $id_t;
            $alamat->jl = $_POST['jl'];
            $alamat->rt = $_POST['rt'];
            $alamat->rw = $_POST['rw'];
            $alamat->dusun = $_POST['dusun'];
            $alamat->desa = $_POST['desa'];
            $alamat->kecamatan = $_POST['kecamatan'];
            $alamat->kode_pos = $_POST['kode_pos'];
            Session::flash('sukses', 'Data berhasil ditambahkan');
            $alamat->save();

            $kontak = new Kontak();
            $kontak->id_user = $id_t;
            $kontak->no_telp = $_POST['no_telp'];
            $kontak->no_hp = $_POST['no_hp'];
            $kontak->email = $_POST['email'];
            Session::flash('sukses', 'Data berhasil ditambahkan');
            $kontak->save();

            $kepegawaian = new Kepegawaian();
            $kepegawaian->id_user = $id_t;
            $kepegawaian->status_kepegawaian = $_POST['status_kepegawaian'];
            $kepegawaian->nik = $_POST['nik'];
            $kepegawaian->niy_nikk = $_POST['niy_nikk'];
            $kepegawaian->nuptk = $_POST['nuptk'];
            $kepegawaian->sk_pengangkatan = $_POST['sk_pengangkatan'];
            Session::flash('sukses', 'Data berhasil ditambahkan');
            $kepegawaian->save();

            Session::flash('sukses', 'Data berhasil disimpan');
            return back();
        }
    }

    public function simpangambar()
    {
        $name = date('YmdHis');
        $acak = rand(111111111, 999999999);
        $idt = str_shuffle($acak);
        $newname = "webcam/images/" . $name . ".jpg";
        $file = file_put_contents($newname, file_get_contents('php://input'));
        if (!$file) {
            print "ERROR: Failed to write data to $filename, check permissions\n";
            exit();
        } else {
            $data = new Gambar;
            $data->id = $idt;
            $data->name = $_POST['name'];
            $data->images = $newname;
            $data->save();
        }

        $url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/' . $newname;
        print "$url\n";
    }

    public function panduan()
    {
        $data = DB::Select('select tb_pengumuman.*, users.nama from tb_pengumuman JOIN users ON tb_pengumuman.id_user = users.nik order by created_at DESC');
        return view('home.panduan', compact('data'));
    }


    public function datapegawai()
    {
        $data = DB::select('select * from users where users.level != "Admin" Order by users.nik ASC');
        return view('home.data-pegawai', compact('data'));
    }

    public function simpandatapegawai(Request $request)
    {
        error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

        $acak = rand(11111, 99999);
        $id_t = str_shuffle($acak);

        $pegawai = new Pegawai();
        $pegawai->id = $id_t;
        $pegawai->nm_lengkap = $_POST['nm_lengkap'];
        $pegawai->jk = $_POST['jk'];
        $pegawai->nik = $_POST['nik'];
        $pegawai->tempat = $_POST['tempat'];
        $pegawai->tgl_lahir = $_POST['tgl_lahir'];
        $pegawai->agama = $_POST['agama'];
        $pegawai->status = $_POST['status'];
        $pegawai->kewarganegaraan = $_POST['kewarganegaraan'];
        $pegawai->nm_ibu = $_POST['nm_ibu'];
        Session::flash('sukses', 'Data berhasil ditambahkan');
        $pegawai->save();

        $nik = $_POST['nik'];

        $user = new User();
        $user->id = $id_t;
        $user->name = $_POST['nm_lengkap'];
        $user->username = $_POST['username'];
        $user->password = bcrypt($_POST['password']);
        $user->level = $_POST['level'];
        $user->nik = $nik;
        Session::flash('sukses', 'Data berhasil ditambahkan');
        $user->save();

        $alamat = new Alamat();
        $alamat->id_pegawai = $id_t;
        $alamat->jl = $_POST['jl'];
        $alamat->rt = $_POST['rt'];
        $alamat->rw = $_POST['rw'];
        $alamat->dusun = $_POST['dusun'];
        $alamat->desa = $_POST['desa'];
        $alamat->kecamatan = $_POST['kecamatan'];
        $alamat->kode_pos = $_POST['kode_pos'];
        Session::flash('sukses', 'Data berhasil ditambahkan');
        $alamat->save();

        $kontak = new Kontak();
        $kontak->id_pegawai = $id_t;
        $kontak->no_telp = $_POST['no_telp'];
        $kontak->no_hp = $_POST['no_hp'];
        $kontak->email = $_POST['email'];
        Session::flash('sukses', 'Data berhasil ditambahkan');
        $kontak->save();

        $kepegawaian = new Kepegawaian();
        $kepegawaian->id_pegawai = $id_t;
        $kepegawaian->status_kepegawaian = $_POST['status_kepegawaian'];
        $kepegawaian->nik = $_POST['nik'];
        $kepegawaian->niy_nikk = $_POST['niy_nikk'];
        $kepegawaian->nuptk = $_POST['nuptk'];
        $kepegawaian->sk_pengangkatan = $_POST['sk_pengangkatan'];
        Session::flash('sukses', 'Data berhasil ditambahkan');
        $kepegawaian->save();

        return back();
        Session::flash('sukses', 'Data berhasil ditambahkan');
    }
}
