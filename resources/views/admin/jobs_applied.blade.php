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
                    <th>Name</th>
                    <th>Applied by</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                @if(count($jobs)>0)
                @php
                    $count=0;
                @endphp

                    @foreach($jobs as $job)
                        @php $count++; @endphp
                        <tr>
                            <td>{{$count}}</td>
                            @if(!is_null($job->jobs))
                                <td style="text-transform: capitalize;">{{$job->jobs->name}}</td>
                            @endif
                            @if(!is_null($job->user))
                            <td>{{$job->user->name}}</td>
                            @endif
                            @php
                                if($job->status==0){
                                    $status = "Pending";
                                    $class = "kt-badge  kt-badge--info kt-badge--inline kt-badge--pill";
                                }
                                elseif($job->status==1){
                                    $status = "Approved";
                                    $class = "kt-badge  kt-badge--success kt-badge--inline kt-badge--pill";
                                }
                            @endphp
                            <td>
                                <span class="{{$class}}">{{$status}}</span>
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
          
      </script>
@endsection
