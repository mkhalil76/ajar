@extends('layouts.main')

@section('ExtraCss')
  <link rel="stylesheet" href="../../node_modules/icheck/skins/all.css" />
    <link rel="stylesheet" href="../../node_modules/select2/dist/css/select2.min.css" />
    <link rel="stylesheet" href="../../node_modules/select2-bootstrap-theme/dist/select2-bootstrap.min.css" />
  <link rel="stylesheet" href="../../node_modules/datatables.net-bs4/css/dataTables.bootstrap4.css" />
  <style type="text/css">
    #order-listing_filter{
      float: left;
    }
    #minimal-checkbox-1{
      margin-top: 10px;
    }
    .icheckbox_square-blue, .iradio_square-blue{
      display: block;
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
                  <th>إستلام</th>
              </tr>
            </thead>
            <tbody>
                <?php $i = 1;?>
              @foreach($reservations as $reservation)
                <tr>
                <td>{{ $i++ }}</td>
                <td><a href="{{ url('user/show/'.$reservation->user->id) }}">{{ $reservation->user->name}}</a></td>
                <td>{{ $reservation->user->national_id }}</td>
                <td> <p>{{ date("Y-m-d", strtotime($reservation->from_date))}} <br> {{date("Y-m-d", strtotime($reservation->to_date))}}</p></td>
                <td>{{ $reservation->car->brand}}-{{ $reservation->car->type}}</td>
                <td> لا </td>
                <td>
                    @if($reservation->to_date < date('Y-m-d'))
                        <h4>متأخر عن التسليم</h4>
                    @endif
                </td>
                <td>
                  @if($reservation->status == 3)
                    <button onclick="showModal({{$reservation->id}})" class="btn btn-primary">إستلام</button>
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

<div class="modal fade show" id="rateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel-2">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel-2"> تقييم المستخدم </h5>
      </div>
      <div class="modal-body">
        {!! Form::open(['route' => 'providers.add-rate', 'files' => true, 'method' => 'POST']) !!}
            @csrf
            <input type="hidden" name="reservation" id="reservation_id">
            <div class="form-group row">
              <label for="car_brand" class="col-sm-8 col-form-label"> تفحيط </label>
              <div class="icheck-square">
              <input tabindex="5" class="checkbox" type="checkbox"  name="is_draft">
            </div>
            </div>
            <div class="form-group row">
              <label for="car_brand" class="col-sm-8 col-form-label" > حادث </label>
              <div class="icheck-square">
              <input tabindex="5" class="checkbox" type="checkbox"  name="is_accident">
              </div>
            </div>
            <div class="form-group row">
              <label for="car_brand" class="col-sm-8 col-form-label" > حجز ولم يستلم السيارة او يلغي الحجز </label>
              <div class="icheck-square">
                <input tabindex="5" class="checkbox"  type="checkbox"  name="is_cancel">
              </div>
            </div>
            <div class="form-group row">
              <label for="car_type" class="col-sm-3 col-form-label" > ملاحظات اخرى </label>
              <div class="col-sm-7">
                <textarea class="form-control"   name="note"></textarea>
              </div>

            </div>

      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success" style="margin-left:300px">حفظ</button>
        <button type="button" class="btn btn-light" data-dismiss="modal">الغاء</button>
      </div>
      {!! Form::close() !!}
    </div>
  </div>
</div>
@endsection
@section('ExtraJs')
<script src="../../node_modules/datatables.net/js/jquery.dataTables.js"></script>
<script src="../../node_modules/datatables.net-bs4/js/dataTables.bootstrap4.js"></script>
<script src="../../js/data-table.js"></script>
<script src="../../node_modules/icheck/icheck.min.js"></script>
<script src="../../node_modules/typeahead.js/dist/typeahead.bundle.min.js"></script>
<script src="../../node_modules/select2/dist/js/select2.min.js"></script>
<script src="../../js/file-upload.js"></script>
<script src="../../js/iCheck.js"></script>
<script src="../../js/typeahead.js"></script>
<script src="../../js/select2.js"></script>
<script>
  function showModal(reservation_id) {
    $('#rateModal').modal('toggle');
    $('#reservation_id').val(reservation_id);
  }
  $(document).ready(function () {
    $('.checkbox').on('change', function(){
        this.value = this.checked ? 0 : 1;
        // alert(this.value);
    }).change();
  });
</script>

@endsection
