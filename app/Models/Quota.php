<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Workspace;

class Quota extends Model
{
    protected $fillable = ['workspace_id', 'monthly_limit'];

    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }
}
