<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\helpers;
use App\Http\Controllers\Controller;
use App\Models\UserLogHistory;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        private UserLogHistory $user_log_history,
    ){}

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function log(Request $request): Factory|View|Application
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $user_logs = $this->user_log_history->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('ip_address', 'like', "%{$value}%")
                        ->orWhere('device_id', 'like', "%{$value}%")
                        ->orWhere('browser', 'like', "%{$value}%")
                        ->orWhere('os', 'like', "%{$value}%")
                        ->orWhere('device_model', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        } else {
            $user_logs = $this->user_log_history;
        }

        $user_logs = $user_logs->with(['user'])->latest()->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.user.log-list', compact('user_logs', 'search'));
    }
}
