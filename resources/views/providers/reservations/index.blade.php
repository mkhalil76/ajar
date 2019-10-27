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

    <h4 class="card-title"> الحجوزات  </h4>
    <div class="row">
      <div class="col-12">
        <div class="table-responsive">
          <table id="order-listing" class="table">
            <thead>
              <tr>
                  <th>#</th>
                  <th> اسم الزبون </th>
                  <th> الرقم الوطني  </th>
                  <th>تاريخ الحجز</th>
                  <th>نوع السيارة </th>
                  <th>موديل السيارة</th>
                  <th> السعر الكلي   </th>
                  <th> حالة الحجز </th>
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
                <td>{{ $reservation->car->model }}</td>
                <td>{{ $reservation->total_price }}</td>
                <td>
                    @if(date("Y-m-d", strtotime($reservation->from_date)) < date("Y-m-d") && $reservation->status == 2 )
                        <div class="badge badge-danger badge-pill">لم يتم الاستلام</div>
                    @elseif($reservation->status == 1)
                    <a href="{{ url('reservations/confirm-reserviation/'.$reservation->id) }}"><button class="btn btn-primary">تأكيد</button></a>
                    <a href="{{ url('reservations/reject-reserviation/'.$reservation->id) }}"><button class="btn btn-danger">رفض</button></a>
                    @elseif($reservation->status == 2)
                      <div class="badge badge-success badge-pill">تم تأكيد الحجز</div>
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
