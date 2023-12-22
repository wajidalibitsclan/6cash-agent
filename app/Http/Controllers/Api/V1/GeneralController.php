<?php

namespace App\Http\Controllers\Api\V1;

use App\CentralLogics\helpers;
use App\Http\Controllers\Controller;
use App\Http\Resources\HelpTopicResource;
use App\Models\HelpTopic;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GeneralController extends Controller
{
    public function __construct(
        private User $user,
        private HelpTopic $help_topic
    ){}

    /**
     * @param Request $request
     * @return array
     */
    public function faq(Request $request): array
    {
        $limit = $request->has('limit') ? $request->limit : 10;
        $offset = $request->has('offset') ? $request->offset : 1;
        $helpTopics = $this->help_topic->select('id', 'question', 'answer', 'ranking', 'created_at')
                        ->orderBy('ranking')->active()
                        ->orderBy("created_at", 'desc')->paginate($limit, ['*'], 'page', $offset);

        $helpTopics = HelpTopicResource::collection($helpTopics);
        return [
            'total_size' => $helpTopics->total(),
            'limit' => (int)$limit,
            'offset' => (int)$offset,
            'helpTopics' => $helpTopics->items()
        ];

    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function check_customer(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $user = $this->user->where('phone', $request->phone)->where('type', 2)->first();

        if (isset($user)) {
            return response()->json([
                'message' => 'Customer is available',
                'data' => ['name' => $user->f_name . ' ' . $user->l_name, 'image' => $user->image],
            ], 200);
        } else {
            return response()->json(['message' => 'customer is not available'], 404);
        }

    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function check_agent(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $user = $this->user->where('phone', $request->phone)->where('type', 1)->first();

        if (isset($user)) {
            return response()->json([
                'message' => 'Agent is available',
                'data' => ['name' => $user->f_name . ' ' . $user->l_name, 'image' => $user->image],
            ], 200);
        } else {
            return response()->json(['message' => 'agent is not available'], 404);
        }

    }

}
