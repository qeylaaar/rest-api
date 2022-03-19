<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Libraries\BaseApi;
class UserController extends Controller
{
    public function index()
    {
				//disini hanya perlu menggunakan BaseApi yg sebelumnya dibuat
				//hanya tinggal menambahkan endpoint yg akan digunakan yaitu '/user'
        $users = (new BaseApi)->index('/user',['limit'=>100]);

        return view('user.index')->with(['users' => $users]);
    }
    public function create(){
        return view('user.create');
    }
    public function store(Request $request)
    {
				// buat variable baru untuk menset parameter agar sesuai dengan documentasi
				$payload = [
            'firstName' => $request->input('nama_depan'),
            'lastName' => $request->input('nama_belakang'),
            'email' => $request->input('email'),
        ];


        $baseApi = new BaseApi;
        $response = $baseApi->create('/user/create', $payload);

				// handle jika request API nya gagal
        // diblade nanti bisa ditambahkan toast alert
        if ($response->failed()) {
            $errors = $response->json('data');
            return back()->with('error', 'ada yang salah');
        }else{
            return redirect('users')->with('success', 'berhasil');
        }
    }
    public function show($id)
{
		//kalian bisa coba untuk dd($response) untuk test apakah api nya sudah benar atau belum
    //sesuai documentasi api detail user akan menshow data detail seperti `email` yg tidak dimunculkan di api list index
    $response = (new BaseApi)->detail('/user', $id);
    $user = $response->json();
    return view('user.edit',compact('user'));
}

public function update(Request $request, $id)
{
//column yg bisa di update sesuai dengan documentasi dummyapi.io hanyalah
// `fisrtName`, `lastName`
$payload = [
    'firstName' => $request->input('nama_depan'),
    'lastName' => $request->input('nama_belakang'),
];

$response = (new BaseApi)->update('/user', $request->id, $payload);
if ($response->failed()) {
    $errors = $response->json('data');
    return back()->with('error', 'ada yang salah');
}else{
    return redirect('users')->with('success', 'berhasil');
}
}
public function destroy($id)
{
    $response = (new BaseApi)->delete('/user',$id);
    if ($response->failed()) {
        $errors = $response->json('data');
        return back()->with('error', 'ada yang salah');
    }else{
        return redirect('users')->with('success', 'berhasil');
    }
}
}
