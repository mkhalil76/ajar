@extends('layouts.main')
@section('content')
<style>
  strong{
    color: red;
    font-size: 11px;
  }
</style>
	<div class="row">
      <div class="col-md-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body col-md-6">
          <h4 class="card-title">إضافة المستندات </h4>
          {!! Form::open(['route' => 'providers.upload-documents', 'files' => true, 'method' => 'POST']) !!}
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
    <script src="../../js/select2.js"></script>
    <!-- End custom js for this page-->
@endsection