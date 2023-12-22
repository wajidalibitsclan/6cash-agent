<?php

namespace App\Http\Controllers\Api\V1\Agent;

use App\Http\Controllers\Controller;
use App\Models\WithdrawRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AgentWithdrawController extends Controller
{
    public function __construct(
        private WithdrawRequest $withdraw_request,
    ) {
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        if (isset($request->web)) {
            if (is_null($request->request_status)) {
                $withdraw_requests = $this->withdraw_request->with('user', 'withdrawal_method')
                    ->where(['user_id' => $request->id])
                    ->latest()
                    ->get();
            } else {
                $withdraw_requests = $this->withdraw_request->with('user', 'withdrawal_method')
                    ->where(['user_id' => $request->id, 'request_status' => $request->request_status])
                    ->latest()
                    ->get();
            }
            return response()->json(response_formatter(DEFAULT_200, $withdraw_requests, null), 200);
        } else {
            $withdraw_requests = $this->withdraw_request->with('user', 'withdrawal_method')
                ->where(['user_id' => auth()->user()->id])
                ->latest()
                ->get();

            return response()->json(response_formatter(DEFAULT_200, $withdraw_requests, null), 200);
        }
    }
}
