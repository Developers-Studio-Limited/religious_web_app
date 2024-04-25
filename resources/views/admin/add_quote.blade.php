@extends('layouts.admin_app')
@section('content')
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
	<form class="kt-form" action="{{ route('submit-daily-quote') }}" enctype="multipart/form-data" method="POST">
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
				<label for="description">Text</label>
				<textarea name="text" class="form-control" id="description"  rows="5" required></textarea>
			</div>
			<div class="form-group" style="display: none">
				<label for="description">Text Color</label>
				<div class="form-group" style="margin-left: 25px;">
                    <label class="color-picker">
                        <div class="color">
                            <input type="color" disabled name="txt_color" required class="color_picker" id="txt_color" value="#ffffff"/>
                        </div>
                    </label>
                </div>
			</div>
			<div class="form-group" style="display: none">
				<label for="description">Background Color</label>
				<div class="form-group" style="margin-left: 25px;">
                    <label class="color-picker">
                        <div class="color">
                            <input type="color" disabled name="back_color" required class="color_picker" id="back_color" value="#000000"/>
                        </div>
                    </label>
                </div>
			</div>
			<div class="form-group">
				<label for="description">Quote Image</label>
				<div class="form-group quote_div" id="quote_div" style="background-color: #000000; height: 200px;width: 100%;text-align: center;">
                    
                </div>
			</div>
			<input type="hidden" name="screenshot_img" id="screenshot_img">
			<!-- <div class="form-group">
				<label for="description">Appen Image</label>
				<div class="form-group append_div" id="append_div">
                    
                </div>
			</div> -->
			<div class="form-group">
				<label for="description">Date From</label>
				<input type="date" name="date_from" class="form-control" value="{{old(date('mm/dd/yy', strtotime('date_from')))}}" id="date_from" required/>
			</div>

			<div class="form-group">
				<label for="date_to">Date To</label>
				<input type="date" name="date_to" class="form-control" value="" id="date_to" required/>
				@if($errors->has('date_to'))
				    <div class="error" style="color: red">{{ $errors->first('date_to') }}</div>
				@endif
			</div>
        </div>
            
		<div class="kt-portlet__foot">
			<div class="kt-form__actions">
				<button type="submit" class="btn btn-primary">Create</button>
			</div>
		</div>
	</form>
</div>

@endsection

@section('page_js')
<script src="{{asset('admin_assets/js/bootstrap-select.js')}}" type="text/javascript"></script>    
<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.0.0-rc.5/dist/html2canvas.min.js"></script> 
<script type="text/javascript">
	$("#description, #txt_color, #back_color").on("change",function(){
		$(".quote_para").remove();
		var text = $("#description").val();
		var txt_color = $("#txt_color").val();
		var back_color = $("#back_color").val();
		if(text !=="")
		{
			$("<p style='color:"+txt_color+";display:flex; height:200px; align-items: center; justify-content: center' class='quote_para'>"+text+"</p>").appendTo(".quote_div");
			$(".quote_div").css('background-color',back_color);
			var screenshot_div =  document.getElementById('quote_div');
			 html2canvas(screenshot_div).then(
	                function (canvas) {
	                    var base64 = $("#screenshot_img").val(canvas.toDataURL('image/png'));
	                    console.log(canvas.toDataURL('image/png'));
	                })
		}
		
	})
</script>
@endsection