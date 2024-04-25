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
                <i class="fa fa-users"></i>
            </span>
            <h3 class="kt-portlet__head-title">
                Communities
            </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-wrapper">
    <div class="kt-portlet__head-actions">
        <div class="dropdown dropdown-inline">
            
        </div>
        &nbsp;
        <a href="{{ route('create-community') }}" class="btn btn-brand btn-elevate btn-icon-sm new-amaal">
            <i class="la la-plus"></i>
            New Record
        </a>
    </div>  
</div>      </div>
    </div>

    <div class="kt-portlet__body tab-content">
        <ul class="nav nav-pills nav-fill" role="tablist">
            <li class="nav-item">
                <a class="nav-link table-tabs active" data-toggle="tab" href="#kt_table_1">Active</a>
            </li>
            <li class="nav-item">
                <a class="nav-link table-tabs" data-toggle="tab" href="#kt_table_2">Pending</a>
            </li>
            <li class="nav-item">
                <a class="nav-link table-tabs" data-toggle="tab" href="#kt_table_3">Disabled</a>
            </li>
            <li class="nav-item">
                <a class="nav-link table-tabs" data-toggle="tab" href="#kt_table_4">Draft</a>
            </li>
        </ul>
            <!--begin: Datatable Active Community -->
            <table class="table table-striped- table-bordered table-hover table-checkable tab-pane active" role="tabpanel" id="kt_table_1">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Members</th>
                        <th>Tasks</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @if(count($active_communities)>0)
                    @php
                        $count=0;
                    @endphp

                        @foreach($active_communities as $community)
                            @php $count++; @endphp
                            {{-- {{ dd($amal['task'][0]['recurring']) }} --}}
                            <tr>
                                <td>{{$count}}</td>
                                <td>{{$community->name}}</td>
                                <td data-container="body" data-toggle="kt-tooltip" data-placement="bottom" title="{{$community->description}}">{{ Str::limit($community->description, 25) }}</td>
                                <td>{{ count($community['communityMembers']) }}</td>
                                <td>{{ count($community['jobs']) }}</td>
                                <td>
                                    <a href="{{route('view-community', $community->id)}}">View</a> |
                                    <a href="{{route('edit-community', $community->id)}}">Edit</a> |
                                    @if($community->is_active == 1)                               
                                        <a href="{{ route('disable-community', $community->id) }}" class="disable_community">Disable</a>
                                    @else
                                        <a href="{{ route('enable-community', $community->id) }}" class="enable_community">Enable</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                            <tr>
                                <td colspan="6" style="text-align: center">No Record Found</td>
                            </tr>
                    @endif
                </tbody>

            </table>
            <!--end: Datatable Active Community -->

            <!--begin: Datatable Pending Community -->
            <table class="table table-striped- table-bordered table-hover table-checkable tab-pane" role="tabpanel" id="kt_table_2">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Members</th>
                        <th>Tasks</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @if(count($pending_communities)>0)
                    @php
                        $count=0;
                    @endphp

                        @foreach($pending_communities as $pend_community)
                            @php $count++; @endphp
                            {{-- {{ dd($amal['task'][0]['recurring']) }} --}}
                            <tr>
                                <td>{{$count}}</td>
                                <td>{{$pend_community->name}}</td>
                                <td data-container="body" data-toggle="kt-tooltip" data-placement="bottom" title="{{$pend_community->description}}">{{ Str::limit($pend_community->description, 35) }}</td>
                                <td>{{ count($pend_community['communityMembers']) }}</td>
                                <td>{{ count($pend_community['jobs']) }}</td>
                                <td>
                                    <a href="{{route('view-community', $pend_community->id)}}">View</a> |
                                    <a href="{{route('edit-community', $pend_community->id)}}">Edit</a> |
                                    <a href="{{ route('approve-community', $pend_community->id) }}" class="approve_community">Approve</a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                            <tr>
                                <td colspan="6" style="text-align: center">No Record Found</td>
                            </tr>
                    @endif
                </tbody>

            </table>
            <!--end: Datatable Pending Community -->

            <!--begin: Datatable Disable Community -->
            <table class="table table-striped- table-bordered table-hover table-checkable tab-pane" role="tabpanel" id="kt_table_3">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Members</th>
                        <th>Tasks</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @if(count($disabled_communities)>0)
                    @php
                        $count=0;
                    @endphp

                        @foreach($disabled_communities as $disable_community)
                            @php $count++; @endphp
                            {{-- {{ dd($amal['task'][0]['recurring']) }} --}}
                            <tr>
                                <td>{{$count}}</td>
                                <td>{{$disable_community->name}}</td>
                                <td data-container="body" data-toggle="kt-tooltip" data-placement="bottom" title="{{$disable_community->description}}">{{ Str::limit($disable_community->description, 35) }}</td>
                                <td>{{ count($disable_community['communityMembers']) }}</td>
                                <td>{{ count($disable_community['jobs']) }}</td>
                                <td>
                                    <a href="{{route('view-community', $disable_community->id)}}">View</a> |
                                    <a href="{{route('edit-community', $disable_community->id)}}">Edit</a> |
                                    <a href="{{ route('enable-community', $disable_community->id) }}" class="enable_community">Enable</a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                            <tr>
                                <td colspan="6" style="text-align: center">No Record Found</td>
                            </tr>
                    @endif
                </tbody>

            </table>
            <!--end: Datatable Disable Community -->

            <!--begin: Datatable Draft Community -->
            <table class="table table-striped- table-bordered table-hover table-checkable tab-pane" role="tabpanel" id="kt_table_4">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Members</th>
                        <th>Tasks</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @if(count($draft_communities)>0)
                    @php
                        $count=0;
                    @endphp

                        @foreach($draft_communities as $draft_community)
                            @php $count++; @endphp
                            {{-- {{ dd($amal['task'][0]['recurring']) }} --}}
                            <tr>
                                <td>{{$count}}</td>
                                <td>{{$draft_community->name}}</td>
                                <td data-container="body" data-toggle="kt-tooltip" data-placement="bottom" title="{{$draft_community->description}}">{{ Str::limit($draft_community->description, 35) }}</td>
                                <td>{{ count($draft_community['communityMembers']) }}</td>
                                <td>{{ count($draft_community['jobs']) }}</td>
                                <td>
                                    <a href="{{route('view-community', $draft_community->id)}}">View</a> |
                                    <a href="{{route('edit-community', $draft_community->id)}}">Edit</a> |
                                    <a href="{{ route('enable-community', $draft_community->id) }}" class="enable_community">Enable</a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                            <tr>
                                <td colspan="6" style="text-align: center">No Record Found</td>
                            </tr>
                    @endif
                </tbody>

            </table>
            <!--end: Datatable Draft Community -->
            
        
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
