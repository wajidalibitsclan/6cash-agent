@extends('layouts.admin.app')

@section('title', translate('Add_withdrawal_methods'))

@push('css_or_js')
    <script src="https://use.fontawesome.com/74721296a6.js"></script>
@endpush


@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="d-flex align-items-center gap-3 mb-4">
            <img width="24" src="{{asset('public/assets/admin/img/media/cash-withdrawal.png')}}" alt="">
            <div class="d-flex align-items-center gap-2">
                <h1 class="page-header-title mb-0">
                    {{translate('Withdrawal Method Add')}}
                </h1>
                <i class="tio-info text-primary cursor-pointer" data-toggle="tooltip" data-placement="top"
                    title="{{translate('Agent/Customer/Merchant will use these methods to withdraw their money directly from admin')}}">
                </i>
            </div>
        </div>
        <!-- End Page Header -->

        <div class="card card-body mb-3">
            <form action="{{route('admin.withdrawal_methods.store')}}" method="post"
                    enctype="multipart/form-data">
                @csrf
                <div class="d-flex align-items-end gap-3 mb-4">
                    <div class="flex-grow-1">
                        <label class="input-label">{{translate('Method Name')}}</label>
                        <input type="text" maxlength="255" name="method_name" id="method_name" class="form-control" placeholder="" required>
                    </div>
                    <button type="button" class="btn btn-primary text-nowrap" id="add-field">{{translate('Add Fields')}}</button>
                </div>

                <div id="method-field"></div>

                <div class="d-flex justify-content-end gap-3">
                    <button type="button" class="btn btn-warning" id="reset">{{translate('Reset')}}</button>
                    <button type="submit" class="btn btn-info">{{translate('Add Method')}}</button>
                </div>
            </form>
        </div>
        
        <div class="card overflow-hidden">
            <!-- Table -->
            <div class="table-responsive datatable-custom">
                <table
                    class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                    <thead class="thead-light">
                    <tr>
                        <th>{{translate('SL')}}</th>
                        <th>{{translate('Method Name')}}</th>
                        <th>{{translate('Fields')}}</th>
                        <th class="text-center">{{translate('action')}}</th>
                    </tr>
                    </thead>

                    <tbody id="set-rows">
                    @foreach($withdrawal_methods as $key=>$withdrawal_method)
                        <tr>
                            <td>{{$withdrawal_methods->firstitem()+$key}}</td>
                            <td>
                                {{$withdrawal_method['method_name']}}
                            </td>
                            <td>
                                @foreach($withdrawal_method['method_fields'] as $key=>$fields)
                                    <span class="badge badge-pill badge-light">
                                        {{translate('Name') . ': ' . $fields['input_name'] . ' | ' . translate('Type') . ': ' . $fields['input_type'] . ' | ' . translate('Placeholder') . ': ' . $fields['placeholder']}}
                                    </span><br/>
                                @endforeach
                            </td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <button class="action-btn btn btn-outline-danger"  onclick="deleteItem({{ $withdrawal_method->id }})">
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                    </button>
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
                    {!! $withdrawal_methods->links() !!}
                </div>
            </div>
        </div>
    </div>
@endsection


@push('script')
    <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
    <script>
        // function fieldTypeChange(count_no) {
        //     $("#field_name_"+count_no).get(0).type = $("#field_type_"+count_no).val();
        // }
        function delete_input_field(row_id) {
            //console.log(row_id);
            $( `#field-row--${row_id}` ).remove();
            count--;
        }


        jQuery(document).ready(function ($) {
            count = 1;
            $('#add-field').on('click', function (event) {
                if(count <= 15) {
                    event.preventDefault();

                    $('#method-field').append(
                        `<div class="d-flex align-items-end gap-3 mb-4 flex-wrap" id="field-row--${count}">
                            <div class="flex-grow-1">
                                <div class="">
                                    <label class="input-label">{{translate('Input Field Type')}} </label>
                                    <select class="form-control" name="field_type[]" id="field_type_${count}" required onchange="fieldTypeChange(${count})">
                                        <option value="string">{{translate('String')}}</option>
                                        <option value="number">{{translate('Number')}}</option>
                                        <option value="date">{{translate('Date')}}</option>
                                        <option value="password">{{translate('Password')}}</option>
                                        <option value="email">{{translate('Email')}}</option>
                                        <option value="phone">{{translate('Phone')}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="">
                                    <label class="input-label">{{translate('Input Field Name')}} </label>
                                    <input type="text" name="field_name[]" class="form-control" maxlength="255" placeholder="" id="field_name_${count}" required>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="">
                                    <label class="input-label">{{translate('Input Field Placeholder/Hints')}} </label>
                                    <input type="text" name="placeholder[]" class="form-control" maxlength="255" placeholder="" required>
                                </div>
                            </div>
                            <div class="" data-toggle="tooltip" data-placement="top" title="{{translate('Remove the input field')}}">
                                <div class="action-btn size-40 btn btn-outline-danger" onclick="delete_input_field(${count})">
                                    <i class="tio-delete-outlined"></i>
                                </div>
                            </div>
                        </div>`
                    );

                    count++;
                } else {
                    Swal.fire({
                        title: '{{translate('Reached maximum')}}',
                        confirmButtonText: '{{translate('ok')}}',
                    });
                }
            })

            $('#reset').on('click', function (event) {
                $('#method-field').html("");
                $('#method_name').val("");
                count=1;
            })
        });
    </script>

    <script>
        function deleteItem(id) {
            // var id = $(this).attr("id");
            Swal.fire({
                title: '{{translate('Are you sure')}}?',
                text: "{{translate('You will not be able to revert this')}}!",
                showCancelButton: true,
                confirmButtonColor: '#174F5B',
                cancelButtonColor: '#EA295E',
                confirmButtonText: '{{translate('Yes, delete it')}}!'
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{route('admin.withdrawal_methods.delete')}}",
                        method: 'POST',
                        data: {id: id},
                        success: function () {
                            toastr.success('{{translate('Removed successfully')}}');
                            location.reload();
                        }
                    });
                }
            })
        }
    </script>
@endpush


@push('script_2')

@endpush
