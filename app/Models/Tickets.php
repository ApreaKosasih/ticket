<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tickets extends Model
{
    use HasFactory;
    protected $table = 'ticket';

    // public function users()
    // {
    //     return $this->hasOne(Users::class,'id');
    // }
    public function progress()
    {
        return $this->hasMany(Progress::class,'id_ticket');
    }
    // public function getPicAttribute($value)
    // {
    //     return DB::table('user')->where('id',$this->id_user_pic)->first();
    // }
    // public function pic_member()
    // {
    //     return $this->hasMany(PicMember::class,'id_ticket');
    // }
}
