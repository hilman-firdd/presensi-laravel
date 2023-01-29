<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Jam;
use App\Presensi;
use App\ArsipUser;
use App\Pengajuancuti;
use App\Jeniscuti;
use App\Pengumuman;
use App\Header;
use App\Cuti;
use DB;
use Auth;
use Session;
use Image;
use Input;
use Excel;
use Mail;
//use App\Registrasi;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }

    //public function registrasi()
    //{
    //return view('admin.registrasi');
    //}

    public function mapslocation(Request $request)
    {
        error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
        date_default_timezone_set('Asia/Jakarta');
        $cari  = date('Y-m-d', strtotime($request['cari']));
        $locations = DB::select('select users.nama, 
            CASE WHEN tb_presensi.lokasi_pulang = "00:00:00" 
            THEN right(tb_presensi.lokasi_pulang, locate(",", reverse(tb_presensi.lokasi_pulang)) - 1)
            ELSE right(tb_presensi.lokasi_berangkat, locate(",", reverse(tb_presensi.lokasi_berangkat)) - 1) END as lng,
            CASE WHEN tb_presensi.lokasi_pulang = "00:00:00" 
            THEN left(tb_presensi.lokasi_pulang, locate(",", reverse(tb_presensi.lokasi_pulang)) - 1)
            ELSE
            left(tb_presensi.lokasi_berangkat, locate(",", reverse(tb_presensi.lokasi_berangkat)) - 1) END as lat
                from tb_presensi 
                LEFT JOIN users ON tb_presensi.id_user = users.nik
                where tb_presensi.tanggal = "' . $cari . '"
                ');
        return view('admin.maps-location', compact('locations', 'cari'));
    }

    /*public function simpanregistrasi(Request $request)
    {
        // dd($request->email);
        $ucapan = '';
        try{
            Mail::send('email', array('pesan' => $ucapan) , function($pesan) use($request){
                $pesan->to($request->email,'Test')->subject('Test');
                $pesan->from(env('MAIL_USERNAME','info@itshop.id'),'Test');
            });
        }catch (Exception $e){
            return response (['status' => false,'errors' => $e->getMessage()]);
        }

        $data = new Registrasi();
        $data->email = $_POST['email'];
        $data->name = $_POST['name'];
        $data->gelar_depan = $_POST['gelar_depan'];
        $data->gelar_belakang = $_POST['gelar_belakang'];
        $data->institusi = $_POST['institusi'];
        $data->no_hp = $_POST['no_hp'];
        $data->informasi = $_POST['informasi'];
        $data->pekerjaan = $_POST['pekerjaan'];
        $data->save();
        Session::flash('sukses', 'Registration Successful Check email to enter the zoom link');
        return back();
    }*/

    public function dashboard()
    {

        $data = DB::select('select * from users');
        $pegawai = DB::select('select count(id) as pegawai from users WHERE NOT level = 0 & 1');
        foreach ($pegawai as $key => $value) {
            $jml_pegawai = $value->pegawai;
        }
        $presensi = DB::select('select count(id_user) as presensi from tb_presensi where tanggal="' . date('Y-m-d') . '"');
        foreach ($presensi as $key => $value) {
            $jml_masuk = $value->presensi;
        }
        $izin = DB::select('select count(id_user) as presensi from tb_presensi where tanggal="' . date('Y-m-d') . '" and keterangan = "2"');
        foreach ($izin as $key => $value) {
            $jml_izin = $value->presensi;
        }
        $alfa = DB::select('select count(id_user) as presensi from tb_presensi where tanggal="' . date('Y-m-d') . '" and keterangan = "3"');
        foreach ($alfa as $key => $value) {
            $jml_alfa = $value->presensi;
        }

        $titip_presensi = DB::select('Select count(tb_presensi.id_user) as presensi, tb_presensi.id_user, tb_presensi.id, users.nama, tb_presensi.berangkat, tb_presensi.lokasi_berangkat, tb_presensi.hardware, tb_presensi.lokasi_pulang, tb_presensi.pulang, tb_presensi.tanggal, DAYNAME(tb_presensi.tanggal) as hari, tb_jammasuk.masuk_senin, tb_jammasuk.masuk_selasa, tb_jammasuk.masuk_rabu, tb_jammasuk.masuk_kamis, tb_jammasuk.masuk_jumat, tb_jammasuk.masuk_sabtu, tb_jammasuk.masuk_minggu, tb_presensi.keterangan_kerja, tb_presensi.ip, tb_presensi.id_session, tb_presensi.id_session_pulang
            from users
            JOIN tb_presensi ON users.id = tb_presensi.id_user
            JOIN tb_jammasuk ON users.id = tb_jammasuk.id_user
            Where tb_presensi.tanggal="' . date('Y-m-d') . '"

            GROUP by tb_presensi.id_session HAVING count(tb_presensi.id_user) > 1
            order by users.nik ASC, users.nama, tb_presensi.tanggal ASC');
        // dd($titip_presensi);

        $jml_tidak_berangkat = $jml_pegawai - $jml_masuk;

        $locations = DB::select('select users.nama, 
            CASE WHEN tb_presensi.lokasi_pulang = "00:00:00" 
            THEN right(tb_presensi.lokasi_pulang, locate(",", reverse(tb_presensi.lokasi_pulang)) - 1)
            ELSE right(tb_presensi.lokasi_berangkat, locate(",", reverse(tb_presensi.lokasi_berangkat)) - 1) END as lng,
            CASE WHEN tb_presensi.lokasi_pulang = "00:00:00" 
            THEN left(tb_presensi.lokasi_pulang, locate(",", reverse(tb_presensi.lokasi_pulang)) - 1)
            ELSE
            left(tb_presensi.lokasi_berangkat, locate(",", reverse(tb_presensi.lokasi_berangkat)) - 1) END as lat
                from tb_presensi 
                LEFT JOIN users ON tb_presensi.id_user = users.nik
                where tb_presensi.tanggal = "' . date('Y-m-d') . '"
                ');

        return view('admin.dashboard', compact('data', 'jml_masuk', 'jml_izin', 'jml_alfa', 'jml_tidak_berangkat', 'titip_presensi', 'locations'));
    }

    public function caribulan(Request $request)
    {
        $data_bulan = $request->bulan;
        if ($request->bulan == 1) {
            $nm_bulan = 'Januari';
        } elseif ($request->bulan == 2) {
            $nm_bulan = 'Februari';
        } elseif ($request->bulan == 3) {
            $nm_bulan = 'Maret';
        } elseif ($request->bulan == 4) {
            $nm_bulan = 'April';
        } elseif ($request->bulan == 5) {
            $nm_bulan = 'Mei';
        } elseif ($request->bulan == 6) {
            $nm_bulan = 'Juni';
        } elseif ($request->bulan == 7) {
            $nm_bulan = 'Juli';
        } elseif ($request->bulan == 8) {
            $nm_bulan = 'Agustus';
        } elseif ($request->bulan == 9) {
            $nm_bulan = 'September';
        } elseif ($request->bulan == 10) {
            $nm_bulan = 'Oktober';
        } elseif ($request->bulan == 11) {
            $nm_bulan = 'November';
        } elseif ($request->bulan == 12) {
            $nm_bulan = 'Desember';
        }

        $bulan = date('M-yy');
        $data = array(
            '1' => '01', '2' => '02', '3' => '03', '4' => '04',
            '5' => '05', '6' => '06', '7' => '07', '8' => '08', '9' => '09', '10' => '10', '11' => '11', '12' => '12'
        );
        $date1 = $request->tahun . '-' . $data[$request->bulan];
        $date2 = $data[$request->bulan] . '-' . $request->tahun;

        $datapresensi = DB::select('select tb_presensi.tanggal, tb_presensi.berangkat, tb_presensi.pulang, tb_presensi.keterangan_kerja, tb_presensi.keterangan_presensi from tb_presensi where tb_presensi.id_user = "' . Auth::user()->id . '" and tanggal LIKE "%' . $date1 . '%" order by tanggal DESC');
        $namaBulan = array(
            1 => "Januari", "Februari", "Maret", "April", "Mei", "Juni",
            "Juli", "Agustus",  "September", "Oktober",  "November", "Desember"
        );
        $hariIni = time();
        $tahun = date("Y", $hariIni);
        return view('admin.rekap-presensi-cari', compact('datapresensi', 'bulan', 'namaBulan', 'hariIni', 'tahun', 'date1', 'date2', 'data_bulan', 'nm_bulan'));
    }

    public function rekappresensi()
    {
        $bulan = date('M-yy');
        $datapresensi = DB::select('select tb_presensi.tanggal, tb_presensi.berangkat, tb_presensi.pulang, tb_presensi.keterangan_kerja, tb_presensi.keterangan_presensi from tb_presensi where tb_presensi.id_user = "' . Auth::user()->id . '" and month(tanggal) = "' . date('m') . '" order by tanggal DESC');
        $namaBulan = array(
            1 => "Januari", "Februari", "Maret", "April", "Mei", "Juni",
            "Juli", "Agustus",  "September", "Oktober",  "November", "Desember"
        );

        $bulanini = date('M');
        $bulanangka = date('n');

        $hariIni = time();
        $tahun = date("Y", $hariIni);
        return view('admin.rekap-presensi', compact('datapresensi', 'bulan', 'namaBulan', 'hariIni', 'tahun', 'bulanini', 'bulanangka'));
    }

    public function jeniscuti()
    {
        $data = DB::select('select * from tb_jeniscuti order by id ASC');
        return view('admin.cuti.jenis-cuti', compact('data'));
    }

    public function simpanjeniscuti(Request $request)
    {
        $data = new Jeniscuti();
        $data->jenis_cuti = $request->jenis_cuti;
        $data->keterangan = $_POST['keterangan'];
        $data->save();
        Session::flash('sukses', 'Data berhasil disimpan');
        return back();
    }

    public function deletejeniscuti(Request $request, $id)
    {
        DB::delete('delete from tb_jeniscuti where id = "' . $id . '"');
        Session::flash('sukses', 'Data berhasil dihapus');
        return back();
    }

    public function updatejeniscuti(Request $request, $id)
    {
        $data = Jeniscuti::find($id);
        $data->jenis_cuti = $request->jenis_cuti;
        $data->keterangan = $_POST['keterangan'];
        $data->save();
        Session::flash('sukses', 'Data berhasil diupdate');
        return back();
    }

    public function lihatcuti()
    {
        $data = DB::select('select * from tb_cuti where id_user = "' . Auth::user()->id . '"');
        return view('admin.cuti.lihat-cuti', compact('data'));
    }

    public function manajemenjatahcuti()
    {
        $nama = DB::select('SELECT id, nama from users WHERE id NOT IN ( SELECT id_user FROM tb_cuti )');
        $data = DB::select('select tb_cuti.*, users.nama, users.nik, users.unit 
            from tb_cuti 
            LEFT JOIN users ON tb_cuti.id_user = users.id
            order by tb_cuti.id_user ASC');
        return view('admin.cuti.manajemen-jatah-cuti', compact('data', 'nama'));
    }

    public function simpanjatahcuti(Request $request)
    {
        $data = new Cuti();
        $data->id_user = $request->id_user;
        $data->cuti_tahunan = $_POST['cuti_tahunan'];
        $data->cuti_bersama = $_POST['cuti_bersama'];
        $data->cuti_berjalan = $_POST['cuti_berjalan'];
        $data->cuti_lain = $_POST['cuti_lain'];
        $data->save();
        Session::flash('sukses', 'Data berhasil disimpan');
        return back();
    }

    public function updatejatahcuti(Request $request, $id)
    {
        $data = Cuti::find($id);
        $data->cuti_tahunan = $_POST['cuti_tahunan'];
        $data->cuti_bersama = $_POST['cuti_bersama'];
        $data->cuti_berjalan = $_POST['cuti_berjalan'];
        $data->cuti_lain = $_POST['cuti_lain'];
        $data->save();
        Session::flash('sukses', 'Data berhasil disimpan');
        return back();
    }

    public function deletejatahcuti(Request $request, $id)
    {
        DB::delete('delete from tb_cuti where id = "' . $id . '"');
        Session::flash('sukses', 'Data berhasil dihapus');
        return back();
    }

    public function pengajuancuti()
    {
        date_default_timezone_set('Asia/Jakarta');
        $tanggal1 = date('Y-m-d', strtotime('-7 day'));
        $tanggal2 = date('Y-m-d', strtotime('29 day'));
        $jeniscuti = DB::select('select * from tb_jeniscuti');
        return view('admin.cuti.pengajuan-cuti', compact('tanggal1', 'tanggal2', 'jeniscuti'));
    }

    public function simpanpengajuancuti(Request $request)
    {
        $tanggal = $_POST['tanggal'];
        $alasan = $_POST['alasan'];
        $jumlah_tanggal = count($tanggal);

        if (empty($request->lampiran)) {
            $namafile = '-';
        } else {
            $this->validate($request, [
                'lampiran' => 'required|mimes:pdf',
            ]);

            $time = Date('YmdHis');

            $file   = $request->file('lampiran');
            $ext    =  $file->getClientOriginalExtension();
            $namafile = $time . "." . $ext;
            $file->move(public_path().'/assets/file/cuti/', $namafile);
        }

        $this->validate($request, [
            'id_cuti' => 'required',
        ]);

        for ($x = 0; $x < $jumlah_tanggal; $x++) {
            DB::insert("INSERT INTO tb_pengajuan_cuti (id_user, tanggal, alasan, status, bukti_pendukung, id_cuti) values ('" . Auth::user()->id . "','$tanggal[$x]','$alasan','pengajuan','$namafile','$request->id_cuti')");
        }

        Session::flash('sukses', 'Pengajuan berhasil dikirim');
        return back();
    }

    public function daftarcuti()
    {
        $data = DB::select('select tb_pengajuan_cuti.*, tb_jeniscuti.jenis_cuti 
            from tb_pengajuan_cuti 
            LEFT JOIN tb_jeniscuti ON tb_pengajuan_cuti.id_cuti = tb_jeniscuti.id
            where tb_pengajuan_cuti.id_user = "' . Auth::user()->id . '" order by tb_pengajuan_cuti.tanggal DESC');
        return view('admin.cuti.daftar-cuti', compact('data'));
    }

    public function pengajuanizin()
    {
        date_default_timezone_set('Asia/Jakarta');
        $tanggal1 = date('Y-m-d', strtotime('-7 day'));
        $tanggal2 = date('Y-m-d', strtotime('29 day'));
        return view('admin.izin.pengajuan-izin', compact('tanggal1', 'tanggal2'));
    }

    public function simpanpengajuanizin(Request $request)
    {
        $tanggal = $_POST['tanggal'];
        $alasan = $_POST['alasan'];
        $jumlah_tanggal = count($tanggal);

        if (empty($request->lampiran)) {
            $namafile = '-';
        } else {
            $this->validate($request, [
                'lampiran' => 'required|mimes:pdf',
            ]);

            $time = Date('YmdHis');

            $file   = $request->file('lampiran');
            $ext    =  $file->getClientOriginalExtension();
            $namafile = $time . "." . $ext;
            $file->move(public_path().'/assets/file/izin/', $namafile);
        }

        for ($x = 0; $x < $jumlah_tanggal; $x++) {
            DB::insert("INSERT INTO tb_pengajuan_izin (id_user, tanggal, alasan, status, bukti_pendukung, jenis_izin) values ('" . Auth::user()->id . "','$tanggal[$x]','$alasan','pengajuan','$namafile','$request->jenis_izin')");
        }

        Session::flash('sukses', 'Pengajuan berhasil dikirim');
        return back();
    }

    public function daftarizin()
    {
        $data = DB::select('select *
            from tb_pengajuan_izin 
            where tb_pengajuan_izin.id_user = "' . Auth::user()->id . '" order by tb_pengajuan_izin.tanggal DESC');
        return view('admin.izin.daftar-izin', compact('data'));
    }

    public function batalpengajuanizin(Request $request)
    {
        if (isset($_POST['batal_pengajuan'])) {
            $idArr = $_POST['checked_id'];
            foreach ($idArr as $id) {
                DB::delete("delete from tb_pengajuan_izin where id=" . $id);
            }
            Session::flash('sukses', 'Data berhasil di hapus');
            return back();
        }
    }

    public function konfirmasiizin()
    {
        $data = DB::select('select users.nama, tb_pengajuan_izin.*
        from tb_pengajuan_izin 
        LEFT JOIN users ON tb_pengajuan_izin.id_user = users.id
        where tb_pengajuan_izin.status = "pengajuan" order by tb_pengajuan_izin.tanggal DESC');
        $data2 = DB::select('select users.nama, tb_pengajuan_izin.*
        from tb_pengajuan_izin 
        LEFT JOIN users ON tb_pengajuan_izin.id_user = users.id
        where NOT tb_pengajuan_izin.status = "pengajuan" order by tb_pengajuan_izin.tanggal DESC');
        return view('admin.izin.konfirmasi-izin', compact('data', 'data2'));
    }

    public function tolakizin(Request $request, $id)
    {
        DB::update("UPDATE tb_pengajuan_izin SET status = 'di tolak' where id=" . $id);
        Session::flash('sukses', 'Data berhasil di simpan');
        return back();
    }

    public function izinkanizin(Request $request, $id)
    {

        $get = DB::select('select * from tb_pengajuan_izin where id = "' . $id . '"');
        foreach ($get as $key => $value) {
            $presensi = new Presensi();
            $presensi->id_user = $value->id_user;
            $presensi->cuti = $value->jenis_izin;
            $presensi->tanggal = $value->tanggal;
            $presensi->save();
        }

        DB::update("UPDATE tb_pengajuan_izin SET status = 'di terima' where id=" . $id);
        Session::flash('sukses', 'Data berhasil di simpan');
        return back();
    }

    public function biodata()
    {
        $id = Auth::user()->id;
        $data = DB::select('select * from users where users.id ="' . $id . '"');
        return view('admin.biodata.index', compact('data'));
    }

    public function editbiodata($id)
    {
        $user = User::find($id);
        $data = DB::select('select * from users 
            left join tb_detail_user ON users.id = tb_detail_user.id_user
            left join tb_kepegawaian ON users.id = tb_kepegawaian.id_user
            left join tb_alamat ON users.id = tb_alamat.id_user
            left join tb_kontak ON users.id = tb_kontak.id_user
            where users.id ="' . $id . '"');
        return view('admin.biodata.edit', compact('biodata', 'data', 'user'));
    }

    public function tambahuser()
    {
        return view('admin.user.tambah');
    }

    public function simpanuser(Request $request)
    {
        error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
        $this->validate($request, [
            'id' => 'unique:users',
            'nik' => 'required|unique:users|max:20',
        ]);

        $user = new User();
        $user->email = $_POST['email'];
        $user->username = $_POST['username'];
        $user->password = bcrypt($_POST['password']);
        $user->password_view = $_POST['password'];
        $user->id = $_POST['nik'];
        $user->nik = $_POST['nik'];
        $user->level = $_POST['level'];
        $user->nama = $_POST['nama'];
        $user->gelar = $_POST['gelar'];
        $user->cluster = $_POST['cluster'];
        $user->jabatan = $_POST['jabatan'];
        $user->alamat_ktp = $_POST['alamat_ktp'];
        $user->alamat_domisili = $_POST['alamat_domisili'];
        $user->no_hp = $_POST['no_hp'];
        $user->save();
        Session::flash('sukses', 'Data berhasil disimpan');
        return back();
    }

    public function updatebiodata(Request $request)
    {
        error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
        $user = User::find(Auth::user()->id_user);
        $user->email = $_POST['email'];
        $user->username = $_POST['username'];
        $user->password = bcrypt($_POST['password']);
        $user->password_view = $_POST['password'];
        $user->nik = $_POST['nik'];
        $user->nama = $_POST['nama'];
        $user->gelar = $_POST['gelar'];
        $user->jabatan = $_POST['jabatan'];
        $user->alamat_ktp = $_POST['alamat_ktp'];
        $user->alamat_domisili = $_POST['alamat_domisili'];
        $user->no_hp = $_POST['no_hp'];
        $user->save();
        Session::flash('sukses', 'Data berhasil disimpan');
        return back();
    }

    public function manajemenuser()
    {
        $data = DB::select('select * from users order by level ASC, nik ASC');
        return view('admin.user.manajemen-user', compact('data'));
    }

    public function manajemenpengumuman()
    {
        $data = DB::select('select tb_pengumuman.*, users.nama
            from users 
            JOIN tb_pengumuman ON tb_pengumuman.id_user = users.nik
            GROUP by tb_pengumuman.id
            order by tb_pengumuman.tanggal DESC');
        return view('admin.pengumuman.manajemen-pengumuman', compact('data'));
    }

    public function simpanmanajemenpengumuman(Request $request)
    {
        $this->validate($request, [
            'judul' => 'required',
            'gambar' => 'required|image|mimes:jpg,png,jpeg',
        ]);

        $extension = Input::file('gambar')->getClientOriginalExtension();
        $fileName = rand(11111, 99999) . '.' . $extension;
        $green = Input::file('gambar');
        $img = Image::make($green)->save(public_path().'/img/pengumuman/' . $fileName);
        $input['gambar'] = $fileName;

        $tanggal = Date('Y-m-d');

        $pengumuman = new Pengumuman();
        $pengumuman->gambar = $input['gambar'];
        $pengumuman->judul = $request->judul;
        $pengumuman->url = str_replace(" ", "_", $request->judul);
        $pengumuman->isi = $request->isi;
        $pengumuman->tanggal = $tanggal;
        $pengumuman->id_user = Auth::user()->nik;
        $pengumuman->view = 0;
        $pengumuman->save();

        Session::flash('sukses', 'Data berhasil di simpan');
        return back();
    }

    public function deletemanajemenpengumuman($id)
    {

        $get = DB::select('select * from tb_pengumuman where id = "' . $id . '"');
        foreach ($get as $key => $value) {
            $target = $value->gambar;
        }

        if (file_exists(public_path().'/img/pengumuman/' . $target)) {
            unlink(public_path().'/img/pengumuman/' . $target);
        } else {
            # code...
        }

        $data = Pengumuman::find($id);
        $data->delete();

        Session::flash('sukses', 'Data berhasil di hapus');
        return back();
    }

    public function header()
    {
        $data = DB::select('select * from tb_header');
        return view('admin.header.atur-header', compact('data'));
    }

    public function updateheader(Request $request, $id)
    {
        if (empty($request->logo)) {
            $header = Header::find($id);
            $header->yayasan = $request->yayasan;
            $header->unit = $request->unit;
            $header->lat = $request->lat;
            $header->lng = $request->lng;
            $header->save();
        } else {
            $this->validate($request, [
                'logo' => 'required|image|mimes:jpg,png,jpeg',
            ]);

            $extension = Input::file('logo')->getClientOriginalExtension();
            $fileName = rand(11111, 99999) . '.' . $extension;
            $green = Input::file('logo');
            $img = Image::make($green)->save(public_path().'/img/header/' . $fileName);
            $input['logo'] = $fileName;

            $header = Header::find($id);
            $header->logo = $input['logo'];
            $header->yayasan = $request->yayasan;
            $header->unit = $request->unit;
            $header->lat = $request->lat;
            $header->lng = $request->lng;
            $header->save();
        }

        Session::flash('sukses', 'Data berhasil di simpan');
        return back();
    }

    public function aturradius()
    {
        $radius = DB::select('select * from tb_radius');
        return view('admin.atur-radius', compact('radius'));
    }

    public function updateradius(Request $request, $id)
    {
        error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
        DB::update("Update tb_radius SET long_bawah = '" . $request->long_bawah . "', long_atas = '" . $request->long_atas . "', lat_bawah = '" . $request->lat_bawah . "', lat_atas = '" . $request->lat_atas . "' where id = '" . $id . "' ");
        Session::flash('sukses', 'Data berhasil di update');
        return back();
    }

    public function jammasuk()
    {

        $nama = DB::select('SELECT * from users WHERE id NOT IN ( SELECT id_user FROM tb_jammasuk )');
        $data = DB::select('select users.nama, tb_jammasuk.id_user, users.nik, tb_jammasuk.id, tb_jammasuk.masuk_senin, tb_jammasuk.masuk_selasa, tb_jammasuk.masuk_rabu, tb_jammasuk.masuk_kamis, tb_jammasuk.masuk_jumat, tb_jammasuk.masuk_sabtu, tb_jammasuk.masuk_minggu, tb_jammasuk.keluar_senin, tb_jammasuk.keluar_selasa, tb_jammasuk.keluar_rabu, tb_jammasuk.keluar_kamis, tb_jammasuk.keluar_jumat, tb_jammasuk.keluar_sabtu, tb_jammasuk.keluar_minggu,tb_jammasuk.wf1, tb_jammasuk.wf2, tb_jammasuk.wf3, tb_jammasuk.wf4, tb_jammasuk.wf5, tb_jammasuk.wf6, tb_jammasuk.wf7 from tb_jammasuk
        left join users ON  tb_jammasuk.id_user = users.id');
        return view('admin.jam-masuk.index', compact('data', 'nama'));
    }

    public function simpanjam()
    {
        $simpan = new Jam();
        $simpan->id_user = $_POST['id_user'];
        $simpan->masuk_senin = $_POST['masuk_senin'];
        $simpan->masuk_selasa = $_POST['masuk_selasa'];
        $simpan->masuk_rabu = $_POST['masuk_rabu'];
        $simpan->masuk_kamis = $_POST['masuk_kamis'];
        $simpan->masuk_jumat = $_POST['masuk_jumat'];
        $simpan->masuk_sabtu = $_POST['masuk_sabtu'];
        $simpan->masuk_minggu = $_POST['masuk_minggu'];
        $simpan->keluar_senin = $_POST['keluar_senin'];
        $simpan->keluar_selasa = $_POST['keluar_selasa'];
        $simpan->keluar_rabu = $_POST['keluar_rabu'];
        $simpan->keluar_kamis = $_POST['keluar_kamis'];
        $simpan->keluar_jumat = $_POST['keluar_jumat'];
        $simpan->keluar_sabtu = $_POST['keluar_sabtu'];
        $simpan->keluar_minggu = $_POST['keluar_minggu'];
        $simpan->wf1 = $_POST['wf1'];
        $simpan->wf2 = $_POST['wf2'];
        $simpan->wf3 = $_POST['wf3'];
        $simpan->wf4 = $_POST['wf4'];
        $simpan->wf5 = $_POST['wf5'];
        $simpan->wf6 = $_POST['wf6'];
        $simpan->wf7 = $_POST['wf7'];
        $simpan->save();
        Session::flash('sukses', 'Data berhasil di simpan');
        return back();
    }

    public function batalpengajuancuti(Request $request)
    {
        if (isset($_POST['batal_pengajuan'])) {
            $idArr = $_POST['checked_id'];
            foreach ($idArr as $id) {
                DB::delete("delete from tb_pengajuan_cuti where id=" . $id);
            }
            Session::flash('sukses', 'Data berhasil di hapus');
            return back();
        }
    }

    public function konfirmasicuti()
    {
        $data = DB::select('select users.nama, tb_pengajuan_cuti.*, tb_jeniscuti.jenis_cuti
        from tb_pengajuan_cuti 
        LEFT JOIN users ON tb_pengajuan_cuti.id_user = users.id
        LEFT JOIN tb_jeniscuti ON tb_pengajuan_cuti.id_cuti = tb_jeniscuti.id
        where tb_pengajuan_cuti.status = "pengajuan" order by tb_pengajuan_cuti.tanggal DESC');
        $data2 = DB::select('select users.nama, tb_pengajuan_cuti.*, tb_jeniscuti.jenis_cuti
        from tb_pengajuan_cuti
        LEFT JOIN users ON tb_pengajuan_cuti.id_user = users.id
        LEFT JOIN tb_jeniscuti ON tb_pengajuan_cuti.id_cuti = tb_jeniscuti.id
        where NOT tb_pengajuan_cuti.status = "pengajuan" order by tb_pengajuan_cuti.tanggal DESC');
        return view('admin.cuti.konfirmasi-cuti', compact('data', 'data2'));
    }

    public function tolakcuti(Request $request, $id)
    {
        DB::update("UPDATE tb_pengajuan_cuti SET status = 'di tolak' where id=" . $id);
        Session::flash('sukses', 'Data berhasil di simpan');
        return back();
    }

    public function izinkancuti(Request $request, $id)
    {
        $get = DB::select('select * from tb_pengajuan_cuti where id = "' . $id . '"');
        foreach ($get as $key => $value) {
            $get_jatah = DB::select('select * from tb_cuti where id_user = "' . $value->id_user . '"');
            $cutitahunan = 0;
            $cutiberjalan = 0;
            $cutibersama = 0;
            $cutilain = 0;
            foreach ($get_jatah as $key => $values) {
                $cutitahunan = $values->cuti_tahunan;
                $cutiberjalan = $values->cuti_berjalan;
                $cutibersama = $values->cuti_bersama;
                $cutilain = $values->cuti_lain;
            }

            if ($value->id_cuti == 1) {
                DB::update('update tb_cuti SET cuti_tahunan = "' . ($cutitahunan - 1) . '" where id_user = "' . $value->id_user . '"');
            } elseif ($value->id_cuti == 2) {
                DB::update('update tb_cuti SET cuti_berjalan = "' . ($cutiberjalan - 1) . '" where id_user = "' . $value->id_user . '"');
            } elseif ($value->id_cuti == 3) {
                DB::update('update tb_cuti SET cuti_bersama = "' . ($cutibersama - 1) . '" where id_user = "' . $value->id_user . '"');
            } elseif ($value->id_cuti == 4) {
                DB::update('update tb_cuti SET cuti_lain = "' . ($cutilain + 1) . '" where id_user = "' . $value->id_user . '"');
            }

            $presensi = new Presensi();
            $presensi->id_user = $value->id_user;
            $presensi->cuti = $value->id_cuti;
            $presensi->tanggal = $value->tanggal;
            $presensi->save();
        }

        DB::update("UPDATE tb_pengajuan_cuti SET status = 'di terima' where id=" . $id);
        Session::flash('sukses', 'Data berhasil di simpan');
        return back();
    }

    public function simpanizin(Request $request)
    {
        error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
        $id_user = $_POST['id_user'];

        date_default_timezone_set('Asia/Jakarta');

        $presensi = new Presensi();
        $presensi->id_user = $id_user;
        $presensi->cuti = 1;
        $presensi->keterangan = $_POST['keterangan'];
        $presensi->keterangan_rinci = $_POST['keterangan_rinci'];
        $presensi->tanggal = $request->tanggal;

        if ($request->tanggal) {
            $tgl = $request->tanggal;
            $xp = explode("", $tgl);
            $rr = array($xp[2], $xp[1], $xp[0]);
            $tanggal = implode("", $rr);
            $presensi->tanggal = $tanggal;
        }

        $tumbukan =
            DB::select('select id_user from tb_presensi where id_user = "' . $id_user . '" and  tanggal="' . date('Y-m-d') . '"');
        if ($tumbukan) {
            Session::flash('gagal', 'Sudah mengisi izin hari ini');
        } else {
            $presensi->save();
            Session::flash('sukses', 'Data berhasil disimpan');
            return back();
        }
        return back();
    }

    public function hapusizin()
    {
        if (isset($_POST['delete_submit'])) {
            $idArr = $_POST['checked_id'];
            foreach ($idArr as $id) {
                DB::delete("DELETE from tb_presensi where tb_presensi.id=" . $id);
            }
            Session::flash('sukses', 'Data berhasil di hapus');
            return back();
        }
    }

    public function updateizin($id)
    {
        error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
        date_default_timezone_set('Asia/Jakarta');
        $keterangan  = $_POST['keterangan'];
        $keterangan_rinci  = $_POST['keterangan_rinci'];
        DB::update("Update tb_presensi SET keterangan = '" . $keterangan . "' where id = '" . $id . "' ");
        DB::update("Update tb_presensi SET keterangan_rinci = '" . $keterangan_rinci . "' where id = '" . $id . "' ");
        Session::flash('sukses', 'Data berhasil di update');
        return back();
    }

    public function kepalaunit()
    {
        $data = DB::select('select * from users where users.level = "Kepala Unit"');
        return view('admin.user.kepala-unit', compact('data'));
    }

    public function detailbiodata($id)
    {
        $user = User::find($id);
        $data = DB::select('select * from users where users.id_user ="' . $id . '"');
        return view('admin.user.detail-biodata', compact('data', 'user'));
    }

    public function editdata($id)
    {
        $user = User::find($id);
        $data = DB::select('select * from users where users.id_user ="' . $id . '"');
        return view('admin.user.edit', compact('data', 'user'));
    }

    public function updatedata(Request $request, $id)
    {
        error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
        $user = User::find($id);
        $user->id = $_POST['nik'];
        $user->email = $_POST['email'];
        $user->username = $_POST['username'];
        $user->password = bcrypt($_POST['password']);
        $user->password_view = $_POST['password'];
        $user->nik = $_POST['nik'];
        $user->nama = $_POST['nama'];
        $user->gelar = $_POST['gelar'];
        $user->jabatan = $_POST['jabatan'];
        $user->alamat_ktp = $_POST['alamat_ktp'];
        $user->alamat_domisili = $_POST['alamat_domisili'];
        $user->no_hp = $_POST['no_hp'];
        $user->level = $_POST['level'];
        $user->cluster = $_POST['cluster'];
        $user->save();
        Session::flash('sukses', 'Data berhasil disimpan');
        return back();
    }

    public function hapusdata()
    {
        if (isset($_POST['delete_submit'])) {
            $idArr = $_POST['checked_id'];
            foreach ($idArr as $id) {
                $getdata = DB::select("select *
            from users
            where users.id = '$id'");

                if ($getdata) :
                    foreach ($getdata as $row) :
                        $data[$id][] = [
                            'id'           => $id,
                            'nik'              => $row->nik,
                            'nama'              => $row->nama,
                            'gelar'              => $row->gelar,
                            'jabatan'              => $row->jabatan,
                            'alamat_ktp'              => $row->alamat_ktp,
                            'alamat_domisili'              => $row->alamat_domisili,
                            'no_hp'              => $row->no_hp,
                            'level'              => $row->level,
                            'unit'              => $row->unit,
                            'username'              => $row->username,
                            'email'              => $row->email,
                            'password'              => $row->password,
                            'password_view'              => $row->password_view
                        ];
                    endforeach;
                    ArsipUser::insert($data[$id]);
                endif;

                DB::delete('DELETE users
            FROM users
            WHERE users.id = "' . $id . '"');
            }
            Session::flash('sukses', 'Data berhasil di arsipkan');
            return back();
        }
    }

    public function arsipuser()
    {
        $data = ArsipUser::all();
        return view('admin.user.arsip', compact('data'));
    }

    public function hapusjam(Request $request)
    {
        if (isset($_POST['delete_submit'])) {
            $idArr = $_POST['checked_id'];
            foreach ($idArr as $id) {
                DB::delete("DELETE from tb_jammasuk where tb_jammasuk.id=" . $id);
            }
            Session::flash('sukses', 'Data berhasil di hapus');
            return back();
        }
        // ------------------------------------------------------------
        elseif (isset($_POST['update_jammasuk'])) {
            $idArr = $_POST['checked_id'];
            foreach ($idArr as $id) {
                if ($request->jammasuk == '00:00:00') {
                } else {
                    DB::update("UPDATE tb_jammasuk SET jammasuk = '" . $request->jammasuk . "' where tb_jammasuk.id=" . $id);
                }
                if ($request->jamkeluar == '00:00:00') {
                } else {
                    DB::update("UPDATE tb_jammasuk SET jamkeluar = '" . $request->jamkeluar . "' where tb_jammasuk.id=" . $id);
                }
                // if ($request->minggu == '00:00:00') {

                // } else {
                //      DB::update("UPDATE tb_jammasuk SET minggu = '".$request->minggu."' where tb_jammasuk.id=".$id);
                // }

            }
            Session::flash('sukses', 'Data jam masuk berhasil di update');
            return back();
        }
        // ------------------------------------------------------------
        elseif (isset($_POST['update_keterangan'])) {
            $input = $request->all();
            $i = 0;
            $count = count($input['id_user']);

            while ($i < $count) {

                $data_id[] = array(
                    'id_user'   => $input['id_user'][$i],
                );

                $data[] = array(
                    'id_user'   => $input['id_user'][$i],
                    'wf1'       => $input['wf1'][$i] ?? "",
                    'wf2'       => $input['wf2'][$i] ?? "",
                    'wf3'       => $input['wf3'][$i] ?? "",
                    'wf4'       => $input['wf4'][$i] ?? "",
                    'wf5'       => $input['wf5'][$i] ?? "",
                    'wf6'       => $input['wf6'][$i] ?? "",
                    'wf7'       => $input['wf7'][$i] ?? "",
                    'masuk_senin'       => $input['masuk_senin'][$i],
                    'masuk_selasa'       => $input['masuk_selasa'][$i],
                    'masuk_rabu'       => $input['masuk_rabu'][$i],
                    'masuk_kamis'       => $input['masuk_kamis'][$i],
                    'masuk_jumat'       => $input['masuk_jumat'][$i],
                    'masuk_sabtu'       => $input['masuk_sabtu'][$i],
                    'masuk_minggu'       => $input['masuk_minggu'][$i],
                    'keluar_senin'       => $input['keluar_senin'][$i],
                    'keluar_selasa'       => $input['keluar_selasa'][$i],
                    'keluar_rabu'       => $input['keluar_rabu'][$i],
                    'keluar_kamis'       => $input['keluar_kamis'][$i],
                    'keluar_jumat'       => $input['keluar_jumat'][$i],
                    'keluar_sabtu'       => $input['keluar_sabtu'][$i],
                    'keluar_minggu'       => $input['keluar_minggu'][$i],
                );

                $i++;
            }
            $j = 0;
            $count1 = count($input['id_user']);
            while ($j < $count1) {
                DB::table('tb_jammasuk')->where('id_user', $data_id[$j]['id_user'])->update($data[$j]);
                $j++;
            }
            Session::flash('sukses', 'Data keterangan kerja berhasil di update');
            return back();
        }
    }

    public function updatejam(Request $request, $id)
    {
        error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
        date_default_timezone_set('Asia/Jakarta');
        $jammasuk  = $_POST['jammasuk'];
        $sabtu = $_POST['sabtu'];
        $minggu = $_POST['minggu'];

        DB::update("Update tb_jammasuk SET jammasuk = '" . $jammasuk . "', sabtu = '" . $sabtu . "', minggu = '" . $minggu . "', keterangan = '" . $request->keterangan . "' where id = '" . $id . "' ");

        Session::flash('sukses', 'Data berhasil disimpan');
        return back();
    }

    public function updatepresensi(Request $request, $id)
    {
        error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
        date_default_timezone_set('Asia/Jakarta');
        $presensi = Presensi::find($id);
        $presensi->berangkat = $request->berangkat;
        $presensi->pulang = $request->pulang;
        $presensi->save();

        Session::flash('sukses', 'Data berhasil di edit');
        return back();
    }

    public function presensi()
    {
        error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
        $tgl = DB::select('select tanggal from tb_presensi group by tanggal');
        date_default_timezone_set('Asia/Jakarta');
        $tanggal  = date('Y-m-d');
        $data = DB::select('Select tb_presensi.id, users.nama, tb_presensi.berangkat, tb_presensi.lokasi_berangkat, tb_presensi.hardware, tb_presensi.lokasi_pulang, tb_presensi.pulang, tb_presensi.tanggal, DAYNAME(tb_presensi.tanggal) as hari, tb_jammasuk.masuk_senin, tb_jammasuk.masuk_selasa, tb_jammasuk.masuk_rabu, tb_jammasuk.masuk_kamis, tb_jammasuk.masuk_jumat, tb_jammasuk.masuk_sabtu, tb_jammasuk.masuk_minggu, tb_presensi.keterangan_kerja, tb_presensi.ip, tb_presensi.id_session, tb_presensi.keterangan_presensi, tb_presensi.id_session_pulang, tb_presensi.swafoto1, tb_presensi.swafoto2, tb_presensi.laporan_wfo
        from users
        JOIN tb_presensi ON users.id = tb_presensi.id_user
        JOIN tb_jammasuk ON users.id = tb_jammasuk.id_user
        Where DATE(tb_presensi.created_at) = "' . $tanggal . '"
        order by users.nama ASC');
        return view('admin.laporan.index', compact('data'));
    }

    public function semuapresensi(Request $request)
    {
        error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
        date_default_timezone_set('Asia/Jakarta');
        $cari  = date('Y-m-d', strtotime($request['cari']));
        $data = DB::select('Select tb_presensi.id, users.nama, tb_presensi.berangkat, tb_presensi.lokasi_berangkat, tb_presensi.hardware, tb_presensi.lokasi_pulang, tb_presensi.pulang, tb_presensi.tanggal, DAYNAME(tb_presensi.tanggal) as hari, tb_jammasuk.masuk_senin, tb_jammasuk.masuk_selasa, tb_jammasuk.masuk_rabu, tb_jammasuk.masuk_kamis, tb_jammasuk.masuk_jumat, tb_jammasuk.masuk_sabtu, tb_jammasuk.masuk_minggu, tb_presensi.keterangan_kerja, tb_presensi.ip, tb_presensi.id_session, tb_presensi.keterangan_presensi, tb_presensi.id_session_pulang, tb_presensi.swafoto1, tb_presensi.swafoto2, tb_presensi.laporan_wfo
        from users
        JOIN tb_presensi ON users.id = tb_presensi.id_user
        JOIN tb_jammasuk ON users.id = tb_jammasuk.id_user
        Where tb_presensi.tanggal = "' . $cari . '"
        order by users.nama ASC');
        return view('admin.laporan.semua-presensi', compact('data', 'cari'));
    }

    //public function jumlahkehadiran()
    //{
    //error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
    //$tgl = DB::select('select tanggal from tb_presensi group by tanggal');
    //date_default_timezone_set('Asia/Jakarta');
    //$tanggal  = date("Y-m-d");
    //$data = DB::select('Select users.nama, tb_presensi.berangkat, tb_presensi.pulang, tb_presensi.tanggal from users, tb_presensi where users.id = tb_presensi.id_user and tb_presensi.tanggal = "'.$tanggal.'" ');
    //return view('admin.laporan.jumlah-kehadiran', compact('data','tanggal','tgl'));
    //}

    public function laporanpresensi()
    {
        error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
        $tgl = DB::select('select tanggal from tb_presensi group by tanggal');
        date_default_timezone_set('Asia/Jakarta');
        $tanggal  = date("Y-m-d");
        return view('admin.laporan.laporan-presensi', compact('data', 'tanggal', 'tgl'));
    }

    public function laporan(Request $request)
    {
        error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
        $dari1 = $request->dari;
        $sampai1 = $request->sampai;
        $dari  = date('Y-m-d', strtotime($request['dari']));
        $sampai  = date('Y-m-d', strtotime('1 DAY', strtotime($_GET['sampai'])));

        $tanggal1 = $request['dari'];

        $gettanggal = strtotime($sampai) -  strtotime($dari);

        $tanggal = DB::select('select date_format(tanggal, "%d") as tanggal from tb_presensi where tanggal between "' . $dari . '" and "' . $sampai . '" group by tanggal');

        $detail = DB::select('select s.nik, s.nama,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "01" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "01" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "01" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 
        
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h1,

        MAX(CASE
        
        WHEN date_format(b.tanggal, "%d") = "02" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "02" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "02" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 
        
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C"
        
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C"
    
        ELSE "" END ) as h2,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "03" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "03" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "03" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 
    
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 
        
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h3,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "04" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "04" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "04" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h4,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "05" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "05" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "05" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h5,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "06" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "06" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "06" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h6,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "07" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "07" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "07" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h7,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "08" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "08" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "08" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h8,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "09" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "09" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "09" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h9,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "10" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "10" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "10" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h10,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "11" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "11" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "11" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h11,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "12" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "12" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "12" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Monday" and  j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h12,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "13" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "13" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "13" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Saturday" and  j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C"

        ELSE "" END ) as h13,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "14" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "14" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "14" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C"
        
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C"

        ELSE "" END ) as h14,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "15" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "15" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "15" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C"

        ELSE "" END ) as h15,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "16" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "16" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "16" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C"

        ELSE "" END ) as h16,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "17" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "17" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "17" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C"

        ELSE "" END ) as h17,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "18" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "18" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "18" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C"

        ELSE "" END ) as h18,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "19" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "19" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "19" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C"

        ELSE "" END ) as h19,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "20" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "20" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "20" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C"

        ELSE "" END ) as h20,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "21" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "21" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "21" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C"

        ELSE "" END ) as h21,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "22" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "22" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "22" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C"

        ELSE "" END ) as h22,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "23" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "23" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "23" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Friday" and  j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C"

        ELSE "" END ) as h23,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "24" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "24" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "24" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C"

        ELSE "" END ) as h24,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "25" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "25" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "25" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C"

        ELSE "" END ) as h25,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "26" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "26" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "26" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C"

        ELSE "" END ) as h26,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "27" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "27" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "27" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C"

        ELSE "" END ) as h27,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "28" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "28" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "28" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C"

        ELSE "" END ) as h28,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "29" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "29" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "29" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C"

        ELSE "" END ) as h29,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "30" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "30" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "30" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C"

        ELSE "" END ) as h30,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "31" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "31" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "31" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C"

        ELSE "" END ) as h31

        from users s
        LEFT join tb_presensi b on s.id = b.id_user
        LEFT join tb_jammasuk j on s.id = j.id_user
        where b.tanggal between "' . $dari . '" and "' . $sampai . '" and s.cluster = "' . $request->cluster . '"
        group by s.id, b.id_user
        order by s.nama ASC');
        $cluster = $request->cluster;
        return view('admin.laporan.lihat-laporan', compact('detail', 'dari', 'sampai', 'data', 'tanggal', 'tanggal1', 'dari1', 'sampai1', 'cluster'));
    }

    public function downloadpresensi(Request $request, $type)
    {
        error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
        $dari1 = $request->dari;
        $sampai1 = $request->sampai;
        $dari  = date('Y-m-d', strtotime($request['dari']));
        $sampai  = date('Y-m-d', strtotime('1 DAY', strtotime($_GET['sampai'])));

        $tanggal1 = $request['dari'];

        $gettanggal = strtotime($sampai) -  strtotime($dari);

        $tanggal = DB::select('select date_format(tanggal, "%d") as tanggal from tb_presensi where tanggal between "' . $dari . '" and "' . $sampai . '" group by tanggal');

        $data = DB::select('select s.nik, s.nama,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "01" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "01" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "01" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "01" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h1,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "02" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "02" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "02" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "02" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h2,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "03" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h3,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "04" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "04" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "04" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "04" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h4,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "05" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "05" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "05" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "05" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h5,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "06" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "06" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "06" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "06" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h6,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "07" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "07" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "07" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "07" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h7,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "08" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "08" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "08" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "08" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h8,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "09" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "09" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "09" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "09" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h9,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "10" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "10" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "10" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C"
        
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "10" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h10,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "11" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "11" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "11" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "11" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h11,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "12" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "12" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "12" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "12" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h12,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "13" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "13" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "13" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "13" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h13,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "14" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "14" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "14" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "14" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h14,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "15" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "15" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "15" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "15" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h15,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "16" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "16" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "16" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "16" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h16,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "17" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "17" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "17" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "17" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h17,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "18" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "18" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "18" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "18" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h18,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "19" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "19" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "19" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "19" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h19,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "20" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "20" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "20" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "20" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h20,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "21" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "21" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "21" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "21" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h21,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "22" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "22" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "22" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "22" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h22,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "23" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "23" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "23" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "23" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h23,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "24" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "24" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "24" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "24" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h24,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "25" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "25" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "25" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "25" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h25,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "26" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "26" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "26" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "26" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h26,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "27" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "27" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "27" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "27" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h27,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "28" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "28" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "28" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "28" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h28,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "29" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "29" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "29" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "29" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h29,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "30" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "30" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "30" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C"
        
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "30" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 

        ELSE "" END ) as h30,

        MAX(CASE

        WHEN date_format(b.tanggal, "%d") = "31" and b.cuti = "D" THEN "D"
        WHEN date_format(b.tanggal, "%d") = "31" and b.cuti = "I1" THEN "I1"
        WHEN date_format(b.tanggal, "%d") = "31" and b.cuti = "]I2" THEN "]I2"

        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and j.keluar_senin < b.pulang and j.wf1 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin > b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Monday" and j.masuk_senin < b.berangkat and b.pulang = "00:00:00" and j.wf1 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Monday" and b.cuti = "4" and j.wf1 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Monday" and b.cuti = "1" and j.wf1 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and j.keluar_selasa < b.pulang and j.wf2 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa > b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Tuesday" and j.masuk_selasa < b.berangkat and b.pulang = "00:00:00" and j.wf2 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "4" and j.wf2 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Tuesday" and b.cuti = "1" and j.wf2 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and j.keluar_rabu < b.pulang and j.wf3 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu > b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Wednesday" and j.masuk_rabu < b.berangkat and b.pulang = "00:00:00" and j.wf3 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "4" and j.wf3 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Wednesday" and b.cuti = "1" and j.wf3 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and j.keluar_kamis < b.pulang and j.wf4 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis > b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Thursday" and j.masuk_kamis < b.berangkat and b.pulang = "00:00:00" and j.wf4 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "4" and j.wf4 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Thursday" and b.cuti = "1" and j.wf4 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and j.keluar_jumat < b.pulang and j.wf5 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat > b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Friday" and j.masuk_jumat < b.berangkat and b.pulang = "00:00:00" and j.wf5 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Friday" and b.cuti = "4" and j.wf5 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Friday" and b.cuti = "1" and j.wf5 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and j.keluar_sabtu < b.pulang and j.wf6 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu > b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Saturday" and j.masuk_sabtu < b.berangkat and b.pulang = "00:00:00" and j.wf6 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "4" and j.wf6 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Saturday" and b.cuti = "1" and j.wf6 = "WFO" THEN "C" 

        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "H"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and j.keluar_minggu < b.pulang and j.wf7 = "WFO" THEN "T"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu > b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "]"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Sunday" and j.masuk_minggu < b.berangkat and b.pulang = "00:00:00" and j.wf7 = "WFO" THEN "T]"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "4" and j.wf7 = "WFO" THEN "S"
        WHEN date_format(b.tanggal, "%d") = "31" and DAYNAME(b.tanggal)="Sunday" and b.cuti = "1" and j.wf7 = "WFO" THEN "C" 
 
        ELSE "" END ) as h31

        from users s
        LEFT join tb_presensi b on s.id = b.id_user
        LEFT join tb_jammasuk j on s.id = j.id_user
        where b.tanggal between "' . $dari . '" and "' . $sampai . '" and s.cluster = "' . $request->cluster . '"
        group by s.id, b.id_user
        order by s.nama ASC');
        $data = array_map(function ($value) {
            return (array)$value;
        }, $data);
        return Excel::create('Data Presensi-' . getdate()[0] . '', function ($excel) use ($data) {
            $excel->sheet('mySheet', function ($sheet) use ($data) {
                $sheet->fromArray($data);
            });
        })->download($type);
    }
}
