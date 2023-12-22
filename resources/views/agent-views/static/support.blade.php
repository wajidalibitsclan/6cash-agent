@extends('layouts.agent.app')


@section('content')
    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h1 class="text-center">{{ translate('Need Any Help') }}</h1>
                        <h3 class="text-center">{{ translate('Feel free to contact us any time.') }}</h3>
                        <h4 class="text-center">{{ translate('We Support 24/7 Hour') }}</h4>
                        <div class="text-center">
                            <a href="tel:8801000000000" class="btn btn-primary"><i
                                    class="fa-solid fa-phone mr-2"></i>{{ translate('Make Call') }}</a>
                            <a href="mailto:6cash@6amtech.com" class="btn btn-primary"><i class="fa-solid fa-envelope mr-2"></i>
                                {{ translate('Send Email') }}</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
