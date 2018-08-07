<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;

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
        $this->attributes['slip'] = Crypt::encrypt(serialize($value));
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'csv_id',
        'no',
        'target',
        'user_id',
        'loginid',
        'slip',
        'filename',
        'download',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];
}
