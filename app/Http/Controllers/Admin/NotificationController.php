<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function destroy($id)
    {
        Notification::where('id', $id)
            ->where('user_id', auth()->id())
            ->delete();
            
        return response()->json(['success' => true]);
    }
    
    public function clearAll()
    {
        Notification::where('user_id', auth()->id())->delete();
        
        return response()->json(['success' => true]);
    }
}