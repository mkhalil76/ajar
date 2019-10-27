<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Ajar</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="../../node_modules/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="../../node_modules/simple-line-icons/css/simple-line-icons.css">
  <link rel="stylesheet" href="../../node_modules/flag-icon-css/css/flag-icon.min.css">
  <link rel="stylesheet" href="../../node_modules/perfect-scrollbar/dist/css/perfect-scrollbar.min.css">
  <!-- endinject -->
  <!-- plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="../../css/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="../../images/favicon.png" />
  <style type="text/css">
    label{
      padding:5px;
    }
  </style>
</head>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper">
      <div class="row">
        <div class="content-wrapper full-page-wrapper d-flex align-items-center auth ">
          <div class="row w-100">
            <div class="col-lg-4 mx-auto">
              <div class="auth-form-dark text-right p-5">
                <h2 style="text-align: center">تسجيل الدخول </h2>
                <form class="pt-5"  autocomplete="off" method="POST" action="{{ url('login') }}">
                  @csrf
                  <div class="form-group">
                    <label for="exampleInputEmail1">الرقم التجاري </label>
                    <input type="text" name="commercial_no" class="form-control{{ $errors->has('commercial_no') ? ' is-invalid' : '' }}" required autofocus>
                    @if ($errors->has('commercial_no'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('commercial_no') }}</strong>
                        </span>
                    @endif
                    <i class="mdi mdi-account"></i>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">رمز الدخول </label>
                    <input type="password" name="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" required autofocus>
                    @if ($errors->has('password'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                    <i class="mdi mdi-eye"></i>
                  </div>
                  <div class="mt-5">
                    <input type="submit" value="تسجيل دخول" class="btn btn-block btn-warning btn-lg font-weight-medium">
                  </div>
                  <div class="mt-3 text-center">
                    <a href="" class="auth-link text-white">نسيت كلمة المرور ؟</a>
                  </div>
                  <div class="mt-3 text-right">
                    <a href="{{ url('/register') }}" class="auth-link text-white">انشأ حساب جديد</a>
                  </div>                    
                </form>
              </div>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
      </div>
      <!-- row ends -->
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
  <!-- inject:js -->
  <script src="../../js/off-canvas.js"></script>
  <script src="../../js/hoverable-collapse.js"></script>
  <script src="../../js/misc.js"></script>
  <script src="../../js/settings.js"></script>
  <script src="../../js/todolist.js"></script>
  <!-- endinject -->
</body>

</html>
