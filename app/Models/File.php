<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File  extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'size',
        'type',
        'created_at',
        'updated_by',
    ];

    
    public function user()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
}
