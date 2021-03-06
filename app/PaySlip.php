<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class PaySlip extends Model
{
    // 論理削除有効化
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    // カラム複合化
    public function getSlipAttribute($value)
    {
        return unserialize( Crypt::decrypt( $value ));
    }

    // カラム暗号化
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
        'line',
        'target',
        'user_id',
        'loginid',
        'slip',
        'filename',
        'download',
        'error',
        'checked_at',
        'check',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];
}
