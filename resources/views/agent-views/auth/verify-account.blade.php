@extends('layouts.agent.app')


@section('content')
    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h1>Verify Your Account</h1>
                        <div>
                            <form action="{{ route('agent.verified.account') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id" value="{{ Auth::user()->id }}">
                                <div class="form-group mb-2">
                                    <label for="">Identification Type</label>
                                    <select name="identification_type" id="" class="form-control" required>
                                        <option value="" selected disabled>Select Identification Type</option>
                                        @foreach (IDENTITY_TYPE as $identityType)
                                            <option value="{{ $identityType['slug'] }}">{{ $identityType['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="old_pin">Identity Number</label>
                                    <input type="text" class="form-control" placeholder="Identitication number"
                                        name="identification_number" required>
                                </div>
                                <div class="form-group">
                                    <label for="">Identity Image</label>
                                    <input type="file" name="identification_image[]">
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Update</button>
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
    <script></script>
@endpush
