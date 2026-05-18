<nav id="main-navbar" class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-sm border-bottom">
  <div class="container-fluid">
    <!-- Toggle button -->
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
      <i class="fas fa-bars text-secondary"></i>
    </button>

    <!-- Brand -->
    <a class="navbar-brand d-lg-none fw-bold text-primary" href="{{ route('home') }}">
      QueueBill
    </a>

    <!-- Title / Subtitle -->
    <div class="d-none d-md-flex align-items-center ms-2">
      <span class="text-secondary fw-semibold text-truncate fs-6">
        @yield('page_title', 'Administrative Panel')
      </span>
    </div>

    <!-- Right elements -->
    <ul class="navbar-nav ms-auto d-flex flex-row align-items-center">
      <!-- User Badge and Logout -->
      @auth
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <div class="avatar-ring d-inline-flex justify-content-center align-items-center bg-primary-soft text-primary rounded-circle me-2" style="width: 32px; height: 32px; font-size: 0.85rem; font-weight: 600;">
            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
          </div>
          <span class="text-dark fw-medium d-none d-sm-inline">{{ auth()->user()->name }}</span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" aria-labelledby="navbarDropdownMenuLink">
          <li>
            <a class="dropdown-item py-2" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
              <i class="fas fa-sign-out-alt fa-fw me-2 text-danger"></i>Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
              @csrf
            </form>
          </li>
        </ul>
      </li>
      @endauth
    </ul>
  </div>
</nav>
