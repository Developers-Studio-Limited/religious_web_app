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
                <i class="kt-font-brand flaticon2-line-chart"></i>
            </span>
            <h3 class="kt-portlet__head-title">
                {{$title}}
            </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-wrapper">

</div>      </div>
    </div>

    <div class="kt-portlet__body">
        <!--begin: Datatable -->
        <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
            <thead>
                <tr>
                    <th>No</th>
                    <th style="text-transform: capitalize;">Name</th>
                    <th>Created by</th>
                    <th>Description</th>
                    <th>Hadiya(PKR)</th>
                    <th>Status</th>
                    <th>Assign</th>
                </tr>
            </thead>

            <tbody>
                @if(count($jobs)>0)
                @php
                    $count=0;
                @endphp

                    @foreach($jobs as $key => $job)
                        @php $count++; @endphp
                        <tr>
                            <td>{{$count}}</td>
                            <td style="text-transform: capitalize;">{{$job->name}}</td>
                            @if(!is_null($job['user']))
                                <td>{{$job['user']['name']}}</td>
                            @else
                                <td>N/A</td>
                            @endif
                            <td data-toggle="tooltip" data-placement="top" title="{{$job->description}}">{{ Str::limit($job->description, 5) }}</td>
                           @if($job->hadiya != "")
                            <td>{{ $job->hadiya }} </td>
                            @endif
                            @if($job->hadiya == "")
                                <td><mark style="background-color: #b3ff20"> Free</mark></td>
                            @endif
                                <td style="text-transform:capitalize;">
                                @php
                                    if($job->status=='pending'){
                                        $class = "kt-badge  kt-badge--info kt-badge--inline kt-badge--pill";
                                    }
                                    elseif($job->status=='approved'){
                                        $class = "kt-badge  kt-badge--success kt-badge--inline kt-badge--pill";
                                    }
                                    elseif($job->status=='progress'){
                                        $class = "kt-badge  kt-badge--brand kt-badge--inline kt-badge--pill";
                                    }
                                    elseif($job->status=='dispute'){
                                        $class = "kt-badge  kt-badge--danger kt-badge--inline kt-badge--pill";
                                    }
                                    elseif($job->status=='dispute_resolved'){
                                        $class = "kt-badge  kt-badge--info kt-badge--inline kt-badge--pill";
                                    }
                                @endphp
                                <span class="{{$class}}">{{$job->status}}</span>
                            </td>
                            <td style="display:flex">

                                <select class="form-control" id="communities{{$key}}">
                                    @foreach($communities as $community)
                                        <option value="{{ $community->id }}">{{ $community->name }}</option>
                                    @endforeach
                                </select>
                                <span style="margin: 0 0 0 5px;"><button data-id="{{ $job->id }}" data-key = "{{ $key }}" class="btn btn-danger assign_button" id="assign_button{{ $key }}">Assigned</button></span>
                            <button class="btn btn-success d-none" id="approve" disabled>Approved</button>
                            </td>
                        </tr>
                    @endforeach
                @else
                        <tr>
                            <td colspan="7" style="text-align: center">No Record Found</td>
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
          $(".assign_button").on('click',function() {
              JobId = $(this).data('id');
              key = $(this).attr('data-key');
              communityId = $("#communities"+key).val();
              $(this).html('Assigning...');
              $(this).css('opacity', '0.4');
              $(this).disabled = true;
              $.ajax({
                  url: '{{route('job.assign')}}',
                  type: 'POST',
                  data: {
                      "_token": "{{ csrf_token() }}",
                      JobId : JobId,
                      communityId : communityId
                  },
                  success: function (data) {

                      if(data == 'Updated'){

                          $('#assign_button'+key).removeClass('btn-danger');
                          $('#assign_button'+key).addClass('btn-success');
                          $('#assign_button'+key).css('opacity', '');
                          $('#assign_button'+key).html('Approved');
                          $('#assign_button'+key).prop('disabled', true);
                      }
                      else{
                          alert('Not Updated');

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
