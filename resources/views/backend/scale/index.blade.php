@extends('layouts.backend.app')
@section('scale', 'active')
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="py-3 mb-4"><span class="text-muted fw-light"></span>{{ $title }}</h4>

            <div class="row">
                <!-- DataTable with Buttons -->
                <div class="col-12 col-lg-6">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="card-title m-0">Aktiva</h6>
                            <h6 class="m-0">12 Juni 2024</h6>
                        </div>
                        <div class="card-datatable table-responsive pt-0">
                            <table id="tax-table" class="datatables-basic table">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;">Aktiva Lancar</th>
                                        <th style="text-align: center;">Nominal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $total_income = 0; @endphp
                                    @foreach ($bank as $item)    
                                        <tr>
                                            <td style="text-align: center;">{{ $item->bank_name }}</td>
                                            <td style="text-align: center;">Rp. {{ number_format($item->income, 2) }}</td>

                                            @php $total_income += $item->income; @endphp
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td></td>
                                        <td style="color: green; text-align: center;">Rp. {{ number_format($total_income, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="card-datatable table-responsive pt-0" style="margin-top: 20%;">
                            <table id="tax-table" class="datatables-basic table">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;">Piutang Usaha</th>
                                        <th style="text-align: center;">Nominal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $total_piutang = 0; @endphp
                                    <tr>   
                                        <td style="text-align: center;">Piutang</td>
                                        <td style="text-align: center;">Rp. {{ number_format($piutang, 2) }}</td>

                                        @php $total_piutang += $piutang; @endphp
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td style="color: green; text-align: center;">Rp. {{ number_format($total_piutang, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="card-datatable table-responsive pt-0" style="margin-top: 20%;">
                            <table id="tax-table" class="datatables-basic table">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;">Deposit</th>
                                        <th style="text-align: center;">Nominal</th>
                                    </tr> 
                                </thead>
                                <tbody>
                                    @php $total_deposit = 0; @endphp
                                    @foreach ($deposit as $item)
                                        <tr>   
                                            <td style="text-align: center;">{{ $item->name }}</td>
                                            <td style="text-align: center;">Rp. {{ number_format($item->nominal, 2) }}</td>

                                            @php $total_deposit += $item->nominal; @endphp
                                        </tr>
                                    @endforeach

                                    <tr>
                                        <td></td>
                                        <td style="color: green; text-align: center;">Rp. {{ number_format($total_deposit, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="card-datatable table-responsive pt-0" style="margin-top: 20%;">
                            <table id="tax-table" class="datatables-basic table">
                                <thead>
                                    @php $total_all = $total_income + $total_piutang + $total_deposit; @endphp
                                    <tr>
                                        <th style="text-align: center;">Total</th>
                                        <th style="color: green; text-align: center;">Rp. {{ number_format($total_all, 2) }}</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-6">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="card-title m-0">Pasiva</h6>
                            <h6 class="m-0">12 Juni 2024</h6>
                        </div>
                        <div class="card-datatable table-responsive pt-0">
                            <table id="tax-table" class="datatables-basic table">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;">Hutang Usaha</th>
                                        <th style="text-align: center;">Nominal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $total_hutang = 0; @endphp
                                    @foreach ($hutang as $item)    
                                        <tr>
                                            <td style="text-align: center;">Hutang {{ $item->product_category }}</td>
                                            <td style="text-align: center;">Rp. {{ number_format($item->debt_to_vendors, 2) }}</td>

                                            @php $total_hutang += $item->debt_to_vendors; @endphp
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td></td>
                                        <td style="color: green; text-align: center;">Rp. {{ number_format($total_hutang, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="card-datatable table-responsive pt-0" style="margin-top: 20%;">
                            <table id="tax-table" class="datatables-basic table">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;">Modal</th>
                                        <th style="text-align: center;">Nominal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $total_asset = 0; @endphp
                                    @foreach ($asset as $item)    
                                        <tr>
                                            <td style="text-align: center;">Hutang {{ $item->name }}</td>
                                            <td style="text-align: center;">Rp. {{ number_format($item->nominal, 2) }}</td>

                                            @php $total_asset += $item->nominal; @endphp
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td></td>
                                        <td style="color: green; text-align: center;">Rp. {{ number_format($total_asset, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="card-datatable table-responsive pt-0" style="margin-top: 28%;">
                            <table id="tax-table" class="datatables-basic table">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;">Saldo Laba</th>
                                        <th style="text-align: center;">Nominal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $total_profit = 0; @endphp   
                                    <tr>
                                        <td style="text-align: center;">Laba Tahun Berjalan</td>
                                        <td style="text-align: center;">Rp. {{ number_format($profit, 2) }}</td>

                                        @php $total_profit += $profit; @endphp
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td style="color: green; text-align: center;">Rp. {{ number_format($total_profit, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="card-datatable table-responsive pt-0" style="margin-top: 20%;">
                            <table id="tax-table" class="datatables-basic table">
                                <thead>
                                    @php $total_all_pasiva = $total_hutang + $total_asset + $total_profit; @endphp
                                    <tr>
                                        <th style="text-align: center;">Total</th>
                                        <th style="color: green; text-align: center;">Rp. {{ number_format($total_all_pasiva, 2) }}</th>
                                    </tr>
                                </thead>
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

