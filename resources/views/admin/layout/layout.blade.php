<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Admin | Dashboard</title>
  <link rel="shortcut icon" href="{{url('/')}}/frontend/images/favicon.png" type="image/x-icon">
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('resources/plugins/fontawesome-free/css/all.min.css')}}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- jQuery UI CSS - MUST be in head -->
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="{{ asset('resources/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{ asset('resources/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
  <link rel="stylesheet" href="{{ asset('resources/dist/css/adminlte.min.css')}}">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{ asset('resources/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="{{ asset('resources/plugins/daterangepicker/daterangepicker.css')}}">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.min.css">
  <!-- Select2 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  @yield('current_page_css')
</head>

<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">
    <input type="hidden" value="{{url('/')}}" id="baseUrl" name="baseUrl">
    <input type="hidden" value="{{ csrf_token() }}" id="csrfToken" name="csrfToken">

    <!-- Navbar Header -->
    @include('admin.layout.header')

    <!-- Main Sidebar Container -->
    @include('admin.layout.sidebar')

    @yield('content')

    <!-- Footer -->
    @include('admin.layout.footer')
  </div>
  <!-- ./wrapper -->

  <!-- SCRIPTS - CORRECT ORDER -->

  <!-- 1. Load jQuery FIRST -->
  <script src="{{ asset('frontend/assets/js/jquery-3.6.0.min.js') }}"></script>

  <!-- 2. Load jQuery UI SECOND (required for sortable and widget bridge) -->
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

  <!-- 3. NOW you can use jQuery UI bridge -->
  <script>
    $.widget.bridge('uibutton', $.ui.button)
  </script>

  <!-- 4. Other plugins -->
  <script src="{{ asset('resources/plugins/bs-custom-file-input/bs-custom-file-input.min.js')}}"></script>
  <script src="{{ asset('resources/plugins/jquery-knob/jquery.knob.min.js')}}"></script>
  <script src="{{ asset('resources/plugins/moment/moment.min.js')}}"></script>
  <script src="{{ asset('resources/plugins/daterangepicker/daterangepicker.js')}}"></script>
  <script src="{{ asset('resources/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  <script src="{{ asset('resources/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
  <script src="{{ asset('resources/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
  <script src="{{ asset('resources/dist/js/adminlte.js')}}"></script>
  <script src="{{ asset('resources/dist/js/pages/dashboard.js')}}"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.all.min.js"></script>
  <script src="https://malsup.github.io/jquery.form.js"></script>

  <!-- Select2 js -->
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  <!-- Custom admin scripts -->
  <script src="{{ asset('backend/admin/assets/js/admin.js') }}"></script>
  <script src="{{ asset('backend/admin/assets/js/get-locations.js') }}"></script>

  <!-- PAGE SPECIFIC SCRIPTS - MOVED HERE AFTER jQuery -->
  @stack('scripts')

  <!-- Active menu script -->
  <script type="text/javascript">
    /*** add active class and stay opened when selected ***/
    var url = window.location;

    // for sidebar menu entirely but not cover treeview
    $('ul.nav-sidebar a').filter(function() {
      if (this.href) {
        return this.href == url || url.href.indexOf(this.href) == 0;
      }
    }).addClass('active');

    // for the treeview
    $('ul.nav-treeview a').filter(function() {
      if (this.href) {
        return this.href == url || url.href.indexOf(this.href) == 0;
      }
    }).parentsUntil(".nav-sidebar > .nav-treeview").addClass('menu-open').prev('a').addClass('active');
  </script>

  @yield('current_page_js')
</body>
</html>