<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use App\Models\Apitoken;
use App\Models\Quota;
use App\Models\Workspace;

class ApiTokenController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->query('search');
        $query = "%$keyword%";

        return view('tokens.index', [
            'tokens' => Apitoken::whereHas('workspace', function ($q) {
                $q->where('user_id', Auth::id());
            })
                ->when($keyword, function ($f) use ($query) {
                    $f->where('name', 'like', $query);
                })
                ->latest()
                ->paginate(5)
                ->withQueryString(),
        ]);
    }

    public function show(Apitoken $token)
    {
        abort_if(!$token, 404);
        abort_if($token->workspace->user_id !== Auth::id(), 403);

        return view('tokens.show', [
            'token' => $token->load('workspace'),
        ]);
    }

    public function create()
    {
        return view('tokens.create', [
            'workspaces' => Workspace::where('user_id', Auth::id())
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'workspace_id' => 'required|exists:workspaces,id',
            'name' => 'required|string|max:100'
        ]);

        $token = Apitoken::create([
            ...$data,
            'token' => bin2hex(Str::random(25)),
        ]);

        return back()->with('token', $token->token)->with('success', 'Token successfully created. Copy this token into your note!');
    }

    public function revoke(Apitoken $token)
    {
        abort_if(!$token, 404);
        abort_if($token->workspace->user_id !== Auth::id(), 403);
        // $workspace = $token->workspace;
        // if (!$workspace) {
        //     return back()->with('error', 'Token Not Found');
        // }
        // if ($workspace->user_id !== Auth::id()) {
        //     return back()->with('forbidden', '');
        // }

        $token->update([
            'revoked_at' => now(),
        ]);

        return back()->with('success', 'Token successfully revoked.');
    }
}
