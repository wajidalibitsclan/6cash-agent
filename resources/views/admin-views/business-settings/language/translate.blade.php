@extends('layouts.admin.app')

@section('title', translate('Language Translate'))

@push('css_or_js')
    <!-- Custom styles for this page -->
    <link href="{{asset('public/assets/admin')}}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="d-flex align-items-center gap-3 pb-2">
            <img width="24" src="{{asset('public/assets/admin/img/media/languages.png')}}" alt="">
            <h2 class="page-header-title">{{translate('Language')}}</h2>
        </div>
        <!-- End Page Header -->

        <div class="row">
            <div class="col-md-12">
                <div class="card" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                    <div class="card-header">
                        <h5 class="mb-0">{{translate('language_content_table')}}</h5>
                        <a href="{{route('admin.business-settings.language.index')}}"
                           class="btn btn-sm btn-danger">
                            <span class="text text-capitalize">{{translate('back')}}</span>
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table" id="dataTable">
                            <thead class="thead-light">
                                <tr>
                                    <th>{{translate('SL')}}</th>
                                    <th>{{translate('key')}}</th>
                                    <th>{{translate('value')}}</th>
                                    <th>{{translate('action')}}</th>
                                    {{--<th></th>--}}
                                </tr>
                            </thead>

                            <tbody>
                            @foreach($lang_data as $count=>$language)
                                <tr id="lang-{{$language['key']}}">
                                    <td>{{$count+1}}</td>
                                    <td>
                                        <div style="white-space: initial; max-width: 500px">
                                            <input type="text" name="key[]" value="{{$language['key']}}" hidden>
                                            <label>{{$language['key']}}</label>
                                        </div>
                                    </td>
                                    <td style="width: 90%; min-width: 300px;">
                                        <textarea type="text" class="form-control w-100" name="value[]"
                                               id="value-{{$count+1}}" style="width: auto"
                                        >{{$language['value']}}</textarea>
                                    </td>
                                    <td style="width: 50%">
                                        <button type="button"
                                                onclick="update_lang('{{urlencode($language['key'])}}',$('#value-{{$count+1}}').val())"
                                                class="btn btn-primary">Update
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <!-- Page level plugins -->
    <script src="{{asset('public/assets/admin')}}/vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <!-- Page level custom scripts -->
    <script>
        // Call the dataTables jQuery plugin
        $(document).ready(function () {
            $('#dataTable').DataTable({
                "pageLength": '{{\App\CentralLogics\Helpers::pagination_limit()}}'
            });
        });

        function update_lang(key, value) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('admin.business-settings.language.translate-submit',[$lang])}}",
                method: 'POST',
                data: {
                    key: key,
                    value: value
                },
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (response) {
                    toastr.success('{{translate('text_updated_successfully')}}');
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        }

        function remove_key(key) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('admin.business-settings.language.remove-key',[$lang])}}",
                method: 'POST',
                data: {
                    key: key
                },
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (response) {
                    toastr.success('{{translate('Key removed successfully')}}');
                    $('#lang-'+key).hide();
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        }
    </script>

@endpush
