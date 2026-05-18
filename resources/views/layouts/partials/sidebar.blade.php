<nav id="sidebarMenu" class="collapse d-lg-block sidebar collapse bg-white shadow-sm border-end">
  <div class="position-sticky">
    <div class="list-group list-group-flush mx-3">
      <!-- App Brand -->
      <div class="text-center pb-4 mb-4 border-bottom">
        <h4 class="fw-bold mb-1 text-primary text-uppercase tracking-wider">QueueBill</h4>
      </div>

      <a href="{{ route('home') }}"
        class="list-group-item list-group-item-action py-2 ripple {{ request()->routeIs('home') ? 'active rounded shadow-sm bg-primary text-white border-0' : 'border-0' }}"
        aria-current="true">
        <i class="fas fa-tachometer-alt fa-fw me-3"></i><span>Dashboard</span>
      </a>

      <a href="{{ route('companies.index') }}"
        class="list-group-item list-group-item-action py-2 ripple {{ request()->routeIs('companies.*') ? 'active rounded shadow-sm bg-primary text-white border-0' : 'border-0' }}">
        <i class="fas fa-building fa-fw me-3"></i><span>Companies</span>
      </a>

      <a href="{{ route('templates.index') }}"
        class="list-group-item list-group-item-action py-2 ripple {{ request()->routeIs('templates.*') ? 'active rounded shadow-sm bg-primary text-white border-0' : 'border-0' }}">
        <i class="fas fa-file-invoice fa-fw me-3"></i><span>Invoice Templates</span>
      </a>

      <a href="{{ route('services.index') }}"
        class="list-group-item list-group-item-action py-2 ripple {{ request()->routeIs('services.*') ? 'active rounded shadow-sm bg-primary text-white border-0' : 'border-0' }}">
        <i class="fas fa-redo fa-fw me-3"></i><span>Recurring Services</span>
      </a>

      <a href="{{ route('invoices.index') }}"
        class="list-group-item list-group-item-action py-2 ripple {{ request()->routeIs('invoices.*') ? 'active rounded shadow-sm bg-primary text-white border-0' : 'border-0' }}">
        <i class="fas fa-receipt fa-fw me-3"></i><span>Generated Invoices</span>
      </a>

      <a href="{{ route('logs.index') }}"
        class="list-group-item list-group-item-action py-2 ripple {{ request()->routeIs('logs.*') ? 'active rounded shadow-sm bg-primary text-white border-0' : 'border-0' }}">
        <i class="fas fa-history fa-fw me-3"></i><span>Activity & Logs</span>
      </a>

      <a href="{{ route('settings') }}"
        class="list-group-item list-group-item-action py-2 ripple {{ request()->routeIs('settings') ? 'active rounded shadow-sm bg-primary text-white border-0' : 'border-0' }}">
        <i class="fas fa-cog fa-fw me-3"></i><span>Settings</span>
      </a>
    </div>
  </div>
</nav>