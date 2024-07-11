@extends('layouts.backend.app')
@section('invoice', 'active')
@section('content')
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4"><span class="text-muted fw-light">{{ $title }} /</span> {{ $action }}</h4>

        <form action="{{ route('backend.invoice.store') }}" method="POST"
            enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title m-0">Invoice</h5>
                            <h5 class="m-0">{{ $data->invoice_number }}</h5>
                        </div>


                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title m-0"></h5>

                                <label for="" style="margin-left: 60%;">
                                    Tanggal Penerbitan : <br>
                                    {{ $data->created_at }}
                                </label>

                                <label for="">
                                    Tanggal Jatuh Tempo : <br>
                                    {{ $data->created_at }}
                                </label>
                            </div>

                            <div class="d-flex justify-content-between align-items-center" style="margin-top: 10%;">
                                <label for="">
                                    <b>Ditagihkan Kepada :</b> <br>
                                    <div style="width: 60%;">
                                        {{ $data->customer->name }}, {{ $data->customer->address }}
                                    </div>
                                    <b>Nomor Pajak :</b>
                                </label>

                                <label for="" style="margin-right: 40%;">
                                    <b>Dikirim Ke :</b> <br>
                                    <div style="width: 60%;">
                                        {{ $data->customer->name }}, {{ $data->customer->address }}
                                    </div>
                                    <b>Nomor Pajak :</b>
                                </label>
                            </div>

                            <div class="d-flex justify-content-between align-items-center" style="margin-top: 5%;">
                                <label for="">
                                    <b>Status :</b> <br>
                                    @if($data->status == 'Belum Lunas')
                                        <label class="btn btn-sm btn-danger">Belum Lunas</label>
                                    @else
                                        <label class="btn btn-sm btn-success">Sudah Lunas</label>
                                    @endif

                                    <br><br>
                                    <b>Ringkasan Produk</b>
                                </label>
                            </div>

                            <div class="table-responsive text-nowrap" style="margin-top: 5%;">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Item</th>
                                            <th>Produk</th>
                                            <th>Keterangan</th>
                                            <th>Harga Jual</th>
                                            <th>NTA</th>
                                            <th>Dari Bank</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                        @foreach ($data_d as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->category->product_category }}</td>
                                                <td>{{ $item->product_name }}</td>
                                                <td>{{ $item->note }}</td>
                                                <td>Rp. {{ number_format($item->total_price_sell) }}</td>
                                                <td>Rp. {{ number_format($item->qty * $item->purchase_price) }}</td>
                                                <td>{{ $item->bank->bank_name }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <dl class="row mb-0" style="margin-top: 10%;">
                                <hr>
                                <dt class="col-6 fw-normal text-heading">Total Harga Jual</dt>
                                <dd id="price_total_selling" class="col-6 text-end price_total_selling">
                                    Rp. {{ number_format($data->price_total_selling) }}
                                </dd>

                                <hr>
                                <dt class="col-6 fw-normal text-heading">Total Modal</dt>
                                <dd id="price_total_purchase" class="col-6 text-end price_total_purchase">
                                    Rp. {{ number_format($data->price_total_purchase) }}
                                </dd>

                                <hr>
                                <dt class="col-6 fw-normal text-heading">Total Keuntungan</dt>
                                <dd id="total_profit" class="col-6 text-end total_profit">
                                    Rp. {{ number_format($data->total_profit) }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>


                <!-- File input -->
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            {{-- <button id="addRowBtn" style="float:right; margin-bottom: 5%;" type="button" class="btn btn-warning">
                                    <i class="ti ti-plus me-sm-1"></i> Tambah Item
                                </button> --}}

                            <div id="rowsContainer">
                                <div class="row item-row" style="margin-top: 10%;">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                         
                        </div>
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end; margin: 3% 3% 0 0;">
                    <a href="{{ route('backend.invoice.index') }}" type="button"
                        class="btn btn-default">
                        Kembali
                    </a>
                </div>
            </div>
        </form>
    </div>
    <!-- / Content -->
</div>
@endsection

@push('js')
 
@endpush
