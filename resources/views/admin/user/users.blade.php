@extends('layouts.admin_app')

@section('page_css')

<link href="{{asset("admin_assets/css/global/plugins.bundle.css")}}" rel="stylesheet" type="text/css" />

<!-- Hotjar Tracking Code for keenthemes.com -->
<script>
    (function(h,o,t,j,a,r){
                h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
                h._hjSettings={hjid:1070954,hjsv:6};
                a=o.getElementsByTagName('head')[0];
                r=o.createElement('script');r.async=1;
                r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
                a.appendChild(r);
            })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
</script>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-37564768-1"></script>
<script>
    window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());
          gtag('config', 'UA-37564768-1');
</script>
@endsection
@section('content')

<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    {{-- <div class="alert alert-light alert-elevate" role="alert">
        <div class="alert-icon"><i class="flaticon-warning kt-font-brand"></i></div>
        <div class="alert-text">
            The default page control presented by DataTables (forward and backward buttons with up to 7 page numbers
            in-between) is fine for most situations.
        </div>
    </div> --}}

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create @if ($title == 'Providers')
                        Provider @else Users
                    @endif</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('create')}}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="forFirstName">First Name</label>
                                    <input type="text" name="first_name"
                                        class="form-control @error('first_name') is-invalid @enderror"
                                        placeholder="First Name" required>
                                    @error('first_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="forLastName">Last Name</label>
                                    <input type="text" name="last_name"
                                        class="form-control @error('last_name') is-invalid @enderror"
                                        placeholder="Last Name" required>
                                    @error('last_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="forEmailName">Email</label>
                                    <input type="email" name="email"
                                        class="form-control @error('email') is-invalid @enderror" placeholder="Email">
                                    @error('email')
                                    <span class="invalid-feedback" role="alert" required>
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="forDOBName">DOB</label>
                                    <input type="date" name="birth_date"
                                        class="form-control @error('birth_date') is-invalid @enderror"
                                        placeholder="Date of Birth">
                                    @error('birth_date')
                                    <span class="invalid-feedback" role="alert" required>
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="forDOBName">Gender</label>
                                    <select name="gender" class="form-control" required>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                    @error('birth_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="forPhoneName">Phone</label>
                                    <input type="number" name="phone"
                                        class="form-control @error('phone') is-invalid @enderror" placeholder="Phone" required>
                                    @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="custom-file">
                                    <input type="file" name="user_image"
                                        class="custom-file-input @error('user_image') is-invalid @enderror"
                                        id="validatedCustomFile" required>
                                    <label class="custom-file-label" for="validatedCustomFile">Choose file...</label>
                                    {{-- <div class="invalid-feedback">Example invalid custom file feedback</div> --}}
                                    @error('user_image')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <br>
                                <div class="form-group">
                                    <label for="forPasswordName">Password</label>
                                    <input type="password" name="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        placeholder="Password" required>
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        @if($title == 'Providers')
                        <input type="hidden" name="type" value="Provider">
                        @endif

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create @if ($title == 'Providers')
                            Provider @else User
                        @endif</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="fa fa-user"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    {{ $title }} Table
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">

                        &nbsp;
                        <a href="#" class="btn btn-brand btn-elevate btn-icon-sm" data-toggle="modal"
                            data-target="#exampleModal">
                            <i class="la la-plus"></i>
                            New Record
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{session('success')}}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
        @endif
        <div class="kt-portlet__body">
            <!--begin: Datatable -->
            <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
                <thead>
                    <tr>
                        <th>Record ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>DOB</th>
                        <th>Phone</th>
                        <th>Gender</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $key => $user)
                    <tr>
                        <td>{{$key + 1}}</td>
                        <td>{{$user->first_name}}</td>
                        <td>{{$user->last_name}}</td>
                        <td>{{$user->email}}</td>
                        <td>{{$user->birth_date}}</td>
                        <td>{{$user->phone}}</td>
                        <td>{{$user->gender}}</td>
                        <td><a href="{{ route('profile', $user->id) }}">View</a> | <a
                                href="{{ route('delete-user', $user->id) }}">Delete</a></td>
                    </tr>
                    @endforeach
                </tbody>

            </table>
            <!--end: Datatable -->
        </div>
    </div>
</div>

@endsection

@section('page_js')
<script type="text/javascript">
    @if (count($errors) > 0)
        $('#exampleModal').modal('show');
    @endif
    </script>
<script src="{{asset('admin_assets/js/datatables/datatables.bundle.js')}}" type="text/javascript"></script>
<script src="{{asset('admin_assets/js/datatables/paginations.js')}}" type="text/javascript"></script>
@endsection