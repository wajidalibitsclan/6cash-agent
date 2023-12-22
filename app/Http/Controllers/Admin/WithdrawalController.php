<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\helpers;
use App\Http\Controllers\Controller;
use App\Models\WithdrawalMethod;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class WithdrawalController extends Controller
{
    public function __construct(
        private WithdrawalMethod $withdrawal_method
    ){}

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function add_method(Request $request): Factory|View|Application
    {
        $withdrawal_methods = $this->withdrawal_method->latest()->paginate(Helpers::pagination_limit());
        return view('admin-views.withdrawal.index', compact('withdrawal_methods'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store_method(Request $request): RedirectResponse
    {
        $request->validate([
            'method_name' => 'required',
            'field_type' => 'required|array',
            'field_name' => 'required|array',
            'placeholder' => 'required|array',
        ]);

        $method_fields = [];
        foreach ($request->field_name as $key=>$field_name) {
            $method_fields[] = [
                'input_type' => $request->field_type[$key],
                'input_name' => strtolower(str_replace(' ', "_", $request->field_name[$key])),
                'placeholder' => $request->placeholder[$key],
            ];
        }

        $this->withdrawal_method->updateOrCreate(
            ['method_name' => $request->method_name],
            ['method_fields' => $method_fields]
        );

        Toastr::success('successfully added');
        return back();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function delete_method(Request $request): RedirectResponse
    {
        $withdrawal_methods = $this->withdrawal_method->find($request->id);
        $withdrawal_methods->delete();

        Toastr::success(translate('successfully removed'));
        return back();
    }
}
