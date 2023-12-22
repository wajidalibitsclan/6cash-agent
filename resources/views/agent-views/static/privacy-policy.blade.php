@extends('layouts.agent.app')


@section('content')
    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h1>{{ translate('privacy_policy') }}</h1>
                        {{-- <h3>This is a Demo Privacy Policy
                        </h3> --}}
                        <div>
                            <p>
                                {{ translate('privacy_policy_para') }}
                            </p>
                        </div>
                        <h3>{{ translate('privacy_policy_heading') }}
                        </h3>
                        <h4>
                            {{ translate('privacy_policy_heading_two') }}
                        </h4>
                        <div>
                            <p>
                                {{ translate('privacy_policy_para_two') }}
                            </p>
                        </div>
                        <h4>
                            {{ translate('privacy_policy_heading_three') }}
                        </h4>
                        <div>
                            <p>
                                {{ translate('privacy_policy_para_fourth') }}
                            </p>
                        </div>
                        <h3>
                            {{ translate('privacy_policy_heading_fourth') }}
                        </h3>
                        <h4>
                            {{ translate('privacy_policy_heading_fifth') }}
                        </h4>
                        <div>
                            <p>
                                {{ translate('privacy_policy_para_fifth') }}
                            </p>
                        </div>
                        <h3>
                            {{ translate('privacy_policy_heading_sixth') }}
                        </h3>
                        <div>
                            <p>
                                {{ translate('privacy_policy_para_sixth') }}
                            </p>
                        </div>
                        <h3>
                            {{ translate('privacy_policy_heading_eighth') }}
                        </h3>
                        <div>
                            <p>
                                {{ translate('privacy_policy_para_seventh') }}
                            </p>
                        </div>
                        <h3>
                            {{ translate('privacy_policy_heading_ninth') }}
                        </h3>
                        <div>
                            <p>
                                {{ translate('privacy_policy_para_eighth') }}
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
