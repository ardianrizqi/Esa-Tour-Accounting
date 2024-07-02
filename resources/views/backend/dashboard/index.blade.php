@extends('layouts.backend.app')
@section('home', 'active')
@section('content')
   

    <!-- / Navbar -->

    <!-- Content wrapper -->
    <div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
        <!-- Sales last year -->
       

        <!-- Total Profit -->
        <div class="col-xl-3 col-md-4 col-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <center>
                        <div class="badge p-2 mb-2 rounded" style="background-color: #FFC55A; border-radius: 1rem !important;">
                            <img src="{{ asset('assets/img/dashboard/money.png') }}" alt="">
                        </div>

                        <h5 class="card-title mb-1 pt-2">Pemasukan Hari Ini</h5>
                    </center>
                   
                    <p class="mb-2 mt-1" style="color: #FFC55A; font-size: 30px;">Rp. 1.000.000</p>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-4 col-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <center>
                        <div class="badge p-2 mb-2 rounded" style="background-color: #FFA21D; border-radius: 1rem !important;">
                            <img src="{{ asset('assets/img/dashboard/money.png') }}" alt="">
                        </div>

                        <h5 class="card-title mb-1 pt-2">Pemasukan Bulan Ini</h5>
                    </center>
                   
                    <p class="mb-2 mt-1" style="color: #FFA21D; font-size: 30px;">Rp. 1.000.000</p>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-4 col-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <center>
                        <div class="badge p-2 mb-2 rounded" style="background-color: #5BD1DC; border-radius: 1rem !important;">
                            <img src="{{ asset('assets/img/dashboard/money.png') }}" alt="">
                        </div>

                        <h5 class="card-title mb-1 pt-2">Pengeluaran Hari Ini</h5>
                    </center>
                   
                    <p class="mb-2 mt-1" style="color: #5BD1DC; font-size: 30px;">Rp. 1.000.000</p>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-4 col-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <center>
                        <div class="badge p-2 mb-2 rounded" style="background-color: #FF3A6E; border-radius: 1rem !important;">
                            <img src="{{ asset('assets/img/dashboard/money.png') }}" alt="">
                        </div>

                        <h5 class="card-title mb-1 pt-2">Pengeluaran Bulan Ini</h5>
                    </center>
                   
                    <p class="mb-2 mt-1" style="color: #FF3A6E; font-size: 30px;">Rp. 1.000.000</p>
                </div>
            </div>
        </div>
 
        <div class="col-6 order-5">
            <div class="card">
              <div class="card-header d-flex align-items-center justify-content-between">
                <div class="card-title mb-0">
                  <h5 class="m-0 me-2">Invoice Terbaru</h5>
                </div>
                <div class="dropdown">
                  <button
                    class="btn p-0"
                    type="button"
                    id="routeVehicles"
                    data-bs-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false">
                    <i class="ti ti-dots-vertical"></i>
                  </button>
                  <div class="dropdown-menu dropdown-menu-end" aria-labelledby="routeVehicles">
                    <a class="dropdown-item" href="javascript:void(0);">Select All</a>
                    <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                    <a class="dropdown-item" href="javascript:void(0);">Share</a>
                  </div>
                </div>
              </div>
              <div class="card-datatable table-responsive">
                <table class="dt-route-vehicles table">
                  <thead class="border-top">
                    <tr>
                      <th></th>
                      <th></th>
                      <th>location</th>
                      <th>starting route</th>
                      <th>ending route</th>
                      <th>warnings</th>
                      <th class="w-20">progress</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
        </div>

        
        <div class="col-6 order-5">
            <div class="card">
              <div class="card-header d-flex align-items-center justify-content-between">
                <div class="card-title mb-0">
                  <h5 class="m-0 me-2">Statistik Mingguan Inovice</h5>
                </div>
                <div class="dropdown">
                  <button
                    class="btn p-0"
                    type="button"
                    id="routeVehicles"
                    data-bs-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false">
                    <i class="ti ti-dots-vertical"></i>
                  </button>
                  <div class="dropdown-menu dropdown-menu-end" aria-labelledby="routeVehicles">
                    <a class="dropdown-item" href="javascript:void(0);">Select All</a>
                    <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                    <a class="dropdown-item" href="javascript:void(0);">Share</a>
                  </div>
                </div>
              </div>
              <div class="card-datatable table-responsive">
                <table class="dt-route-vehicles table">
                  <thead class="border-top">
                    <tr>
                      <th></th>
                      <th></th>
                      <th>location</th>
                      <th>starting route</th>
                      <th>ending route</th>
                      <th>warnings</th>
                      <th class="w-20">progress</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
        </div>
    </div>

@endsection

