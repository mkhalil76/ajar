@extends('layouts.main')
@section('content')

	<div class="row">
      <div class="col-md-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body col-md-8">
          <h4 class="card-title">بيانات الشركة</h4>
          <div class="card-body">
                <ul class="nav nav-tabs tab-basic" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">تفاصيل الشركة</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">المستندات</a>
                  </li>
                </ul>
                <div class="tab-content tab-content-basic">
                  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <form class="pt-4" method="POST" action="{{ route('providers.update-provider') }}">
                                @csrf
                              <div class="form-group">
                                <label for="commercial_no" >الرقم التجاري </label>
                              <input type="text" class="form-control{{ $errors->has('commercial_no') ? ' is-invalid' : '' }}" name="commercial_no" value="{{ $provider->commercial_no }}" class="form-control" id="commercial_no" required autofocus>
                                @if ($errors->has('commercial_no'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('commercial_no') }}</strong>
                                    </span>
                                @endif

                              </div>
                            <input type="hidden" name="provider_id" value="{{ $provider->id }}"/>
                              <div class="form-group">
                                <label for="office_name" >إسم المكتب </label>
                                <input type="text" class="form-control{{ $errors->has('office_name') ? ' is-invalid' : '' }}" value="{{ $provider->office_name }}" class="form-control" name="office_name" id="office_name" required autofocus>
                                @if ($errors->has('office_name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('office_name') }}</strong>
                                    </span>
                                @endif

                              </div>
                              <div class="form-group">
                                <label for="owner_name" >إسم المالك </label>
                                <input type="text" class="form-control{{ $errors->has('owner_name') ? ' is-invalid' : '' }}"  value="{{ $provider->owner_name }}" class="form-control" name="owner_name" id="owner_name" required autofocus>
                                @if ($errors->has('owner_name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('owner_name') }}</strong>
                                    </span>
                                @endif

                              </div>
                               <div class="form-group">
                                <label for="mobile" >رقم الجوال </label>
                                <input type="text" class="form-control{{ $errors->has('admin_mobile') ? ' is-invalid' : '' }}" value="{{ $provider->admin_mobile }}" class="form-control" name="admin_mobile" id="admin_mobile" required autofocus>
                                @if ($errors->has('admin_mobile'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('admin_mobile') }}</strong>
                                    </span>
                                @endif

                              </div>
                              <div class="form-group">
                                <label for="password" >رمز الدخول </label>
                                <input type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" class="form-control" id="password"  required autofocus>
                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif

                              </div>
                              <div class="mt-5">
                                <input type="submit" value="حفظ" class="btn btn-block btn-primary btn-lg font-weight-medium">
                              </div>

                            </form>
                  </div>
                  <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        {!! Form::open(['route' => 'providers.update-documents', 'files' => true, 'method' => 'POST']) !!}
                        <!--<form class="forms-sample" enctype="multipart/form-data" method="POST" action="{{ route('providers.upload-documents') }}">-->
                          @csrf
                          <div class="form-group">
                            <label>السجل التجاري</label>
                            <input type="file" name="commercial_log" class="file-upload-default">
              
                            <div class="input-group col-xs-12">
                              <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                              <div class="input-group-append">
                                <button class="file-upload-browse btn btn-info" type="button">رفع</button>                          
                              </div>
                            </div>
                            @if ($errors->has('commercial_log'))
                              <strong >{{ $errors->first('commercial_log') }}</strong>
                            @endif
                          </div>
                        <input type="hidden" name="provider_document_id" value="{{$provider->documents->id}}"/>
                        <input type="hidden" name="provider_id" value="{{$provider->id}}"/>
                          <div class="form-group">
                            <label>الشعار</label>
                            <input type="file" name="logo" class="file-upload-default">
                            <div class="input-group col-xs-12">
                              <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                              <div class="input-group-append">
                                <button class="file-upload-browse btn btn-info" type="button">رفع</button>                          
                              </div>
                            </div>
                            @if ($errors->has('logo'))
                              <strong >{{ $errors->first('logo') }}</strong>
                            @endif
                          </div>
                          <button type="submit" class="btn btn-success mr-2">التالي</button>
                          {!! Form::close() !!}
                  </div>
                </div>
              </div>
        </div>

      </div>
    </div>
</div>
@endsection
@section('ExtraJs')
    <!-- Plugin js for this page-->
    <script src="../../node_modules/icheck/icheck.min.js"></script>
    <script src="../../node_modules/typeahead.js/dist/typeahead.bundle.min.js"></script>
    <script src="../../node_modules/select2/dist/js/select2.min.js"></script>
    <!-- endinject -->
    <!-- Custom js for this page-->
    <script src="../../js/file-upload.js"></script>
    <script src="../../js/iCheck.js"></script>
    <script src="../../js/typeahead.js"></script>

    <!-- End custom js for this page-->
    <script>
        $(document).ready(function () {
            $.ajax({
              url:"{{ route('get-cities') }}",
              type:"GET",
              dataType: 'JSON',
            }).done(function (res) {
              var html = "";
              $.each(res.cities, function( index, value ) {
                html += "<option value="+value.id+">"+value.name+"</option>"
                $('#city_id').append(html);
              });
            });
        });
    </script>
@endsection