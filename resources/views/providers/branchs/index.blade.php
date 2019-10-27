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

    <h4 class="card-title"> الفروع </h4>
      <div class="row button-group">
      <div class="col-md-5 m-b-30">
          <a href="{{ url('branchs/new/branch') }}" class="btn btn-info" role="button" aria-pressed="true" title=""><i class="mdi mdi-plus"></i>  إضافة فرع جديد </a>
      </div>
  </div>
    <div class="row">
      <div class="col-12">
        <div class="table-responsive">
          <table id="order-listing" class="table">
            <thead>
              <tr>
                  <th>#</th>
                  <th>المدينة</th>
                  <th>الموقع</th>
                  <th>رقم الفرع</th>
                  <th>إجراءات</th>
              </tr>
            </thead>
            <tbody>
              <?php $index = 1;?>
              @foreach($branches as $branch)
                <tr>
                  <td>{{ $index++ }}</td>
                  <td>{{ $branch->city }}</td>
                  <td>{{ $branch->location_id }}</td>
                  <td>{{ $branch->branch_mobile }}</td>
                  <td>
                  <a href="{{ url('branchs/edit/'.$branch->id) }}"><button class="btn btn-info">تعديل</button></a>
                  <a href="{{ url('branchs/delete', $branch->id) }}"><button class="btn btn-danger">حذف</button></a>
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
