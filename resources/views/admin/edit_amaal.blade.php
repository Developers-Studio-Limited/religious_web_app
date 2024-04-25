@extends('layouts.admin_app')
@section('content')
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
	<form class="kt-form" action="{{route('submit-amal')}}" enctype="multipart/form-data" method="POST">
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
				<input type="text" class="form-control" aria-describedby="emailHelp" placeholder="Enter Name" name="name" value="{{$amaal->name}}" required>
				
			</div>
			<div class="form-group">
				<label class="form-label">Icon</label>
				<div class="input-group">
					<div class="custom-file">
						<input type="file" name="image" class="custom-file-input" id="inputGroupFile01"
						aria-describedby="inputGroupFileAddon01">
						<label class="custom-file-label" for="inputGroupFile01">Choose Icon</label>
					</div>
				</div>
				@if (!is_null($amaal->icon))
					
					<img src="{{ asset("images/categories/".$amaal->icon) }}" alt="Icon" class="edit_amal_icon" style="width:150px;margin-top: 5px;">
				@endif
			</div>
			<div class="form-group">
				<label for="description">Description</label>
				<textarea name="description" class="form-control" id="description"  rows="5">{{ $amaal->description }}</textarea>
			</div>

			<input type="hidden" name="amaal_id" value="{{$amaal->id}}">
		</div>
		<div class="kt-portlet__foot">
			<div class="kt-form__actions">
				<button type="submit" class="btn btn-primary">Update</button>
			</div>
		</div>
	</form>
</div>

	@endsection