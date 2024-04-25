@extends('layouts.admin_app')
@section('content')
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
	<form class="kt-form" action="{{route('update-sub-category')}}" enctype="multipart/form-data" method="POST">
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
				<input type="text" class="form-control" aria-describedby="emailHelp" placeholder="Enter Name" name="name" value="{{$sub_category->name}}" required>
			</div>
			<div class="form-group">
                <label>Parent Category</label>
                <select name="parent_category" id="parent_category" class="form-control">
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ ( $category->id == $sub_category->parent_id) ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
				<label>Naiki</label>
				<input type="number" class="form-control" placeholder="Enter Naiki" name="naiki" value="{{$sub_category->naiki}}" required>
				
			</div>
			<div class="form-group">
				<label for="description">Description</label>
				<textarea name="description" class="form-control" id="description"  rows="5">{{ $sub_category->description }}</textarea>
			</div>

			<input type="hidden" name="sub_category_id" value="{{$sub_category->id}}">
		</div>
		<div class="kt-portlet__foot">
			<div class="kt-form__actions">
				<button type="submit" class="btn btn-primary">Update</button>
			</div>
		</div>
	</form>
</div>

	@endsection