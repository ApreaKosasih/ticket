<?php

namespace App\Http\Controllers;

use App\Models\Progress;
use Illuminate\Http\Request;
use Session;

class ProgressController extends Controller
{
    public function create(Request $req,$id_ticket){
        $progress = new Progress();
        $progress->id_ticket = $id_ticket;
        $progress->deskripsi = $req->progress;
        $progress->createdBy = Session::get('id_user');
        
        if($progress->save()){
            session(['type_modal' => 'success', 'message' => 'berhasil simpan perkembangan']);
            return redirect()->route('ticket.detail',['id'=> $id_ticket]);
        }
        else{
            session(['type_modal' => 'fail', 'message' => 'gagal simpan perkembangan']);
            return redirect()->route('ticket.detail');
        }
    }
}
