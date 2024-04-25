<!DOCTYPE html>
<!--
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 4 & Angular 8
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
Renew Support: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<html lang="en" >
    <!-- begin::Head -->
    
<!-- Mirrored from keenthemes.com/metronic/preview/demo1/custom/pages/user/login-2.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 02 Oct 2019 15:20:05 GMT -->
<!-- Added by HTTrack --><meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->
<head>
        <meta charset="utf-8"/>

        <title>{{ $title }}</title>
        <meta name="description" content="Login page example">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!--begin::Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Roboto:300,400,500,600,700">        <!--end::Fonts -->

        
		<!--begin::Page Custom Styles(used by this page) -->
		<link href="{{asset("admin_assets/css/login/login.css")}}" rel="stylesheet" type="text/css" />
		<!--end::Page Custom Styles -->
        
        <!--begin::Global Theme Styles(used by all pages) -->
		<link href="{{asset("admin_assets/css/plugins/plugins.bundle.css")}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('admin_assets/css/style.bundle.css')}}" rel="stylesheet" type="text/css" />
		<!--end::Global Theme Styles -->

        <!--begin::Layout Skins(used by all pages) -->
        
		<link href="{{asset('admin_assets/css/skins/header/base/light.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('admin_assets/css/skins/header/menu/light.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('admin_assets/css/skins/brand/dark.css')}}" rel="stylesheet" type="text/css" />
		<link href="{{asset('admin_assets/css/skins/aside/dark.css')}}" rel="stylesheet" type="text/css" />        <!--end::Layout Skins -->

        <link rel="shortcut icon" href="https://keenthemes.com/metronic/themes/metronic/theme/default/demo1/dist/assets/media/logos/favicon.ico" />

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
</script>    </head>
    <!-- end::Head -->

    <!-- begin::Body -->
    <body  class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--fixed kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading"  >

       
    	<!-- begin:: Page -->
	<div class="kt-grid kt-grid--ver kt-grid--root">
		<div class="kt-grid kt-grid--hor kt-grid--root kt-login kt-login--v2 kt-login--signin" id="kt_login">
	<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" style="background-image: url('{{ asset('admin_assets/img/bg/bg-1.jpg')}}');">
		<div class="kt-grid__item kt-grid__item--fluid kt-login__wrapper">
			<div class="kt-login__container">
				<div class="kt-login__logo">
					<a href="#">
						<img src="{{asset('admin_assets/img/logos/logo-mini-2-md.png')}}">
					</a>
				</div>
				<div class="kt-login__signin">
					<div class="kt-login__head">
						<h3 class="kt-login__title">Forget Password</h3>
					</div>
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{session('error')}}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
                    @endif
                    @if(isset($expired))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{$expired}}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
                    @endif
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{session('success')}}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
                    @endif
					<form class="kt-form" method="POST" action="{{ route('submit-forget-password') }}">
						@csrf
						<input type="hidden" name="token" value="{{ $token }}"/>
                        <div class="input-group">
							<input class="form-control @error('password') is-invalid @enderror" type="password" placeholder="Password" name="password" required autocomplete="password">
							@error('password')
								<span class="invalid-feedback" role="alert">
									<strong>{{ $message }}</strong>
								</span>
							@enderror
						</div>
						<div class="input-group">
							<input class="form-control @error('confirm_password') is-invalid @enderror" type="password" placeholder="Confirm Password" name="confirm_password" required autocomplete="confirm-password">
							@error('confirm_password')
								<span class="invalid-feedback" role="alert">
									<strong>{{ $message }}</strong>
								</span>
							@enderror
						</div>
						<div class="kt-login__actions">
							<button type="submit" class="btn btn-pill kt-login__btn-primary">Submit</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
	</div>
	
<!-- end:: Page -->


        <!-- begin::Global Config(global config for global JS sciprts) -->
        <script>
            var KTAppOptions = {
    "colors": {
        "state": {
            "brand": "#5d78ff",
            "dark": "#282a3c",
            "light": "#ffffff",
            "primary": "#5867dd",
            "success": "#34bfa3",
            "info": "#36a3f7",
            "warning": "#ffb822",
            "danger": "#fd3995"
        },
        "base": {
            "label": [
                "#c5cbe3",
                "#a1a8c3",
                "#3d4465",
                "#3e4466"
            ],
            "shape": [
                "#f0f3ff",
                "#d9dffa",
                "#afb4d4",
                "#646c9a"
            ]
        }
    }
};
        </script>
        <!-- end::Global Config -->

    	<!--begin::Global Theme Bundle(used by all pages) -->
		<script src="{{asset('admin_assets/js/plugins.bundle.js')}}" type="text/javascript"></script>
		<script src="{{asset('admin_assets/js/scripts.bundle.js')}}" type="text/javascript"></script>
				<!--end::Global Theme Bundle -->

        
		<!--begin::Page Scripts(used by this page) -->
		<script src="{{asset('admin_assets/js/login-general.js')}}" type="text/javascript"></script>
		<!--end::Page Scripts -->
            </body>
    <!-- end::Body -->

<!-- Mirrored from keenthemes.com/metronic/preview/demo1/custom/pages/user/login-2.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 02 Oct 2019 15:20:08 GMT -->
</html>