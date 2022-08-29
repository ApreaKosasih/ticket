<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PicMember extends Model
{
    use HasFactory;
    protected $table = 'ticket_pic_member';

    public function tickets()
    {
        return $this->belongsTo(Tickets::class,'id');
    }
    // public function users()
    // {
    //     return $this->hasOne(Users::class,'id');
    // }
}
