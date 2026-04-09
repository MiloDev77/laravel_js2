<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Workspace;
use App\Models\Apitoken;

class Usagelog extends Model
{
    public $timestamps = false;
    protected $fillable = ['workspace_id', 'apitoken_id', 'service', 'duration', 'cost_per_second'];
    protected static function booted()
    {
        static::creating(fn($m) => $m->created_at = $m->created_at ?? now());
    }

    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }

    public function apitoken()
    {
        return $this->belongsTo(Apitoken::class);
    }

    public function getTotalCostAttribute(): float
    {
        return $this->duration * $this->cost_per_second;
    }
}
