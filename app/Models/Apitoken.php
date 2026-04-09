<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Usagelog;
use App\Models\Workspace;

class Apitoken extends Model
{
    protected $fillable = ['workspace_id', 'name', 'token', 'revoked_at'];
    protected $dates = ['revoked_at'];

    public function usageLogs()
    {
        return $this->hasMany(Usagelog::class, 'apitoken_id');
    }

    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }

    public function isActive(): bool
    {
        return is_null($this->revoked_at);
    }

    public function isUsable(): bool
    {
        if (!$this->isActive()) return false;
        return $this->workspace->isQuotaExceed();
    }
}
