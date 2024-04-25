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
                Tasks
            </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-wrapper">
    <div class="kt-portlet__head-actions">
        <div class="dropdown dropdown-inline">
            
        </div>
        &nbsp;
        <a href="{{ route('add-task') }}" class="btn btn-brand btn-elevate btn-icon-sm new-amaal">
            <i class="la la-plus"></i>
            New Record
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
                    <th>Name</th>
                    <th>Description</th>
                    <th>Sub Category</th>
                    <th>Recurring Type</th>
                    {{-- <th>Naiki</th> --}}
                    <th>Task Count</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @if(count($tasks)>0)
                @php
                    $count=0;
                @endphp

                    @foreach($tasks as $task)
                        @php $count++; @endphp
                        {{-- {{ dd($amal['task'][0]['recurring']) }} --}}
                        <tr>
                            <td>{{$count}}</td>
                            <td>{{$task->name}}</td>
                            <td data-toggle="tooltip" data-placement="top" title="{{$task->description}}">{{ Str::limit($task->description, 35) }}</td>
                            <td>{{ $task['category']['name'] }}</td>
                            <td>{{$task['recurring_type']}}</td>
                            {{-- <td>{{ $task['category']['naiki'] }}</td> --}}
                            <td>{{$task['task_count']}}</td>
                            <td>
                                <a href="{{route('edit-task', $task->id)}}">Edit</a> |
                                <a href="{{route('view-task', $task->id)}}">View</a> |
                                @if($task['is_active']==1)
                                    <a href="{{ route('disable-task', $task->id) }}" data-id="{{$task->id}}" class="disable_task">Disable</a>
                                @else
                                    <a href="{{ route('enable-task', $task->id) }}" data-id="{{$task->id}}" class="enable_task">Enable</a>
                                @endif
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

        //   $(".delete_amal").on('click',function(){
        //     /*alert($(this).data('id'));*/
        //     Swal.fire({
        //       title: 'Are you sure?',
        //       text: "Are you sure to delete amaal",
        //       icon: 'warning',
        //       showCancelButton: true,
        //       confirmButtonColor: '#3085d6',
        //       cancelButtonColor: '#d33',
        //       confirmButtonText: 'Delete'
        //     }).then((result) => {
        //        console.log(result);
        //        if (result.value==true) {
        //         var amal_id = $(this).data('id');
        //         $.ajax({
        //             url: '{{route('delete-amal')}}',
        //             type: 'POST',
        //             data: {
        //                 "_token": "{{ csrf_token() }}",
        //                 amal_id : amal_id
        //             },
        //             success: function (data) {
        //               if(data=='success'){
        //                 Swal.fire(
        //                   'Success!',
        //                   'Amaal deleted successfully',
        //                   'success'
        //                 );
        //                 setTimeout(function(){
        //                    window.location.reload();
        //                 }, 2000);
        //               }
        //               else{
                        
        //               }
                       
        //             }
        //         });
        //        } 
        //     });
        //   });
      </script>
@endsection
