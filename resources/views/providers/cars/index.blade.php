@extends('layouts.main')

@section('ExtraCss')
  <link rel="stylesheet" href="../../node_modules/datatables.net-bs4/css/dataTables.bootstrap4.css" />
  <style type="text/css">
    #order-listing_filter{
      float: left;
    }
  </style>
@endsection
@section('content')
<div class="card">

  <div class="card-body">

    <h4 class="card-title">معرض السيارات </h4>
      <div class="row button-group">
      <div class="col-md-5 m-b-30">
          <a href="{{ url('/new-car') }}" class="btn btn-info" role="button" aria-pressed="true" title=""><i class="mdi mdi-plus"></i> سيارة جديدة </a>
      </div>
  </div>
    <div class="row">
      <div class="col-12">
        <div class="table-responsive">
          <table id="order-listing" class="table">
            <thead>
              <tr>
                  <th>#</th>
                  <th>نوع السيارة </th>
                  <th>موديل السيارة </th>
                  <th>الشركة</th>
                  <th>التصنيف </th>
                  <th>سعر التاجير اليومي </th>
                  <th>الحالة </th>
                  <th>إجراءات</th>
              </tr>
            </thead>
            <tbody>
              <?php $index = 1;?>
              @foreach($provider->cars as $car)
                <tr>
                  <td>{{ $index++ }}</td>
                  <td>{{ $car->type }}</td>
                  <td>{{ $car->model }}</td>
                  <td>{{ $car->brand }}</td>
                  <td>{{ $car->category }}</td>
                  <td>{{ $car->price_per_day}}</td>
                  <td>
                    @if($car->status == 1)
                      <label class="badge badge-warning">محجوزة</label>
                    @elseif($car->status == 0)
                      <label class="badge badge-success">متوفر في المعارض</label>
                    @endif
                  </td>
                  <td>
                  <a href="{{ route('car.show', $car->id) }}"><button class="btn btn-info">عرض</button></a>
                  <a href="{{ route('car.delete', $car->id) }}"><button class="btn btn-danger">حذف</button></a>
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
<script src="../../node_modules/datatables.net/js/jquery.dataTables.js"></script>
<script src="../../node_modules/datatables.net-bs4/js/dataTables.bootstrap4.js"></script>
<script src="../../js/data-table.js"></script>
@endsection
