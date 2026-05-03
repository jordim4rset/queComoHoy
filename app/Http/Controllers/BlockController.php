<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Block;

class BlockController extends Controller
{
    public function block(Request $request)
    {
        $block = new Block();
        $block->id_bloqueador = $request->input('id_bloqueador');
        $block->id_bloqueado = $request->input('id_bloqueado');
        $block->save();

        return redirect()->back();
    }

    public function unblock(Request $request)
    {
        Block::where('id_bloqueador', $request->input('id_bloqueador'))
            ->where('id_bloqueado', $request->input('id_bloqueado'))
            ->delete();

        return redirect()->back();
    }
}
