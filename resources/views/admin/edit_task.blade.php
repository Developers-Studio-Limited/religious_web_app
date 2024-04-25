@extends('layouts.admin_app')
@section('content')
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
	{{-- <form class="kt-form" action="{{ route('submit-edit-sub-amaal') }}" enctype="multipart/form-data" method="POST"> --}}
	<form class="kt-form" action="{{ route('update-task') }}" enctype="multipart/form-data" method="POST">
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

			<div class="form-group">
				<label>Name</label>
				<input type="text" class="form-control" aria-describedby="emailHelp" placeholder="Enter Name" name="name" value="{{$task->name}}" required>
			</div>
            <div class="form-group">
                <label>Category</label>
                <select class="form-control kt-selectpicker parent_category" id="parent_category" name="parent_category" disabled required>
                    <option value="" selected disabled>Select Category</option>
                    @if(!empty($category))
                    @foreach ($category as $category)
                        <option value="{{ $category->id }}" {{ ( $category->id == $task['category']['parentCategory']['id']) ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                    @endif
                </select>
                {{-- <input type="text" class="form-control" aria-describedby="emailHelp" placeholder="Enter Name" readonly disabled name="category" value="{{$task['category']['parentCategory']['name']}}" required> --}}
            </div>
            <div class="form-group div_sub_category">
                <label>Sub Category</label>
                <select class="form-control kt-selectpicker" disabled id="sel_sub_category" name="sel_sub_category"required>
                    @if(!empty($sub_categories))
                    @foreach ($sub_categories as $sub_category)
                        <option value="{{ $sub_category->id }}" {{ ( $sub_category->id == $task['category']['id']) ? 'selected' : '' }}>{{ $sub_category->name }}</option>
                    @endforeach
                    @endif
                </select>
                {{-- <input type="text" class="form-control" placeholder="Enter Name" readonly disabled name="sub_catgeory" value="{{$task['category']['name']}}" required> --}}
            </div>
            <div class="form-group">
                <label>Select Event</label>
                <input type="text" class="form-control" name="tags" value="{{ $tagNames }}"/>
            </div>

            <div class="form-group">
				<label for="description">Description</label>
				<textarea name="description" class="form-control" id="description" rows="5">{{ $task->description }}</textarea>
			</div>
            <div class="form-group">
				<label>Recurring Type</label>
                <select class="form-control" name="recurring_type" id="recurring_type" disabled required>
                    <option value="" selected disabled>Select Recurring Type</option>
                    <option value="once" {{ ( $task->recurring_type == "once") ? 'selected' : '' }}>Once</option>
                    <option value="daily" {{ ( $task->recurring_type == "daily") ? 'selected' : '' }}>Daily</option>
                    <option value="weekly" {{ ( $task->recurring_type == "weekly") ? 'selected' : '' }}>Weekly</option>
                    <option value="monthly" {{ ( $task->recurring_type == "monthly") ? 'selected' : '' }}>Monthly</option>
                    <option value="yearly" {{ ( $task->recurring_type == "yearly") ? 'selected' : '' }}>Yearly</option>
                </select>
                {{-- <input type="text" class="form-control"  placeholder="Enter Name" name="recurring_type" value="{{$task->recurring_type}}" required> --}}
			</div>
			@if ($task->recurring_type == "weekly" && count($task->taskDay) > 0)
				<div class="form-group" id="div_week_days">
					<label>Weekdays</label>
					<select class="form-control kt-selectpicker" disabled style="display: none;" multiple name="week_days[]" id="week_days">
                        <option value="1" @foreach ($task->taskDay as $taskday) @if ($taskday->number== 1) selected @endif @endforeach>Monday</option>
                        <option value="2" @foreach ($task->taskDay as $taskday) @if ($taskday->number== 2) selected @endif @endforeach>Tuesday</option>
                        <option value="3" @foreach ($task->taskDay as $taskday) @if ($taskday->number== 3) selected @endif @endforeach>Wednesday</option>
                        <option value="4" @foreach ($task->taskDay as $taskday) @if ($taskday->number== 4) selected @endif @endforeach>Thursday</option>
                        <option value="5" @foreach ($task->taskDay as $taskday) @if ($taskday->number== 5) selected @endif @endforeach>Friday</option>
                        <option value="6" @foreach ($task->taskDay as $taskday) @if ($taskday->number== 6) selected @endif @endforeach>Saturday</option>
                        <option value="7" @foreach ($task->taskDay as $taskday) @if ($taskday->number== 7) selected @endif @endforeach>Sunday</option>
                    </select>
                    {{-- <input type="text" class="form-control" placeholder="Enter Name" readonly disabled name="name" value="{{ $task->taskDay->pluck('number')->implode(', ') }}" required> --}}
				</div>
			@elseif ($task->recurring_type == "monthly" && count($task->taskDay) > 0)
				<div class="form-group" id="div_month_days">
					<label>Days</label>
                    <select class="form-control kt-selectpicker" disabled multiple name="month_days[]" id="month_days">
                        <option value="1" @foreach ($task->taskDay as $taskday) @if ($taskday->number== 1) selected @endif @endforeach>1</option>
                        <option value="2" @foreach ($task->taskDay as $taskday) @if ($taskday->number== 2) selected @endif @endforeach>2</option>
                        <option value="3" @foreach ($task->taskDay as $taskday) @if ($taskday->number== 3) selected @endif @endforeach>3</option>
                        <option value="4" @foreach ($task->taskDay as $taskday) @if ($taskday->number== 4) selected @endif @endforeach>4</option>
                        <option value="5" @foreach ($task->taskDay as $taskday) @if ($taskday->number== 5) selected @endif @endforeach>5</option>
                        <option value="6" @foreach ($task->taskDay as $taskday) @if ($taskday->number== 6) selected @endif @endforeach>6</option>
                        <option value="7" @foreach ($task->taskDay as $taskday) @if ($taskday->number== 7) selected @endif @endforeach>7</option>
                        <option value="8" @foreach ($task->taskDay as $taskday) @if ($taskday->number== 8) selected @endif @endforeach>8</option>
                        <option value="9" @foreach ($task->taskDay as $taskday) @if ($taskday->number== 9) selected @endif @endforeach>9</option>
                        <option value="10" @foreach ($task->taskDay as $taskday) @if ($taskday->number== 10) selected @endif @endforeach>10</option>
                        <option value="11" @foreach ($task->taskDay as $taskday) @if ($taskday->number== 11) selected @endif @endforeach>11</option>
                        <option value="12" @foreach ($task->taskDay as $taskday) @if ($taskday->number== 12) selected @endif @endforeach>12</option>
                        <option value="13" @foreach ($task->taskDay as $taskday) @if ($taskday->number== 13) selected @endif @endforeach>13</option>
                        <option value="14" @foreach ($task->taskDay as $taskday) @if ($taskday->number== 14) selected @endif @endforeach>14</option>
                        <option value="15" @foreach ($task->taskDay as $taskday) @if ($taskday->number== 15) selected @endif @endforeach>15</option>
                        <option value="16" @foreach ($task->taskDay as $taskday) @if ($taskday->number== 16) selected @endif @endforeach>16</option>
                        <option value="17" @foreach ($task->taskDay as $taskday) @if ($taskday->number== 17) selected @endif @endforeach>17</option>
                        <option value="18" @foreach ($task->taskDay as $taskday) @if ($taskday->number== 18) selected @endif @endforeach>18</option>
                        <option value="19" @foreach ($task->taskDay as $taskday) @if ($taskday->number== 19) selected @endif @endforeach>19</option>
                        <option value="20" @foreach ($task->taskDay as $taskday) @if ($taskday->number== 20) selected @endif @endforeach>20</option>
                        <option value="21" @foreach ($task->taskDay as $taskday) @if ($taskday->number== 21) selected @endif @endforeach>21</option>
                        <option value="22" @foreach ($task->taskDay as $taskday) @if ($taskday->number== 22) selected @endif @endforeach>22</option>
                        <option value="23" @foreach ($task->taskDay as $taskday) @if ($taskday->number== 23) selected @endif @endforeach>23</option>
                        <option value="24" @foreach ($task->taskDay as $taskday) @if ($taskday->number== 24) selected @endif @endforeach>24</option>
                        <option value="25" @foreach ($task->taskDay as $taskday) @if ($taskday->number== 25) selected @endif @endforeach>25</option>
                        <option value="26" @foreach ($task->taskDay as $taskday) @if ($taskday->number== 26) selected @endif @endforeach>26</option>
                        <option value="27" @foreach ($task->taskDay as $taskday) @if ($taskday->number== 27) selected @endif @endforeach>27</option>
                        <option value="28" @foreach ($task->taskDay as $taskday) @if ($taskday->number== 28) selected @endif @endforeach>28</option>
                        <option value="29" @foreach ($task->taskDay as $taskday) @if ($taskday->number== 29) selected @endif @endforeach>29</option>
                        <option value="30" @foreach ($task->taskDay as $taskday) @if ($taskday->number== 30) selected @endif @endforeach>30</option>
                    </select>
					{{-- <input type="text" class="form-control" placeholder="Enter Name" name="name" value="{{ $task->taskDay->pluck('number')->implode(', ') }}" required> --}}
				</div>
			@endif
			</div>
            <div class="form-group">
				<label>Task Count</label>
				<input type="number" min="{{$task->task_count}}" class="form-control" aria-describedby="emailHelp" placeholder="Enter Task Count" id="task_count" name="task_count" value="{{$task->task_count}}" required>
                <input type="hidden" name="last_task_count_val" id="last_task_count_val" value="{{$task->task_count}}">
            </div>
            <div class="form-group">
				<label>Task Time</label>
                <div id="div_task_time">
                    @foreach ($task['taskTime'] as $tasks_time)
                        <div class="col-lg-6 col-md-9 col-sm-12" style="margin-top: 10px;">
                            <div class="input-group timepicker">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="la la-clock-o"></i>
                                    </span>
                                </div>
                                <input type="hidden" name="task_timepick_id[]" value="{{ $tasks_time['id'] }}">
                                <input class="form-control task_timepicker" id="task_timepicker" name="task_timepicker[]" placeholder="from" value="{{ $tasks_time['time'] }}" type="text"/>
                                <input class="form-control task_timepicker" id="task_timepicker" name="task_timepicker2[]" placeholder="to" value="{{ $tasks_time['time_to'] }}" type="text"/>
                                <input type="text" class="form-control" placeholder="Time Name" style="margin-left:5px" name="task_time_name[]" value="{{ $tasks_time['name'] }}">
                                <input type="number" min="1" class="form-control" placeholder="Time Quantity" style="margin-left:5px" name="task_time_quant[]" value="{{ $tasks_time['quantity'] }}">
                                <div class="div_task_time_action" style="margin: 10px 0 0 5px;">
                                    <a href="javascript:void(0)" class="enable_task_time" id="enable_{{ $tasks_time['id'] }}" data-time-id="{{ $tasks_time['id'] }}" @if($tasks_time['is_active'] == 1) style="display:none;" @endif>Enable</a>
                                    <a href="javascript:void(0)" class="disable_task_time" id="disable_{{ $tasks_time['id'] }}" data-time-id="{{ $tasks_time['id'] }}" @if($tasks_time['is_active'] == 0) style="display:none;" @endif>Disable</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
			</div>

			<input type="hidden" name="task_id" value="{{ $task->id }}">
		</div>
		<div class="kt-portlet__foot">
			<div class="kt-form__actions" style="margin-left: 10px;">
				<button type="submit" class="btn btn-primary">Update</button>
			</div>
		</div>
	</form>
</div>

@endsection

@section('page_js')
<script src="{{asset('admin_assets/js/bootstrap-select.js')}}" type="text/javascript"></script>
<script src="{{asset('admin_assets/js/bootstrap-timepicker.js')}}" type="text/javascript"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>
    var tags = <?php echo $tags ?>;
    $('input[name="tags"]').amsifySuggestags({
        type : 'amsify',
        suggestions: tags ,
    });

    $("#parent_category").on('change', function() {
        // console.log('working');
        var cat_id = $( "#parent_category option:selected" ).val();
        $.ajax({
            url:" {{ route('category-detail') }}",
            type: "POST",
            data: {
                "_token": "{{ csrf_token() }}",
                cat_id : cat_id
            },
            success: function(response){
                if (response.length === 0) {
                    $(".div_sub_category").hide();
                }
                else{
                    var teamPositionFilter = $('#sel_sub_category');
                    teamPositionFilter.selectpicker('deselectAll');
                    teamPositionFilter.find('option').remove();
                    teamPositionFilter.selectpicker('refresh');
                    $(".div_amaal_detail").show();
                    $.each( response, function( index, detail ){
                        $('#sel_sub_category').append('<option value="'+detail.id+'" name="sub_category">'+detail.name+'</option>');

                    });
                    teamPositionFilter.selectpicker('refresh');
                }

            }
        });
    });
    $('#recurring_type').on('change', function() {
                if(this.value=='weekly'){
                    $("#week_days").prop('required',true);
                    // $("#months").prop('required',false);
                    $("#month_days").prop('required',false);
                    // $("#div_months").hide();
                    $("#div_month_days").hide();
                    $("#div_week_days").show();
                }
                else if(this.value=='monthly'){
                    // $("#months").prop('required',true);
                    $("#months").prop('required',true);
                    $("#week_days").prop('required',false);
                    // $("#div_months").show();
                    $("#div_month_days").show();
                    $("#div_week_days").hide();
                }
                else{
                    // $("#months").prop('required',false);
                    $("#month_days").prop('required',false);
                    $("#week_days").prop('required',false);
                    // $("#div_months").hide();
                    $("#div_month_days").hide();
                    $("#div_week_days").hide();
                }
    });
    $('#task_count, #recurring_type').change(function(){
        var count = $("#task_count").val();
        var min_count = "<?php echo $task->task_count; ?>";
        var last_val = $("#last_task_count_val").val();
        if(count !== '' && count >= min_count){
            // $("#div_task_time").empty();
            html = '';
            var new_div_count = $(".div_new_task_time").length;
            var loop_start = count - min_count - new_div_count ;
            if((count - last_val) > 0)
            {
                for(var i=1;i<=loop_start;i++){
                    html+= '<div class="col-lg-6 col-md-9 col-sm-12 div_new_task_time" style="margin-top: 10px;">'+
                                '<div class="input-group timepicker">'+
                                    '<div class="input-group-prepend">'+
                                        '<span class="input-group-text">'+
                                            '<i class="la la-clock-o"></i>'+
                                        '</span>'+
                                    '</div>'+
                                    '<input class="form-control task_timepicker" id="task_timepicker" name="task_timepicker_new[]" required placeholder="Select time" type="text"/>'+
                                    '<input type="text" class="form-control" placeholder="Time Name" style="margin-left:5px" required name="task_time_name_new[]">'+
                                    '<input type="number" min="1" class="form-control" placeholder="Time Quantity" style="margin-left:5px" required name="task_time_quant_new[]">'+
                                '</div>'+
                            '</div>';
                }
                $("#div_task_time").append(html);
            }
            else if((count - last_val) < 0)
            {
                $(".div_new_task_time").slice(count - last_val).remove();
            }

            $('.task_timepicker, #kt_timepicker_2_validate, #kt_timepicker_3_validate').timepicker({
                defaultTime: '',
                minuteStep: 1,
                showSeconds: true,
                showMeridian: false,
                snapToStep: true
            });
        }
        last_val = $("#last_task_count_val").val(count);
    });
    $(".enable_task_time").on("click",function(){
        var task_time_id = $(this).attr('data-time-id');
        $.ajax({
            url:" {{ route('enable-task-time') }}",
            type: "POST",
            data: {
                "_token": "{{ csrf_token() }}",
                task_time_id : task_time_id,
            },
            success: function(response){
                $("#disable_"+task_time_id).show();
                $("#enable_"+task_time_id).hide();
                swal({
                    title: "Enabled",
                    text: "Task time is enabled successfully",
                    icon: "success",
                });
            }
        });
    });
    $(".disable_task_time").on("click",function(){
        var task_time_id = $(this).attr('data-time-id');
        $.ajax({
            url:" {{ route('disable-task-time') }}",
            type: "POST",
            data: {
                "_token": "{{ csrf_token() }}",
                task_time_id : task_time_id,
            },
            success: function(response){
                $("#enable_"+task_time_id).show();
                $("#disable_"+task_time_id).hide();
                swal({
                    title: "Disabled!",
                    text: "Task time is disabled successfully",
                    icon: "success",
                });
            }
        });

    });
</script>
@endsection
