@extends('layouts.admin_app')
@section('content')
<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{session('success')}}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <div class="kt-portlet">
        <div class="kt-portlet__body">
            <div class="kt-widget kt-widget--user-profile-3">
                <div class="kt-widget__top">

                    <div class="kt-widget__pic kt-widget__pic--danger kt-font-danger kt-font-bolder kt-font-light kt-hidden">
                        JM
                    </div>
                    <div class="kt-widget__content">
                        <div class="kt-widget__head">
                            <a href="#" class="kt-widget__username" style="font-size: x-large;font-weight:600;">
                                {{ $community->name }}
                            </a>
                            <div class="kt-widget__action">
                                <button type="button" class="btn btn-brand btn-sm btn-upper add_member"  data-toggle="modal" data-target="#exampleModal">add member</button>
                            </div>
                        </div>

                        <div class="kt-widget__info" style="margin-top: 15px;">
                            <div class="kt-widget__desc">
                                {{ $community->description }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

                        <!-- begin:: Content -->
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
            <!--Begin::App-->
        <div class="kt-grid kt-grid--desktop kt-grid--ver kt-grid--ver-desktop kt-app">
            <!--Begin:: App Aside Mobile Toggle-->
            <button class="kt-app__aside-close" id="kt_contact_aside_close">
                <i class="la la-close"></i>
            </button>
            <!--End:: App Aside Mobile Toggle-->



            <!--Begin:: App Content-->
            <div class="kt-grid__item kt-grid__item--fluid kt-app__content">
                <!--Begin::Section-->
                <div class="row" style="max-height: 500px; overflow-y: auto;">
                    <div class="col-xl-12">
                        <!--Begin::Portlet-->
                        <div class="kt-portlet kt-portlet--height-fluid">
                            <div class="kt-portlet__head kt-portlet__head--noborder" style="margin-bottom: 30px; border-bottom: 2px solid gray;">
                                <div class="kt-portlet__head-label" style="width:100%">
                                    <h3 class="kt-portlet__head-title" style="font-size: x-large;">Jobs</h3>
                                    <label class="kt-portlet__head-title" style="font-size: large;text-align: right;width: 100%;">Total Jobs: {{ count($community['jobs']) }}</label>
                                </div>

                            </div>
                            @if(count($community['jobs']) > 0)
                                @foreach ($community['jobs'] as $jobs)
                                    <div class="kt-portlet__body" @if(!$loop->first) style="margin-top:5px;" @endif>
                                        <!--begin::Widget -->
                                        <div class="kt-widget kt-widget--user-profile-2">
                                            <div class="kt-widget__head" style="margin-bottom:10px">
                                                <div class="kt-widget__info" style="width:100%">
                                                    @if($jobs['subCategory'])
                                                        <a href="#" class="kt-widget__username">
                                                            {{ $jobs['title'] }}
                                                        </a>
                                                        @php
                                                            $count = 0;
                                                        @endphp
                                                        @if(count($jobs['userJob'])>0)
                                                            @foreach ($jobs['userJob'] as $user_job)
                                                                @php
                                                                    $count = $count + $user_job['count_done'];
                                                                @endphp
                                                            @endforeach
                                                        @endif
                                                        <span style="float: right !important">Quantity Done: {{ $count }}/{{ $jobs['quantity'] }}</span>

                                                        <span class="kt-widget__desc" style="font-size:12px">
                                                            Maximum Assignee: {{ $jobs['max_assignee'] }}
                                                        </span>
                                                        <span class="kt-widget__desc" style="font-size:12px">
                                                            Quantity: {{ $jobs['quantity'] }}
                                                        </span>
                                                        @if($jobs['type'] == "kura" && $jobs['is_kura'] == 0 && ($count>=$jobs['quantity'] || $jobs['due_date'] < date('Y-m-d')))
                                                        <span style="float: right !important">
                                                            <button type="button" class="btn btn-brand btn-sm btn-upper generate_kura" data-job-id="{{ $jobs['id'] }}" data-job-title="{{ $jobs['title'] }}" data-toggle="modal" data-target="#kuraModal">Generate Kura</button>
                                                        </span>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>

                                            @if($loop->last)
                                                <div class="kt-widget__footer" style="margin-top: 0;"></div>
                                            @else
                                                <div class="kt-widget__footer" style="border-bottom: 1px solid;margin-top: 0;">
                                                    {{-- <button type="button" class="btn btn-label-primary btn-lg btn-upper">write message</button> --}}
                                                </div>
                                            @endif
                                        </div>
                                        <!--end::Widget -->
                                    </div>
                                @endforeach
                            @else
                                <div class="kt-portlet__body">
                                    <div class="kt-widget kt-widget--user-profile-2">
                                        <div class="kt-widget__head" style="text-align: center">
                                            <div class="kt-widget__info" style="width:100%">
                                                <a href="#" class="kt-widget__username">
                                                    No Job Found
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                        </div>
                        <!--End::Portlet-->
                    </div>

                </div>
                <!--End::Section-->


            </div>
            <!--Begin:: App Content-->
            <div class="kt-grid__item kt-grid__item--fluid kt-app__content">
                <!--Begin::Section-->
                <div class="row" style="max-height: 500px; overflow-y: auto;">
                    <div class="col-xl-12">
                        <!--Begin::Portlet-->
                        <div class="kt-portlet kt-portlet--height-fluid">
                            <div class="kt-portlet__head kt-portlet__head--noborder" style="margin-bottom: 30px; border-bottom: 2px solid gray;">
                                <div class="kt-portlet__head-label" style="width:100%">
                                    <h3 class="kt-portlet__head-title" style="font-size: x-large;">Members</h3>
                                    <label class="kt-portlet__head-title" style="font-size: large;text-align: right;width: 100%;">Total Members: {{ count($community['users']) }}</label>
                                </div>

                            </div>
                            @if(count($community['users']) > 0)
                                @foreach ($community['users'] as $members)
                                    <div class="kt-portlet__body"  style=" @if(!$loop->first) margin-top:5px;@endif @if($loop->last) padding-bottom:0px; @endif">
                                        <!--begin::Widget -->
                                        <div class="kt-widget kt-widget--user-profile-2">
                                            <div class="kt-widget__head" style="margin-bottom:10px">
                                                <div class="kt-widget__info">
                                                    @if($members)
                                                        <a href="#" class="kt-widget__username" style="text-transform: capitalize;">
                                                            {{ $members->name }}
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>

                                            @if($loop->last)
                                                <div class="kt-widget__footer" style="margin-top: 0;"></div>
                                            @else
                                                <div class="kt-widget__footer" style="border-bottom: 1px solid;margin-top: 0;">
                                                    {{-- <button type="button" class="btn btn-label-primary btn-lg btn-upper">write message</button> --}}
                                                </div>
                                            @endif
                                        </div>
                                        <!--end::Widget -->
                                    </div>
                                @endforeach
                            @else
                                <div class="kt-portlet__body">
                                    <div class="kt-widget kt-widget--user-profile-2">
                                        <div class="kt-widget__head" style="text-align: center">
                                            <div class="kt-widget__info" style="width:100%">
                                                <a href="#" class="kt-widget__username">
                                                    No Member Found
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <!--End::Portlet-->
                    </div>

                </div>
                <!--End::Section-->


            </div>
            <!--End:: App Content-->
        </div>
    <!--End::App-->
    </div>
    <!-- end:: Content -->

<!-- Modal POPUP To Add Community Member -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <form action="{{ route('add-community-member') }}" method="POST" name="add-amaal" enctype="multipart/form-data" autocomplete="off">
          @csrf
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Add Member</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="row" style="padding: 5px;">
                  <div class="form-group" style="width: 100%">
                      <label class="form-label">Member</label>
                      <select class="form-control kt-selectpicker sel_members" id="sel_members" name="sel_members[]" required multiple>
                        @if(!empty($all_users))
                            @foreach ($all_users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        @endif
                      </select>
                      <input type="hidden" name="comm_id" value="{{ $community->id }}">
                  </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save</button>
            </div>
          </div>
      </form>
    </div>
  </div>
</div>
<!-- Modal Popup to generate Kura -->
<div class="modal fade" id="kuraModal" tabindex="-1" role="dialog" aria-labelledby="kuraModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <form action="{{ route('add-kura') }}" method="POST" name="generate-kura" enctype="multipart/form-data" autocomplete="off">
          @csrf
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="kuraModalLabel"></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="row" style="padding: 5px;">
                  <div class="form-group" style="width: 100%; margin-bottom:0">
                    <label class="form-label"><b>Kura Winners</b></label>
                    <input type="hidden" name="job_id" id="job_id" value="">
                    <button type="button" class="btn btn-brand btn-sm btn-upper refresh_kura" style="position: absolute; right:10px;">Refresh</button>
                  </div>
                 <div class="kura_body">

                 </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save</button>
            </div>
          </div>
      </form>
    </div>
  </div>
</div>

@endsection

@section('page_js')
    <script src="{{asset('admin_assets/js/list-columns.js')}}" type="text/javascript"></script>
    <script src="{{asset('admin_assets/js/bootstrap-select.js')}}" type="text/javascript"></script>
    <script>
        $(".add_member").on('click',function(){
            $('#sel_members').selectpicker('deselectAll');
        })
        $(".generate_kura").on('click',function(){
            $(".kura_body").empty();
            var job_id = $(this).attr("data-job-id");
            var job_title = $(this).attr("data-job-title");
            $("#job_id").val(job_id);
            $("#kuraModalLabel").text(job_title);
            generateKura(job_id);
        })
        $(".refresh_kura").on('click',function(){
            $(".kura_body").empty();
            var job_id = $("#job_id").val();
            generateKura(job_id);
        })
        function generateKura(job_id)
        {
            $("#overlay").fadeIn(300);　
            $.ajax({
                url:" {{ route('generate-kura') }}",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    job_id : job_id
                },
                success: function(response){
                    console.log(response);
                    var html ="";
                    var no = "";
                    $.each( response, function( index, detail ){
                        no = index + 1;
                        html+= '<div class="row">'+
                                    '<span class="form-label" style="margin-left:5px;" >'+no+'. '+detail.user.name+'</span>'+
                                    '<input name="winners[]" value="'+detail.user.id+'" style="display:none;"/>'+
                                '</div>';

                    });
                    $(".kura_body").append(html);
                    $("#overlay").fadeOut(300);　
                }
            });
        }
    </script>
@endsection
