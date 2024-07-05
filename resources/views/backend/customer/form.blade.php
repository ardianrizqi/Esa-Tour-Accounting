@extends('layouts.backend.app')
@section('customer', 'active')
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
                                <form action="{{ route('backend.customer.store') }}" method="POST" enctype="multipart/form-data" class="row g-3">
                                    @csrf

                                    <input type="hidden" id="customer_id" name="customer_id" @isset($data) value="{{ $data->id }}" @endisset>

                                    <div class="col-12 col-md-4">
                                        <label class="form-label" for="modalEditUserFirstName">Nama</label><span style="color: red;"> *</span>
                                        <input type="text" id="name" name="name" class="form-control" placeholder="Ketik nama" required @isset($data) value="{{ $data->name }}" @endisset/>
                                    </div>
        
                                    <div class="col-12 col-md-4">
                                        <label class="form-label" for="modalEditUserLastName">Kontak</label><span style="color: red;"> *</span>
                                        <input type="text" id="contact" name="contact" class="form-control" placeholder="Ketik Kontak" required @isset($data) value="{{ $data->contact }}" @endisset/>
                                    </div>
        
                                    <div class="col-12 col-md-4">
                                        <label class="form-label" for="modalEditUserName">Email</label><span style="color: red;"> *</span>
                                        <input type="email" id="email" name="email" class="form-control" placeholder="Ketik Email" required @isset($data) value="{{ $data->email }}" @endisset/>
                                    </div>
        
                                    <div class="col-12 col-md-4">
                                        <label class="form-label" for="province_id">Provinsi</label><span style="color: red;"> *</span>
                                        <select id="province_id" name="province_id" class="select2 form-select" aria-label="Default select example" required>
                                            <option>-- Pilih Provinsi --</option>
        
                                            @foreach ($provinces as $item) 
                                                <option @isset($data) @if($item->id == $data->province_id) selected  @endif @endisset value="{{ $item->id }}" >{{ $item->name }}</option>
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
                                        <input type="text" id="post_code" name="post_code" class="form-control" placeholder="Ketik Kode Pos" @isset($data) value="{{ $data->post_code }}" @endisset/>
                                    </div>
        
                                    <div class="col-12 col-md-6">
                                        <label class="form-label" for="address">Alamat</label>
                                        <textarea id="address" rows="4" name="address" class="form-control">@isset($data) {{ $data->address }} @endisset</textarea>
                                    </div>
                                   
                                    <div class="col-12" style="display: flex; justify-content: flex-end; margin-top: 5%;">
                                        <a href="{{ route('backend.customer.index') }}" class="btn btn-label-secondary">
                                            Batal
                                        </a>
        
                                        <button type="submit" class="btn btn-warning me-sm-3 me-1">Buat</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            $('#province_id').change(function() {
                var province_id = $(this).val();
                var getCityUrl = '{{ route("backend.customer.get_city", ":province_id") }}';
                var url = getCityUrl.replace(':province_id', province_id);
                var customer_id = $('#customer_id').val();
                url += '?customer_id=' + customer_id;

                if (province_id) {
                    $('#loading').show();

                    $.ajax({
                        url: url,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $('#city_id').empty().trigger('change');
                            $('#city_id').append('<option>-- Pilih City --</option>');

                            $.each(data.data, function(key, value) {
                                if (data.selected_city !== 0) {
                                    if (data.selected_city == value.id) {
                                        $('#city_id').append('<option value="' + value.id + '" selected>' + value.name + '</option>');
                                    }else{
                                        $('#city_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                                    }
                                }else{
                                    $('#city_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                                }
                            });

                            $('#city_id').trigger('change');
                            if (data.selected_city == 0) {
                            }

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
                var getDistrictUrl = '{{ route("backend.customer.get_district", ":district_id") }}';
                var url = getDistrictUrl.replace(':district_id', district_id);
                var customer_id = $('#customer_id').val();
                url += '?customer_id=' + customer_id;

                if (district_id) {
                    $('#loading').show();

                    $.ajax({
                        url: url,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $('#district_id').empty().trigger('change');
                            $('#district_id').append('<option>-- Pilih Kecamatan --</option>');

                            $.each(data.data, function(key, value) {
                                if (data.selected_district !== 0) {
                                    if (data.selected_district == value.id) {
                                        $('#district_id').append('<option value="' + value.id + '" selected>' + value.name + '</option>');
                                    }else{
                                        $('#district_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                                    }
                                }else{
                                    $('#district_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                                }
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

            if ($('#province_id').val()) {
                $('#province_id').trigger('change');
            }
        });
       
    </script>
@endpush