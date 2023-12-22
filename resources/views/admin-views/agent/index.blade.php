@extends('layouts.admin.app')

@section('title', translate('Add New Agent'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="d-flex align-items-center gap-3 mb-3">
            <img width="24" src="{{asset('public/assets/admin/img/media/agent.png')}}" alt="">
            <h2 class="page-header-title">{{translate('Add New Agent')}}</h2>
        </div>
        <!-- End Page Header -->

        <div class="card card-body">
            <form action="{{route('admin.agent.store')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-sm-6 col-lg-4">
                        <div class="form-group">
                            <label class="input-label"
                                    for="exampleFormControlInput1">{{translate('First Name')}}</label>
                            <input type="text" name="f_name" class="form-control" value="{{ old('f_name') }}"
                                    placeholder="{{translate('First Name')}}"
                                    required>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4">
                        <div class="form-group">
                            <label class="input-label"
                                    for="exampleFormControlInput1">{{translate('Last Name')}}</label>
                            <input type="text" name="l_name" class="form-control" value="{{ old('l_name') }}"
                                    placeholder="{{translate('Last Name')}}"
                                    required>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4">
                        <div class="form-group">
                            <label class="input-label"
                                    for="exampleFormControlInput1">{{translate('email')}}
                                <small class="text-muted">({{translate('optional')}})</small></label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                                    placeholder="{{translate('Ex : ex@example.com')}}">
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4">
                        <div class="form-group">
                            <label class="input-label d-block">{{translate('phone')}}<small class="text-danger"></small></label>
                            <div class="input-group __input-grp">
                                <select id="country_code" name="country_code" class="__input-grp-select" required>
                                    <option value="" disabled selected>{{ translate('select') }}</option>
                                    @foreach(PHONE_CODE as $country_code)
                                        <option value="{{ $country_code['code'] }}" {{ $current_user_info && strpos($country_code['name'], $current_user_info->countryName) !== false ? 'selected' : '' }}>{{ $country_code['name'] }}</option>
                                    @endforeach
                                </select>
                                <input type="text" name="phone" class="form-control __input-grp-input" value="{{ old('phone') }}"
                                        placeholder="{{translate('Ex : 171*******')}}" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4">
                        <div class="form-group">
                            <label class="input-label">{{translate('Gender')}}</label>
                            <select name="gender" class="form-control" required>
                                <option value="" selected
                                        disabled>{{translate('Select Gender')}}</option>
                                <option value="male" {{ (old("gender") == 'male' ? "selected":"") }}>{{translate('Male')}}</option>
                                <option value="female" {{ (old("gender") == 'female' ? "selected":"") }}>{{translate('Female')}}</option>
                                <option value="other" {{ (old("gender") == 'other' ? "selected":"") }}>{{translate('Other')}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4">
                        <div class="form-group">
                            <label class="input-label">{{translate('Occupation')}}</label>
                            <input type="text" name="occupation" class="form-control" value="{{ old('occupation') }}"
                                    placeholder="{{translate('Ex : Businessman')}}"
                                    required>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4 ">
                        <label class="input-label">{{translate('PIN')}}</label>
                        <div class="input-group input-group-merge">
                            <input type="password" name="password" class="js-toggle-password form-control form-control input-field"
                                    placeholder="{{translate('4 digit PIN')}}" required maxlength="4"
                                    data-hs-toggle-password-options='{
                                        "target": "#changePassTarget",
                                        "defaultClass": "tio-hidden-outlined",
                                        "showClass": "tio-visible-outlined",
                                        "classChangeTarget": "#changePassIcon"
                                        }'>
                            <div id="changePassTarget" class="input-group-append">
                                <a class="input-group-text" href="javascript:">
                                    <i id="changePassIcon" class="tio-visible-outlined"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group pt-4">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <label class="text-dark mb-0">{{translate('Agent Image')}}</label>
                        <small class="text-danger"> *( {{translate('ratio 1:1')}} )</small>
                    </div>
                    <div class="custom-file">
                        <input type="file" name="image" id="customFileEg1" class="custom-file-input"
                                accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" required>
                        <label class="custom-file-label"
                                for="customFileEg1">{{translate('choose')}} {{translate('file')}}</label>
                    </div>

                    <div class="text-center mt-3">
                        <img class="border rounded-10 w-200" id="viewer"
                                src="{{asset('public/assets/admin/img/400x400/img2.jpg')}}" alt="agent image"/>
                    </div>
                </div>

                <div class="d-flex gap-3 justify-content-end">
                    <button type="reset" class="btn btn-secondary">{{translate('reset')}}</button>
                    <button type="submit" class="btn btn-primary">{{translate('submit')}}</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('script_2')

    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#viewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg1").change(function () {
            readURL(this);
        });
    </script>

    <script src="{{asset('public/assets/admin/js/spartan-multi-image-picker.js')}}"></script>
    <script type="text/javascript">
        $(function () {
            $("#coba").spartanMultiImagePicker({
                fieldName: 'identity_image[]',
                maxCount: 5,
                rowHeight: '120px',
                groupClassName: 'col-2',
                maxFileSize: '',
                placeholderImage: {
                    image: '{{asset('public/assets/admin/img/400x400/img2.jpg')}}',
                    width: '100%'
                },
                dropFileLabel: "Drop Here",
                onAddRow: function (index, file) {

                },
                onRenderedPreview: function (index) {

                },
                onRemoveRow: function (index) {

                },
                onExtensionErr: function (index, file) {
                    toastr.error('Please only input png or jpg type file', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                },
                onSizeErr: function (index, file) {
                    toastr.error('File size too big', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });
        });
    </script>
@endpush
