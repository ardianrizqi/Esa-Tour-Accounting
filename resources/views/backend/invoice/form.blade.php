@extends('layouts.backend.app')
@section('invoice', 'active')
@section('content')
    <div class="content-wrapper">
        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="py-3 mb-4"><span class="text-muted fw-light">{{ $title }} /</span> {{ $action }}</h4>

            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 mb-4">
                                    <label for="customer" class="form-label">Pelanggan</label>
                                    <button style="width: auto; padding: 5px 10px; font-size: 12px; float:right; margin-bottom: 2%;" type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editUser">
                                        Add (+)
                                    </button>
                                    <select id="customer_id" name="customer_id" class="select2 form-select form-select-lg" data-allow-clear="true">
                                        <option>-- Pilih Pelanggan --</option>

                                        @foreach ($customers as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                        
                                <div class="col-md-3 mb-4">
                                    <label for="select2Basic" class="form-label">Tanggal Penerbitan</label>
                                    <input type="date" class="form-control" id="floatingInput" placeholder="John Doe" aria-describedby="floatingInputHelp" />
                                </div>

                                <div class="col-md-3 mb-4">
                                    <label for="select2Basic" class="form-label">Invoice Fisik</label>
                                    <select id="invoice_fisik" class="select2 form-select form-select-lg" data-allow-clear="true">
                                        <option value="AK">Alaska</option>
                                        <option value="HI">Hawaii</option>
                                        <option value="CA">California</option>
                                    </select>
                                </div>

                                <div class="col-md-3 mb-4">
                                    <label for="select2Basic" class="form-label">Nomor Invoice</label>
                                    <input type="text" class="form-control" id="floatingInput" aria-describedby="floatingInputHelp" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        

                <!-- File input -->
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <button style="float:right; margin-bottom: 5%;" type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editUser">
                                <i class="ti ti-plus me-sm-1"></i> Tambah Item
                            </button>
                            
                            <div class="row" style="margin-top: 10%;">
                                <hr>
                                <div class="col-md-2 mb-4">
                                    <label for="select2Basic" class="form-label">Kategori Item</label> 
                                    <select id="select2Basic" class="select2 form-select form-select-lg" data-allow-clear="true">
                                        <option value="AK">Alaska</option>
                                        <option value="HI">Hawaii</option>
                                        <option value="CA">California</option>
                                    </select>
                                </div>
                        
                                <div class="col-md-2 mb-4">
                                    <label for="select2Basic" class="form-label">Nama Produk</label>
                                    <input type="text" class="form-control" id="floatingInput" placeholder="John Doe" aria-describedby="floatingInputHelp" />
                                </div>

                                <div class="col-md-1 mb-4">
                                    <label for="select2Basic" class="form-label">Kuantiti</label>
                                    <input type="text" class="form-control" id="floatingInput" placeholder="" aria-describedby="floatingInputHelp" />
                                </div>

                                <div class="col-md-2 mb-4">
                                    <label for="select2Basic" class="form-label">Harga Jual</label>
                                    <div class="input-group">
                                        <span class="input-group-text" id="basic-addon11">Rp.</span>
                                        <input
                                          type="text"
                                          class="form-control"
                                          placeholder=""
                                          aria-label=""
                                          aria-describedby="basic-addon11" />
                                    </div>
                                </div>

                                <div class="col-md-2 mb-4">
                                    <label for="select2Basic" class="form-label">Dari Bank</label> 
                                    <select id="from_bank" class="select2 form-select form-select-lg" data-allow-clear="true">
                                        <option value="AK">Alaska</option>
                                        <option value="HI">Hawaii</option>
                                        <option value="CA">California</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-2 mb-4">
                                    <label for="select2Basic" class="form-label">Harga Beli</label>
                                    <div class="input-group">
                                        <span class="input-group-text" id="basic-addon11">Rp.</span>
                                        <input
                                          type="text"
                                          class="form-control"
                                          placeholder=""
                                          aria-label=""
                                          aria-describedby="basic-addon11" />
                                    </div>
                                </div>

                                <div class="col-md-1 mb-4">
                                    <label for="select2Basic" class="form-label">Jumlah</label>
                                    <label for="select2Basic" class="form-label">RP. 1000000</label>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label for="select2Basic" class="form-label">Keterangan</label>
                                    <textarea id="floatingInput" rows="4" class="form-control"></textarea>
                                </div>

                                <div class="col-md-2 mb-4">
                                    <label for="select2Basic" class="form-label">Hutang Ke Vendor</label>
                                    <div class="input-group">
                                        <span class="input-group-text" id="basic-addon11">Rp.</span>
                                        <input
                                          type="text"
                                          class="form-control"
                                          placeholder=""
                                          aria-label=""
                                          aria-describedby="basic-addon11" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">                            
                            <dl class="row mb-0">
                                <hr>
                                <dt class="col-6 fw-normal text-heading">Total Harga Jual</dt>
                                <dd class="col-6 text-end">Rp. 5.000.000</dd>
        
                                <hr>
                                <dt class="col-6 fw-normal text-heading">Total Modal</dt>
                                <dd class="col-6 text-end">Rp. 3.800.000</dd>

                                <hr>
                                <dt class="col-6 fw-normal text-heading">Total Keuntungan</dt>
                                <dd class="col-6 text-end">Rp. 1.200.000</dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end; margin: 3% 3% 0 0;">
                    <a href="{{ route('backend.invoice.index') }}" type="button" class="btn btn-default">
                        Kembali
                    </a>

                    <a style="margin-left: 3%;" href="{{ route('backend.invoice.create') }}" type="button" class="btn btn-warning">
                        Buat
                    </a>
                </div>
            </div>
        </div>
        <!-- / Content -->
        <!-- Edit User Modal -->
        <div class="modal fade" id="editUser" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-simple modal-edit-user">
                <div class="modal-content p-3 p-md-5">
                    <div class="modal-body">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <div class="text-center mb-4">
                            <h3 class="mb-2">Tambah Pelanggan</h3>
                            {{-- <p class="text-muted">Updating user details will receive a privacy audit.</p> --}}
                        </div>
                        <form id="customer_form" class="row g-3">
                            <div class="col-12 col-md-4">
                                <label class="form-label" for="modalEditUserFirstName">Nama</label><span style="color: red;"> *</span>
                                <input type="text" id="name" name="name" class="form-control" placeholder="Ketik nama" required/>
                            </div>

                            <div class="col-12 col-md-4">
                                <label class="form-label" for="modalEditUserLastName">Kontak</label><span style="color: red;"> *</span>
                                <input type="text" id="contact" name="contact" class="form-control" placeholder="Ketik Kontak" required/>
                            </div>

                            <div class="col-12 col-md-4">
                                <label class="form-label" for="modalEditUserName">Email</label><span style="color: red;"> *</span>
                                <input type="email" id="email" name="email" class="form-control" placeholder="Ketik Email" required/>
                            </div>

                            <div class="col-12 col-md-4">
                                <label class="form-label" for="province_id">Provinsi</label><span style="color: red;"> *</span>
                                <select id="province_id" name="province_id" class="select2 form-select" aria-label="Default select example" required>
                                    <option selected>-- Pilih Provinsi --</option>

                                    @foreach ($provinces as $item) 
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 col-md-4">
                                <label class="form-label" for="city_id">Kota</label><span style="color: red;"> *</span>
                                <select id="city_id" name="city_id" class="select2 form-select" aria-label="Default select example" required>
                                    <option>-- Pilih Kota --</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-4">
                                <label class="form-label" for="district_id">Kecamatan</label><span style="color: red;"> *</span>
                                <select id="district_id" name="district_id" class="select2 form-select" aria-label="Default select example" required>
                                    <option>-- Pilih Kecamatan --</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-4">
                                <label class="form-label" for="post_code">Kode Pos</label>
                                <input type="text" id="post_code" name="post_code" class="form-control" placeholder="Ketik Kode Pos" />
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label" for="address">Alamat</label>
                                <textarea id="address" rows="4" name="address" class="form-control"></textarea>
                            </div>
                           
                            <div class="col-12" style="display: flex; justify-content: flex-end; margin-top: 5%;">
                                <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">
                                    Batal
                                </button>

                                <button id="submit_customer" type="submit" class="btn btn-warning me-sm-3 me-1">Buat</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Edit User Modal -->
    </div>
@endsection

@push('js')
    <script>
        $('#province_id').change(function() {
            var province_id = $(this).val();

            if (province_id) {
                $('#loading').show();

                $.ajax({
                    url: 'get-city/' + province_id,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#city_id').empty().trigger('change');
                        $('#city_id').append('<option>-- Pilih City --</option>');

                        $.each(data, function(key, value) {
                            $('#city_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });

                        $('#city_id').trigger('change');
                        $('#loading').hide();
                    }
                });
            } else {
                $('#city_id').empty();
                $('#city_id').append('<option selected>-- Pilih City --</option>');
                $('#city_id').select2();
            }
        });

        $('#city_id').change(function() {
            var district_id = $(this).val();

            if (district_id) {
                $('#loading').show();

                $.ajax({
                    url: 'get-district/' + district_id,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#district_id').empty().trigger('change');
                        $('#district_id').append('<option>-- Pilih Kecamatan --</option>');

                        $.each(data, function(key, value) {
                            $('#district_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });

                        $('#district_id').trigger('change');
                        $('#loading').hide();
                    }
                });
            } else {
                $('#district_id').empty().trigger('change');
                $('#district_id').append('<option selected>-- Pilih City --</option>');
                $('#district_id').trigger('change');
            }

        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#customer_form').on('submit', function(event) {
            event.preventDefault();

            var formData = $(this).serialize();
            $('#loading').show();
          

            $.ajax({
                url: 'customer-store',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(data) {
                    if (data.status == 200) {
                        $('#customer_id').empty().trigger('change');
                        $('#customer_id').append('<option>-- Pilih Pelanggan --</option>');

                        $.each(data.data, function(key, value) {
                            // console.log(value);
                            $('#customer_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });

                        $('#customer_id').trigger('change');

                        Swal.fire('Sukses !!', data.message, 'success');
                    }else{
                        Swal.fire('Gagal !!', data.message, 'error');
                    }
                   
                    $('#loading').hide();

                    $('#editUser').find('.btn-close').trigger('click');
                },
                error: function(xhr, status, error) {
                  
                }
            });
        });
    </script>
@endpush