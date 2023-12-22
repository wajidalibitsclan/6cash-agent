<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
    public $basePath;
    public $token;
    public $transaction;
    public function __construct()
    {
        $this->basePath = env('APP_URL');
        $this->token = Session::get('token');
        $this->transaction = new Transaction();
    }
    //Agent Dashboard Page
    public function index()
    {
        try {
            $id = Auth::user()->id;
            $endpoint = $this->basePath . "/api/v1/agent/linked-website-web";
            $header = [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'User-Agent' => 'Dart',
                'Authorization' => 'Bearer ' . $this->token
            ];
            $payload = [
                'id' => $id,
                'web' => 'done',
            ];
            $response = Http::withHeaders($header)->get($endpoint, $payload);
            $linkedWebsites = $response->json();

            $transaction = [];
            for ($i = 1; $i <= 12; $i++) {
                $from = date('Y-' . $i . '-01');
                $to = date('Y-' . $i . '-30');
                $transaction[$i] = $this->transaction->where(['user_id' => Auth::user()->id])
                    ->whereBetween('created_at', [$from, $to])
                    ->select([DB::raw("SUM(debit) as total_credit")])
                    ->orderBy("total_credit", 'desc')
                    ->groupBy('id')
                    ->first()->total_credit ?? 0;
            }

            return view('agent-views.dashboard.dashboard', compact('linkedWebsites', 'transaction'));
        } catch (\Exception $error) {
            return redirect()->back()->withErrors($error->getMessage());
        }
    }
}
