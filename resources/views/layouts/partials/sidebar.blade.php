<nav id="sidebarMenu" class="collapse d-lg-block sidebar collapse bg-white shadow-sm border-end">
  <div class="position-sticky">
    <div class="list-group list-group-flush mx-3">
      <!-- App Brand -->
      <div class="text-center pb-4 mb-4 border-bottom">
        @if(auth()->user()->company_logo)
          <div class="mb-2">
            <img src="{{ asset(auth()->user()->company_logo) }}" alt="Logo" style="max-height: 50px; max-width: 180px; object-fit: contain;">
          </div>
        @else
          <h4 class="fw-bold mb-1 text-primary text-uppercase tracking-wider">QueueBill</h4>
        @endif
      </div>

      <a href="{{ route('home') }}"
        class="list-group-item list-group-item-action d-flex align-items-center py-2 ripple {{ request()->routeIs('home') ? 'active rounded-3 bg-primary-soft text-primary border-0 fw-bold shadow-none' : 'border-0 text-secondary' }}"
        aria-current="true">
        <div class="d-flex align-items-center justify-content-center me-3" style="width: 24px;">
            <i class="fas fa-layer-group fs-6"></i>
        </div>
        <span class="fs-7 tracking-wide">Dashboard</span>
      </a>

      <a href="{{ route('companies.index') }}"
        class="list-group-item list-group-item-action d-flex align-items-center py-2 ripple {{ request()->routeIs('companies.*') ? 'active rounded-3 bg-primary-soft text-primary border-0 fw-bold shadow-none' : 'border-0 text-secondary' }}">
        <div class="d-flex align-items-center justify-content-center me-3" style="width: 24px;">
            <i class="far fa-building fs-6"></i>
        </div>
        <span class="fs-7 tracking-wide">Companies</span>
      </a>

      <a href="{{ route('templates.index') }}"
        class="list-group-item list-group-item-action d-flex align-items-center py-2 ripple {{ request()->routeIs('templates.*') ? 'active rounded-3 bg-primary-soft text-primary border-0 fw-bold shadow-none' : 'border-0 text-secondary' }}">
        <div class="d-flex align-items-center justify-content-center me-3" style="width: 24px;">
            <i class="far fa-file-code fs-6"></i>
        </div>
        <span class="fs-7 tracking-wide">Invoice Templates</span>
      </a>

      <a href="{{ route('services.index') }}"
        class="list-group-item list-group-item-action d-flex align-items-center py-2 ripple {{ request()->routeIs('services.*') ? 'active rounded-3 bg-primary-soft text-primary border-0 fw-bold shadow-none' : 'border-0 text-secondary' }}">
        <div class="d-flex align-items-center justify-content-center me-3" style="width: 24px;">
            <i class="fas fa-sync-alt fs-6"></i>
        </div>
        <span class="fs-7 tracking-wide">Recurring Services</span>
      </a>

      <a href="{{ route('invoices.index') }}"
        class="list-group-item list-group-item-action d-flex align-items-center py-2 ripple {{ request()->routeIs('invoices.*') ? 'active rounded-3 bg-primary-soft text-primary border-0 fw-bold shadow-none' : 'border-0 text-secondary' }}">
        <div class="d-flex align-items-center justify-content-center me-3" style="width: 24px;">
            <i class="far fa-file-alt fs-6"></i>
        </div>
        <span class="fs-7 tracking-wide">Generated Invoices</span>
      </a>

      <a href="{{ route('logs.index') }}"
        class="list-group-item list-group-item-action d-flex align-items-center py-2 ripple {{ request()->routeIs('logs.*') ? 'active rounded-3 bg-primary-soft text-primary border-0 fw-bold shadow-none' : 'border-0 text-secondary' }}">
        <div class="d-flex align-items-center justify-content-center me-3" style="width: 24px;">
            <i class="fas fa-history fs-6"></i>
        </div>
        <span class="fs-7 tracking-wide">Activity & Logs</span>
      </a>

      <a href="{{ route('settings') }}"
        class="list-group-item list-group-item-action d-flex align-items-center py-2 ripple {{ request()->routeIs('settings') ? 'active rounded-3 bg-primary-soft text-primary border-0 fw-bold shadow-none' : 'border-0 text-secondary' }}">
        <div class="d-flex align-items-center justify-content-center me-3" style="width: 24px;">
            <i class="fas fa-sliders-h fs-6"></i>
        </div>
        <span class="fs-7 tracking-wide">Settings</span>
      </a>
    </div>
  </div>
</nav>