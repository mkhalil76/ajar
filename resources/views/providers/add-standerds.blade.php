@extends('layouts.main')
@section('content')

	<div class="row">
      <div class="col-md-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body col-md-8">
          <h4 class="card-title"> إضافة معايير السيارات </h4>
          {!! Form::open(['route' => 'providers.add-standerds', 'method' => 'POST']) !!}
          <!--<form class="forms-sample" enctype="multipart/form-data" method="POST" action="{{ route('providers.upload-documents') }}">-->
            @csrf
            <div class="form-group row">
            <input type="hidden" value="{{$provider_id}}" name="provider_id"/>
                <label for="city_id" class="col-sm-3 col-form-label" id="city_lable">نوع التأمين </label>
                <div class="col-sm-7">
                    <select class="js-example-basic-single" name="insurance" id="insurance_id" style="width:100%" required>
                      <option value=""> إختر نوع التأمين</option>
                      <option value="1">شامل</option>
                      <option value="2">قسط التأمين</option>
                      <option value="3">ضد الغير</option>
                    </select>
                    
                </div>
              </div>
              <div class="form-group row">
                        <label for="city_id" class="col-sm-3 col-form-label" id="city_lable">نوع الرخصة </label>
                        <div class="col-sm-7">
                            <select class="js-example-basic-single" name="licens_type" id="licens_type_id" style="width:100%" required>
                              <option value="" >إختر نوع الرخصة</option>
                              <option value="1">سعودية</option>
                              <option value="2" > دول مجلس التعاون  </option>
                              <option value="3"> دولية </option>
                            </select>
                            
                        </div>
                      </div>
              <div class="form-group row">
                  <label for="city_id" class="col-sm-3 col-form-label" > العمر من  </label>
                  <div class="col-sm-7">
                      <input type="text" name="from_age" class="form-control" id="age_from_id" required/>
                  </div>
                </div>
                <div class="form-group row">
                    <label for="city_id" class="col-sm-3 col-form-label" > العمر الى  </label>
                        <div class="col-sm-7">
                            <input type="text" name="to_age" class="form-control" id="age_to_id" required/>
                        </div>
                    </div>
                    <div class="form-group row">
                            <label for="city_id" class="col-sm-3 col-form-label" id="city_lable"> الكيلو المجاني </label>
                            <div class="col-sm-7">
                                <select class="js-example-basic-single" name="free_kilo" id="free_kilo_id" style="width:100%" required>
                                  <option value="" >إختر الكيلو المجاني </option>
                                  <option value="1">مفتوح</option>
                                  <option value="2" > 200  </option>
                                  <option value="3"> 400 </option>
                                </select>
                                
                            </div>
                          </div>
            <button type="submit" class="btn btn-success mr-2" id="save-button">التالي</button>
          {!! Form::close() !!}
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