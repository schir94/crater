<?php

namespace Crater\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Credits extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount'
    ];

    protected $hidden = [
        'id',
    ];

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}