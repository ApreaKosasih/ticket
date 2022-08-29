<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    use HasFactory;
    protected $table = 'user';

    // public function tickets()
    // {
    //     return $this->belongsTo(Tickets::class,'id_user');
    // }
    // public function pic_member()
    // {
    //     return $this->belongsTo(PicMember::class,'id_user');
    // }
}
