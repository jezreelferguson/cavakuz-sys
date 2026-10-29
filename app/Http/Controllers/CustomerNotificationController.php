<?php

namespace App\Http\Controllers;

use App\Models\CustomerNotification;
use Illuminate\Http\Request;

class CustomerNotificationController extends Controller
{
    public function index(Request $request)
    {
        return response()->json([
            'message' => 'Notifications fetched successfully.',
            'data' => CustomerNotification::query()
                ->where('customer_id', $request->user()->id)
                ->latest()
                ->get(),
        ]);
    }

    public function markRead(CustomerNotification $notification)
    {
        $notification->update(['is_read' => true]);

        return response()->json([
            'message' => 'Notification marked as read.',
            'data' => $notification->fresh(),
        ]);
    }
}
