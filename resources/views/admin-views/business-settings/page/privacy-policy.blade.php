@extends('layouts.admin.app')

@section('title', translate('Privacy Policy'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="d-flex align-items-center gap-3 pb-2 mb-3">
            <img width="24" src="{{asset('public/assets/admin/img/media/compliant.png')}}" alt="">
            <h2 class="mb-0 text-capitalize">{{translate('privacy_policy')}}</h2>
        </div>
        <!-- End Page Header -->

        <div class="card">
            <div class="card-body">
                <form action="{{route('admin.pages.privacy-policy')}}" method="post" id="tnc-form">
                    @csrf
                    <div class="form-group">
                        <textarea class="ckeditor form-control" name="privacy_policy">{!! $data['value']??'' !!}</textarea>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">{{translate('submit')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('script_2')
    <script src="//cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('.ckeditor').ckeditor();
        });
    </script>
@endpush
