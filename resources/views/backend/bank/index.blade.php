@extends('layouts.backend.app')
@section('bank', 'active')
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="py-3 mb-4"><span class="text-muted fw-light"></span>{{ $title }}</h4>

            <!-- DataTable with Buttons -->
            <div class="card">
                <div style="display: flex; justify-content: flex-end; margin: 3% 3% 0 0;">
                    <a href="{{ route('backend.bank.create') }}" type="button" class="btn btn-primary">
                        <i class="ti ti-plus me-sm-1"></i>Tambah
                    </a>
                </div>
                
                <div class="card-datatable table-responsive pt-0">
                    <table id="bank-table" class="datatables-basic table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Bank</th>
                                <th>Pemasukan</th>
                                <th>Pengeluaran</th>
                                <th>Saldo</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/js/backend/bank.js') }}"></script>
    <script>
        var deleteUrl = '{{ route("backend.bank.destroy", ":id") }}';
    </script>
@endpush

