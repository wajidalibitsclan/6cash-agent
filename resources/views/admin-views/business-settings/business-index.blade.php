@extends('layouts.admin.app')

@section('title', translate('Settings'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="d-flex align-items-center gap-3 pb-2">
            <img width="24" src="{{asset('public/assets/admin/img/media/business-setup.png')}}" alt="">
            <h2 class="page-header-title">{{translate('Business Setup')}}</h2>
        </div>
        <!-- End Page Header -->

        <div class="inline-page-menu my-4">
            @include('admin-views.business-settings.partial._business-setup-tabs')
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 text-capitalize d-flex align-items-center gap-1"><i class="tio-briefcase"></i> {{translate('Business Information')}} </h5>
            </div>
            <div class="card-body">
                    {{--<div class="mb-3 mt-3">--}}
                        {{--<div class="card">--}}
                            {{--<div class="card-body" style="padding-bottom: 12px">--}}
                                {{--<div class="row">--}}
                                    {{--@php($config=\App\CentralLogics\Helpers::get_business_settings('maintenance_mode'))--}}
                                        {{--<div class="col-6">--}}
                                            {{--<h5 class="text-capitalize">--}}
                                                {{--<i class="tio-settings-outlined"></i>--}}
                                                {{--{{translate('maintenance_mode')}}--}}
                                                {{--</h5>--}}
                                                {{--</div>--}}
                                                {{-- <div class="col-6">--}}
                                                {{--<label class="switch ml-3 float-right">--}}
                                                {{--<input type="checkbox" class="status" onclick="maintenance_mode()"--}}
                                                {{--{{isset($config) && $config?'checked':''}}>--}}
                                            {{--<span class="slider round"></span>--}}
                                        {{--</label>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    <form action="{{route('admin.business-settings.update-setup')}}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            @php($name=\App\Models\BusinessSetting::where('key','business_name')->first())
                            <div class="col-sm-6 col-xl-4">
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlInput1">{{translate('business')}} {{translate('name')}}</label>
                                    <input type="text" name="restaurant_name" value="{{$name->value??''}}" class="form-control"
                                        placeholder="{{translate('New Business')}}" required>
                                </div>
                            </div>
                            @php($currency_code=\App\Models\BusinessSetting::where('key','currency')->first())
                            <div class="col-sm-6 col-xl-4">
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlInput1">{{translate('currency')}}</label>
                                    <select name="currency" class="form-control js-select2-custom">
                                        @foreach(\App\Models\Currency::orderBy('currency_code')->get() as $currency)
                                            <option
                                                value="{{$currency['currency_code']}}" {{$currency_code?($currency_code->value==$currency['currency_code']?'selected':''):''}}>
                                                {{$currency['currency_code']}} ( {{$currency['currency_symbol']}} )
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @php($currency_symbol_position=\App\Models\BusinessSetting::where('key','currency_symbol_position')->first())
                            <div class="col-sm-6 col-xl-4">
                                <div class="form-group">
                                    <label class="input-label text-capitalize" for="currency_symbol_position">{{translate('currency_symbol_positon')}}</label>
                                    <select name="currency_symbol_position" class="form-control js-select2-custom" id="currency_symbol_position">
                                        <option
                                            value="left" {{$currency_symbol_position?($currency_symbol_position->value=='left'?'selected':''):''}}>
                                            {{translate('left')}} ({{\App\CentralLogics\Helpers::currency_symbol()}}123)
                                        </option>
                                        <option
                                            value="right" {{$currency_symbol_position?($currency_symbol_position->value=='right'?'selected':''):''}}>
                                            {{translate('right')}} (123{{\App\CentralLogics\Helpers::currency_symbol()}})
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 col-xl-4">
                                @php($pagination_limit=\App\CentralLogics\Helpers::get_business_settings('pagination_limit')??25)
                                <div class="form-group">
                                    <label
                                        class="input-label">{{translate('pagination')}} {{translate('settings')}}</label>
                                    <input type="number" value="{{$pagination_limit}}" min="1"
                                        name="pagination_limit" class="form-control" placeholder="25">
                                </div>
                            </div>
                            <div class="col-sm-6 col-xl-4">
                                <div class="form-group">
                                    <label class="input-label text-capitalize" for="country">{{translate('country')}}</label>
                                    <select id="country" name="country" class="form-control  js-select2-custom">
                                        <option value="" selected disabled>{{translate('Select Country')}}</option>
                                        <option value="AF">Afghanistan</option>
                                        <option value="AX">Åland Islands</option>
                                        <option value="AL">Albania</option>
                                        <option value="DZ">Algeria</option>
                                        <option value="AS">American Samoa</option>
                                        <option value="AD">Andorra</option>
                                        <option value="AO">Angola</option>
                                        <option value="AI">Anguilla</option>
                                        <option value="AQ">Antarctica</option>
                                        <option value="AG">Antigua and Barbuda</option>
                                        <option value="AR">Argentina</option>
                                        <option value="AM">Armenia</option>
                                        <option value="AW">Aruba</option>
                                        <option value="AU">Australia</option>
                                        <option value="AT">Austria</option>
                                        <option value="AZ">Azerbaijan</option>
                                        <option value="BS">Bahamas</option>
                                        <option value="BH">Bahrain</option>
                                        <option value="BD">Bangladesh</option>
                                        <option value="BB">Barbados</option>
                                        <option value="BY">Belarus</option>
                                        <option value="BE">Belgium</option>
                                        <option value="BZ">Belize</option>
                                        <option value="BJ">Benin</option>
                                        <option value="BM">Bermuda</option>
                                        <option value="BT">Bhutan</option>
                                        <option value="BO">Bolivia, Plurinational State of</option>
                                        <option value="BQ">Bonaire, Sint Eustatius and Saba</option>
                                        <option value="BA">Bosnia and Herzegovina</option>
                                        <option value="BW">Botswana</option>
                                        <option value="BV">Bouvet Island</option>
                                        <option value="BR">Brazil</option>
                                        <option value="IO">British Indian Ocean Territory</option>
                                        <option value="BN">Brunei Darussalam</option>
                                        <option value="BG">Bulgaria</option>
                                        <option value="BF">Burkina Faso</option>
                                        <option value="BI">Burundi</option>
                                        <option value="KH">Cambodia</option>
                                        <option value="CM">Cameroon</option>
                                        <option value="CA">Canada</option>
                                        <option value="CV">Cape Verde</option>
                                        <option value="KY">Cayman Islands</option>
                                        <option value="CF">Central African Republic</option>
                                        <option value="TD">Chad</option>
                                        <option value="CL">Chile</option>
                                        <option value="CN">China</option>
                                        <option value="CX">Christmas Island</option>
                                        <option value="CC">Cocos (Keeling) Islands</option>
                                        <option value="CO">Colombia</option>
                                        <option value="KM">Comoros</option>
                                        <option value="CG">Congo</option>
                                        <option value="CD">Congo, the Democratic Republic of the</option>
                                        <option value="CK">Cook Islands</option>
                                        <option value="CR">Costa Rica</option>
                                        <option value="CI">Côte d'Ivoire</option>
                                        <option value="HR">Croatia</option>
                                        <option value="CU">Cuba</option>
                                        <option value="CW">Curaçao</option>
                                        <option value="CY">Cyprus</option>
                                        <option value="CZ">Czech Republic</option>
                                        <option value="DK">Denmark</option>
                                        <option value="DJ">Djibouti</option>
                                        <option value="DM">Dominica</option>
                                        <option value="DO">Dominican Republic</option>
                                        <option value="EC">Ecuador</option>
                                        <option value="EG">Egypt</option>
                                        <option value="SV">El Salvador</option>
                                        <option value="GQ">Equatorial Guinea</option>
                                        <option value="ER">Eritrea</option>
                                        <option value="EE">Estonia</option>
                                        <option value="ET">Ethiopia</option>
                                        <option value="FK">Falkland Islands (Malvinas)</option>
                                        <option value="FO">Faroe Islands</option>
                                        <option value="FJ">Fiji</option>
                                        <option value="FI">Finland</option>
                                        <option value="FR">France</option>
                                        <option value="GF">French Guiana</option>
                                        <option value="PF">French Polynesia</option>
                                        <option value="TF">French Southern Territories</option>
                                        <option value="GA">Gabon</option>
                                        <option value="GM">Gambia</option>
                                        <option value="GE">Georgia</option>
                                        <option value="DE">Germany</option>
                                        <option value="GH">Ghana</option>
                                        <option value="GI">Gibraltar</option>
                                        <option value="GR">Greece</option>
                                        <option value="GL">Greenland</option>
                                        <option value="GD">Grenada</option>
                                        <option value="GP">Guadeloupe</option>
                                        <option value="GU">Guam</option>
                                        <option value="GT">Guatemala</option>
                                        <option value="GG">Guernsey</option>
                                        <option value="GN">Guinea</option>
                                        <option value="GW">Guinea-Bissau</option>
                                        <option value="GY">Guyana</option>
                                        <option value="HT">Haiti</option>
                                        <option value="HM">Heard Island and McDonald Islands</option>
                                        <option value="VA">Holy See (Vatican City State)</option>
                                        <option value="HN">Honduras</option>
                                        <option value="HK">Hong Kong</option>
                                        <option value="HU">Hungary</option>
                                        <option value="IS">Iceland</option>
                                        <option value="IN">India</option>
                                        <option value="ID">Indonesia</option>
                                        <option value="IR">Iran, Islamic Republic of</option>
                                        <option value="IQ">Iraq</option>
                                        <option value="IE">Ireland</option>
                                        <option value="IM">Isle of Man</option>
                                        <option value="IL">Israel</option>
                                        <option value="IT">Italy</option>
                                        <option value="JM">Jamaica</option>
                                        <option value="JP">Japan</option>
                                        <option value="JE">Jersey</option>
                                        <option value="JO">Jordan</option>
                                        <option value="KZ">Kazakhstan</option>
                                        <option value="KE">Kenya</option>
                                        <option value="KI">Kiribati</option>
                                        <option value="KP">Korea, Democratic People's Republic of</option>
                                        <option value="KR">Korea, Republic of</option>
                                        <option value="KW">Kuwait</option>
                                        <option value="KG">Kyrgyzstan</option>
                                        <option value="LA">Lao People's Democratic Republic</option>
                                        <option value="LV">Latvia</option>
                                        <option value="LB">Lebanon</option>
                                        <option value="LS">Lesotho</option>
                                        <option value="LR">Liberia</option>
                                        <option value="LY">Libya</option>
                                        <option value="LI">Liechtenstein</option>
                                        <option value="LT">Lithuania</option>
                                        <option value="LU">Luxembourg</option>
                                        <option value="MO">Macao</option>
                                        <option value="MK">Macedonia, the former Yugoslav Republic of</option>
                                        <option value="MG">Madagascar</option>
                                        <option value="MW">Malawi</option>
                                        <option value="MY">Malaysia</option>
                                        <option value="MV">Maldives</option>
                                        <option value="ML">Mali</option>
                                        <option value="MT">Malta</option>
                                        <option value="MH">Marshall Islands</option>
                                        <option value="MQ">Martinique</option>
                                        <option value="MR">Mauritania</option>
                                        <option value="MU">Mauritius</option>
                                        <option value="YT">Mayotte</option>
                                        <option value="MX">Mexico</option>
                                        <option value="FM">Micronesia, Federated States of</option>
                                        <option value="MD">Moldova, Republic of</option>
                                        <option value="MC">Monaco</option>
                                        <option value="MN">Mongolia</option>
                                        <option value="ME">Montenegro</option>
                                        <option value="MS">Montserrat</option>
                                        <option value="MA">Morocco</option>
                                        <option value="MZ">Mozambique</option>
                                        <option value="MM">Myanmar</option>
                                        <option value="NA">Namibia</option>
                                        <option value="NR">Nauru</option>
                                        <option value="NP">Nepal</option>
                                        <option value="NL">Netherlands</option>
                                        <option value="NC">New Caledonia</option>
                                        <option value="NZ">New Zealand</option>
                                        <option value="NI">Nicaragua</option>
                                        <option value="NE">Niger</option>
                                        <option value="NG">Nigeria</option>
                                        <option value="NU">Niue</option>
                                        <option value="NF">Norfolk Island</option>
                                        <option value="MP">Northern Mariana Islands</option>
                                        <option value="NO">Norway</option>
                                        <option value="OM">Oman</option>
                                        <option value="PK">Pakistan</option>
                                        <option value="PW">Palau</option>
                                        <option value="PS">Palestinian Territory, Occupied</option>
                                        <option value="PA">Panama</option>
                                        <option value="PG">Papua New Guinea</option>
                                        <option value="PY">Paraguay</option>
                                        <option value="PE">Peru</option>
                                        <option value="PH">Philippines</option>
                                        <option value="PN">Pitcairn</option>
                                        <option value="PL">Poland</option>
                                        <option value="PT">Portugal</option>
                                        <option value="PR">Puerto Rico</option>
                                        <option value="QA">Qatar</option>
                                        <option value="RE">Réunion</option>
                                        <option value="RO">Romania</option>
                                        <option value="RU">Russian Federation</option>
                                        <option value="RW">Rwanda</option>
                                        <option value="BL">Saint Barthélemy</option>
                                        <option value="SH">Saint Helena, Ascension and Tristan da Cunha</option>
                                        <option value="KN">Saint Kitts and Nevis</option>
                                        <option value="LC">Saint Lucia</option>
                                        <option value="MF">Saint Martin (French part)</option>
                                        <option value="PM">Saint Pierre and Miquelon</option>
                                        <option value="VC">Saint Vincent and the Grenadines</option>
                                        <option value="WS">Samoa</option>
                                        <option value="SM">San Marino</option>
                                        <option value="ST">Sao Tome and Principe</option>
                                        <option value="SA">Saudi Arabia</option>
                                        <option value="SN">Senegal</option>
                                        <option value="RS">Serbia</option>
                                        <option value="SC">Seychelles</option>
                                        <option value="SL">Sierra Leone</option>
                                        <option value="SG">Singapore</option>
                                        <option value="SX">Sint Maarten (Dutch part)</option>
                                        <option value="SK">Slovakia</option>
                                        <option value="SI">Slovenia</option>
                                        <option value="SB">Solomon Islands</option>
                                        <option value="SO">Somalia</option>
                                        <option value="ZA">South Africa</option>
                                        <option value="GS">South Georgia and the South Sandwich Islands</option>
                                        <option value="SS">South Sudan</option>
                                        <option value="ES">Spain</option>
                                        <option value="LK">Sri Lanka</option>
                                        <option value="SD">Sudan</option>
                                        <option value="SR">Suriname</option>
                                        <option value="SJ">Svalbard and Jan Mayen</option>
                                        <option value="SZ">Swaziland</option>
                                        <option value="SE">Sweden</option>
                                        <option value="CH">Switzerland</option>
                                        <option value="SY">Syrian Arab Republic</option>
                                        <option value="TW">Taiwan, Province of China</option>
                                        <option value="TJ">Tajikistan</option>
                                        <option value="TZ">Tanzania, United Republic of</option>
                                        <option value="TH">Thailand</option>
                                        <option value="TL">Timor-Leste</option>
                                        <option value="TG">Togo</option>
                                        <option value="TK">Tokelau</option>
                                        <option value="TO">Tonga</option>
                                        <option value="TT">Trinidad and Tobago</option>
                                        <option value="TN">Tunisia</option>
                                        <option value="TR">Turkey</option>
                                        <option value="TM">Turkmenistan</option>
                                        <option value="TC">Turks and Caicos Islands</option>
                                        <option value="TV">Tuvalu</option>
                                        <option value="UG">Uganda</option>
                                        <option value="UA">Ukraine</option>
                                        <option value="AE">United Arab Emirates</option>
                                        <option value="GB">United Kingdom</option>
                                        <option value="US">United States</option>
                                        <option value="UM">United States Minor Outlying Islands</option>
                                        <option value="UY">Uruguay</option>
                                        <option value="UZ">Uzbekistan</option>
                                        <option value="VU">Vanuatu</option>
                                        <option value="VE">Venezuela, Bolivarian Republic of</option>
                                        <option value="VN">Viet Nam</option>
                                        <option value="VG">Virgin Islands, British</option>
                                        <option value="VI">Virgin Islands, U.S.</option>
                                        <option value="WF">Wallis and Futuna</option>
                                        <option value="EH">Western Sahara</option>
                                        <option value="YE">Yemen</option>
                                        <option value="ZM">Zambia</option>
                                        <option value="ZW">Zimbabwe</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 col-xl-4">
                                @php($tz=\App\Models\BusinessSetting::where('key','timezone')->first())
                                @php($tz=$tz?$tz->value:0)
                                <div class="form-group">
                                    <label class="input-label text-capitalize">{{translate('time')}} {{translate('zone')}}</label>
                                    <select name="timezone" class="form-control js-select2-custom">
                                        <option value="UTC" {{$tz?($tz==''?'selected':''):''}}>UTC</option>
                                        <option value="Etc/GMT+12"  {{$tz?($tz=='Etc/GMT+12'?'selected':''):''}}>(GMT-12:00) International Date Line West</option>
                                        <option value="Pacific/Midway"  {{$tz?($tz=='Pacific/Midway'?'selected':''):''}}>(GMT-11:00) Midway Island, Samoa</option>
                                        <option value="Pacific/Honolulu"  {{$tz?($tz=='Pacific/Honolulu'?'selected':''):''}}>(GMT-10:00) Hawaii</option>
                                        <option value="US/Alaska"  {{$tz?($tz=='US/Alaska'?'selected':''):''}}>(GMT-09:00) Alaska</option>
                                        <option value="America/Los_Angeles"  {{$tz?($tz=='America/Los_Angeles'?'selected':''):''}}>(GMT-08:00) Pacific Time (US & Canada)</option>
                                        <option value="America/Tijuana"  {{$tz?($tz=='America/Tijuana'?'selected':''):''}}>(GMT-08:00) Tijuana, Baja California</option>
                                        <option value="US/Arizona"  {{$tz?($tz=='US/Arizona'?'selected':''):''}}>(GMT-07:00) Arizona</option>
                                        <option value="America/Chihuahua"  {{$tz?($tz=='America/Chihuahua'?'selected':''):''}}>(GMT-07:00) Chihuahua, La Paz, Mazatlan</option>
                                        <option value="US/Mountain"  {{$tz?($tz=='US/Mountain'?'selected':''):''}}>(GMT-07:00) Mountain Time (US & Canada)</option>
                                        <option value="America/Managua"  {{$tz?($tz=='America/Managua'?'selected':''):''}}>(GMT-06:00) Central America</option>
                                        <option value="US/Central"  {{$tz?($tz=='US/Central'?'selected':''):''}}>(GMT-06:00) Central Time (US & Canada)</option>
                                        <option value="America/Mexico_City"  {{$tz?($tz=='America/Mexico_City'?'selected':''):''}}>(GMT-06:00) Guadalajara, Mexico City, Monterrey</option>
                                        <option value="Canada/Saskatchewan"  {{$tz?($tz=='Canada/Saskatchewan'?'selected':''):''}}>(GMT-06:00) Saskatchewan</option>
                                        <option value="America/Bogota"  {{$tz?($tz=='America/Bogota'?'selected':''):''}}>(GMT-05:00) Bogota, Lima, Quito, Rio Branco</option>
                                        <option value="US/Eastern"  {{$tz?($tz=='US/Eastern'?'selected':''):''}}>(GMT-05:00) Eastern Time (US & Canada)</option>
                                        <option value="US/East-Indiana"  {{$tz?($tz=='US/East-Indiana'?'selected':''):''}}>(GMT-05:00) Indiana (East)</option>
                                        <option value="Canada/Atlantic"  {{$tz?($tz=='Canada/Atlantic'?'selected':''):''}}>(GMT-04:00) Atlantic Time (Canada)</option>
                                        <option value="America/Caracas"  {{$tz?($tz=='America/Caracas'?'selected':''):''}}>(GMT-04:00) Caracas, La Paz</option>
                                        <option value="America/Manaus"  {{$tz?($tz=='America/Manaus'?'selected':''):''}}>(GMT-04:00) Manaus</option>
                                        <option value="America/Santiago"  {{$tz?($tz=='America/Santiago'?'selected':''):''}}>(GMT-04:00) Santiago</option>
                                        <option value="Canada/Newfoundland"  {{$tz?($tz=='Canada/Newfoundland'?'selected':''):''}}>(GMT-03:30) Newfoundland</option>
                                        <option value="America/Sao_Paulo"  {{$tz?($tz=='America/Sao_Paulo'?'selected':''):''}}>(GMT-03:00) Brasilia</option>
                                        <option value="America/Argentina/Buenos_Aires"  {{$tz?($tz=='America/Argentina/Buenos_Aires'?'selected':''):''}}>(GMT-03:00) Buenos Aires, Georgetown</option>
                                        <option value="America/Godthab"  {{$tz?($tz=='America/Godthab'?'selected':''):''}}>(GMT-03:00) Greenland</option>
                                        <option value="America/Montevideo"  {{$tz?($tz=='America/Montevideo'?'selected':''):''}}>(GMT-03:00) Montevideo</option>
                                        <option value="America/Noronha"  {{$tz?($tz=='America/Noronha'?'selected':''):''}}>(GMT-02:00) Mid-Atlantic</option>
                                        <option value="Atlantic/Cape_Verde"  {{$tz?($tz=='Atlantic/Cape_Verde'?'selected':''):''}}>(GMT-01:00) Cape Verde Is.</option>
                                        <option value="Atlantic/Azores"  {{$tz?($tz=='Atlantic/Azores'?'selected':''):''}}>(GMT-01:00) Azores</option>
                                        <option value="Africa/Casablanca"  {{$tz?($tz=='Africa/Casablanca'?'selected':''):''}}>(GMT+00:00) Casablanca, Monrovia, Reykjavik</option>
                                        <option value="Etc/Greenwich"  {{$tz?($tz=='Etc/Greenwich'?'selected':''):''}}>(GMT+00:00) Greenwich Mean Time : Dublin, Edinburgh, Lisbon, London</option>
                                        <option value="Europe/Amsterdam"  {{$tz?($tz=='Europe/Amsterdam'?'selected':''):''}}>(GMT+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna</option>
                                        <option value="Europe/Belgrade"  {{$tz?($tz=='Europe/Belgrade'?'selected':''):''}}>(GMT+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague</option>
                                        <option value="Europe/Brussels"  {{$tz?($tz=='Europe/Brussels'?'selected':''):''}}>(GMT+01:00) Brussels, Copenhagen, Madrid, Paris</option>
                                        <option value="Europe/Sarajevo"  {{$tz?($tz=='Europe/Sarajevo'?'selected':''):''}}>(GMT+01:00) Sarajevo, Skopje, Warsaw, Zagreb</option>
                                        <option value="Africa/Lagos"  {{$tz?($tz=='Africa/Lagos'?'selected':''):''}}>(GMT+01:00) West Central Africa</option>
                                        <option value="Asia/Amman"  {{$tz?($tz=='Asia/Amman'?'selected':''):''}}>(GMT+02:00) Amman</option>
                                        <option value="Europe/Athens"  {{$tz?($tz=='Europe/Athens'?'selected':''):''}}>(GMT+02:00) Athens, Bucharest, Istanbul</option>
                                        <option value="Asia/Beirut"  {{$tz?($tz=='Asia/Beirut'?'selected':''):''}}>(GMT+02:00) Beirut</option>
                                        <option value="Africa/Cairo"  {{$tz?($tz=='Africa/Cairo'?'selected':''):''}}>(GMT+02:00) Cairo</option>
                                        <option value="Africa/Harare"  {{$tz?($tz=='Africa/Harare'?'selected':''):''}}>(GMT+02:00) Harare, Pretoria</option>
                                        <option value="Europe/Helsinki"  {{$tz?($tz=='Europe/Helsinki'?'selected':''):''}}>(GMT+02:00) Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius</option>
                                        <option value="Asia/Jerusalem"  {{$tz?($tz=='Asia/Jerusalem'?'selected':''):''}}>(GMT+02:00) Jerusalem</option>
                                        <option value="Europe/Minsk"  {{$tz?($tz=='Europe/Minsk'?'selected':''):''}}>(GMT+02:00) Minsk</option>
                                        <option value="Africa/Windhoek"  {{$tz?($tz=='Africa/Windhoek'?'selected':''):''}}>(GMT+02:00) Windhoek</option>
                                        <option value="Asia/Kuwait"  {{$tz?($tz=='Asia/Kuwait'?'selected':''):''}}>(GMT+03:00) Kuwait, Riyadh, Baghdad</option>
                                        <option value="Europe/Moscow"  {{$tz?($tz=='Europe/Moscow'?'selected':''):''}}>(GMT+03:00) Moscow, St. Petersburg, Volgograd</option>
                                        <option value="Africa/Nairobi"  {{$tz?($tz=='Africa/Nairobi'?'selected':''):''}}>(GMT+03:00) Nairobi</option>
                                        <option value="Asia/Tbilisi"  {{$tz?($tz=='Asia/Tbilisi'?'selected':''):''}}>(GMT+03:00) Tbilisi</option>
                                        <option value="Asia/Tehran"  {{$tz?($tz=='Asia/Tehran'?'selected':''):''}}>(GMT+03:30) Tehran</option>
                                        <option value="Asia/Muscat"  {{$tz?($tz=='Asia/Muscat'?'selected':''):''}}>(GMT+04:00) Abu Dhabi, Muscat</option>
                                        <option value="Asia/Baku"  {{$tz?($tz=='Asia/Baku'?'selected':''):''}}>(GMT+04:00) Baku</option>
                                        <option value="Asia/Yerevan"  {{$tz?($tz=='Asia/Yerevan'?'selected':''):''}}>(GMT+04:00) Yerevan</option>
                                        <option value="Asia/Kabul"  {{$tz?($tz=='Asia/Kabul'?'selected':''):''}}>(GMT+04:30) Kabul</option>
                                        <option value="Asia/Yekaterinburg"  {{$tz?($tz=='Asia/Yekaterinburg'?'selected':''):''}}>(GMT+05:00) Yekaterinburg</option>
                                        <option value="Asia/Karachi"  {{$tz?($tz=='Asia/Karachi'?'selected':''):''}}>(GMT+05:00) Islamabad, Karachi, Tashkent</option>
                                        <option value="Asia/Calcutta"  {{$tz?($tz=='Asia/Calcutta'?'selected':''):''}}>(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi</option>
                                        <!-- <option value="Asia/Calcutta"  {{$tz?($tz=='Asia/Calcutta'?'selected':''):''}}>(GMT+05:30) Sri Jayawardenapura</option> -->
                                        <option value="Asia/Katmandu"  {{$tz?($tz=='Asia/Katmandu'?'selected':''):''}}>(GMT+05:45) Kathmandu</option>
                                        <option value="Asia/Almaty"  {{$tz?($tz=='Asia/Almaty'?'selected':''):''}}>(GMT+06:00) Almaty, Novosibirsk</option>
                                        <option value="Asia/Dhaka"  {{$tz?($tz=='Asia/Dhaka'?'selected':''):''}}>(GMT+06:00) Astana, Dhaka</option>
                                        <option value="Asia/Rangoon"  {{$tz?($tz=='Asia/Rangoon'?'selected':''):''}}>(GMT+06:30) Yangon (Rangoon)</option>
                                        <option value="Asia/Bangkok"  {{$tz?($tz=='"Asia/Bangkok'?'selected':''):''}}>(GMT+07:00) Bangkok, Hanoi, Jakarta</option>
                                        <option value="Asia/Krasnoyarsk"  {{$tz?($tz=='Asia/Krasnoyarsk'?'selected':''):''}}>(GMT+07:00) Krasnoyarsk</option>
                                        <option value="Asia/Hong_Kong"  {{$tz?($tz=='Asia/Hong_Kong'?'selected':''):''}}>(GMT+08:00) Beijing, Chongqing, Hong Kong, Urumqi</option>
                                        <option value="Asia/Kuala_Lumpur"  {{$tz?($tz=='Asia/Kuala_Lumpur'?'selected':''):''}}>(GMT+08:00) Kuala Lumpur, Singapore</option>
                                        <option value="Asia/Irkutsk"  {{$tz?($tz=='Asia/Irkutsk'?'selected':''):''}}>(GMT+08:00) Irkutsk, Ulaan Bataar</option>
                                        <option value="Australia/Perth"  {{$tz?($tz=='Australia/Perth'?'selected':''):''}}>(GMT+08:00) Perth</option>
                                        <option value="Asia/Taipei"  {{$tz?($tz=='Asia/Taipei'?'selected':''):''}}>(GMT+08:00) Taipei</option>
                                        <option value="Asia/Tokyo"  {{$tz?($tz=='Asia/Tokyo'?'selected':''):''}}>(GMT+09:00) Osaka, Sapporo, Tokyo</option>
                                        <option value="Asia/Seoul"  {{$tz?($tz=='Asia/Seoul'?'selected':''):''}}>(GMT+09:00) Seoul</option>
                                        <option value="Asia/Yakutsk"  {{$tz?($tz=='Asia/Yakutsk'?'selected':''):''}}>(GMT+09:00) Yakutsk</option>
                                        <option value="Australia/Adelaide"  {{$tz?($tz=='Australia/Adelaide'?'selected':''):''}}>(GMT+09:30) Adelaide</option>
                                        <option value="Australia/Darwin"  {{$tz?($tz=='Australia/Darwin'?'selected':''):''}}>(GMT+09:30) Darwin</option>
                                        <option value="Australia/Brisbane"  {{$tz?($tz=='Australia/Brisbane'?'selected':''):''}}>(GMT+10:00) Brisbane</option>
                                        <option value="Australia/Canberra"  {{$tz?($tz=='Australia/Canberra'?'selected':''):''}}>(GMT+10:00) Canberra, Melbourne, Sydney</option>
                                        <option value="Australia/Hobart"  {{$tz?($tz=='Australia/Hobart'?'selected':''):''}}>(GMT+10:00) Hobart</option>
                                        <option value="Pacific/Guam"  {{$tz?($tz=='Pacific/Guam'?'selected':''):''}}>(GMT+10:00) Guam, Port Moresby</option>
                                        <option value="Asia/Vladivostok"  {{$tz?($tz=='Asia/Vladivostok'?'selected':''):''}}>(GMT+10:00) Vladivostok</option>
                                        <option value="Asia/Magadan"  {{$tz?($tz=='Asia/Magadan'?'selected':''):''}}>(GMT+11:00) Magadan, Solomon Is., New Caledonia</option>
                                        <option value="Pacific/Auckland"  {{$tz?($tz=='Pacific/Auckland'?'selected':''):''}}>(GMT+12:00) Auckland, Wellington</option>
                                        <option value="Pacific/Fiji"  {{$tz?($tz=='Pacific/Fiji'?'selected':''):''}}>(GMT+12:00) Fiji, Kamchatka, Marshall Is.</option>
                                        <option value="Pacific/Tongatapu"  {{$tz?($tz=='Pacific/Tongatapu'?'selected':''):''}}>(GMT+13:00) Nuku'alofa</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 col-xl-4">
                                @php($inactive_auth_minute = Helpers::get_business_settings('inactive_auth_minute'))
                                <div class="form-group">
                                    <label class="input-label text-capitalize d-flex flex-wrap align-items-center column-gap-2" for="inactive_auth_minute">
                                        {{translate('Inactive auth token expire time')}}
                                        <small class="text-danger">( {{translate('In Minute')}} )</small>
                                        <i class="tio-info cursor-pointer text-primary" data-toggle="tooltip" data-placement="top"
                                           title="{{ translate('User will be logged out if no activity happened within this time') }}">
                                        </i>
                                    </label>
                                    <input type="number" name="inactive_auth_minute" class="form-control" id="inactive_auth_minute" value="{{$inactive_auth_minute??''}}" min="0" required>
                                </div>
                            </div>

                            @php($phone=\App\Models\BusinessSetting::where('key','phone')->first())
                            <div class="col-sm-6 col-xl-4 ">
                                <div class="form-group">
                                    <label class="input-label">{{translate('phone')}}</label>
                                    <input type="text" value="{{$phone->value??''}}"
                                        name="phone" class="form-control"
                                        placeholder="" required>
                                </div>
                            </div>

                            @php($hotline=\App\Models\BusinessSetting::where('key','hotline_number')->first())
                            <div class="col-sm-6 col-xl-4 ">
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlInput1">{{translate('Hotline Number')}}</label>
                                    <input type="text" value="{{$hotline->value??''}}"
                                        name="hotline_number" class="form-control"
                                        placeholder="" required>
                                </div>
                            </div>

                            @php($email=\App\Models\BusinessSetting::where('key','email')->first())
                            <div class="col-sm-6 col-xl-4 ">
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlInput1">{{translate('email')}}</label>
                                    <input type="email" value="{{$email->value??''}}"
                                        name="email" class="form-control" placeholder=""
                                        required>
                                </div>
                            </div>

                            @php($two_factor = \App\CentralLogics\Helpers::get_business_settings('two_factor'))
                            <div class="col-sm-6 col-xl-4">
                                <div class="form-group">
                                    <label class="input-label">{{translate('Two Factor Authentication')}}</label>
                                    <div class="input-group">
                                        <!-- Custom Radio -->
                                        <div class="form-control">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" value="1"
                                                    name="two_factor"
                                                    id="two_factor_on" {{$two_factor==1?'checked':''}}>
                                                <label class="custom-control-label"
                                                    for="two_factor_on">{{translate('on')}}</label>
                                            </div>
                                        </div>
                                        <!-- End Custom Radio -->

                                        <!-- Custom Radio -->
                                        <div class="form-control">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" value="0"
                                                    name="two_factor"
                                                    id="two_factor_off" {{$two_factor==0?'checked':''}}>
                                                <label class="custom-control-label"
                                                    for="two_factor_off">{{translate('off')}}</label>
                                            </div>
                                        </div>
                                        <!-- End Custom Radio -->
                                    </div>
                                </div>
                            </div>

                            @php($phone_verification=\App\CentralLogics\Helpers::get_business_settings('phone_verification'))
                            <div class="col-sm-6 col-xl-4">
                                <div class="form-group">
                                    <div class="d-flex align-items-center gap-2">
                                        <label>{{translate('phone')}} {{translate('verification')}} ( OTP )</label>
                                        <small class="text-danger">*</small>
                                    </div>
                                    <div class="input-group">
                                        <!-- Custom Radio -->
                                        <div class="form-control">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" value="1"
                                                    name="phone_verification"
                                                    id="phone_verification_on" {{ $phone_verification==1?'checked':''}}>
                                                <label class="custom-control-label"
                                                    for="phone_verification_on">{{translate('on')}}</label>
                                            </div>
                                        </div>
                                        <!-- End Custom Radio -->

                                        <!-- Custom Radio -->
                                        <div class="form-control">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" value="0"
                                                    name="phone_verification"
                                                    id="phone_verification_off" {{ $phone_verification==0?'checked':''}}>
                                                <label class="custom-control-label"
                                                    for="phone_verification_off">{{translate('off')}}</label>
                                            </div>
                                        </div>
                                        <!-- End Custom Radio -->
                                    </div>
                                </div>
                            </div>
                            @php($agent_self_registration=\App\CentralLogics\Helpers::get_business_settings('agent_self_registration'))
                            <div class="col-sm-6 col-xl-4">
                                <div class="form-group">
                                    <div class="d-flex align-items-center gap-2">
                                        <label>{{translate('Agent Self Registration')}}
                                            <i class="tio-info cursor-pointer text-primary" data-toggle="tooltip" data-placement="top"
                                               title="{{ translate('When this field is active agent can register themself using the agent app') }}">

                                            </i>
                                        </label>
                                    </div>
                                    <div class="input-group">
                                        <!-- Custom Radio -->
                                        <div class="form-control">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" value="1"
                                                    name="agent_self_registration"
                                                    id="agent_self_registration_on" {{ $agent_self_registration==1?'checked':''}}>
                                                <label class="custom-control-label"
                                                    for="agent_self_registration_on">{{translate('on')}}</label>
                                            </div>
                                        </div>
                                        <!-- End Custom Radio -->

                                        <!-- Custom Radio -->
                                        <div class="form-control">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" value="0"
                                                    name="agent_self_registration"
                                                    id="agent_self_registration_off" {{ $agent_self_registration==0?'checked':''}}>
                                                <label class="custom-control-label"
                                                    for="agent_self_registration_off">{{translate('off')}}</label>
                                            </div>
                                        </div>
                                        <!-- End Custom Radio -->
                                    </div>
                                </div>
                            </div>

                            {{--@php($email_verification=\App\CentralLogics\Helpers::get_business_settings('email_verification'))--}}
                                {{--<div class="col-sm-6 col-xl-4 ">--}}
                                {{--<div class="form-group">--}}
                                {{--<label>{{translate('email')}} {{translate('verification')}}</label><small--}}
                                {{--style="color: red">*</small>--}}
                                {{--<div class="input-group">--}}
                                {{--<!-- Custom Radio -->--}}
                                {{--<div class="form-control">--}}
                                {{--<div class="custom-control custom-radio">--}}
                                {{--<input type="radio" class="custom-control-input" value="1"--}}
                                {{--name="email_verification"--}}
                                {{--id="email_verification_on" {{$email_verification==1?'checked':''}}>--}}
                                {{--<label class="custom-control-label"--}}
                                {{--for="email_verification_on">{{translate('on')}}</label>--}}
                                {{--</div>--}}
                                {{--</div>--}}
                                {{--<!-- End Custom Radio -->--}}

                                {{--<!-- Custom Radio -->--}}
                                {{--<div class="form-control">--}}
                                {{--<div class="custom-control custom-radio">--}}
                                {{--<input type="radio" class="custom-control-input" value="0"--}}
                                {{--name="email_verification"--}}
                                {{--id="email_verification_off" {{$email_verification==0?'checked':''}}>--}}
                                {{--<label class="custom-control-label"--}}
                                {{--for="email_verification_off">{{translate('off')}}</label>--}}
                                {{--</div>--}}
                                {{--</div>--}}
                                {{--<!-- End Custom Radio -->--}}
                                {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}

                            <div class="col-sm-6 col-xl-4">
                                @php($address=\App\Models\BusinessSetting::where('key','address')->first())
                                <div class="form-group">
                                    <label class="input-label">{{translate('address')}}</label>
                                    <textarea type="text" id="address" name="address" class="form-control"
                                            rows="1" required>{{$address->value??''}}</textarea>
                                </div>
                            </div>

                            @php($footer_text=\App\Models\BusinessSetting::where('key','footer_text')->first())
                            <div class="col-sm-6 col-xl-4">
                                <div class="form-group">
                                    <label class="input-label">{{translate('footer')}} {{translate('text')}}</label>
                                    <textarea type="text" name="footer_text" class="form-control" rows="1" required>{{$footer_text->value??''}}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6">
                                @php($logo=\App\Models\BusinessSetting::where('key','logo')->first())
                                @php($logo=$logo->value??'')
                                <div class="form-group">
                                    <label class="input-label d-flex align-items-center gap-2">{{translate('logo')}}
                                        <small class="text-danger">* ( {{translate('ratio')}} 3:1 )</small>
                                    </label>

                                    <div class="custom-file">
                                        <input type="file" name="logo" id="customFileEg1" class="custom-file-input"
                                               accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                        <label class="custom-file-label" for="customFileEg1">{{translate('choose File')}}</label>
                                    </div>

                                    <div class="text-center mt-3">
                                        <img class="border rounded-10 mx-w300 w-100" id="viewer"
                                             onerror="this.src='{{asset('public/assets/admin/img/160x160/img2.jpg')}}'"
                                             src="{{asset('storage/app/public/business/'.$logo)}}" alt="logo image"/>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                @php($favicon=\App\CentralLogics\helpers::get_business_settings('favicon'))
                                <div class="form-group">
                                    <label class="input-label d-flex align-items-center gap-2">{{translate('Favicon')}}
                                        <small class="text-danger">* ( {{translate('ratio')}} 1:1 )</small>
                                    </label>

                                    <div class="custom-file">
                                        <input type="file" name="favicon" id="customFileEg2" class="custom-file-input"
                                               accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                        <label class="custom-file-label" for="customFileEg2">{{translate('choose File')}}</label>
                                    </div>

                                    <div class="text-center mt-3" style="aspect-ratio: 2; overflow: hidden;">
                                        <img class="border rounded-10 w-100 img-fit" id="viewer1" style=" max-width: 180px; max-height: 180px"
                                             onerror="this.src='{{asset('public/assets/admin/img/160x160/img2.jpg')}}'"
                                             src="{{asset('storage/app/public/favicon/'.$favicon)}}" alt="favicon"/>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-3">
                            <button type="reset" class="btn btn-secondary">{{translate('reset')}}</button>
                            <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}" onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}" class="btn btn-primary">{{trans('messages.submit')}}</button>
                        </div>
                    </form>
            </div>
        </div>
    </div>

@endsection

@push('script_2')
    <script>

        function maintenance_mode() {
        @if(env('APP_MODE')=='demo')
            toastr.warning('{{translate('Sorry! You can not enable maintenance mode in demo!')}}');
        @else
            Swal.fire({
                title: '{{translate('Are you sure?')}}',
                text: '{{translate('Be careful before you turn on/off maintenance mode')}}',
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#014F5B',
                cancelButtonText: 'No',
                confirmButtonText: 'Yes',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.get({
                        url: '#',
                        contentType: false,
                        processData: false,
                        beforeSend: function () {
                            $('#loading').show();
                        },
                        success: function (data) {
                            toastr.success(data.message);
                        },
                        complete: function () {
                            $('#loading').hide();
                        },
                    });
                } else {
                    location.reload();
                }
            })
        @endif
        };

        function readURL(input, viewId) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $(`#${viewId}`).attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg1").change(function () {
            readURL(this, 'viewer');
        });
        $("#customFileEg2").change(function () {
            readURL(this, 'viewer1');
        });
    </script>

    <script>
        $(document).on('ready', function () {
            @php($country=\App\CentralLogics\Helpers::get_business_settings('country')??'BD')
            $("#country option[value='{{$country}}']").attr('selected', 'selected').change();
        })
    </script>
@endpush
