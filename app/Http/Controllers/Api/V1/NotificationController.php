<?php

namespace App\Http\Controllers\Api\V1;

use App\CentralLogics\helpers;
use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    public function __construct(
        private Notification $notification
    )
    {}

    /**
     * @param Request $request
     * @return array
     */
    public function get_customer_notification(Request $request): array
    {
        $limit = $request->has('limit') ? $request->limit : 10;
        $offset = $request->has('offset') ? $request->offset : 1;

        $notifications = $this->notification->active()->where('receiver', 'customers')->orWhere('receiver', 'all')->latest()->paginate($limit, ['*'], 'page', $offset);
        $notifications = NotificationResource::collection($notifications);
        return [
            'total_size' => $notifications->total(),
            'limit' => $limit,
            'offset' => $offset,
            'notifications' => $notifications->items()
        ];
    }

    /**
     * @param Request $request
     * @return array
     */
    public function get_agent_notification(Request $request): array
    {
        $limit = $request->has('limit') ? $request->limit : 10;
        $offset = $request->has('offset') ? $request->offset : 1;

        $notifications = $this->notification->active()->where('receiver', 'agents')->orWhere('receiver', 'all')->paginate($limit, ['*'], 'page', $offset);
        $notifications = NotificationResource::collection($notifications);
        return [
            'total_size' => $notifications->total(),
            'limit' => $limit,
            'offset' => $offset,
            'notifications' => $notifications->items()
        ];
    }
}
