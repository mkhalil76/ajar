<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Ajar</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-notify/0.2.0/css/bootstrap-notify.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-notify/0.2.0/css/styles/alert-bangtidy.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-notify/0.2.0/css/styles/alert-blackgloss.min.css">
  <link rel="stylesheet" href="{{ asset('node_modules/mdi/css/materialdesignicons.min.css')}}">
  <link rel="stylesheet" href="/../../node_modules/simple-line-icons/css/simple-line-icons.css">
  <link rel="stylesheet" href="/../../node_modules/flag-icon-css/css/flag-icon.min.css">
  <link rel="stylesheet" href="/../../node_modules/perfect-scrollbar/dist/css/perfect-scrollbar.min.css">
  <!-- endinject -->
  <!-- plugin css for this page -->
  <link rel="stylesheet" href="../../node_modules/font-awesome/css/font-awesome.min.css" />
  <link rel="stylesheet" href="../../node_modules/jquery-bar-rating/dist/themes/fontawesome-stars.css">
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="../../css/style.css">
  <link rel="stylesheet" href="../../node_modules/jquery-toast-plugin/dist/jquery.toast.min.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="../../../../images/favicon.png" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/earlyaccess/droidarabicnaskh.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-notify/0.2.0/js/bootstrap-notify.min.js"></script>
  <style>
    .notifyjs-corner{
      left:0px;
      top:18%;
    }
    </style>
  @yield('ExtraCss')
</head>
<body class="rtl" style="font-family:'Droid Arabic Naskh', serif; line-height:60px;">
  <div class="container-scroller">
    <!-- partial:../../partials/_navbar.html -->
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-top justify-content-center">

        <a class="navbar-brand brand-logo-mini" href="../../index.html"><img src="../../images/logo-mini.svg" alt="logo"/></a>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center">


      </div>
    </nav>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <div class="row row-offcanvas row-offcanvas-right">
        <!-- partial:../../partials/_settings-panel.html -->
        <div class="theme-setting-wrapper">

          <div id="theme-settings" class="settings-panel">
            <i class="settings-close mdi mdi-close"></i>
            <p class="settings-heading">SIDEBAR SKINS</p>
            <div class="sidebar-bg-options selected" id="sidebar-light-theme"><div class="img-ss rounded-circle bg-light border mr-3"></div>Light</div>
            <div class="sidebar-bg-options" id="sidebar-dark-theme"><div class="img-ss rounded-circle bg-dark border mr-3"></div>Dark</div>
            <p class="settings-heading mt-2">HEADER SKINS</p>
            <div class="color-tiles mx-0 px-4">
              <div class="tiles primary"></div>
              <div class="tiles success"></div>
              <div class="tiles warning"></div>
              <div class="tiles danger"></div>
              <div class="tiles pink"></div>
              <div class="tiles info"></div>
              <div class="tiles dark"></div>
              <div class="tiles default"></div>
            </div>
          </div>
        </div>
        <div id="right-sidebar" class="settings-panel">
          <i class="settings-close mdi mdi-close"></i>
          <ul class="nav nav-tabs" id="setting-panel" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="todo-tab" data-toggle="tab" href="#todo-section" role="tab" aria-controls="todo-section" aria-expanded="true">TO DO LIST</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="chats-tab" data-toggle="tab" href="#chats-section" role="tab" aria-controls="chats-section">CHATS</a>
            </li>
          </ul>
          <div class="tab-content" id="setting-content">
            <div class="tab-pane fade show active scroll-wrapper" id="todo-section" role="tabpanel" aria-labelledby="todo-section">
              <div class="add-items d-flex px-3 mb-0">
                <form class="form w-100">
                  <div class="form-group d-flex">
                    <input type="text" class="form-control todo-list-input" placeholder="Add To-do">
                    <button type="submit" class="add btn btn-primary todo-list-add-btn" id="add-task">Add</button>
                  </div>
                </form>
              </div>
              <div class="list-wrapper px-3">
                <ul class="d-flex flex-column-reverse todo-list">
                  <li>
                    <div class="form-check">
                      <label class="form-check-label">
                        <input class="checkbox" type="checkbox">
                        Team review meeting at 3.00 PM
                      </label>
                    </div>
                    <i class="remove mdi mdi-close-circle-outline"></i>
                  </li>
                  <li>
                    <div class="form-check">
                      <label class="form-check-label">
                        <input class="checkbox" type="checkbox">
                        Prepare for presentation
                      </label>
                    </div>
                    <i class="remove mdi mdi-close-circle-outline"></i>
                  </li>
                  <li>
                    <div class="form-check">
                      <label class="form-check-label">
                        <input class="checkbox" type="checkbox">
                        Resolve all the low priority tickets due today
                      </label>
                    </div>
                    <i class="remove mdi mdi-close-circle-outline"></i>
                  </li>
                  <li class="completed">
                    <div class="form-check">
                      <label class="form-check-label">
                        <input class="checkbox" type="checkbox" checked>
                        Schedule meeting for next week
                      </label>
                    </div>
                    <i class="remove mdi mdi-close-circle-outline"></i>
                  </li>
                  <li class="completed">
                    <div class="form-check">
                      <label class="form-check-label">
                        <input class="checkbox" type="checkbox" checked>
                        Project review
                      </label>
                    </div>
                    <i class="remove mdi mdi-close-circle-outline"></i>
                  </li>
                </ul>
              </div>
              <div class="events py-4 border-bottom px-3">
                <div class="wrapper d-flex mb-2">
                  <i class="mdi mdi-circle-outline text-primary mr-2"></i>
                  <span>Feb 11 2018</span>
                </div>
                <p class="mb-0 font-weight-thin text-gray">Creating component page</p>
                <p class="text-gray mb-0">build a js based app</p>
              </div>
              <div class="events pt-4 px-3">
                <div class="wrapper d-flex mb-2">
                  <i class="mdi mdi-circle-outline text-primary mr-2"></i>
                  <span>Feb 7 2018</span>
                </div>
                <p class="mb-0 font-weight-thin text-gray">Meeting with Alisa</p>
                <p class="text-gray mb-0 ">Call Sarah Graves</p>
              </div>
            </div>
            <!-- To do section tab ends -->
            <div class="tab-pane fade" id="chats-section" role="tabpanel" aria-labelledby="chats-section">
              <div class="d-flex align-items-center justify-content-between border-bottom">
                <p class="settings-heading border-top-0 mb-3 pl-3 pt-0 border-bottom-0 pb-0">Friends</p>
                <small class="settings-heading border-top-0 mb-3 pt-0 border-bottom-0 pb-0 pr-3 font-weight-normal">See All</small>
              </div>
              <ul class="chat-list">
                <li class="list active">
                  <div class="profile"><img src="http://via.placeholder.com/100x100/f4f4f4/000000" alt="image"><span class="online"></span></div>
                  <div class="info">
                    <p>Thomas Douglas</p>
                    <p>Available</p>
                  </div>
                  <small class="text-muted my-auto">19 min</small>
                </li>
                <li class="list">
                  <div class="profile"><img src="http://via.placeholder.com/100x100/f4f4f4/000000" alt="image"><span class="offline"></span></div>
                  <div class="info">
                    <div class="wrapper d-flex">
                      <p>Catherine</p>
                    </div>
                    <p>Away</p>
                  </div>
                  <div class="badge badge-success badge-pill my-auto mx-2">4</div>
                  <small class="text-muted my-auto">23 min</small>
                </li>
                <li class="list">
                  <div class="profile"><img src="http://via.placeholder.com/100x100/f4f4f4/000000" alt="image"><span class="online"></span></div>
                  <div class="info">
                    <p>Daniel Russell</p>
                    <p>Available</p>
                  </div>
                  <small class="text-muted my-auto">14 min</small>
                </li>
                <li class="list">
                  <div class="profile"><img src="http://via.placeholder.com/100x100/f4f4f4/000000" alt="image"><span class="offline"></span></div>
                  <div class="info">
                    <p>James Richardson</p>
                    <p>Away</p>
                  </div>
                  <small class="text-muted my-auto">2 min</small>
                </li>
                <li class="list">
                  <div class="profile"><img src="http://via.placeholder.com/100x100/f4f4f4/000000" alt="image"><span class="online"></span></div>
                  <div class="info">
                    <p>Madeline Kennedy</p>
                    <p>Available</p>
                  </div>
                  <small class="text-muted my-auto">5 min</small>
                </li>
                <li class="list">
                  <div class="profile"><img src="http://via.placeholder.com/100x100/f4f4f4/000000" alt="image"><span class="online"></span></div>
                  <div class="info">
                    <p>Sarah Graves</p>
                    <p>Available</p>
                  </div>
                  <small class="text-muted my-auto">47 min</small>
                </li>
              </ul>
            </div>
            <!-- chat tab ends -->
          </div>
        </div>
        <!-- partial -->
        <!-- partial:../../partials/_sidebar.html -->
        <?php
          $provider = Auth::user();
        ?>
        @if($provider->super_admin == 0)
        @php
         $documents = $provider->documents;
        @endphp
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
          <ul class="nav">
            <li class="nav-item nav-profile">
              <div class="nav-link">
                <div class="profile-image">
                  @if(!empty($documents->logo))
                  <img src="{{$documents->logo}}" alt="image"/>
                      <span class="online-status online"></span> <!--change class online to offline or busy as needed-->
                  </div>
                  @endif
                <div class="profile-name">
                  <p class="name">
                    {{ Auth::user()->office_name }}
                  </p>
                  <p class="designation">
                    {{ Auth::user()->owner_name }}
                  </p>
                </div>
              </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/') }}">
                    <i class="icon-check menu-icon"></i>
                    <span class="menu-title"> الرئيسية</span>
                  </a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="{{ url('branchs/') }}">
                <i class="icon-check menu-icon"></i>
                <span class="menu-title">ادارة الفروع</span>
              </a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="{{ url('/cars/') }}">
                <i class="icon-check menu-icon"></i>
                <span class="menu-title"> ادارة السيارات</span>
              </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/reservations/delivery') }}">
                    <i class="icon-check menu-icon"></i>
                    <span class="menu-title">تسليم سيارة</span>
                  </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/reservations/receipt') }}">
                    <i class="icon-check menu-icon"></i>
                    <span class="menu-title">إستلام سيارة</span>
                  </a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="{{ url('/provider/details') }}">
                <i class="icon-check menu-icon"></i>
                <span class="menu-title">بيانات الشركة</span>
              </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/logout') }}">
                    <i class="icon-check menu-icon"></i>
                    <span class="menu-title">تسجيل خروج</span>
                  </a>
                </li>
          </ul>
        </nav>
        @else
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
          <ul class="nav">
            <li class="nav-item nav-profile">
              <div class="nav-link">
                <div class="profile-image">
                  <img src="https://images.askmen.com/1080x540/2016/01/25-021526-facebook_profile_picture_affects_chances_of_getting_hired.jpg" alt="image"/>
                      <span class="online-status online"></span> <!--change class online to offline or busy as needed-->
                  </div>
                <div class="profile-name">
                  <p class="name">
                    {{ Auth::user()->owner_name }}
                  </p>
                </div>
              </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#page-layouts" aria-expanded="false" aria-controls="page-layouts">
                  <i class="icon-check menu-icon"></i>
                  <span class="menu-title">إدارة الاماكن</span>
                </a>
                <div class="collapse" id="page-layouts">
                  <ul class="nav flex-column sub-menu">
                    <li class="nav-item d-none d-lg-block"> <a class="nav-link" href="{{ url('admin/cities')  }}">المدن</a></li>
                    <li class="nav-item"> <a class="nav-link" href="{{ url('admin/locations') }}">المواقع</a></li>
                  </ul>
                </div>
              </li>
            <li class="nav-item">
            <a class="nav-link" href="{{ url('admin/users-list') }}">
                <i class="icon-check menu-icon"></i>
                <span class="menu-title"> الزبائن </span>
              </a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="{{ url('admin/providers') }}">
                <i class="icon-check menu-icon"></i>
                <span class="menu-title"> شركات التأجير </span>
              </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/reservations/delivery') }}">
                    <i class="icon-check menu-icon"></i>
                    <span class="menu-title"> إدارة السيارات</span>
                  </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/reservations/receipt') }}">
                    <i class="icon-check menu-icon"></i>
                    <span class="menu-title"> </span>
                  </a>
            </li>
          </ul>
        </nav>
        @endif
        <!-- partial -->
        <div class='notifications bottom-right'></div>
        <div class="content-wrapper">
        @include('layouts.messages')
        @yield('content')
        <!-- content-wrapper ends -->
        <!-- partial:../../partials/_footer.html -->
                <!-- partial -->
      </div>
      @include('layouts.footer')

      <!-- row-offcanvas ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->

  <!-- plugins:js -->
  <script src="../../node_modules/jquery/dist/jquery.min.js"></script>
  <script src="../../node_modules/popper.js/dist/umd/popper.min.js"></script>
  <script src="../../node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
  <script src="../../node_modules/perfect-scrollbar/dist/js/perfect-scrollbar.jquery.min.js"></script>
  <!-- endinject -->
  <!-- Plugin js for this page-->
  <script src="../../node_modules/jquery-bar-rating/dist/jquery.barrating.min.js"></script>
  <script src="../../node_modules/chart.js/dist/Chart.min.js"></script>
  <script src="../../node_modules/raphael/raphael.min.js"></script>
  <script src="../../node_modules/morris.js/morris.min.js"></script>
  <script src="../../node_modules/jquery-sparkline/jquery.sparkline.min.js"></script>
  <script src="../../node_modules/jquery-toast-plugin/dist/jquery.toast.min.js"></script>
  <!-- End plugin js for this page-->
  <!-- inject:js -->
  <script src="../../js/off-canvas.js"></script>
  <script src="../../js/hoverable-collapse.js"></script>
  <script src="../../js/misc.js"></script>
  <script src="../../js/settings.js"></script>
  <script src="../../js/todolist.js"></script>
  <script src="../../js/toastDemo.js"></script>
  <script src="../../js/notify.min.js"></script>
  <script src="../../js/modal-demo.js"></script>

@if($provider instanceof \App\Models\Provider)
  <script src="https://js.pusher.com/4.4/pusher.min.js"></script>
  <script>
    $( document ).ready(function() {
      $('.notifyjs-corner').css({
          'top':'10%',
          'left':'0px'
        });
      $.notify('test', {
        position:'top left',
        className:'success',
        autoHide:true,
        autoHideDelay: 1,
      });

      $('.notifyjs-corner').click(function (){
        window.location.href = "{{URL::to('reservations/')}}"
      });
    });
    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = false;

    var pusher = new Pusher('30aeee72864c3e56290a', {
      cluster: 'ap2',
      forceTLS: true
    });

    var channel = pusher.subscribe('new-notification'+{{Auth::user()->id}});
    channel.bind('App\\Events\\SendNotification', function(data) {
      $('.notifyjs-corner').css({
          'top':'10%',
          'left':'0px'
        });
      $.notify(JSON.stringify(data.message).replace(' " ', ' '), {
        position:'top left',
        className:'success',
        autoHide:false,
      });
      //console.log(data);
      console.log(JSON.stringify(data));
      //alert(JSON.stringify(data));
    });
 </script>
 @endif
  <!-- endinject -->
  @yield('ExtraJs')

</body>

</html>
