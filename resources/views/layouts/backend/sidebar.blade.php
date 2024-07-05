<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
	<div class="app-brand demo">
	  <a href="#" class="app-brand-link">
		  <img src="{{ asset('assets/img/esa_logo.png') }}" alt="">
		  {{-- <span class="app-brand-text demo menu-text fw-bold">ESA TOUR</span> --}}
	  </a>

	  <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
		<i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
		<i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
	  </a>
	</div>

	<div class="menu-inner-shadow"></div>

	<ul class="menu-inner py-1">
	  	<!-- Dashboards -->
		<li class="menu-item @yield('home')">
			<a href="{{ route('backend.dashboard.index') }}" class="menu-link">
				<i class="menu-icon tf-icons ti ti-smart-home"></i>
				<div data-i18n="Home">Home</div>
			</a>
		</li>

		<li class="menu-header small text-uppercase">
			<span class="menu-header-text" data-i18n="Transactions">Transactions</span>
		</li>

	  <!-- Invoice -->
		<li class="menu-item @yield('invoice')">
			<a href="{{ route('backend.invoice.index') }}" class="menu-link">
				<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-file-invoice"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 7l1 0" />
					<path d="M9 13l6 0" /><path d="M13 17l2 0" />
				</svg>
				<div data-i18n="Invoice" style="margin-left:3%;">Invoice</div>
			</a>
		</li>


	  <!-- Apps & Pages -->
		<li class="menu-header small text-uppercase">
			<span class="menu-header-text" data-i18n="Apps & Pages">Master Data</span>
		</li>

		<li class="menu-item">
			<a href="app-email.html" class="menu-link">
				<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-shopping-cart"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M17 17h-11v-14h-2" /><path d="M6 5l14 1l-1 7h-13" /></svg>
				<div data-i18n="Produk" style="margin-left:3%;">Produk</div>
			</a>
		</li>

		<li class="menu-item @yield('customer')">
			<a href="{{ route('backend.customer.index') }}" class="menu-link">
				<i class="menu-icon tf-icons ti ti-users"></i>
				<div data-i18n="Pelanggan">Pelanggan</div>
			</a>
		</li>
	</ul>
  </aside>