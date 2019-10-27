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
          <h4 class="card-title"> إضافة الية السداد </h4>
          {!! Form::open(['route' => 'providers.add-payment-type', 'method' => 'POST']) !!}
          <!--<form class="forms-sample" enctype="multipart/form-data" method="POST" action="{{ route('providers.upload-documents') }}">-->
            @csrf
            <div class="form-group row">
            <input type="hidden" value="{{$provider_id}}" name="provider_id"/>
                <label for="city_id" class="col-sm-3 col-form-label" id="city_lable"> الية السداد </label>
                <div class="col-sm-7">
                    <select class="js-example-basic-single" name="payment_type" id="payment_type_id" style="width:100%" required>
                      <option value=""> إختر  الية السداد</option>
                      <option value="1">البطاقات االئتمانية</option>
                      <option value="2"> خدمة السداد</option>
                      <option value="3"> الدفع عند الاستلام</option>
                    </select>
                    
                </div>
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