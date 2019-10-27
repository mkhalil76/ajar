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
        <h4 class="card-title" >  تعديل المدينة  </h4>
        {!! Form::open(['url' => 'admin/city-update', 'files' => true, 'method' => 'POST']) !!}
        <!--<form class="forms-sample" enctype="multipart/form-data" method="POST" action="{{ route('providers.upload-documents') }}">-->
          @csrf
          <div class="form-group row">
            <label for="car_brand" class="col-sm-3 col-form-label">  أسم المدينة </label>
            <div class="col-sm-7">
            <input type="text" class="form-control" name="name" id="city_name" value="{{ $city->name }}" />
                @if ($errors->has('name'))
                   <div class="alert alert-danger" role="alert">{{ $errors->first('name') }}
                   </div>
                @endif
            </div>
          </div>
            <input type="hidden" name="city_id" value="{{ $city->id }}" />
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
