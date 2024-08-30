@extends('layouts.backend.app')
@section('profit_loss', 'active')
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="py-3 mb-4"><span class="text-muted fw-light"></span>{{ $title }}</h4>

            <div class="row">
                <!-- DataTable with Buttons -->
                <div class="col-12 col-lg-6">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="card-title m-0">Penjualan</h6>
                            <h6 class="m-0"></h6>
                        </div>
                        <div class="card-datatable table-responsive pt-0">
                            <table id="tax-table" class="datatables-basic table">
                                <thead style="background-color: rgb(233, 232, 232);">
                                    <tr>
                                        <th style="text-align: center;">Kategori</th>
                                        <th style="text-align: center;">Nominal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $total_sale = 0; @endphp
                                    @foreach ($invoice as $item)                                       
                                        <tr>
                                            <td style="text-align: center;">{{ $item->product_category }}</td>
                                            <td style="text-align: center;">Rp. {{ number_format($item->selling_price, 2) }}</td>

                                            @php $total_sale += $item->selling_price; @endphp
                                        </tr>
                                    @endforeach
                                    <tr style="background-color: rgb(233, 232, 232);">
                                        <td style="text-align: center;">Total</td>
                                        <td style="text-align: center;">Rp. {{ number_format($total_sale, 2) }}</td>
                                    </tr>

                                    <tr>
                                        <td style="text-align: center;">Retur Penjualan Tiket</td>
                                        <td style="text-align: center; color:red;">-Rp. @if($retur_sale) {{ number_format($retur_sale->price, 2) }} @else 0 @endif</td>
                                    </tr>

                                    <tr>
                                        <td style="text-align: center;">PPN</td>
                                        <td style="text-align: center; color:red;">-Rp. @if($ppn) {{ number_format($ppn->ppn, 2) }} @else 0 @endif</td>
                                    </tr>

                                    @php 
                                        $nominal_ppn = 0;
                                        $nominal_retur_sale = 0;

                                        if ($ppn) {
                                            $nominal_ppn = $ppn->ppn;
                                        }

                                        if ($retur_sale) {
                                            $nominal_retur_sale = $retur_sale->price;
                                        }

                                        $final_sale = $total_sale - $nominal_ppn - $nominal_retur_sale;
                                    
                                    @endphp

                                    <tr style="background-color: rgb(233, 232, 232);">
                                        <td style="text-align: center;">Total</td>
                                        <td style="text-align: center; color: green;">Rp. {{ number_format($final_sale, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="card-title m-0">Harga Pokok Pembelian</h6>
                            <h6 class="m-0"></h6>
                        </div>
                        <div class="card-datatable table-responsive pt-0">
                            <table id="tax-table" class="datatables-basic table">
                                <thead style="background-color: rgb(233, 232, 232);">
                                    <tr>
                                        <th style="text-align: center;">Kategori</th>
                                        <th style="text-align: center;">Nominal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $total_purchase = 0; @endphp
                                    @foreach ($invoice as $item)                                       
                                        <tr>
                                            <td style="text-align: center;">{{ $item->product_category }}</td>
                                            <td style="text-align: center;">Rp. {{ number_format($item->purchase_price, 2) }}</td>

                                            @php $total_purchase += $item->purchase_price; @endphp
                                        </tr>
                                    @endforeach

                                    <tr style="background-color: rgb(233, 232, 232);">
                                        <td style="text-align: center;">Total</td>
                                        <td style="text-align: center;">Rp. {{ number_format($total_purchase, 2) }}</td>
                                    </tr>

                                    <tr>
                                        <td style="text-align: center;">Retur Pembelian Tiket</td>
                                        <td style="text-align: center; color:red;">-Rp. @if($retur_purchase) {{ number_format($retur_purchase->price, 2) }} @else 0 @endif</td>
                                    </tr>

                                    @php 
                                        $nominal_retur_purchase = 0;

                                        if ($retur_purchase) {
                                            $nominal_retur_purchase = $retur_purchase->price;
                                        }

                                        $final_purchase = $total_purchase - $nominal_retur_purchase;     
                                    @endphp
                                    
                                    <tr style="background-color: rgb(233, 232, 232);">
                                        <td style="text-align: center;">Total</td>
                                        <td style="text-align: center; color: green;">Rp. {{ number_format($final_purchase, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="card-title m-0">Laba Kotor</h6>
                            <h6 class="m-0"></h6>
                        </div>
                        <div class="card-datatable table-responsive pt-0">
                            <table id="tax-table" class="datatables-basic table">
                                {{-- <thead style="background-color: rgb(233, 232, 232);">
                                    <tr>
                                        <th style="text-align: center;">Penjualan</th>
                                        <th style="text-align: center;">Harga Pokok Pembelian</th>
                                    </tr>
                                </thead> --}}
                                <tbody>
                                    <tr>
                                        <td style="text-align: center;">Penjualan</td>
                                        <td style="text-align: center;">Rp. {{ number_format($final_sale, 2) }}</td>
                                    </tr>

                                    <tr>
                                        <td style="text-align: center;">Harga Pokok Pembelian</td>
                                        <td style="text-align: center; color:red;">-Rp. {{ number_format($final_purchase, 2) }}</td>
                                    </tr>

                                    <tr style="background-color: rgb(233, 232, 232);">
                                        <td style="text-align: center;">Total</td>
                                        <td style="text-align: center; color: green;">Rp. {{ number_format($final_sale - $final_purchase, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-6">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="card-title m-0">Biaya Operasional</h6>
                            <h6 class="m-0"></h6>
                        </div>

                        <div class="card-datatable table-responsive pt-0">
                            <table id="tax-table" class="datatables-basic table">
                                <thead style="background-color: rgb(233, 232, 232);">
                                    <tr>
                                        <th style="text-align: center;">Kategori</th>
                                        <th style="text-align: center;">Nominal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $total_expense = 0; @endphp

                                    @foreach ($expense as $item)                                       
                                        <tr>
                                            <td style="text-align: center;">{{ $item->name }}</td>
                                            <td style="text-align: center;">Rp. {{ number_format($item->nominal, 2) }}</td>

                                            @php $total_expense += $item->nominal; @endphp
                                        </tr>
                                    @endforeach

                                    <tr style="background-color: rgb(233, 232, 232);">
                                        <td style="text-align: center;">Total</td>
                                        <td style="text-align: center;">Rp. {{ number_format($total_expense, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="card-title m-0">Laba Bersih Perusahaan</h6>
                            <h6 class="m-0"></h6>
                        </div>
                        <div class="card-datatable table-responsive pt-0">
                            <table id="tax-table" class="datatables-basic table">
                                <tbody>
                                    <tr>
                                        <td style="text-align: center;">Harga Pokok Pembelian</td>
                                        <td style="text-align: center;">Rp. {{ number_format($final_purchase, 2) }}</td>
                                    </tr>

                                    <tr>
                                        <td style="text-align: center;">Biaya Operasional</td>
                                        <td style="text-align: center; color:red;">-Rp. {{ number_format($total_expense, 2) }}</td>
                                    </tr>

                                    <tr style="background-color: rgb(233, 232, 232);">
                                        <td style="text-align: center;">Total</td>
                                        <td style="text-align: center; color: green;">Rp. {{ number_format($final_purchase - $total_expense, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    {{-- <script src="{{ asset('assets/js/backend/tax.js') }}"></script>
    <script>
        var deleteUrl = '{{ route("backend.tax.destroy", ":id") }}';
    </script> --}}
@endpush

