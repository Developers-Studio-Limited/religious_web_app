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
                Quotes
            </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-wrapper">
    <div class="kt-portlet__head-actions">
        <div class="dropdown dropdown-inline">
            
        </div>
        &nbsp;
        <a href="{{route('add-quote')}}" class="btn btn-brand btn-elevate btn-icon-sm new-amaal">
            <i class="la la-plus"></i>
            New Quote
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
                    <th>Text</th>
                    <th>Text Color</th>
                    <th>Background Color</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @if(count($quotes)>0)
                @php
                    $count=0;
                @endphp

                    @foreach($quotes as $quote)
                        @php $count++; @endphp
                        <tr>
                            <td>{{$count}}</td>
                             <td data-container="body" data-toggle="kt-tooltip" data-placement="bottom" title="{{$quote->text}}">{{ Str::limit($quote->text, 35) }}</td>
                            <td><span class="spn-color" style="background-color:{{$quote->color}}"></span></td>
                            <td><span class="spn-color" style="background-color:{{$quote->background}}"></span></td>
                            <td>{{date('Y-m-d', strtotime($quote->date_from))}} - {{date('Y-m-d', strtotime($quote->date_to))}}</td>
                            <td>
                                <a href="{{route('edit-quote', $quote->id)}}">Edit</a> | 
                                <a href="{{route('delete-quote', $quote->id)}}">Delete</a>
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
        <!--end: Datatable -->
    </div>
</div>  
    <!-- Modal POPUP -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <form action="{{route('add-amaal')}}" method="POST" name="add-amaal" autocomplete="off">
            @csrf
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Quote</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="row" style="padding: 5px;">
                    <div class="form-group" style="width: 100%">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control modal_name" required>
                    </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
              </div>
            </div>
        </form>
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
