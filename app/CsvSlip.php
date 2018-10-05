<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class CsvSlip extends Model
{
    // カラム複合化
    public function getHeaderAttribute($value)
    {
        return unserialize(Crypt::decrypt($value));
    }

    // カラム暗号化
    public function setHeaderAttribute($value)
    {
        $this->attributes['header'] = Crypt::encrypt(serialize($value));
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'target',
        'filename',
        'header',
        'line',
        'error',
        'upload_userid',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];
}
