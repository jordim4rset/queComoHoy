<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Block;
use Illuminate\Support\Facades\Auth;

class BlockController extends Controller
{
    public function block(Request $request)
    {
        $request->validate([
            'id_bloqueado' => 'required|exists:users,id|different:' . Auth::id(),
        ]);

        Block::firstOrCreate([
            'id_bloqueador' => Auth::id(),
            'id_bloqueado' => $request->input('id_bloqueado'),
        ]);

        return redirect()->back()->with('success', 'Usuario bloqueado correctamente.');
    }

    public function unblock(Request $request)
    {
        $request->validate([
            'id_bloqueado' => 'required|exists:users,id',
        ]);

        Block::where('id_bloqueador', Auth::id())
            ->where('id_bloqueado', $request->input('id_bloqueado'))
            ->delete();

        return redirect()->back()->with('success', 'Usuario desbloqueado correctamente.');
    }
}
