@extends('layouts.agent.app')


@section('content')
    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h1>{{ translate('Terms of Use') }}</h1>
                        <h3>{{ translate('This is A test terms & conditions') }}
                        </h3>
                        <div>
                            <p>
                                {{ translate('term_para') }}
                            </p>
                        </div>
                        <h3>
                            {{ translate('USE OF PLATFORM AND SERVICES') }}
                        </h3>
                        <div>
                            <p>
                                {{ translate('term_para_two') }}
                            </p>
                        </div>
                        <h3>{{ translate('Personal Information that you provide') }}
                        </h3>
                        <div>
                            <p>
                                {{ translate('term_para_three') }}
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
