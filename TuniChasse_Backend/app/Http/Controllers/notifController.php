<?php

namespace App\Http\Controllers;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class notifController extends Controller
{
    public function getnotif()
    {
        $notifs = Notification::all();

        if ($notifs->count() > 0) {
            \Log::info(['notifs' => $notifs]);
            return response()->json([ 'notifs' => $notifs], 200);
        } else {
            \Log::info('No notifs found');
            return response()->json(['message' => 'No notifs found'], 404);
        }
    }
}
