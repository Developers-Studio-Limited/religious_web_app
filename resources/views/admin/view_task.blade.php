@extends('layouts.admin_app')
@section('content')
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
	{{-- <form class="kt-form" action="{{ route('submit-edit-sub-amaal') }}" enctype="multipart/form-data" method="POST"> --}}
	<form class="kt-form" action="javascript:void(0)" enctype="multipart/form-data" method="POST">
		@csrf
		 @if(session('success'))
	        <div class="alert alert-success alert-dismissible fade show" role="alert">
	          {{session('success')}}
	          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
	            <span aria-hidden="true">&times;</span>
	          </button>
	        </div>
	    @endif
		<div class="kt-portlet__body">

            <img src="{{asset('storage/services/0h8WQHLFLtYnEr0PClp9JCtv8ftByTd2dcFxl6q2.png')}}">

			<div class="form-group">
				<label>Name</label>
				<input type="text" class="form-control" aria-describedby="emailHelp" placeholder="Enter Name" readonly disabled name="name" value="{{$task->name}}" required>
			</div>
            <div class="form-group">
                <label>Category</label>
                <input type="text" class="form-control" aria-describedby="emailHelp" placeholder="Enter Name" readonly disabled name="name" value="{{$task['category']['parentCategory']['name']}}" required>
            </div>
            <div class="form-group">
                <label>Sub Category</label>
                <input type="text" class="form-control" aria-describedby="emailHelp" placeholder="Enter Name" readonly disabled name="name" value="{{$task['category']['name']}}" required>
            </div>
            <div class="form-group">
                <label>Select Event</label>
                <input type="text" class="form-control" readonly disabled name="tags" value="{{ $tagNames }}"/>
            </div>
			<div class="form-group">
				<label for="description">Description</label>
				<textarea name="description" class="form-control" id="description" readonly disabled rows="5">{{ $task->description }}</textarea>
			</div>
            <div class="form-group">
				<label>Recurring Type</label>
                <input type="text" class="form-control" aria-describedby="emailHelp" placeholder="Enter Name" readonly disabled name="name" value="{{$task->recurring_type}}" required>
			</div>
			@if ($task->recurring_type == "weekly" && count($task->taskDay) > 0)
				<div class="form-group">
					<label>Weekdays</label>
					<input type="text" class="form-control" aria-describedby="emailHelp" placeholder="Enter Name" readonly disabled name="name" value="{{ $task->taskDay->pluck('number')->implode(', ') }}" required>
				</div>
			@elseif ($task->recurring_type == "monthly" && count($task->taskDay) > 0)
				<div class="form-group">
					<label>Days</label>
					<input type="text" class="form-control" aria-describedby="emailHelp" placeholder="Enter Name" readonly disabled name="name" value="{{ $task->taskDay->pluck('number')->implode(', ') }}" required>
				</div>
			@endif
			</div>
            <div class="form-group">
				<label>Task Count</label>
				<input type="number" min="1" class="form-control" readonly disabled aria-describedby="emailHelp" placeholder="Enter Task Count" name="task_count" value="{{$task->task_count}}" required>
			</div>
            <div class="form-group">
				<label>Task Time</label>
                @foreach ($task['taskTime'] as $tasks_time)
                    <div class="col-lg-4 col-md-9 col-sm-12" style="margin-top: 10px;">
                        <div class="input-group timepicker">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="la la-clock-o"></i>
                                </span>
                            </div>
                            <input type="hidden" name="task_timepick_id[]" value="{{ $tasks_time['id'] }}">
                            <input class="form-control" id="task_timepicker" name="task_timepicker[]" readonly disabled placeholder="from" value="{{ $tasks_time['time'] }}" type="text"/>
                            <input class="form-control" id="task_timepicker2" name="task_timepicker2[]" readonly disabled placeholder="to" value="{{ $tasks_time['time_to'] }}" type="text"/>
                            <input type="text" class="form-control" placeholder="Time Name" style="margin-left:5px" readonly disabled name="task_time_name[]" value="{{ $tasks_time['name'] }}">
                            <input type="text" class="form-control" placeholder="Time Quantity" style="margin-left:5px" readonly disabled name="task_time_quant[]" value="{{ $tasks_time['quantity'] }}">
                        </div>
                    </div>
                @endforeach
			</div>

			<input type="hidden" name="amaal_id" value="{{ $task->id }}">
		</div>
		{{-- <div class="kt-portlet__foot">
			<div class="kt-form__actions">
				<button type="submit" class="btn btn-primary">Update</button>
			</div>
		</div> --}}
	</form>
</div>

@endsection

@section('page_js')
<script src="{{asset('admin_assets/js/bootstrap-select.js')}}" type="text/javascript"></script>
<script src="{{asset('admin_assets/js/bootstrap-timepicker.js')}}" type="text/javascript">
</script>

    <script>
        $('input[name="tags"]').amsifySuggestags({
            type : 'amsify',
            suggestions:['ali'] ,
        });
    </script>
@endsection
