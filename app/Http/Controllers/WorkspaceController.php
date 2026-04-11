<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Models\Workspace;

class WorkspaceController extends Controller
{
    private function authorizeWorkspace(Workspace $workspace): void
    {
        if ($workspace->user_id !== Auth::id()) abort(403);
    }

    public function index(Request $request)
    {
        $keyword = $request->query('search');
        $query = "%$keyword%";
        return view('workspaces.index', [
            'workspaces' => Workspace::where('user_id', Auth::id())
                ->when($keyword, function ($q) use ($query) {
                    $q->where('name', 'like', $query)
                        ->orWhere('description', 'like', $query);
                })
                ->orderBy('name')
                ->paginate(5)
                ->withQueryString(),
        ]);
    }

    public function show(Workspace $workspace)
    {
        return view('workspaces.show', [
            'workspace' => $workspace->load('apiTokens'),
        ]);
    }

    public function create()
    {
        return view('workspaces.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('workspaces')->where('user_id', Auth::id()),
            ],
            'description' => 'nullable|string',
        ]);

        Workspace::create([
            ...$data,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('workspace.index')->with('success', 'New workspace added.');
    }

    public function edit(Workspace $workspace)
    {
        $this->authorizeWorkspace($workspace);
        return view('workspaces.edit', [
            'workspace' => $workspace,
        ]);
    }

    public function update(Request $request, Workspace $workspace)
    {
        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('workspaces')->where('user_id', Auth::id())->ignore($workspace->id),
            ],
            'description' => 'string',
        ]);

        $workspace->update([
            ...$data,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('workspace.index')->with('success', 'Workspace success updated.');
    }

    public function delete(Workspace $workspace)
    {
        $this->authorizeWorkspace($workspace);
        $workspace->delete();
        return back()->with('success', 'Workspace success delete');
    }
}
