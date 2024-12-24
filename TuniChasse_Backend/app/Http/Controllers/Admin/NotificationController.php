<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function addnotif(){

        return view('Admin.Components.addnotif');
    }
    public function store(Request $request)
    {
    // Validation (add rules based on your requirements)
    $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'required|string|max:255',

    ]);

    try {

        $notif = new Notification();
        $notif->title = $request->input('title');
        $notif->content = $request->input('content');

        $notif->save();

        // Handle success
        return redirect()->route('admin.listnotif')->with('success', 'Proi added successfully');
    } catch (\Exception $e) {
        // Handle error
        return back()->withInput()->withErrors(['error' => 'Failed to add proi. Please try again.']);
    }
    }

    }
