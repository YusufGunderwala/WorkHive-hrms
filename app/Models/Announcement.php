<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'title',
        'content',
        'department',
        'type',
        'start_date',
        'end_date',
        'created_by'
    ];

    public function scopeActive($query)
    {
        $today = now()->toDateString();
        return $query->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
