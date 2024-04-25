@extends('layouts.admin_app')
@section('content')
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
	<form class="kt-form" action="{{ route('create-task') }}" enctype="multipart/form-data" method="POST">
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
				<input type="text" class="form-control" aria-describedby="emailHelp" placeholder="Enter Name" name="name" value="" required>
			</div>
            <div class="form-group">
                <label>Category</label>
				<select class="form-control kt-selectpicker parent_category" id="parent_category" name="parent_category" style="max-height: 100px;" required>
                    <option value="" selected disabled>Select Category</option>
                    @if(!empty($category))
                    @foreach ($category as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                    @endif
                </select>
            </div>

            <div class="form-group div_sub_category" >
                <label>Sub Category</label>
                <select class="form-control sel_sub_category kt-selectpicker" name="sel_sub_category" id="sel_sub_category">

                </select>
            </div>
{{--            <div class="form-group" >--}}
{{--                <label>Select Event</label>--}}
{{--                <select class="form-control kt-selectpicker" style="display: none;" multiple name="tags[]" id="week_days">--}}
{{--                    @foreach($tags as $tag)--}}
{{--                    <option value="{{ $tag->id }}">{{ $tag->name }}</option>--}}
{{--                    @endforeach--}}
{{--                </select>--}}
{{--            </div>--}}

            <div class="form-group">
                <label>Select Event</label>
                <input type="text" class="form-control" name="tags" value=""/>
            </div>

			<div class="form-group">
				<label for="description">Description</label>
				<textarea name="description" class="form-control" id="description" rows="5" required pattern="\S+" title="This field is required"></textarea>
			</div>
            <div class="form-group">
				<label>Recurring Type</label>
				<select class="form-control" name="recurring_type" id="recurring_type" required>
                    <option value="" selected disabled>Select Recurring Type</option>
                    <option value="once">Once</option>
                    <option value="daily">Daily</option>
                    <option value="weekly">Weekly</option>
                    <option value="monthly">Monthly</option>
                    <option value="yearly">Yearly</option>
                </select>
			</div>
            <div class="form-group" id="div_week_days" style="display: none;">
				<label>Weekdays</label>
				<select class="form-control kt-selectpicker" style="display: none;" multiple name="week_days[]" id="week_days">
                    <option value="1">Monday</option>
                    <option value="2">Tuesday</option>
                    <option value="3">Wednesday</option>
                    <option value="4">Thursday</option>
                    <option value="5">Friday</option>
                    <option value="6">Saturday</option>
                    <option value="7">Sunday</option>
                </select>
			</div>
            {{-- <div class="form-group" style="display: none;" id="div_months">
				<label>Months</label>
				<select class="form-control kt-selectpicker" style="display: none;" multiple name="months[]" id="months">
                    <option value="01">January</option>
                    <option value="02">February</option>
                    <option value="03">March</option>
                    <option value="04">April</option>
                    <option value="05">May</option>
                    <option value="06">June</option>
                    <option value="07">July</option>
                    <option value="08">August</option>
                    <option value="09">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                </select>
			</div> --}}
            <div class="form-group" style="display: none;" id="div_month_days">
				<label>Days</label>
				<select class="form-control kt-selectpicker" multiple name="month_days[]" id="month_days">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                    <option value="19">19</option>
                    <option value="20">20</option>
                    <option value="21">21</option>
                    <option value="22">22</option>
                    <option value="23">23</option>
                    <option value="24">24</option>
                    <option value="25">25</option>
                    <option value="26">26</option>
                    <option value="27">27</option>
                    <option value="28">28</option>
                    <option value="29">29</option>
                    <option value="30">30</option>
                </select>
			</div>
            <div class="form-group">
				<label>Task Count</label>
				<input type="number" min="1" class="form-control" id="task_count" placeholder="Enter Task Count" name="task_count" value="" required>
			</div>
            <div class="form-group">
				<label>Task Detail</label>
                <div class="col-lg-4 col-md-9 col-sm-12" id="div_task_time" style="margin-top: 10px;">
                    <div class="input-group timepicker">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="la la-clock-o"></i>
                            </span>
                        </div>
                        <input class="form-control task_timepicker" style="width: 35px;" id="task_timepicker" name="task_timepicker[]" placeholder="Select time" value="" type="text" required/>
                        <input class="form-control task_timepicker" style="width: 35px;" id="task_timepicker2" name="task_timepicker2[]" placeholder="Select time" value="" type="text" required/>
                        <input type="text" class="form-control" placeholder="Name" style="margin-left:5px" required name="task_time_name[]" value="">
                        <input type="number" min="1" class="form-control" placeholder="Quantity" style="margin-left:5px" required name="task_time_quant[]" value="1">
                    </div>
                </div>
			</div>
		</div>
		<div class="kt-portlet__foot">
			<div class="kt-form__actions">
				<button type="submit" id="submit_task" class="btn btn-primary">Create</button>
			</div>
		</div>
	</form>
</div>

@endsection

@section('page_js')
<script src="{{asset('admin_assets/js/bootstrap-select.js')}}" type="text/javascript"></script>
<script src="{{asset('admin_assets/js/bootstrap-timepicker.js')}}" type="text/javascript"></script>



    <script type="text/javascript">

        var tags = <?php echo $tags ?>;
        $('input[name="tags"]').amsifySuggestags({
            type : 'amsify',
            suggestions: tags ,
        });
        $(document).ready(function(){
            $(".task_timepicker").keydown(function (event) {
                event.preventDefault();
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
               if(count !== ''){
                   $("#div_task_time").empty();
                   html = '';
                   for(var i=1; i<=count; i++){
                        html+=  '<div class="input-group timepicker" style="margin-top: 10px;">'+
                                    '<div class="input-group-prepend">'+
                                        '<span class="input-group-text">'+
                                            '<i class="la la-clock-o"></i>'+
                                        '</span>'+
                                    '</div>'+
                                    '<input class="form-control task_timepicker" id="task_timepicker" name="task_timepicker[]" placeholder="from" value="" type="text" required/>'+
                                    '<input class="form-control task_timepicker" style="width: 35px;" id="task_timepicker2" name="task_timepicker2[]" placeholder="to" value="" type="text" required/>'+
                                    '<input class="form-control" name="task_time_name[]" style="margin-left:5px" required placeholder="Name" value="" type="text" required/>'+
                                    '<input class="form-control" name="task_time_quant[]" style="margin-left:5px" required placeholder="Quantity" value="1" type="number" min="1" required/>'+
                                '</div>';
                   }
                   $("#div_task_time").append(html);
                   $('.task_timepicker, #kt_timepicker_2_validate, #kt_timepicker_3_validate').timepicker({
                        defaultTime: '',
                        minuteStep: 1,
                        showSeconds: true,
                        showMeridian: false,
                        snapToStep: true
                    });
               }
            });
        })

    </script>
@endsection
