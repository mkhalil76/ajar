@extends('layouts.main')

@section('ExtraCss')
<link rel="stylesheet" href="../../node_modules/icheck/skins/all.css" />
  <link rel="stylesheet" href="../../node_modules/select2/dist/css/select2.min.css" />
  <link rel="stylesheet" href="../../node_modules/select2-bootstrap-theme/dist/select2-bootstrap.min.css" />
  <style type="text/css">
    .select2-search__field{
      height: 30px;
    }

  </style>
@endsection
@section('content')
        <div class="row user-profile">
          <div class="col-lg-4 side-left align-items-stretch">
            <div class="row">
              <div class="col-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body avatar">
                    <h4 class="card-title"> بيانات الزبون </h4>
                    <img width="600" height="800" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQJCq8ocdeBpdZgNebcoY0sM0Fl4T5rs31ughmmkCuVGkJ9lCASlA" />
                    <p class="designation">
                    الاسم : {{ $user->name }} <br>
                    العمر : {{ $user->age}} <br>
                     رقم الهوية : {{ $user->national_id }} <br>
                     رقم الجوال: {{ $user->mobile }}<br>
                     البريد الالكتروني : {{$user->email }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-8 side-right stretch-card">
            <div class="card">
              <div class="card-body">
                <div class="wrapper d-block d-sm-flex align-items-center justify-content-between">
                  <h4 class="card-title mb-0"> تفاصيل </h4>
                  <ul class="nav nav-tabs tab-solid tab-solid-primary mb-0" id="myTab" role="tablist">
                        <li class="nav-item">
                          <a class="nav-link active" id="info-tab" data-toggle="tab" href="#info" role="tab" aria-controls="info" aria-expanded="true">المستندات</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" id="avatar-tab" data-toggle="tab" href="#avatar" role="tab" aria-controls="avatar">تقييم الزبون </a>
                        </li>
                      </ul>
                </div>
                <div class="wrapper">
                  <hr>
                  <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info">
                      @if(!empty($user->documents))  
                      <ul>
                        <li><a style="text-decoration: none" href="{{ $user->documents->national_id_image }}" target="_blank">صورة الهوية</a></li>
                        <li><a style="text-decoration: none" href="{{ $user->documents->driving_license_image }}" target="_blank">رخصة القيادة</a></li>
                        <li><a style="text-decoration: none" href="{{ $user->documents->job_card_image }}">بطاقة العمل</a></li>
                        </ul>
                      @else
                        <h4>لايوجد اي مستندات</h4>
                      @endif  
                    </div><!-- tab content ends -->
                    <div class="tab-pane fade" id="avatar" role="tabpanel" aria-labelledby="avatar-tab">
                        
                    </div>
                    <div class="tab-pane fade" id="security" role="tabpanel" aria-labelledby="security-tab">
                      <form action="#">
                        <div class="form-group">
                          <label for="change-password">Change password</label>
                          <input type="password" class="form-control" id="change-password" placeholder="Enter you current password">
                        </div>
                        <div class="form-group">
                          <input type="password" class="form-control" id="new-password" placeholder="Enter you new password">
                        </div>
                        <div class="form-group mt-5">
                          <button type="submit" class="btn btn-success mr-2">Update</button>
                          <button class="btn btn-outline-danger">Cancel</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

@endsection

@section('ExtraJs')
<script src="../../node_modules/icheck/icheck.min.js"></script>
<script src="../../node_modules/typeahead.js/dist/typeahead.bundle.min.js"></script>
<script src="../../node_modules/select2/dist/js/select2.min.js"></script>
<script src="../../js/file-upload.js"></script>
<script src="../../js/iCheck.js"></script>
<script src="../../js/typeahead.js"></script>
<script src="../../js/select2.js"></script>
<script>

</script>
@endsection