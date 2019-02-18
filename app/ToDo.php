<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ToDo extends Model
{
    protected $table = 'to_dos';

    protected $guarded = [];

    public function owner()
    {
        return $this->belongsTo(User::class);
    }
}
