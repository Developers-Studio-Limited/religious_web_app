@extends('layouts.admin_app')
@section('content')
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">


        <div class="kt-portlet kt-portlet--mobile">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{session('success')}}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <div class="kt-portlet__head kt-portlet__head--lg">
                <div class="kt-portlet__head-label">
            <span class="kt-portlet__head-icon">
                <i class="fa fa-tasks"></i>
            </span>
                    <h3 class="kt-portlet__head-title">
                        Service Providers
                    </h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                    <div class="kt-portlet__head-wrapper">
                        <div class="kt-portlet__head-actions">
                            <div class="dropdown dropdown-inline">

                            </div>
                            &nbsp;
                            <a href="#" class="btn btn-brand btn-elevate btn-icon-sm new-amaal">
                                <i class="la la-plus"></i>
                                Add
                            </a>
                        </div>
                    </div>      </div>
            </div>

            <div class="kt-portlet__body">
                <!--begin: Datatable -->
                <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Community Name</th>
                        <th>Account Name</th>
                        <th>Account title</th>
                        <th>IBAN</th>
                        <th style="text-align: center">Document Image</th>
                        <th>Type</th>
                        <th>Action</th>
                    </tr>
                    </thead>

                    <tbody>
                    @if(count($serviceProviders)>0)
                        @php
                            $count=0;
                        @endphp

                        @foreach($serviceProviders as  $key => $serviceProvider)
                            @php $count++; @endphp
                            {{-- {{ dd($amal['task'][0]['recurring']) }} --}}
                            <tr>
                                <td>{{$count}}</td>
                                <td>{{$serviceProvider->community->name}}</td>
                                <td>{{$serviceProvider->account_name}}</td>
                                <td>{{$serviceProvider->account_title}}</td>
                                <td>{{$serviceProvider->IBAN}}</td>
                                <td style="text-align: center"><a href="{{ asset('/storage'.$serviceProvider->registration_document) }}"><img height="30%" width="30%" src="{{ asset('/storage'.$serviceProvider->registration_document) }}"></a></td>
                                <td>{{$serviceProvider->type}}</td>
                                <td>@if($serviceProvider->is_approve == 0)
                                    <button data-id = "{{ $serviceProvider->id }}" id="confirm{{ $key }}" data-key="{{ $key }}" class="btn btn-danger btn-elevate btn-icon-sm confirm">
                                        Confirm
                                    </button>
                                    @endif
                                    @if($serviceProvider->is_approve == 1)
                                        <button id="approve" disabled class="btn btn-success btn-elevate btn-icon-sm">
                                            Approved
                                    </button>
                                    @endif
                                    <button id="approve{{ $key }}" disabled class="btn btn-success btn-elevate btn-icon-sm d-none">
                                        Approved
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8" style="text-align: center">No Record Found</td>
                        </tr>
                    @endif
                    </tbody>

                </table>
                <!--end: Datatable -->
            </div>
        </div>

    </div>
    <!-- end:: Content -->
@endsection

@section('page_js')
    <script src="{{asset('admin_assets/js/basic.js')}}" type="text/javascript"></script>

    <script type="text/javascript">
        $(".confirm").on('click',function() {
            ServiceProviderId = $(this).data('id');
            key = $(this).attr('data-key');
            $(this).html('Confirming...');
            $(this).css('opacity', '0.4');
            $(this).disabled = true;
            $.ajax({
                url: '{{route('service-provider.approve')}}',
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    id : ServiceProviderId
                },
                success: function (data) {

                    if(data == 'Approved'){
                        $('#approve'+key).removeClass('d-none');
                        $('#confirm'+key).hide();
                    }
                    else{
                        alert('not Approved');

                    }

                },
                error: function(jqXHR, textStatus, errorThrown){
                    $(this).html('Confirm');
                    $(this).css('opacity', '0');
                    $(this).disabled = false;
                },
            });

        });


    </script>
@endsection
