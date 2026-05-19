<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BlockController extends Controller
{
    public function block(Request $request, User $user): RedirectResponse
    {
        if ($request->user()->is($user)) {
            return back()->withErrors([
                'block' => 'No puedes bloquear tu propio usuario.',
            ]);
        }

        Block::firstOrCreate([
            'id_bloqueador' => $request->user()->id,
            'id_bloqueado' => $user->id,
        ]);

        return redirect()->back();
    }

    public function unblock(Request $request, User $user): RedirectResponse
    {
        Block::where('id_bloqueador', $request->user()->id)
            ->where('id_bloqueado', $user->id)
            ->delete();

        return redirect()->back();
    }
}
