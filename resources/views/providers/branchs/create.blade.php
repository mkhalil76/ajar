@extends('layouts.main')
@section('content')
<style>
  strong{
    color: red;
    font-size: 11px;
  }
  #city_lable{
    padding-top: 25px;
  }
  input {
    border:1px solid black;
  }
  #save-button{
    text-align: center;
  }
</style>
	<div class="row">
      <div class="col-md-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body col-md-8">
          <h4 class="card-title">تسجيل الفرع</h4>
          {!! Form::open(['url' => 'branchs/add-branch', 'files' => true, 'method' => 'POST']) !!}
          <!--<form class="forms-sample" enctype="multipart/form-data" method="POST" action="{{ route('providers.upload-documents') }}">-->
            @csrf
            <div class="form-group row">
            <input type="hidden" value="{{$provider_id}}" name="provider_id"/>
                <label for="city_id" class="col-sm-3 col-form-label" id="city_lable">المدينة</label>
                <div class="col-sm-7">
                    <select class="js-example-basic-single" value="{{ old('city') }}" name="city" id="city_id" style="width:100%" required>
                      <option value=""  > إختر المدينة </option>
                    </select>
                    @if ($errors->has('city'))
                       <strong>
                          {{ $errors->first('city') }}
                       </strong>
                    @endif
                </div>
              </div>
              <div class="form-group row" id="locations">
                  <label for="city_id" class="col-sm-3 col-form-label">الموقع </label>
                  <div class="col-sm-7">
                    <select class="js-example-basic-single" value="{{ old('location_id') }}" name="location_id" id="locations_id" style="width:100%" required>
                        <option value="" > إختر الموقع </option>
                      </select>
                  </div>
                </div>
                <div class="form-group row">
                    <label for="city_id" class="col-sm-3 col-form-label">  رقم جوال المسؤول </label>
                    <div class="col-sm-7">
                        <input type="text" value="{{ old('branch_mobile') }}" name="branch_mobile" class="form-control" id="branch_mobile_id" required/>
                    </div>
                    @if ($errors->has('branch_mobile'))
                    <strong>
                       {{ $errors->first('branch_mobile') }}
                    </strong>
                 @endif
                </div>
            <button type="submit" class="btn btn-success mr-2" id="save-button">حفظ</button>
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
            $("#locations").hide();
            $.ajax({
              url:"{{ route('get-cities') }}",
              type:"GET",
              dataType: 'JSON',
            }).done(function (res) {
              console.log(res);
              var html = "";
              html += "<option value=''>إختر المدينة</option>";
              $.each(res.cities, function( index, value ) {

                html += "<option value="+value.id+">"+value.name+"</option>"
                $('#city_id').html(html);
              });
            });

            $('#city_id').change(function () {
                $("#locations").show();
                var city_id = $('#city_id').val();
                $.ajax({
                    url:'{{ url('/api/get-locations/') }}'+'/'+$(this).val(),
                    type:"GET",
                    dataType: 'JSON',
                }).done(function (res) {
                    console.log(res);
                    var html = "";
                    html += "<option value=''>إختر الموقع</option>";
                    $.each(res.items, function( index, value ) {

                        html += "<option value="+value.id+">"+value.name+"</option>"
                        $('#locations_id').html(html);
                    });
                });
            });
        });
    </script>
@endsection
