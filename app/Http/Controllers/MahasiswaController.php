<?php
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\DB;
use App\Models\Kelas;
 
 
class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
	public function index()
	{
    	//yang semula mahasiswa::all, diubah menjadi with() yang menyatakan relasi
		$mahasiswa = Mahasiswa::with('kelas')->get();
		$paginate = Mahasiswa::orderBy('nim', 'asc')->paginate(3);
		return view('mahasiswas.index', ['mahasiswa' => $mahasiswa, 'paginate'=>$paginate]);
 
	}
	public function create()
	{
		$kelas = kelas::all(); // mendapatkan data dari tabel kelas
		return view('mahasiswas.create',['kelas'=>$kelas]);
	}
	public function store(Request $request)
	{
		//melakukan validasi data
		$request->validate([
			'Nim' => 'required',
			'Nama' => 'required',
			'Kelas' => 'required',
			'Jurusan' => 'required',
		]);

		$mahasiswa = new Mahasiswa;
		$mahasiswa->nim = $request->get('Nim');
		$mahasiswa->nama = $request->get('Nama');
		$mahasiswa->jurusan = $request->get('Jurusan');
		$mahasiswa->no_handphone = $request->get('No_Handphone');
		$mahasiswa->email = $request->get('Email');
		$mahasiswa->tanggal_lahir = $request->get('Tanggal_Lahir');
		$mahasiswa->save();

		$kelas = new Kelas;
		$kelas->id = $request->get('Kelas');

		//fungsi eloquent untuk menambah data dengan relasi belongsTo
		$mahasiswa->kelas()->associate($kelas);
		$mahasiswa->save();

		//jika data berhasil ditambahakan, maka akan kembali ke halaman utama
		return redirect()->route('mahasiswas.index')
		->with('success', 'Mahasiswa Berhasil Ditambahkan');
	}
	public function show($Nim)
	{
		$mahasiswa = Mahasiswa::with('kelas')->where('nim', $Nim)->first();
		return view('mahasiswas.detail', ['Mahasiswa' -> $mahasiswa]);
	}
	public function edit($Nim)
	{
		$mahasiswa = Mahasiswa::with('kelas')->where('nim', $Nim)->first();
		$kelas = Kelas::all();
		return view('mahasiswas.edit', compact('mahasiswa','kelas'));
	}
	public function update(Request $request, $Nim)
	{
		$request->validate([
			'Nim' => 'required',
			'Nama' => 'required',
			'Kelas' => 'required',
			'Jurusan' => 'required',
		]);

		$mahasiswa = Mahasiswa::with('kelas')->where('nim', $Nim)->first();
		$mahasiswa->nim = $request->get('Nim');
		$mahasiswa->nama = $request->get('Nama');
		$mahasiswa->jurusan = $request->get('Jurusan');
		$mahasiswa->no_handphone = $request->get('No_Handphone');
		$mahasiswa->email = $request->get('Email');
		$mahasiswa->tanggal_lahir = $request->get('Tanggal_Lahir');
		$mahasiswa->save();

		$kelas = new Kelas;
		$kelas->id = $request->get('Kelas');

		//fungsi eloquent untuk menambah data dengan relasi belongsTo
		$mahasiswa->kelas()->associate($kelas);
		$mahasiswa->save();

		//jika data berhasil ditambahakan, maka akan kembali ke halaman utama
		return redirect()->route('mahasiswas.index')
		->with('success', 'Mahasiswa Berhasil Ditambahkan');
	}
}