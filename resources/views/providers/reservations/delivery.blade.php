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

    <h4 class="card-title"> تسليم سيارة  </h4>
    <div class="row">
      <div class="col-12">
        <div class="table-responsive">
          <table id="order-listing" class="table">
            <thead>
              <tr>
                  <th>#</th>
                  <th> اسم الزبون </th>
                  <th>الرقم الوطني</th>
                  <th>تاريخ الحجز</th>
                  <th>نوع السيارة </th>
                  <th>حالة الدفع</th>
                  <th>ملاحظات</th>
                  <th>تسليم</th>
              </tr>
            </thead>
            <tbody>
                <?php $i = 1;?>
              @foreach($reservations as $reservation)
                <tr>
                <td>{{ $i++ }}</td>
                <td><a href="{{ url('user/show/'.$reservation->user->id) }}">{{ $reservation->user->name}}</a></td>
                <td>{{ $reservation->user->national_id}}</td>
                <td> <p>{{ date("Y-m-d", strtotime($reservation->from_date))}} <br> {{date("Y-m-d", strtotime($reservation->to_date))}}</p></td>
                <td>{{ $reservation->car->brand}}-{{ $reservation->car->type}}</td>
                <td> لا </td>
                
                <td>
                  @if(date("Y-m-d", strtotime($reservation->from_date)) < date("Y-m-d"))
                    
                    <?php
                      $date_from = new DateTime(date("Y-m-d", strtotime($reservation->from_date)));
                      $today = new DateTime(date("Y-m-d"));
                      $interval = $date_from->diff($today);
                      $diff = $interval->format('%a');
                    ?>
                    <div class="badge badge-danger badge-pill"> متأخر عن الاستلام {{ $diff }} يوم</div>
                  @endif
                </td>
                <td>
                  @if($reservation->status == 2)
                      <a href="{{ url('reservations/submit-delivery/'.$reservation->id) }}"><button class="btn btn-primary">تسليم</button></a>
                  @elseif(date("Y-m-d", strtotime($reservation->from_date)) < date("Y-m-d") && $reservation->status == 2)
                      <div class="badge badge-danger badge-pill">لم يتم الاستلام</div> | 
                      
                  @endif
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
