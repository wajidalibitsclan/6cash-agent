@extends('layouts.admin.app')

@section('title', translate('FAQ'))

@push('css_or_js')
    <!-- Custom styles for this page -->
    {{-- <link href="{{asset('public/assets/admin')}}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"> --}}
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="d-flex align-items-center gap-3 mb-3">
            <img width="24" src="{{asset('public/assets/admin/img/media/faq.png')}}" alt="">
            <h2 class="page-header-title">{{translate('FAQ')}}</h2>
        </div>
        <!-- End Page Header -->

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{translate('FAQ')}} {{translate('Table')}} </h5>
                <button class="btn btn-primary btn-icon-split for-addFaq" data-toggle="modal"
                        data-target="#addModal">
                    <i class="tio-add"></i>
                    <span class="text">{{translate('Add')}} {{translate('faq')}}  </span>
                </button>
            </div>
            <div class="card-">
                <div class="table-responsive">
                    <table class="table table-borderless table-align-middle card-table">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">{{translate('SL')}}</th>
                                <th scope="col">{{translate('Question')}}</th>
                                <th scope="col">{{translate('Answer')}}</th>
                                <th scope="col">{{translate('Ranking')}}</th>
                                <th scope="col">{{translate('Status')}} </th>
                                <th class="text-center">{{translate('Action')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($helps as $k=>$help)
                            <tr>
                                <td scope="row">{{$k+1}}</td>
                                <td><div class="mn-w160">{{$help['question']}}</div></td>
                                <td><div class="mn-w400">{{$help['answer']}}</div></td>
                                <td>{{$help['ranking']}}</td>

                                <td>
                                    <label class="switcher">
                                        <input type="checkbox" class="switcher_input status_id"
                                                data-id="{{ $help->id }}" {{$help->status == 1?'checked':''}} onchange="statusUpdate({{$help->id}})">
                                        <span class="switcher_control"></span>
                                    </label>
                                </td>
                                <td>
                                    {{-- @if($help->status== 1)
                                    <a class=" status_id  btn btn-success btn-sm" data-id="{{ $help->id }}">
                                        <i class="fa fa-sync"></i>
                                    </a>
                                    @else
                                    <a class=" status_id btn btn-danger btn-sm" data-id="{{ $help->id }}">
                                        <i class="fa fa-sync"></i>
                                    </a>
                                    @endif --}}

                                    {{--
                                        <a href="{{ route('admin.helpTopic.delete',$help->id) }}" class="btn btn-danger btn-sm " onclick="alert('Are You sure to Delete')"  >
                                            <i class="fa fa-trash"></i> --}}
                                    {{-- </a> --}}

                                    <div class="d-flex justify-content-center gap-2">
                                        <a class="action-btn btn btn-outline-primary"
                                            data-toggle="modal" data-target="#editModal"
                                            data-id="{{ $help->id }}" onclick="editItem({{ $help->id }})">
                                            <i class="tio-edit"></i>

                                        </a>
                                        <a class="action-btn btn btn-outline-danger"
                                            id="{{$help['id']}}" onclick="deleteItem({{ $help->id }})">
                                            <i class="tio-add-to-trash"></i>
                                        </a>
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
                        {!! $helps->links() !!}
                    </div>
                </div>
            </div>
        </div>

        {{-- add modal --}}
        <div class="modal fade" tabindex="-1" role="dialog" id="addModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title mb-0">{{translate('Add Help Topic')}}</h4>
                        <button type="button" class="close fs-30" data-dismiss="modal" aria-label="Close"> <span
                                aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('admin.helpTopic.add-new') }}" method="post" id="addForm">
                        @csrf
                        <div class="modal-body pt-3 pb-0" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">

                            <div class="form-group">
                                <label class=" text-dark">{{translate('Question')}}</label>
                                <input type="text" class="form-control" name="question" placeholder="{{translate('Type Question')}}">
                            </div>

                            <div class="form-group">
                                <label class=" text-dark">{{translate('Answer')}}</label>
                                <textarea class="form-control" name="answer" rows="6" placeholder="{{translate('Type Answer')}}"></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="ranking" class=" text-dark">{{translate('Ranking')}}</label>
                                        <input type="number" name="ranking" class="form-control" autofoucs>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="control-label mb-2 text-dark">{{translate('Status')}}</div>
                                        <label class="custom-switch d-flex align-items-center gap-2 pl-0">
                                            <input type="checkbox" name="status" id="e_status" value="1"
                                                   class="custom-switch-input">
                                            {{-- <span class="custom-switch-indicator"></span> --}}
                                            <span class="custom-switch-description">{{translate('Active')}}</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{translate('Close')}}</button>
                            <button class="btn btn-primary">{{translate('Save')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- edit modal --}}
    <div class="modal fade" tabindex="-1" role="dialog" id="editModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title mb-0">{{translate('Edit Modal Help Topic')}}</h4>
                    <button type="button" class="close fs-30" data-dismiss="modal" aria-label="Close"> <span
                            aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" method="post" id="editForm" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                    @csrf
                    {{-- @method('put') --}}
                    <div class="modal-body pt-3 pb-0">
                        <div class="form-group">
                            <label>{{translate('Question')}}</label>
                            <input type="text" class="form-control" name="question" placeholder="{{translate('Type Question')}}"
                                   id="e_question" class="e_name">
                        </div>
                        <div class="form-group">
                            <label>{{translate('Answer')}}</label>
                            <textarea class="form-control" name="answer"
                                      rows="6" placeholder="{{translate('Type Answer')}}" id="e_answer"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="ranking">{{translate('Ranking')}}</label>
                            <input type="number" name="ranking" class="form-control" id="e_ranking" required
                                    autofoucs>
                        </div>
                    </div>
                    <div class="modal-footer pt-0 border-0">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{translate('Close')}}</button>
                        <button class="btn btn-primary">{{translate('update')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('script')
    <!-- Page level plugins -->
    {{-- <script src="{{asset('public/assets/admin')}}/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="{{asset('public/assets/admin')}}/vendor/datatables/dataTables.bootstrap4.min.js"></script> --}}

    <!-- Page level custom scripts -->
    {{-- <script src="{{asset('public/assets/admin')}}/js/demo/datatables-demo.js"></script> --}}

    <script>
        // $(document).ready(function () {
        //     $('#dataTable').DataTable();
        // });

        function statusUpdate(id) {
            // let id = $(this).attr('data-id');
            $.ajax({
                url: "status/" + id,
                type: 'get',
                dataType: 'json',
                success: function (res) {
                    toastr.success(res.success);
                    window.location.reload();
                }

            });
        }

        function editItem(id) {
            // let id = $(this).attr("data-id");
            console.log(id);
            $.ajax({
                url: "edit/" + id,
                type: "get",
                data: {"_token": "{{ csrf_token() }}"},
                dataType: "json",
                success: function (data) {
                    // console.log(data);
                    $("#e_question").val(data.question);
                    $("#e_answer").val(data.answer);
                    $("#e_ranking").val(data.ranking);


                    $("#editForm").attr("action", "update/" + data.id);


                }
            });

        }

        function deleteItem(id) {
            // var id = $(this).attr("id");
            Swal.fire({
                title: '{{translate('Are you sure delete this FAQ')}}?',
                text: "{{translate('You will not be able to revert this')}}!",
                showCancelButton: true,
                confirmButtonColor: '#014F5B',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{translate('Yes, delete it')}}!'
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{route('admin.helpTopic.delete')}}",
                        method: 'POST',
                        data: {id: id},
                        success: function () {
                            toastr.success('{{translate('FAQ deleted successfully')}}');
                            location.reload();
                        }
                    });
                }
            })
        }
    </script>
@endpush
