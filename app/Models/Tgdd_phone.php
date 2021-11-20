<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tgdd_phone extends Model
{
    use HasFactory;
    protected $table = 'tgddphone';
    public function tgddphone_detail(){
        return $this->hasOne(Tgdd_phone_detail::class,'phone_id','id');
    }
}
