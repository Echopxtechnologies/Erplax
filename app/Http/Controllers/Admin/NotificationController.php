<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get authenticated user ID based on active guard
     */
    protected function getAuthUserId(): ?int
    {
        if (Auth::guard('admin')->check()) {
            return Auth::guard('admin')->id();
        }
        
        if (Auth::guard('web')->check()) {
            return Auth::guard('web')->id();
        }
        
        if (Auth::check()) {
            return Auth::id();
        }
        
        return null;
    }

    /**
     * Get the current guard type
     */
    protected function getGuardType(): string
    {
        if (Auth::guard('admin')->check()) {
            return 'admin';
        }
        return 'user';
    }

    /**
     * Get notifications for the current user
     */
    public function index()
    {
        $userId = $this->getAuthUserId();
        
        if (!$userId) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $notifications = Notification::where('user_id', $userId)
            ->where('user_type', $this->getGuardType())
            ->latest('created_at')
            ->take(20)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $notifications,
            'count' => $notifications->count()
        ]);
    }

    /**
     * Delete a notification
     */
    public function destroy($id)
    {
        $userId = $this->getAuthUserId();
        
        if (!$userId) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $deleted = Notification::where('id', $id)
            ->where('user_id', $userId)
            ->where('user_type', $this->getGuardType())
            ->delete();

        return response()->json(['success' => (bool) $deleted]);
    }
    
    /**
     * Clear all notifications for the current user
     */
    public function clearAll()
    {
        $userId = $this->getAuthUserId();
        
        if (!$userId) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        Notification::where('user_id', $userId)
            ->where('user_type', $this->getGuardType())
            ->delete();
        
        return response()->json(['success' => true]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($id)
    {
        $userId = $this->getAuthUserId();
        
        if (!$userId) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        Notification::where('id', $id)
            ->where('user_id', $userId)
            ->where('user_type', $this->getGuardType())
            ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    /**
     * Get unread count
     */
    public function unreadCount()
    {
        $userId = $this->getAuthUserId();
        
        if (!$userId) {
            return response()->json(['success' => false, 'count' => 0], 401);
        }

        $count = Notification::where('user_id', $userId)
            ->where('user_type', $this->getGuardType())
            ->where('is_read', false)
            ->count();

        return response()->json(['success' => true, 'count' => $count]);
    }
}