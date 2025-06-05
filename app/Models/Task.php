<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',   // tambahkan atribut title sesuai error
        // tambahkan atribut lain yang boleh di mass assign, misal:
        'description',
        'assigned_to',
         'created_by',
        'status',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function assigned()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
