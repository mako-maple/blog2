<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaySlip extends Model
{
    // 論理削除有効化
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    // カラム暗号化
    public function getSlipAttribute($value)
    {
        return unserialize(Crypt::decrypt($value));
    }

    // カラム複合化
    public function setSlipAttribute($value)
    {
        return Crypt::encrypt(serialize($value));
    }
}
