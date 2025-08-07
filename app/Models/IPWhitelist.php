<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IPWhitelist extends Model
{
    use HasFactory;

    protected $table = 'i_p_whitelists';

    protected $fillable = [
        'ip_address',
        'description',
        'added_by',
    ];

    public function adder()
    {
        return $this->belongsTo(User::class, 'added_by');
    }
}
