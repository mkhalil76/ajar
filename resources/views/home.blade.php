@extends('layouts.main')
@section('ExtraCss')
<link rel="stylesheet" href="../../node_modules/fullcalendar/dist/fullcalendar.min.css" />
@endsection
@section('content')
          <div class="row">
            <div class="col-md-6 col-lg-4 grid-margin stretch-card">
            	
              <div class="card">
                <div class="card-body">
                  <div class="d-flex align-items-center justify-content-md-center">
                    <a href="{{ url('/cars') }}"><i class="mdi mdi-car icon-lg text-success"></i></a>
                    <div class="ml-3">
                      <p class="mb-0"> {{ $car_count }} </p>
                      <h6>معرض السيارات </h6>
                    </div>
                  </div>
                </div>
              </div>
          	
            </div>
            <div class="col-md-6 col-lg-4 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex align-items-center justify-content-md-center">
                  <a href="{{ url('/branchs') }}"><i class="mdi mdi-home icon-lg text-warning"></i></a>
                    <div class="ml-3">
                    <p class="mb-0">{{ $branch_count }}</p>
                      <h6>الفروع </h6>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-lg-4 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex align-items-center justify-content-md-center">
                  <a href="{{ url('reservations/') }}"><i class="mdi mdi-chart-line icon-lg text-info"></i></a>
                  <div class="ml-3">
                      <p class="mb-0">  </p>
                      <h6> الحجوزات </h6>
                    </div>
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