@extends('layouts.admin_app')
@section('content')
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
	<form class="kt-form" action="{{ route('update-community') }}" enctype="multipart/form-data" method="POST">
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
				<input type="text" class="form-control" placeholder="Enter Name" name="name" value="{{ $community->name }}" required>
			</div>
			<div class="form-group">
				<label for="description">Description</label>
				<textarea name="description" class="form-control" id="description"  rows="5" required>{{ $community->description }}</textarea>
			</div>
        </div>
            <input type="hidden" name="community_id" value="{{ $community->id }}">
		<div class="kt-portlet__foot">
			<div class="kt-form__actions">
				<button type="submit" class="btn btn-primary">Update</button>
			</div>
		</div>
	</form>
</div>

@endsection

@section('page_js')
<script src="{{asset('admin_assets/js/bootstrap-select.js')}}" type="text/javascript"></script>    
        
    </script>
@endsection