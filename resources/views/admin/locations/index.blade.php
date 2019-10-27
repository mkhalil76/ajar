@extends('layouts.main')
@section('ExtraCss')
<link rel="stylesheet" href="../../node_modules/fullcalendar/dist/fullcalendar.min.css" />
@endsection
@section('content')
<div class="card">

    <div class="card-body">

      <h4 class="card-title">قائمة المناطق</h4>
        <div class="row button-group">
        <div class="col-md-5 m-b-30">
            <a href="{{ url('admin/new-location') }}" class="btn btn-info" role="button" aria-pressed="true" title=""><i class="mdi mdi-plus"></i>  أضف منطقة جديدة </a>
        </div>
    </div>
      <div class="row">
        <div class="col-12">
          <div class="table-responsive">
            <table id="order-listing" class="table">
              <thead>
                <tr>
                    <th>#</th>
                    <th>المنطقة</th>
                    <th>المدينة</th>
                    <th>إجراءات</th>
                </tr>
              </thead>
              <tbody>
                <?php $index = 1;?>
                @foreach($locations as $location)
                  <tr>
                    <td>{{ $index++ }}</td>
                    <td>{{ $location->name }}</td>
                    <th><?php
                        $city = \App\Models\City::where('id', '=', $location->city_id)->first();
                    ?>
                        {{ $city->name }}
                    </th>
                    <td>
                    <a href="{{ url('admin/location-edit/'.$location->id) }}"><button class="btn btn-info">تعديل</button></a>
                    <a href="{{ url('admin/location-delete/'.$location->id) }}"><button class="btn btn-danger">حذف</button></a>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  @endsection
@section('ExtraJs')
  <script src="../../js/calendar.js"></script>
  <script src="../../node_modules/moment/moment.js"></script>
  <script src="../../node_modules/fullcalendar/dist/fullcalendar.min.js"></script>
@endsection
