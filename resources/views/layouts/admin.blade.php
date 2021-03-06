<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>FlowerBot</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="{{ asset('ti-icons/css/themify-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('base/vendor.bundle.base.css') }}">
  <!-- endinject -->
  <!-- plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
  <!-- endinject -->
  @laravelViewsStyles
  @notifyCss
</head>
<body>
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo me-5"><img src="" class="me-2" alt="FlowerBot" /></a>
        <a class="navbar-brand brand-logo-mini"><img src=""  alt="FlowerBot"/></a>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <ul class="navbar-nav navbar-nav-right" style="margin-right:10px">
          <li class="nav-item nav-profile dropdown" style="margin-right:10px">
            @if (auth()->user()->current_shop != null)
              <a class="nav-link dropdown-toggle" href="{{ route('shop.switch') }}" data-bs-toggle="dropdown" id="shopsDropdown" style="margin-right:10px">
                <div class="nav-username">{{ auth()->user()->current_shop->username }}</div>
              </a>
            @endif
          </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
                data-toggle="offcanvas">
          <span class="ti-view-list"></span>
        </button>
      </div>
    </nav>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_sidebar.html -->
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.users') }}">
              <span class="menu-title">????????????????????????</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.statistic.users') }}">
              <span class="menu-title">?????? ??????????????</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.statistic.deliveries') }}">
              <span class="menu-title">????????????????</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.statistic.cards') }}">
              <span class="menu-title">?????? ??????????</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.mailing.create') }}">
              <span class="menu-title">????????????????</span>
            </a>
          </li>
        </ul>
      </nav>
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          @yield('content')
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        <footer>

        </footer>
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  <!-- container-scroller -->
    @laravelViewsScripts
    <x:notify-messages />
    @notifyJs
    <style>
      .notify {
        z-index: 1000000;
        align-items: flex-end;
      }
    </style>
</body>

</html>

