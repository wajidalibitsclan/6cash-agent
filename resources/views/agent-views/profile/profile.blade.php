@extends('layouts.agent.app')


@section('content')
    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h1 class="px-2"> {{ translate('Agent Profile') }}</h1>
                        <div>
                            <form action="{{ route('agent.profile.edit') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id" value="{{ $user->id }}">
                                <input type="hidden" name="country_id" value="{{ $user->country_id }}" id="set_country_id">
                                <input type="hidden" name="city_id" value="{{ $user->city_id }}" id="set_city_id">
                                @isset($user->image)
                                    <div class="form-group px-3">
                                        <img src="{{ asset('public/storage/agent/' . $user->image) }}"
                                            class="img-fluid rounded-circle" width="100" height="100" alt="">
                                    </div>
                                @else
                                    <div class="form-group px-3">
                                        <img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png"
                                            class="img-fluid rounded-circle" width="100" height="100" alt="">
                                    </div>
                                @endisset

                                <div class="row p-3">
                                    <div class="col-md-6 mb-3">
                                        <label for="">Profile Picture</label>
                                        <input type="file" class="form-control" name="image">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="gender">{{ translate('Gender') }}</label>
                                        <select id="" name="gender" class="form-control">
                                            <option value="" disabled>{{ translate('Select Gender') }}</option>
                                            <option value="Male" {{ $user->gender === 'Male' ? 'selected' : '' }}>
                                                {{ translate('Male') }}
                                            </option>
                                            <option value="Female" {{ $user->gender === 'Female' ? 'selected' : '' }}>
                                                {{ translate('Female') }}
                                            </option>
                                            <option value="Other" {{ $user->gender === 'Other' ? 'selected' : '' }}>
                                                {{ translate('Other') }}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="f_name">{{ translate('Occupation') }}</label>
                                        <input type="text" class="form-control" value="{{ $user->occupation }}"
                                            name="occupation">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="f_name">{{ translate('First Name') }}</label>
                                        <input type="text" class="form-control" value="{{ $user->f_name }}"
                                            name="f_name">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="f_name">{{ translate('Last Name') }}</label>
                                        <input type="text" class="form-control" value="{{ $user->l_name }}"
                                            name="l_name">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="country">{{ translate('Country') }}</label>
                                        <select name="country" id="" class="form-control"
                                            onchange="selectCity(event)">
                                            <option value="" selected disabled>Select Country
                                            </option>
                                            @foreach (\App\Models\Country::active()->get() as $country)
                                                <option value="{{ $country->name }}"
                                                    {{ $country->id === $user->country_id ? 'selected' : '' }}>
                                                    {{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="city">{{ translate('City') }}</label>
                                        <select name="city" id="city-list" class="form-control"
                                            onchange="setCityId(event)">
                                            <option value="" selected disabled class="remove-city-list">Select City
                                            </option>
                                            @foreach (\App\Models\City::authCity(auth()->user()->country_id)->active()->get() as $city)
                                                <option class="remove-city-list" value="{{ $city->name }}"
                                                    {{ $city->id === $user->city_id ? 'selected' : '' }}>
                                                    {{ $city->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="f_name">{{ translate('Email Address') }}
                                            ({{ translate('Optional') }})</label>
                                        <input type="email" class="form-control" value="{{ $user->email }}"
                                            name="email">
                                    </div>
                                </div>

                                <div class="form-group p-3">
                                    <button type="submit" class="btn btn-primary">{{ translate('update') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        function selectCity(country) {
            let country_name = country.target.value;
            $.ajax({
                url: "{{ route('agent.city.list') }}",
                type: "post",
                data: {
                    country_name: country_name,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    $("#set_country_id").val(response.country_id);
                    $(".remove-city-list").remove();
                    $("#city-list").append(
                        `<option class="remove-city-list" value="" selected disabled>Select City</option>`);
                    response.data.forEach(element => {
                        $("#city-list").append(
                            `<option class="remove-city-list" value="${element.name}">${element.name}</option>`
                        )
                    });
                }
            });
        }

        function setCityId(event) {
            let city = event.target.value;
            let country_id = $("#set_country_id").val();
            $.ajax({
                url: "{{ route('agent.city.city_id') }}",
                type: 'post',
                data: {
                    city: city,
                    country_id: country_id,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $("#set_city_id").val(response.city_id);
                }
            });
        }
    </script>
@endpush
