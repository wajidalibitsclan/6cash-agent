<?php

namespace App\Http\Controllers\Agent\Profile;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\CentralLogics\helpers;
use App\Models\City;
use App\Models\Country;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\JsonResponse;

class ProfileController extends Controller
{
    public $basePath;

    public function __construct()
    {
        $this->basePath = env('APP_URL');
    }
    // Show Profile 
    public function index()
    {
        try {
            $user = User::with('country.cities')->where('id', auth()->user()->id)->first();
            return view('agent-views.profile.profile', compact('user'));
        } catch (\Exception $error) {
            return back()->withErrors($error->getMessage());
        }
    }
    //Submit Profile
    public function submit(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'f_name' => 'required',
                'l_name' => 'required',
                'email' => '',
                'image' => '',
                'country' => '',
                'city' => '',
                'gender' => 'required',
                'occupation' => 'required',
                'country_id' => 'required',
                'city_id' => 'required'
            ]);

            if ($validator->fails()) {
                return back()->with('errors', $validator->getMessageBag());
            }
            $endpoint = $this->basePath . "/api/v1/agent/update-profile-new";
            $token = Session::get('token');

            $Bearer = "Bearer " . $token;
            $header = [
                'Content-Type' => 'application/json',
                'User-Agent' => 'Dart',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $token
            ];

            $payload = [
                'f_name' => $request->f_name,
                'l_name' => $request->l_name,
                'email' => $request->email,
                'gender' => $request->gender,
                'occupation' => $request->occupation,
                'id' => $request->id,
                'image' => '',
                'country' => $request->country,
                'city' => $request->city,
                'country_id' => $request->country_id,
                'city_id' => $request->city_id
            ];

            $response = Http::withHeaders($header)->post($endpoint, $payload);
            $responseBody = $response->json();

            if (isset($responseBody['code']) && $responseBody['code'] === 'success') {
                $user = User::find($request->id);
                $user->image = $request->has('image') ? Helpers::update('agent/', $user->image, 'png', $request->image) : $user->image;
                $user->save();

                Toastr::success(translate('Profile updated successfully!'));
                return back()->with('success', 'Profile updated successfully!');
            } else {
                return back()->with('error', translate('Something went wrong!'));
            }
        } catch (\Exception $error) {
            return back()->withErrors($error->getMessage());
        }
    }
    // Change PIN 
    public function changePin()
    {
        try {
            return view('agent-views.profile.edit-pin');
        } catch (\Exception $error) {
            return back()->withErrors($error->getMessage());
        }
    }
    //Update PIN
    public function updatePin(Request $request)
    {
        try {
            $endpoint = $this->basePath . "/api/v1/agent/change-pin-web";
            $token = Session::get('token');

            $header = [
                'Content-Type' => 'application/json',
                'User-Agent' => 'Dart',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $token
            ];

            $payload = [
                'old_pin' => $request->old_pin,
                'new_pin' => $request->new_pin,
                'confirm_pin' => $request->confirm_pin,
                'id' => $request->id,
                'web' => 'done'
            ];

            $response = Http::withHeaders($header)->post($endpoint, $payload);
            $responseBody = $response->json();

            if (isset($responseBody['errors'])) {
                return back()->withErrors(translate('Something went wrong!'));
            } else if (isset($responseBody['code']) && $responseBody['code'] === 'success') {
                Toastr::success(translate('Pin has been updated successfully!'));
                return back();
            } else {
                return back()->withErrors(translate('Something went wrong!'));
            }
        } catch (\Exception $error) {
            return back()->withErrors($error->getMessage());
        }
    }
    //Remove Account
    public function removeAccount($id)
    {
        try {
            $user = User::where('id', $id)->first();
            if (isset($user)) {
                $user->delete();
                Session::remove('token');
                Auth::logout();
                Toastr::success(translate('Account has been deleted successfully!'));
                return redirect()->route('agent.login');
            } else {
                return back()->withErrors(translate('Something went wrong!'));
            }
        } catch (\Exception $error) {
            return back()->withErrors($error->getMessage());
        }
    }
    //Update Account
    public function updateAccount(Request $request)
    {
        try {
            return view('agent-views.auth.verify-account');
        } catch (\Exception $error) {
        }
    }
    //Verify Account
    public function verifyAccount(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'identification_number' => 'required',
                'identification_type' => 'required|in:passport,driving_licence,nid,trade_license',
                // 'identification_image' => 'required|mimes:png',
            ], [
                'identification_number.required' => translate('Identication number is required'),
                'identification_image.required' => translate('Identification image is required'),
                // 'identification_image.mimes' => translate('Identication image must be PNG')
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $user = User::find($request->id);
            if ($user->is_kyc_verified == 1) {
                return redirect()->back()->withErrors(translate('Sorry, your account is already active!'));
            }

            $identity_images = [];
            foreach ($request->identification_image as $image) {
                $identity_images[] = Helpers::file_uploader('user/identity/', 'png', $image);
            }


            $user->identification_number = $request->identification_number;
            $user->identification_type = $request->identification_type;
            $user->identification_image = $identity_images;
            $user->is_kyc_verified = 0;
            $user->save();
            Toastr::success(translate('Request has been sent!'));

            return redirect()->back();
        } catch (\Exception $error) {
            return back()->withErrors($error->getMessage());
        }
    }
    // GET Cities
    public function getCities(Request $request)
    {
        $data = Country::withACtiveCityAndCurrency()->where('id', $request->country)->first();
        return response()->json([
            'data' => $data
        ]);
    }
    //GET Agent 
    public function getAgent(Request $request)
    {
        $id = auth()->user()->id;
        $city = City::with(['users' => function ($query) use ($id) {
            $query->where('id', '!=', $id)->where('is_kyc_verified', 1);
        }])->where('id', $request->city)->first();

        return response()->json([
            'data' => $city
        ]);
    }
    //GET Agent Records
    public function getAgentRecord(Request $request)
    {
        $phone = $request->country_code . $request->phone;
        $agent = User::where('type', 1)->whereNot('id', auth()->user()->id)->where('phone', 'LIKE', "%$phone%")->get();
        return response()->json([
            'data' => $agent,
        ]);
    }
    // GET City List
    public function getCityList(Request $request): JsonResponse
    {
        try {
            $cities = Country::where(['name' => $request->country_name])
                ->withActiveCity()
                ->first();

            return response()->json([
                'data' => $cities->cities,
                'country_id' => $cities->id
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'error' => $error->getMessage()
            ]);
        }
    }
    // FETCH City By ID
    public function fetchCityId(Request $request)
    {
        try {
            $city = City::where(['name' => $request->city, 'country_id' => $request->country_id])->first();
            return response()->json([
                'city_id' => $city->id
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'error' => $error->getMessage()
            ]);
        }
    }
}
