<?php
//kurang tambah waktu chat
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tickets;
use App\Models\Chats;
use App\Models\Users;
use Session;

class TicketsController extends Controller
{
    public function __construct(){
        if(!Session::has('id_user')){
            return \Redirect::to('/')->send();
        }
        // \Debugbar::enable();
        // dd(Session::all());
    }
    public function index(){
        // session(['level_user' => "1",'id_user'=>'SG.0728.2022']); //user
        // session(['level_user' => "2",'id_user'=>'SG.0612.2021']); //operator
        // session(['level_user' => "3",'id_user'=>'SG.0156.2013']); //admin

        $id_user = Session::get('id_user');

        ///get all nik///
        $a1 = Tickets::select('id_user')->groupBy('id_user')->get()->pluck('id_user')->toArray();
        $a2 = Tickets::select('id_user_pic')->groupBy('id_user_pic')->get()->pluck('id_user_pic')->toArray();
        $b1 = Chats::select('to_user')->get()->pluck('to_user')->toArray();
        $b2 = Chats::select('from_user')->get()->pluck('from_user')->toArray();       
        $listNik = array_values(array_unique(array_merge($a1,$a2,$b1,$b2)));
        $nik = '';

        foreach($listNik as $key => $value){
            if($key==count($listNik)-1){
                $nik .= "'$value'";
            }
            else{
                $nik .= "'$value',";
            }
        }
        ///end get all nik///

        ///get all user in///
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', 'https://cb.web.id/ggklikv2/api/getAllUserIn', ['form_params' => ['nik' => $nik]]);
        $responseBody = json_decode($response->getBody());

        if($response->getStatusCode() != 200){
            session(['type_modal' => 'fail', 'message' => 'Ada Masalah Padda server']);
            return redirect()->route('ticket.index');
        }
        $listUser = collect($responseBody->listdata);
        ///end get all user in///

        ///get all user///
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', 'https://cb.web.id/ggklikv2/api/getAllUser', []);
        $responseBody = json_decode($response->getBody());
        
        if($response->getStatusCode() != 200){
            session(['type_modal' => 'fail', 'message' => 'Ada Masalah Padda server']);
            return redirect()->route('ticket.index');
        }
        $users = collect($responseBody->listdata);
        ///end get all user///
        
        if(Session::get('level_user')==1){
            ///mapping ticket with data user from server///
            $ticket = Tickets::with(['progress'])->where('id_user',Session::get('id_user'))->get();
            $ticket->each(function ($t) use($listUser){
                $user = $listUser->filter(function($u) use ($t) {
                    return stripos($u->nik,$t->id_user) !== false;
                })->values();
                $userPic = $listUser->filter(function($u) use ($t) {
                    return stripos($u->nik,$t->id_user_pic) !== false;
                })->values();

                unset($t->id_user);
                $t->user = $user;
                unset($t->id_user_pic);
                $t->userPic = $userPic;
            });
            ///end mapping ticket with data user from server///

            $pribadi = $ticket->filter(function($t) use ($id_user) {
                if(count($t->user)==0){
                    return true;
               }
               else{
                    return stripos($t->user[0]->nik,$id_user) !== false;
               }
            })->values();
            $total_waiting = count($ticket->whereIn('status', [0,1])->all());

            $datas = (object) [
                "pribadi"=>$pribadi,
                "total_waiting"=>$total_waiting
            ]; 
        }
        else if(Session::get('level_user')==2){
            ///mapping ticket with data user from server///
            $ticket = Tickets::with(['progress'])->get();

            $ticket->each(function ($t) use($listUser){
                $user = $listUser->filter(function($u) use ($t) {
                    return stripos($u->nik,$t->id_user) !== false;
                })->values();
                $userPic = $listUser->filter(function($u) use ($t) {
                    return stripos($u->nik,$t->id_user_pic) !== false;
                })->values();

                unset($t->id_user);
                $t->user = $user;
                unset($t->id_user_pic);
                $t->userPic = $userPic;
            });
            ///end mapping ticket with data user from server///

            $tugas = $ticket->filter(function($t) use ($id_user) {
                if(count($t->userPic)==0){
                     return true;
                }
                else{
                    return stripos($t->userPic[0]->nik,$id_user) !== false;
                }
            })->values();
            $pribadi = $ticket->filter(function($t) use ($id_user) {
               if(count($t->user)==0){
                    return true;
               }
               else{
                    return stripos($t->user[0]->nik,$id_user) !== false;
               }
            })->values();

            $datas = (object) [
                "tugas"=>$tugas,
                "pribadi"=>$pribadi
            ];
        }
        else{ 
            ///mapping ticket with data user from server///
            $ticket = Tickets::with(['progress'])->get();
            $ticket->each(function ($t) use($listUser){
                $user = $listUser->filter(function($u) use ($t) {
                    return stripos($u->nik,$t->id_user) !== false;
                })->values();
                $userPic = $listUser->filter(function($u) use ($t) {
                    return stripos($u->nik,$t->id_user_pic) !== false;
                })->values();

                unset($t->id_user);
                $t->user = $user;
                unset($t->id_user_pic);
                $t->userPic = $userPic;
            });
            ///end mapping ticket with data user from server///

            $tugas = $ticket;
            $pribadi = $ticket->filter(function($t) use ($id_user) {
                return stripos($t->user[0]->nik,$id_user) !== false;
            })->values();
            $total_waiting = count($pribadi->whereIn('status', [0,1])->all());

            $datas = (object) [
                "tugas"=>$tugas,
                "pribadi"=>$pribadi,
                "total_waiting"=>$total_waiting
            ];
        }
        // dd($datas);

        return view('index',[
            "parentview"=>'ticket',
            "subview"=>'',
            "tickets"=>$datas,
            "users"=>$users
        ]);
    }
    public function detail($id){
        // session(['level_user' => "1",'id_user'=>'SG.0728.2022']); //user
        // session(['level_user' => "2",'id_user'=>'SG.0612.2021']); //operator
        // session(['level_user' => "3",'id_user'=>'SG.0156.2013']); //

        ///get all nik///
        $a1 = Tickets::select('id_user')->groupBy('id_user')->get()->pluck('id_user')->toArray();
        $a2 = Tickets::select('id_user_pic')->groupBy('id_user_pic')->get()->pluck('id_user_pic')->toArray();
        $b1 = Chats::select('to_user')->get()->pluck('to_user')->toArray();
        $b2 = Chats::select('from_user')->get()->pluck('from_user')->toArray();       
        $listNik = array_values(array_unique(array_merge($a1,$a2,$b1,$b2)));
        $nik = '';
        
        foreach($listNik as $key => $value){
            if($key==count($listNik)-1){
                $nik .= "'$value'";
            }
            else{
                $nik .= "'$value',";
            }
        }
        ///end get all nik///
        
        ///get all user///
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', 'https://cb.web.id/ggklikv2/api/getAllUser', []);
        $responseBody = json_decode($response->getBody());
        
        if($response->getStatusCode() != 200){
            session(['type_modal' => 'fail', 'message' => 'Ada Masalah Padda server']);
            return redirect()->route('ticket.index');
        }
        $users = collect($responseBody->listdata);
        ///end get all user///
        
        ///get all user in///
        $response = $client->request('POST', 'https://cb.web.id/ggklikv2/api/getAllUserIn', ['form_params' => ['nik' => $nik]]);
        $responseBody = json_decode($response->getBody());
        
        if($response->getStatusCode() != 200){
            session(['type_modal' => 'fail', 'message' => 'Ada Masalah Padda server']);
            return redirect()->route('ticket.index');
        }
        $listUser = collect($responseBody->listdata);        
        ///get all user in///

        ///mapping ticket with data user from server///
        $ticket = Tickets::with(['progress'])->findOrFail($id);
        $user = $listUser->filter(function($u) use ($ticket) {
            return stripos($u->nik,$ticket->id_user) !== false;
        })->values();
        $userPic = $listUser->filter(function($u) use ($ticket) {
            return stripos($u->nik,$ticket->id_user_pic) !== false;
        })->values();

        unset($ticket->id_user);
        $ticket->user = $user;
        unset($ticket->id_user_pic);
        $ticket->userPic = $userPic;
        ///end mapping ticket with data user from server///

        $chats = Chats::where('id_ticket',$id)->get();
            $chats->each(function ($c) use($listUser){
                $toUser = $listUser->filter(function($u) use ($c) {
                    return stripos($u->nik,$c->to_user) !== false;
                })->values();
                $fromUser = $listUser->filter(function($u) use ($c) {
                    return stripos($u->nik,$c->from_user) !== false;
                })->values();

                unset($c->id_user);
                $c->to_user = $toUser;
                unset($c->id_user_pic);
                $c->from_user = $fromUser;
            });
        ///end mapping ticket with data user from server///
        // dd($listUser,$ticket,$chats,$users);

        return view('index',[
            "parentview"=>'ticket',
            "subview"=>'detail',
            "ticket"=>$ticket,
            "chats"=>$chats,
            // "users"=>$users
        ]);
    }
    public function add(){
        return view('index',[
            "parentview"=>'ticket',
            "subview"=>'create'
        ]);
    }
    public function create(Request $req){
        $ticket = new Tickets();
        // $ticket->id_user = $req->id_user;
        $ticket->id_user = Session::get('id_user');
        if($req->type_ticket==0){
            $ticket->no_ticket = "T".rand();
        }
        else if($req->type_ticket==1){
            $ticket->no_ticket = "P".rand();
        }
        else{
            $ticket->no_ticket = "M".rand();
        }
        $ticket->type_ticket = $req->type_ticket;
        $ticket->label = $req->label;
        $ticket->judul = $req->judul;
        $ticket->deskripsi = $req->deskripsi;
        if($req->hasFile('foto')){
            $ticket->foto = $req->file('foto')->getClientOriginalName();
            $req->file('foto')->move(public_path() . '/ticket',$req->file('foto')->getClientOriginalName());
        }
        $ticket->status = 0;
        $ticket->createdBy = Session::get('id_user');
        // dd($ticket);

        if($ticket->save()){
            session(['type_modal' => 'success', 'message' => 'berhasil simpan']);
            return redirect()->route('ticket.index');
        }
        else{
            session(['type_modal' => 'fail', 'message' => 'gagal simpan']);
            return redirect()->route('ticket.index');
        }
    }
    public function destroy($id){
        $ticket = Tickets::findOrFail($id);
        $ticket->id_user = $ticket->id_user;
        $ticket->no_ticket = $ticket->no_ticket;
        $ticket->type_ticket = $ticket->type_ticket;
        $ticket->label = $ticket->label;
        $ticket->judul = $ticket->judul;
        $ticket->deskripsi = $ticket->deskripsi;
        $ticket->foto = $ticket->foto;
        $ticket->status = 2;
        if($ticket->save()){
            session(['type_modal' => 'success', 'message' => 'berhasil hapus']);
            return redirect()->route('ticket.index');
        }
        else{
            session(['type_modal' => 'fail', 'message' => 'gagal hapus']);
            return redirect()->route('ticket.index');
        }
    }
}
