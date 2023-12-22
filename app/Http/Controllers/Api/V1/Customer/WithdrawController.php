<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\CentralLogics\helpers;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WithdrawalMethod;
use App\Models\WithdrawRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WithdrawController extends Controller
{
    public function __construct(
        private WithdrawRequest $withdraw_request
    )
    {}

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        $withdraw_requests = $this->withdraw_request->with('user', 'withdrawal_method')
            ->where(['user_id' => auth()->user()->id])
            ->latest()
            ->get();

        return response()->json(response_formatter(DEFAULT_200, $withdraw_requests, null), 200);
    }
}
