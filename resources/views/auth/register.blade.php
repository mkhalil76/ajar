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
  <style>
      input{
        direction: rtl;
      }
  </style>
</head>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper">
      <div class="row">
        <div class="content-wrapper full-page-wrapper d-flex align-items-center auth">
          <div class="row w-100">
            <div class="col-lg-4 mx-auto">
              <div class="auth-form-dark text-right p-5">
                <h2>انشاء حساب جديد </h2>
                <form class="pt-4" method="POST" action="{{ route('register') }}">
                    @csrf
                  <div class="form-group">
                    <label for="commercial_no" >الرقم التجاري </label>
                    <input type="text" class="form-control{{ $errors->has('commercial_no') ? ' is-invalid' : '' }}" name="commercial_no" value="{{old('commercial_no')}}" class="form-control" id="commercial_no" required autofocus>
                    @if ($errors->has('commercial_no'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('commercial_no') }}</strong>
                        </span>
                    @endif
                    <i class="mdi mdi-account"></i>
                  </div>
                  <div class="form-group">
                    <label for="office_name" >إسم المكتب </label>
                    <input type="text" class="form-control{{ $errors->has('office_name') ? ' is-invalid' : '' }}" value="{{old('office_name')}}" class="form-control" name="office_name" id="office_name" required autofocus>
                    @if ($errors->has('office_name'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('office_name') }}</strong>
                        </span>
                    @endif
                    <i class="mdi mdi-user"></i>
                  </div>
                  <div class="form-group">
                    <label for="owner_name" >إسم المالك </label>
                    <input type="text" class="form-control{{ $errors->has('owner_name') ? ' is-invalid' : '' }}" value="{{old('owner_name')}}" class="form-control" name="owner_name" id="owner_name" required autofocus>
                    @if ($errors->has('owner_name'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('owner_name') }}</strong>
                        </span>
                    @endif
                    <i class="mdi mdi-user"></i>
                  </div>
                   <div class="form-group">
                    <label for="mobile" >رقم الجوال </label>
                    <input type="text" class="form-control{{ $errors->has('admin_mobile') ? ' is-invalid' : '' }}" value="{{old('admin_mobile')}}" class="form-control" name="admin_mobile" id="admin_mobile" required autofocus>
                    @if ($errors->has('admin_mobile'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('admin_mobile') }}</strong>
                        </span>
                    @endif
                    <i class="mdi mdi-phone"></i>
                  </div>
                  <div class="form-group">
                    <label for="password" >رمز الدخول </label>
                    <input type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" class="form-control" id="password" required autofocus>
                    @if ($errors->has('password'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                    <i class="mdi mdi-eye"></i>
                  </div>
                  <div class="mt-5">
                    <input type="submit" value="تسجيل جديد" class="btn btn-block btn-primary btn-lg font-weight-medium">
                  </div>
                  <div class="mt-2 text-center">
                    <a href="{{ url('/') }}" class="auth-link text-black">لديك حساب  ؟<span class="font-weight-medium">تسجيل دخول </span></a>
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