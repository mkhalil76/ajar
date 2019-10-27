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
                    <h4 class="card-title">معلومات السيارة</h4>
                    <img width="600" height="800" src="https://www.cstatic-images.com/car-pictures/xl/usc80hyc012a121001.png" />
                    <p class="designation">
                    النوع : {{ $car->type }} <br>
                    الشركة : {{ $car->brand}} <br>
                     الموديل : {{ $car->model }} <br>
                     التصنيف: {{ $car->category }}<br>
                     الفرع : {{$car->branch->city }} - {{ $car->branch->location }}</p>
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
                          <a class="nav-link active" id="info-tab" data-toggle="tab" href="#info" role="tab" aria-controls="info" aria-expanded="true">تعديل</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" id="avatar-tab" data-toggle="tab" href="#avatar" role="tab" aria-controls="avatar">تفاصيل الحجز</a>
                        </li>
                      </ul>
                </div>
                <div class="wrapper">
                  <hr>
                  <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info">
                            {!! Form::open(['route' => 'car.edit', 'files' => true, 'method' => 'POST']) !!}
                            @csrf
                             <div class="form-group row">
                               <label for="car_brand" class="col-sm-3 col-form-label"> إختر الشركة </label>
                               <div class="col-sm-7">
                               <input type="hidden" name="car_id" value="{{ $car->id }}"/>
                                   <select class="form-control " name="brand" id="car_brand" style="width:100%" required>
                                     <option value="">إختر الشركة </option>
                                     @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}" {{($brand->name == $car->brand)?'selected':''}}>{{ $brand->name }}</option>
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
                                  <select class="form-control" name="type" id="car_type" style="width:100%" required>
                                   <option value=""> إختر السيارة </option>
                                   @foreach($cartype as $type)
                                        <option value="{{ $type->id}}" {{($car->type == $type->name)?'selected':''}}>{{ $type->name }}</option>
                                   @endforeach
                                   </select>
                                   @if ($errors->has('type'))
                                      <div class="alert alert-danger">{{ $errors->first('type') }}</div>
                                   @endif
                               </div>
                             </div>
                             <div class="form-group row">
                               <label for="car_type" class="col-sm-3 col-form-label">موديل السيارة </label>
                               <div class="col-sm-7">
                                  <select class="form-control" name="model" id="car_type" style="width:100%" required>
                                   <?php $year = date("Y");?>
                                   <option value="">إختر الموديل </option>
                                   @for($i = $year; $i >= 2010; $i--)
                                     <option value="{{ $i }}" {{($i == $car->model)?'selected':'' }}>{{ $i }}</option>
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
                                   <select class="form-control" name="branch_id" id="branch" style="width:100%" required>
                                     <option value="">إختر الفرع </option>
                                     @foreach($branches as $branch)
                                       <option value="{{ $branch->id }}" {{($branch->id == $car->branch_id )?'selected':'' }} >{{ $branch->city }}</option>
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
                                   <input tabindex="5" name="can_delivery_in_another_branch" {{ ($car->can_delivery_in_another_branch == 1)?'checked':'' }} type="checkbox" id="can_deleviry" >
                                 </div>
                                 @if ($errors->has('can_delivery_in_another_branch'))
                                      <div class="alert alert-danger" role="alert">{{ $errors->first('can_delivery_in_another_branch') }}</div>
                                 @endif
                               </div>
                             </div>
                             <div class="form-group row">
                               <label for="car_type" class="col-sm-3 col-form-label"> التصنيف </label>
                               <div class="col-sm-7">
                                  <select class="form-control" name="category" id="cat_id" style="width:100%" required>
                                   <?php
                                       $car_category = [
                                           '',
                                           'عائلية',
                                           'صغيرة',
                                           'دفع رباعي'
                                       ];
                                   ?>
                                   <option value="" >إختر التصنيف</option>
                                   <option value="1" {{ ($car->category == "عائلية")?'selected':'' }}>عائلية</option>
                                   <option value="2" {{ ($car->category == "صغيرة")?'selected':'' }}>صغيرة </option>
                                   <option value="3" {{ ($car->category == 'دفع رباعي')?'selected':'' }}>دفع رباعي </option>
                                   </select>
                                   @if ($errors->has('category'))
                                      <div class="alert alert-danger" role="alert">{{ $errors->first('category') }}</div>
                                   @endif
                               </div>
                             </div>
                             <div class="form-group row">
                               <label for="car_type" class="col-sm-3 col-form-label">اضافات اخرى </label>
                               <div class="col-sm-7">
                                    {!! Form::select('features_id[]', $features, isset($car_features) ? $car_features : null, ['class' => 'js-example-basic-single', 'multiple'=>true, 'style' => 'width: 100%; height: auto!important;', 'required']) !!}
                                   @if ($errors->has('features_id'))
                                      <div class="alert alert-danger" role="alert">{{ $errors->first('features_id') }}</div>
                                   @endif
                               </div>
                             </div>
                             <div class="form-group row">
                               <label for="car_type" class="col-sm-3 col-form-label">سعر الاجار لكل يوم </label>
                               <div class="col-sm-7">
                               <input type="text" class="form-control" id="p_p_d" name="price_per_day" value="{{$car->price_per_day}}">
                                 @if ($errors->has('price_per_day'))
                                   <div class="alert alert-danger" role="alert">{{ $errors->first('price_per_day') }}</div>
                                 @endif
                               </div>
                             </div>
                             <button type="submit" class="btn btn-success mr-2">حفظ </button>
                           {{ Form::close() }}
                    </div><!-- tab content ends -->
                    <div class="tab-pane fade" id="avatar" role="tabpanel" aria-labelledby="avatar-tab">
                        @if($reservation == null)
                        <div class="wrapper mb-5 mt-4">
                            <p class="d-inline ml-3 text-muted"> لايوجد حجوزات لهذه السيارة حاليا !</p>
                        </div>
                        @else
                          <ul>
                            <?php $user = \App\Models\User::where('id', '=', $reservation->user_id)->first();?>
                          <li>أسم الزبون : {{ $user->name }}</li>
                          <li> رقم جوال الزبون : {{ $user->mobile }}</li>
                          <li>تاريخ الاستلام : {{ date("Y-m-d", strtotime($reservation->from_date)) }}</li>
                          <li>تاريخ التسليم : {{ date("Y-m-d", strtotime($reservation->to_date)) }}</li>
                          <li>السعر : {{ $reservation->total_price }}</li>
                          </ul>
                        @endif
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
