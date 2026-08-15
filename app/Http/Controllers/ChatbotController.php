<?php

namespace App\Http\Controllers;

use App\Support\ChatbotIntent;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    public function tanya(Request $request)
    {
        $pesan = trim((string) $request->input('pesan'));

        if ($pesan === '') {
            return response()->json(['message' => 'Pesan tidak boleh kosong.'], 422);
        }

        return response()->json([
            'jawaban' => ChatbotIntent::tanya(mb_substr($pesan, 0, 500), $request->user()),
        ]);
    }
}