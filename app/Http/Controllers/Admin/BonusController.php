<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\helpers;
use App\Http\Controllers\Controller;
use App\Models\Bonus;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BonusController extends Controller
{
    public function __construct(
        private Bonus $bonus
    ){}

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    function index(Request $request): View|Factory|Application
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $bonuses = $this->bonus->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('title', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        } else {
            $bonuses = $this->bonus;
        }

        $bonuses = $bonuses->latest()->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.bonus.index', compact('bonuses', 'search'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required',
            'user_type' => 'required|in:all,customer,agent',
            'maximum_add_money_amount' => 'required|numeric|min:0|not_in:0',
            'limit_per_user' => 'required|numeric|min:1',
            'bonus_type' => 'required|in:percentage,flat',
            'bonus' => 'required|numeric|min:0|not_in:0',
            'maximum_bonus_amount' => $request['bonus_type'] == 'percentage' ? 'required|numeric|min:0|not_in:0' : '',
            'start_date_time' => 'date',
            'end_date_time' => 'date',
        ]);

        $bonus = $this->bonus;
        $bonus->title = $request['title'];
        $bonus->user_type = $request['user_type'];
        $bonus->min_add_money_amount = $request['maximum_add_money_amount'];
        $bonus->limit_per_user = $request['limit_per_user'];
        $bonus->bonus_type = $request['bonus_type'];
        $bonus->bonus = $request['bonus'];
        $bonus->max_bonus_amount = !is_null($request['maximum_bonus_amount']) ? $request['maximum_bonus_amount'] : 0;
        $bonus->start_date_time = $request['start_date_time'];
        $bonus->end_date_time = $request['end_date_time'];
        $bonus->save();

        Toastr::success(translate('Bonus added successfully!'));
        return back();
    }

    /**
     * @param $id
     * @return Application|Factory|View
     */
    public function edit($id): Factory|View|Application
    {
        $bonus = $this->bonus->find($id);
        return view('admin-views.bonus.edit', compact('bonus'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'title' => 'required',
            'user_type' => 'required|in:all,customer,agent',
            'maximum_add_money_amount' => 'required|numeric|min:0|not_in:0',
            'limit_per_user' => 'required|numeric|min:1',
            'bonus_type' => 'required|in:percentage,flat',
            'bonus' => 'required|numeric|min:0|not_in:0',
            'maximum_bonus_amount' => $request['bonus_type'] == 'percentage' ? 'required|numeric|min:0|not_in:0' : '',
            'start_date_time' => 'date',
            'end_date_time' => 'date',
        ]);

        $bonus = $this->bonus->find($id);
        $bonus->title = $request['title'];
        $bonus->user_type = $request['user_type'];
        $bonus->min_add_money_amount = $request['maximum_add_money_amount'];
        $bonus->limit_per_user = $request['limit_per_user'];
        $bonus->bonus_type = $request['bonus_type'];
        $bonus->bonus = $request['bonus'];
        $bonus->max_bonus_amount = !is_null($request['maximum_bonus_amount']) ? $request['maximum_bonus_amount'] : 0;
        $bonus->start_date_time = $request['start_date_time'];
        $bonus->end_date_time = $request['end_date_time'];
        $bonus->save();

        Toastr::success(translate('Bonus updated successfully!'));
        return back();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function status(Request $request): RedirectResponse
    {
        $bonus = $this->bonus->find($request->id);
        $bonus->is_active = !$bonus->is_active;
        $bonus->save();

        Toastr::success(translate('Bonus status updated!'));
        return back();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function delete(Request $request): RedirectResponse
    {
        $bonus = $this->bonus->find($request->id);
        $bonus->delete();

        Toastr::success(translate('Bonus removed!'));
        return back();
    }
}
