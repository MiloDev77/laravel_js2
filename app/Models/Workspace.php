<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Models\User;
use App\Models\Quota;
use App\Models\Apitoken;
use App\Models\Usagelog;

class Workspace extends Model
{
    use HasUuids;

    protected $fillable = ['user_id', 'name', 'description'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function usagelogs()
    {
        return $this->hasMany(Usagelog::class, 'workspace_id');
    }

    public function apitokens()
    {
        return $this->hasMany(Apitoken::class, 'workspace_id');
    }

    public function quota()
    {
        return $this->hasOne(Quota::class, 'workspace_id');
    }

    public function currentMonthUsage()
    {
        return $this->usagelogs()
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->selectRaw('SUM(duration * cost_per_second) as total')
            ->value('total') ?? 0;
    }

    public function isQuotaExceed(): bool
    {
        $quota = $this->quota;
        if (!$quota || is_null($quota->monthly_limit)) return false;
        return $this->currentMonthUsage() >= $quota->monthly_limit;
    }
}
