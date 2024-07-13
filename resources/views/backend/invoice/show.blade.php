@extends('layouts.backend.app')
@section('invoice', 'active')
@section('content')
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4"><span class="text-muted fw-light">{{ $title }} /</span> {{ $action }}</h4>

        <form action="{{ route('backend.invoice.update_details', $data->id) }}" method="POST"
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

                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title m-0">Pembayaran Customer</h5>
                        </div>

                        <div class="card-body">
                            <div>
                                <label id="receivables_label" class="form-label receivables_label" style="color: red;">Piutang Customer: Rp. {{ number_format($data->receivables) }}</label>
                                <input type="hidden" id="receivables" class="receivables" value="{{ $data->price_total_selling }}">
                            </div>

                            <button id="add_payment_cust" style="float:right; margin-bottom: 5%;" type="button" class="btn btn-warning">
                                <i class="ti ti-plus me-sm-1"></i>
                            </button>

                            <div id="rowsContainer">
                                @if (count($customer_payment) > 0)
                                    @foreach ($customer_payment as $key => $item)
                                        <div class="row piutang-row" style="margin-top: 10%;">
                                            <div class="col-md-3 mb-4">
                                                <label for="select2Basic" class="form-label">Nominal</label>
                                                <div class="input-group">
                                                    <span class="input-group-text" id="basic-addon11">Rp.</span>
                                                    <input id="nominal" type="text" class="form-control nominal" placeholder="" aria-label="" aria-describedby="basic-addon11"  name="nominal[]" oninput="update_piutang(this)" value="{{ $item->nominal }}"/>
                                                </div>
                                            </div>
        
                                            <div class="col-md-3 mb-4">
                                                <label for="select2Basic" class="form-label">Tanggal</label>
                                                <input type="date" class="form-control" id="date" name="date[]" value="{{ $item->date }}"/>
                                            </div>
        
                                            <div class="col-md-3 mb-4">
                                                <label for="select2Basic" class="form-label">Keterangan</label>
                                                <textarea id="floatingInput" rows="1" class="form-control" name="note[]">{{ $item->note }}</textarea>
                                            </div>
        
                                            <div class="col-md-2 mb-4">
                                                <label @if($key == 0) for="bank_id" @else for="bank_id_"{{ $key }} @endif  class="form-label">Ke Bank</label>
                                                <select @if($key == 0) id="bank_id" @else id="bank_id_"{{ $key }} @endif class="select2 form-select form-select-lg" data-allow-clear="true" name="bank_id[]">
                                                    <option>-- Pilih Bank --</option>
            
                                                    @foreach ($bank as $item2)                                           
                                                        <option @if($item->bank_id == $item2->id) selected @endif value="{{ $item2->id }}">{{ $item2->bank_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            @if ($key !== 0)
                                                <div class="col-md-12 mb-4" style="display: flex; justify-content: flex-end;">
                                                    <button type="button" class="btn btn-danger removeRowBtn">
                                                        <i class="ti ti-trash me-sm-1"></i>
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                @else
                                    <div class="row piutang-row" style="margin-top: 10%;">
                                        <div class="col-md-3 mb-4">
                                            <label for="select2Basic" class="form-label">Nominal</label>
                                            <div class="input-group">
                                                <span class="input-group-text" id="basic-addon11">Rp.</span>
                                                <input id="nominal" type="text" class="form-control nominal" placeholder="" aria-label="" aria-describedby="basic-addon11"  name="nominal[]" oninput="update_piutang(this)"/>
                                            </div>
                                        </div>

                                        <div class="col-md-3 mb-4">
                                            <label for="select2Basic" class="form-label">Tanggal</label>
                                            <input type="date" class="form-control" id="date" name="date[]"/>
                                        </div>

                                        <div class="col-md-3 mb-4">
                                            <label for="select2Basic" class="form-label">Keterangan</label>
                                            <textarea id="floatingInput" rows="1" class="form-control" name="note[]"></textarea>
                                        </div>

                                        <div class="col-md-2 mb-4">
                                            <label for="bank_id" class="form-label">Ke Bank</label>
                                            <select id="bank_id" class="select2 form-select form-select-lg" data-allow-clear="true" name="bank_id[]">
                                                <option>-- Pilih Bank --</option>
        
                                                @foreach ($bank as $item)                                           
                                                    <option value="{{ $item->id }}">{{ $item->bank_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif
                             
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
                    <button style="margin-left: 3%;"  type="submit" class="btn btn-warning">
                        Perbarui
                    </button>
                </div>
            </div>
        </form>
    </div>
    <!-- / Content -->
</div>
@endsection

@push('js')
    <script>
        function initializeSelect2(element) {
            $(element).select2({
                theme: 'default' // Ensure Select2 uses Bootstrap 4 theme
            });
        }

        let total_receivables = 0;

        function update_piutang()
        {
            total_receivables = 0;
            total_nominal = 0;            
            var receivables_price = $('#receivables').val().replace(/[^0-9]/g, '') || 0;
            // console.log(receivables_price);

            $('.piutang-row').each(function() {
                var nominal = $(this).find('.nominal').val().replace(/[^0-9]/g, '') || 0;
                // console.log(nominal);
                // console.log('==========');
                // console.log(Number(receivables_price) - Number(nominal));

                // total_receivables = Number(receivables_price) - Number(nominal);
                total_nominal += Number(nominal);
            });

            total_receivables = Number(receivables_price) - Number(total_nominal);
            // console.log(total_nominal);
            $('#receivables_label').text("Total Piutang Rp. " + total_receivables.toLocaleString());
        }

        initializeSelect2('#bank_id');

        $('#add_payment_cust').click(function() {
            var clonedRow = $('.piutang-row:first').clone();
            clonedRow.find('input, textarea').val('');

            // console.log(clonedRow);
            clonedRow.find('.select2-container').remove();
            // clonedRow.find('select').removeAttr('data-select2-id').removeAttr('aria-hidden').removeClass('select2-hidden-accessible').removeClass('select2');

            // console.log(clonedRow);

            let param_id = [];

            clonedRow.find('select').each(function() {
                var newId = $(this).attr('id') + '_' + $('.piutang-row').length;
                // console.log(newId);
                $(this).attr('id', newId);
                $(this).addClass('select2');
                param_id.push(newId);
            });


            $('#rowsContainer').append(clonedRow);

            initializeSelect2('#' + clonedRow.find('#bank_id').attr('id'));

            param_id.forEach(element => {
                initializeSelect2('#' + clonedRow.find('#'+element).attr('id'));
            });


            clonedRow.append('<div class="col-md-12 mb-4" style="display: flex; justify-content: flex-end;"><button type="button" class="btn btn-danger removeRowBtn"><i class="ti ti-trash me-sm-1"></i></button></div>');

            clonedRow.find('.removeRowBtn').click(function() {
                $(this).closest('.piutang-row').remove();
            });
        });

        $(document).on('click', '.removeRowBtn', function() {
            $(this).closest('.piutang-row').remove();
            update_piutang();
        });
    </script>
@endpush
