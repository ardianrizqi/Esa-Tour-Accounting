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
                                    <label for="select2Basic" class="form-label">Pelanggan</label> 
                                    <button style="width: auto; padding: 5px 10px; font-size: 12px; float:right; margin-bottom: 2%;" type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editUser">
                                        Add (+)
                                    </button>
                                    <select id="select2Basic" class="select2 form-select form-select-lg" data-allow-clear="true">
                                        <option value="AK">Alaska</option>
                                        <option value="HI">Hawaii</option>
                                        <option value="CA">California</option>
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
                            {{-- <div class="row">
                                <hr>
                                <div style="display: flex; justify-content: flex-end;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Total Harga Jual</label> 
                                        </div>

                                        <div class="col-md-6">
                                            <label for="select2Basic" class="form-label">Rp. 5.000.000</label> 
                                        </div>
                                    </div>
                                </div>
                        
                                <hr>
                                <div style="display: flex; justify-content: flex-end;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Total Modal</label> 
                                        </div>

                                        <div class="col-md-6">
                                            <label for="select2Basic" class="form-label">Rp. 5.000.000</label> 
                                        </div>
                                    </div>
                                </div>

                                <hr>
                                <div class="col-md-12 mb-4">
                                    <div class="row" style="float: right;">
                                        <div class="col-md-6">
                                            <label for="select2Basic" class="form-label">Total Harga Jual</label> 
                                        </div>

                                        <div class="col-md-6">
                                            <label for="select2Basic" class="form-label">Rp. 5.000.000</label> 
                                        </div>
                                    </div>
                                </div>
                            </div> --}}

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
                        <form id="editUserForm" class="row g-3" onsubmit="return false">
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="modalEditUserFirstName">First Name</label>
                                <input
                                type="text"
                                id="modalEditUserFirstName"
                                name="modalEditUserFirstName"
                                class="form-control"
                                placeholder="John" />
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="modalEditUserLastName">Last Name</label>
                                <input
                                type="text"
                                id="modalEditUserLastName"
                                name="modalEditUserLastName"
                                class="form-control"
                                placeholder="Doe" />
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="modalEditUserName">Username</label>
                                <input
                                type="text"
                                id="modalEditUserName"
                                name="modalEditUserName"
                                class="form-control"
                                placeholder="john.doe.007" />
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="modalEditUserEmail">Email</label>
                                <input
                                type="text"
                                id="modalEditUserEmail"
                                name="modalEditUserEmail"
                                class="form-control"
                                placeholder="example@domain.com" />
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="modalEditUserStatus">Status</label>
                                <select
                                id="modalEditUserStatus"
                                name="modalEditUserStatus"
                                class="select2 form-select"
                                aria-label="Default select example">
                                <option selected>Status</option>
                                <option value="1">Active</option>
                                <option value="2">Inactive</option>
                                <option value="3">Suspended</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="modalEditTaxID">Tax ID</label>
                                <input
                                type="text"
                                id="modalEditTaxID"
                                name="modalEditTaxID"
                                class="form-control modal-edit-tax-id"
                                placeholder="123 456 7890" />
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="modalEditUserPhone">Phone Number</label>
                                <div class="input-group">
                                <span class="input-group-text">US (+1)</span>
                                <input
                                    type="text"
                                    id="modalEditUserPhone"
                                    name="modalEditUserPhone"
                                    class="form-control phone-number-mask"
                                    placeholder="202 555 0111" />
                                </div>
                            </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="modalEditUserLanguage">Language</label>
                            <select
                            id="modalEditUserLanguage"
                            name="modalEditUserLanguage"
                            class="select2 form-select"
                            multiple>
                            <option value="">Select</option>
                            <option value="english" selected>English</option>
                            <option value="spanish">Spanish</option>
                            <option value="french">French</option>
                            <option value="german">German</option>
                            <option value="dutch">Dutch</option>
                            <option value="hebrew">Hebrew</option>
                            <option value="sanskrit">Sanskrit</option>
                            <option value="hindi">Hindi</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="modalEditUserCountry">Country</label>
                            <select
                            id="modalEditUserCountry"
                            name="modalEditUserCountry"
                            class="select2 form-select"
                            data-allow-clear="true">
                            <option value="">Select</option>
                            <option value="Australia">Australia</option>
                            <option value="Bangladesh">Bangladesh</option>
                            <option value="Belarus">Belarus</option>
                            <option value="Brazil">Brazil</option>
                            <option value="Canada">Canada</option>
                            <option value="China">China</option>
                            <option value="France">France</option>
                            <option value="Germany">Germany</option>
                            <option value="India">India</option>
                            <option value="Indonesia">Indonesia</option>
                            <option value="Israel">Israel</option>
                            <option value="Italy">Italy</option>
                            <option value="Japan">Japan</option>
                            <option value="Korea">Korea, Republic of</option>
                            <option value="Mexico">Mexico</option>
                            <option value="Philippines">Philippines</option>
                            <option value="Russia">Russian Federation</option>
                            <option value="South Africa">South Africa</option>
                            <option value="Thailand">Thailand</option>
                            <option value="Turkey">Turkey</option>
                            <option value="Ukraine">Ukraine</option>
                            <option value="United Arab Emirates">United Arab Emirates</option>
                            <option value="United Kingdom">United Kingdom</option>
                            <option value="United States">United States</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="switch">
                            <input type="checkbox" class="switch-input" />
                            <span class="switch-toggle-slider">
                                <span class="switch-on"></span>
                                <span class="switch-off"></span>
                            </span>
                            <span class="switch-label">Use as a billing address?</span>
                            </label>
                        </div>
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
                            <button
                            type="reset"
                            class="btn btn-label-secondary"
                            data-bs-dismiss="modal"
                            aria-label="Close">
                            Cancel
                            </button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Edit User Modal -->
    </div>
@endsection