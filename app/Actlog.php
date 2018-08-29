<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Actlog extends Model
{
    protected $connection;

    const UPDATED_AT = null;

    public function __construct(array $attributes = []) {
        parent::__construct($attributes);
        $connection = env('DB_LOG_CONNECTION', env('DB_CONNECTION', 'mysql'));
    }

    // カラム Json複合化
    public function getMessageAttribute($value)
    {
        return json_decode($value);
    }

    // カラム Json化
    public function setMessageAttribute($value)
    {
        $this->attributes['message'] = json_encode($value);
    }


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'route',
        'url',
        'method',
        'status',
        'message',
        'remote_addr',
        'user_agent',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];
}
