<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Apitoken;
use App\Models\Usagelog;
use App\Models\Quota;
use App\Models\Workspace;

class PublicApiController extends Controller
{
    public function generate(Request $request)
    {
        $data = $request->validate([
            'token' => 'required|string|max:100',
            'prompt' => 'required|string'
        ]);

        $token = Apitoken::where('token', $data['token'])->with('workspace.quota')->first();
        if (!$token || !$token->isActive()) {
            return response()->json(["status" => "error", "message" => "Invalid or inactive token."], 403);
        }

        if ($token->workspace->isQuotaExceed()) {
            return response()->json(["status" => "error", "message" => "Monthly quota exceed."], 402);
        }

        $duration = rand(3, 600);

        Usagelog::create([
            'workspace_id' => $token->workspace->id,
            'apitoken_id' => $token->id,
            'service' => 'text',
            'duration' => $duration,
            'cost_per_second' => 0.02,
            'created_at' => now(),
        ]);

        return response()->json([
            "status" => "success",
            "data" => [
                "response" => fake()->paragraph(),
                "token_name" => $token->name,
                "workspace" => $token->workspace->name
            ],
        ], 200);
    }
}
