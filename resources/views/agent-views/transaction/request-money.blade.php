@extends('layouts.agent.app')


@section('content')
    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h1>{{ translate('Request Money') }}</h1>

                        <form action="{{ route('agent.requested.money') }}" method="POST">
                            @csrf
                            <input type="hidden" value="{{ Auth::user()->id }}" name="id">
                            <div class="form-group">
                                <label for="">{{ translate('Amount') }}</label>
                                <input type="number" name="amount" placeholder="{{ translate('Amount') }}"
                                    id="amount-input" class="form-control"
                                    style="border: none;font-size: 34px;font-weight:bold">
                            </div>
                            <div class="form-group">
                                <h3>{{ translate('Available Balance') }} <span
                                        class="text-primary">{{ $userData['balance'] }} EUR</span>
                                </h3>
                            </div>
                            <div class="form-group mt-2">
                                <button type="button" onclick="suggestionHandler(500)" class="btn btn-light">500</button>
                                <button type="button" onclick="suggestionHandler(1000)"
                                    class="btn btn-light">1,000</button>
                                <button type="button" onclick="suggestionHandler(2000)"
                                    class="btn btn-light">2,000</button>
                                <button type="button" onclick="suggestionHandler(5000)"
                                    class="btn btn-light">5,000</button>
                            </div>
                            <div class="form-group mb-2">
                                <label for="">{{ translate('note') }} ({{ translate('Optional') }})</label>
                                <textarea name="note" placeholder="Enter note" class="form-control" cols="3" rows="3"></textarea>
                            </div>
                            <div class="form-group mt-2">
                                <button type="submit" class="btn btn-primary">{{ translate('Request Money') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        function suggestionHandler(amount) {
            $("#amount-input").val(amount)
        }
    </script>
@endpush
