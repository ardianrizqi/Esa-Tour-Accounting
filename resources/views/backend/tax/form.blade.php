@extends('layouts.backend.app')
@section('tax', 'active')
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
                            <form action="{{ route('backend.tax.store') }}" method="POST"
                                enctype="multipart/form-data" class="row g-3">
                                @csrf

                                <input type="hidden" id="tax_id" name="tax_id" @isset($data) value="{{ $data->id }}"
                                    @endisset>

                                <div class="col-12 col-md-6">
                                    <label class="form-label" for="modalEditUserFirstName">Tanggal</label><span
                                        style="color: red;"> *</span>
                                    <input type="date" id="date" name="date" class="form-control"
                                        required @isset($data) value="{{ $data->date }}" @endisset />
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label" for="account_id">Dari Invoice</label><span style="color: red;">*</span>
                                    <select id="invoice_id" name="invoice_id" class="select2 form-select"
                                        aria-label="Default select example" required>
                                        <option>-- Pilih Invoice --</option>

                                        @foreach($invoice as $item)
                                            <option @isset($data) @if($item->id == $data->invoice_id) selected @endif @endisset value="{{ $item->id }}" >{{ $item->invoice_number }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label" for="modalEditUserFirstName">Nama Pajak</label><span
                                        style="color: red;"> *</span>
                                    <input type="text" id="name" name="name" class="form-control"
                                        placeholder="Ketik Nama Pajak" required @isset($data) value="{{ $data->name }}" @endisset/>
                                </div>
    

                                <div class="col-12 col-md-6">
                                    <label for="select2Basic" class="form-label">Nominal</label><span style="color: red;">*</span>
                                    <div class="input-group">
                                        <span class="input-group-text" id="basic-addon11">Rp.</span>
                                        <input id="nominal" type="text" class="form-control" placeholder="" aria-label="" aria-describedby="basic-addon11"  name="nominal" @isset($data) value="{{ $data->nominal }}" @endisset required/>
                                    </div>
                                </div>

                                <div class="col-12" style="display: flex; justify-content: flex-end; margin-top: 5%;">
                                    <a href="{{ route('backend.tax.index') }}"
                                        class="btn btn-label-secondary">
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

    <div class="modal fade" id="category_expense" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-simple modal-edit-user">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-4">
                        <h3 class="mb-2">Tambah Kategori</h3>
                        {{-- <p class="text-muted">Updating user details will receive a privacy audit.</p> --}}
                    </div>
                    <form id="category_form" class="row g-3">
                        <div class="col-12 col-md-12">
                            <label class="form-label" for="modalEditUserFirstName">Nama</label><span style="color: red;"> *</span>
                            <input type="text" id="category_name" name="category_name" class="form-control" placeholder="Ketik nama" required/>
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
</div>
@endsection

@push('js')
    <script>
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#category_form').on('submit', function(event) {
                event.preventDefault();

                var formData = $(this).serialize();
                $('#loading').show();
            

                $.ajax({
                    url: 'category-store',
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(data) {
                        if (data.status == 200) {
                            $('#category_expense_id').empty().trigger('change');
                            $('#category_expense_id').append('<option>-- Pilih Kategori --</option>');

                            $.each(data.data, function(key, value) {
                                // console.log(value);
                                $('#category_expense_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                            });

                            $('#category_expense_id').trigger('change');

                            Swal.fire('Sukses !!', data.message, 'success');
                        }else{
                            Swal.fire('Gagal !!', data.message, 'error');
                        }
                    
                        $('#loading').hide();

                        $('#category_expense').find('.btn-close').trigger('click');
                    },
                    error: function(xhr, status, error) {
                    
                    }
                });
            });
        });

    </script>
@endpush
