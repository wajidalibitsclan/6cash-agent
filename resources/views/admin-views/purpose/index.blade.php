@extends('layouts.admin.app')

@section('title', translate('Add Purpose'))

@push('css_or_js')
    <script src="https://use.fontawesome.com/74721296a6.js"></script>
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="d-flex align-items-center gap-3 mb-3">
            <img width="24" src="{{asset('public/assets/admin/img/media/target.png')}}" alt="">
            <h2 class="page-header-title">{{translate('Add New Purpose')}}</h2>
        </div>
        <!-- End Page Header -->


        <div class="alert alert-primary text-center" role="alert">
            {{ translate('Customers can use these purposes when they will send money') }}
        </div>

        <div class="card card-body my-3">
            <form action="{{route('admin.purpose.store')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group lang_form">
                            <label class="input-label"
                                    for="exampleFormControlInput1">{{ translate('name') }}</label>
                            <input type="text" name="title" class="form-control"
                                    placeholder="{{translate('New Title')}}" required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group lang_form">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <label class="text-dark mb-0">{{translate('Color')}}</label>
                                <small class="text-danger"> * ( {{ translate('choose_in_HEXA_format') }} )</small>
                            </div>
                            <input type="color" name="color" class="form-control p-1 overflow-hidden cursor-pointer" required>
                        </div>
                    </div>
                    <div class="col-12 from_part_2">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <label class="text-dark mb-0">{{translate('Image')}}</label>
                            <small class="text-danger"> *( {{translate('ratio 1:1')}} )</small>
                        </div>
                        <div class="custom-file">
                            <input type="file" name="logo" id="customFileEg1" class="custom-file-input"
                                    accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" required>
                            <label class="custom-file-label" for="customFileEg1">
                                {{ translate('choose file') }}</label>
                        </div>
                    </div>
                    <div class="col-12 from_part_2">
                        <div class="form-group">
                            <div class="text-center mt-3">
                                <img class="border rounded-10 mx-w300 w-100" id="viewer"
                                        src="{{ asset('public/assets/admin/img/900x400/img1.jpg') }}" alt="image"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-3">
                    <button type="reset" class="btn btn-secondary">{{translate('Reset')}}</button>
                    <button type="submit" class="btn btn-primary">{{translate('submit')}}</button>
                </div>
            </form>
        </div>

        <div class="card">
            <div class="card-header __wrap-gap-10">
                <div class="d-flex align-items-center gap-2">
                    <h5 class="card-header-title">{{translate('Purpose Table')}}</h5>
                    <span class="badge badge-soft-secondary text-dark">{{ $purposes->total() }}</span>
                </div>
            </div>
            <!-- Table -->
            <div class="table-responsive datatable-custom">
                <table
                    class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                    <thead class="thead-light">
                    <tr>
                        <th>{{translate('SL')}}</th>
                        <th>{{translate('Title')}}</th>
                        <th>{{translate('Color')}}</th>
                        <th>{{translate('Logo')}}</th>
                        <th class="text-center">{{translate('Action')}}</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($purposes as $key=>$purpose)
                        <tr>
                            <td>{{$purposes->firstitem()+$key}}</td>
                            <td>
                                <span class="d-block font-size-sm text-body">
                                    {{$purpose['title']}}
                                </span>
                            </td>
                            <td>
                                <div class="btn-pill"
                                        style="height: 1em; width: 4em; background-color: {{$purpose['color']??''}}"></div>
                            </td>
                            <td>
                                <img class="shadow mx-h60"
                                        onerror="this.src='{{asset('public/assets/admin/img/160x160/img1.jpg')}}'"
                                        src="{{asset('storage/app/public/purpose')}}/{{$purpose['logo']}}">
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{route('admin.purpose.edit', ['id'=>$purpose['id']])}}"
                                    class="action-btn btn btn-outline-primary"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                    <button onclick="delete_purpose('{{route('admin.purpose.delete', ['id'=>$purpose['id']])}}')"
                                    class="action-btn btn btn-outline-danger"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="table-responsive mt-4 px-3">
                <div class="d-flex justify-content-end">
                    {!! $purposes->links() !!}
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script_2')
    <script>
        function delete_purpose($route) {
            swal({
                title: "Are you sure?",
                text: "You won't be able to revert this !",
                icon: "warning",
                buttons: true,
                dangerMode: true,
                showCancelButton: true,
            })
                .then((result) => {
                    console.log(result);
                    if (result.value === true) {
                        window.location.href = $route;
                    }
                });
        }
    </script>
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
@endpush
