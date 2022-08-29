<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use App\Models\Users;
use Illuminate\Http\Request;
use Session;

class LoginController extends Controller
{
    public function index(){
        return view('login');
    }
    public function dologin(Request $req){
        // $user = Users::where('nik',$req->nik)->where('password',md5($req->password))->first();
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', 'https://cb.web.id/ggklikv2/api/getUser', ['form_params' => ['username' => $req->username,'password'=>$req->password]]);
     
        $responseBody = (object) json_decode($response->getBody());
        // dd($responseBody);
        
        if($response->getStatusCode() != 200 || $responseBody->status=="fail"){
            session(['type_modal' => 'fail', 'message' => 'gagal login']);
            return redirect()->route('login');
        }

        if($responseBody->data->divisi->id==4 && in_array($responseBody->data->level->id, [1,2,3,4])){
            session(['level_user' => "3",'id_user'=>$responseBody->data->nik]);
        }
        else if($responseBody->data->divisi->id==4 && in_array($responseBody->data->level->id, [5,6,7])){
            // if($responseBody->data->nik = "SG.0728.2022"){
            //     session(['level_user' => "1",'id_user'=>$responseBody->data->nik]);
            // }
            // else{
                session(['level_user' => "2",'id_user'=>$responseBody->data->nik]);
            // }
        }
        else{
            session(['level_user' => "1",'id_user'=>$responseBody->data->nik]);
        }
        
        session(['type_modal' => 'success', 'message' => 'berhasil login']);
        return redirect()->route('ticket.index');
    }
    public function logout(){
        Session::forget('level_user');
        Session::forget('id_user');
        // dd(Session::all());
        return redirect()->route('login');
    }
}
