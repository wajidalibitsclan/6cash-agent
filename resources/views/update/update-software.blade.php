@extends('layouts.blank')

@section('content')
    <div class="container">
        <div class="row mt-5">
            <div class="col-2"></div>
            <div class="col-md-8">
                <div class="card mt-3">
                    <div class="card-body">
                        <div class="mar-ver pad-btm text-center mb-3">
                            <h1 class="h3">6cash Software Update</h1>
                            <span class="alert-warning text-danger px-1" role="alert">
                                    Note: Update can take more than 5 minutes. Please do not close the window.
                            </span>
                        </div>
                        <form method="POST" action="{{route('update-system')}}">
                            @csrf
                            <div class="form-group">
                                <label for="purchase_code" class="gap-2 mb-2">Codecanyon Username</label>
                                <input type="text" class="form-control" id="username" value="{{env('BUYER_USERNAME')}}"
                                       name="username" required>
                            </div>

                            <div class="form-group">
                                <label for="purchase_code" class="gap-2 mb-2 mt-2">Purchase Code</label>
                                <input type="text" class="form-control" id="purchase_key"
                                       value="{{env('PURCHASE_CODE')}}" name="purchase_key" required>
                            </div>
                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-info">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-2"></div>
        </div>
    </div>
@endsection
