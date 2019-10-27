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
      <div class="col-md-12 d-flex align-items-stretch grid-margin">
        <div class="row flex-grow">
          <div class="col-12 grid-margin">
          <div class="col-12 stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">إضافة سيارة جديدة </h4>
                <hr>
                {!! Form::open(['route' => 'car.save', 'files' => true, 'method' => 'POST']) !!}
                 @csrf
                  <div class="form-group row">
                    <label for="car_brand" class="col-sm-3 col-form-label"> إختر الشركة </label>
                    <div class="col-sm-7">
                        <select class="js-example-basic-single" name="brand" id="car_brand" style="width:100%" required>
                          <option value="">إختر الشركة </option>
                          @foreach($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                          @endforeach
                        </select>
                        @if ($errors->has('brand'))
                           <div class="alert alert-danger" role="alert">{{ $errors->first('brand') }}
                           </div>
                        @endif
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="car_type" class="col-sm-3 col-form-label">نوع السيارة </label>
                    <div class="col-sm-7">
                       <select class="js-example-basic-single" name="type" id="car_type" style="width:100%" required>
                        <option value=""> إختر السيارة </option>
                        </select>
                        @if ($errors->has('type'))
                           <div class="alert alert-danger">{{ $errors->first('type') }}</div>
                        @endif
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="car_type" class="col-sm-3 col-form-label">موديل السيارة </label>
                    <div class="col-sm-7">
                       <select class="js-example-basic-single" name="model" id="car_type" style="width:100%" required>
                        <?php $year = date("Y");?>
                        <option value="">إختر الموديل </option> 
                        @for($i = $year; $i >= 2010; $i--)
                          <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                        </select>
                        @if ($errors->has('model'))
                           <div class="alert alert-danger" role="alert">{{ $errors->first('model') }}</div>
                        @endif
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="car_type" class="col-sm-3 col-form-label">إختر الفرع </label>
                  <div class="col-sm-7">
                        <select class="js-example-basic-single" name="branch_id" id="branch" style="width:100%" required>
                          <option value="">إختر الفرع </option>
                          @foreach($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->city }}</option>
                          @endforeach
                        </select>
                        @if ($errors->has('branch_id'))
                           <div class="alert alert-danger" role="alert">{{ $errors->first('branch_id') }}
                           </div>
                        @endif
                    </div>
                  </div>                  
                  <div class="form-group row">
                    <label for="car_type" class="col-sm-3 col-form-label">إمكانية التسليم في فرع اخر</label>
                    <div class="col-sm-7">
                      <div class="icheck-square">
                        <input tabindex="5" name="can_delivery_in_another_branch" type="checkbox" id="can_deleviry" >
                      </div>
                      @if ($errors->has('can_delivery_in_another_branch'))
                           <div class="alert alert-danger" role="alert">{{ $errors->first('can_delivery_in_another_branch') }}</div>
                      @endif
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="car_type" class="col-sm-3 col-form-label"> التصنيف </label>
                    <div class="col-sm-7">
                       <select class="js-example-basic-single" name="category" id="cat_id" style="width:100%" required>
                        <?php
                            $car_category = [
                                '',
                                'عائلية',
                                'صغيرة',
                                'دفع رباعي'
                            ];
                        ?>
                        <option value="">إختر التصنيف</option>

                        <option value="1">عائلية</option>
                        <option value="2">رياضية </option>
                        <option value="3">دفع رباعي </option>
                        </select>
                        @if ($errors->has('category'))
                           <div class="alert alert-danger" role="alert">{{ $errors->first('category') }}</div>
                        @endif
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="car_type" class="col-sm-3 col-form-label">اضافات اخرى </label>
                    <div class="col-sm-7">
                       <select class="js-example-basic-single" name="features_id[]" id="features" style="width:100%" multiple>
                        @foreach($features as $feature)
                          <option value="{{$feature->id}}">{{ $feature->name }}</option>
                        @endforeach
                        </select>
                        @if ($errors->has('features_id'))
                           <div class="alert alert-danger" role="alert">{{ $errors->first('features_id') }}</div>
                        @endif
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="car_type" class="col-sm-3 col-form-label">سعر الاجار لكل يوم </label>
                    <div class="col-sm-7">
                      <input type="text" class="form-control" id="p_p_d" name="price_per_day">
                      @if ($errors->has('price_per_day'))
                        <div class="alert alert-danger" role="alert">{{ $errors->first('price_per_day') }}</div>
                      @endif
                    </div>

                  </div>
                  <div class="form-group row">
                    <label for="car_type" class="col-sm-3 col-form-label"> صورة السيارة  </label>
                    <div class="col-sm-7">
                      <input type="file" class="form-control" id="car_photo" name="picture">
                    @if ($errors->has('picture'))
                        <div class="alert alert-danger" role="alert">{{ $errors->first('picture') }}</div>
                    @endif
                    </div>
                  </div>
                  <button type="submit" class="btn btn-success mr-2">حفظ </button>
                {{ Form::close() }}
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
      $(document).ready(function () {
          $('#car_brand').on('change', function () {
              $.ajax({
                type:'GET',
                dataType:'json',
                url:'{{ url('/api/car-type/') }}'+'/'+$(this).val(),
                success:function(response){
                    var html = "";
                    $.each(response.items , function (i,v){
                        html += "<option value="+v.id+">"+v.name+"</option>";
                        $('#car_type').html(html);
                    });
                }
              });
          });
      });
  </script>
@endsection