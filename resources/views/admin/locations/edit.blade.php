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
@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body col-md-8">
        <h4 class="card-title" >  تعديل   </h4>
        {!! Form::open(['url' => 'admin/update-location', 'files' => true, 'method' => 'POST']) !!}
        <!--<form class="forms-sample" enctype="multipart/form-data" method="POST" action="{{ route('providers.upload-documents') }}">-->
          @csrf
          <div class="form-group row">
            <label for="car_brand" class="col-sm-3 col-form-label">  أسم المنطقة </label>
            <div class="col-sm-7">
                <input type="text" class="form-control" value="{{ $location->name }}" name="name" id="city_name"  />
                @if ($errors->has('name'))
                   <div class="alert alert-danger" role="alert">{{ $errors->first('name') }}
                   </div>
                @endif
            </div>
          </div>
          <div class="form-group row">
            <label for="car_brand" class="col-sm-3 col-form-label"> إختر المدينة </label>
            <div class="col-sm-7">
                <select class="js-example-basic-single" name="city_id" id="city_ids" style="width:100%" required>
                  <option value="">إختر المدينة </option>
                  @foreach($cities as $city)
                    <option value="{{ $city->id }}" {{ ($city->id == $location->city_id)?"selected":"" }}>{{ $city->name }}</option>
                  @endforeach
                </select>
                @if ($errors->has('city'))
                   <div class="alert alert-danger" role="alert">{{ $errors->first('city') }}
                   </div>
                @endif
            </div>
            <input type="hidden" name="location_id" value="{{ $location->id }}"/>
          </div>
          <button type="submit" class="btn btn-success mr-2"> حفظ </button>
        {!! Form::close() !!}
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
