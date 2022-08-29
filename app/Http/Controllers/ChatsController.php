<?php

namespace App\Http\Controllers;

use App\Models\Chats;
use App\Models\Tickets;
use Illuminate\Http\Request;
use Session;

class ChatsController extends Controller
{
    public function create(Request $req,$id_ticket){
        $chat = new Chats();
        $chat->id_ticket = $id_ticket;
        $chat->deskripsi = $req->pesan;
        
        $ticket = Tickets::findOrFail($id_ticket);
        if($ticket->id_user==Session::get('id_user')){
            $chat->to_user = $ticket->id_user_pic;
            $chat->from_user = $ticket->id_user;
        }
        else{
            $chat->to_user = $ticket->id_user;
            $chat->from_user = Session::get('id_user');
        }
        
        if($req->hasFile('foto')){
            $chat->foto = $req->file('foto')->getClientOriginalName();
            $req->file('foto')->move(public_path() . '/chat',$req->file('foto')->getClientOriginalName());
        }
        $chat->createdBy = Session::get('id_user');
        // dd($chat);
        
        if($chat->save()){
            session(['type_modal' => 'success', 'message' => 'berhasil simpan chat']);
            return redirect()->route('ticket.detail',['id'=> $id_ticket]);
        }
        else{
            session(['type_modal' => 'fail', 'message' => 'gagal simpan chat']);
            return redirect()->route('ticket.detail');
        }
    }
}
