<nav class="navbar fixed-top navbar-expand-lg navbar-light bg-white" style="z-index: 999;min-height: 60px;box-shadow: 0px 0px 20px rgba(1, 41, 112, 0.1)">
  <div class="container-fluid">
    <a class="navbar-brand toggle-sidebar-btn" href="#">Election Monitoring</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      {{-- <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link {{$title == "Dasboard" ? "active font-weight-bold" : "" }}" href="/dashboard">Dashboard</a>
        </li>
        @foreach (\App\Models\Tab::all() as $item )
          <li class="nav-item">
            <a class="nav-link {{$item->name == $title ? "active font-weight-bold" : "" }}" style="text-transform: capitalize" href="/view/module/{{$item->name}}">{{$item->name}}</a>
          </li>
        @endforeach
      </ul> --}}
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              {{ucfirst(auth()->user()->firstname)}}
          </a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink">
            <li>
              <a class="dropdown-item d-flex align-items-center" href="/users/profile">
                <i class="bi bi-gear"></i>
                <span>Profile Settings</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li>
              <a class="dropdown-item d-flex align-items-center" href="/logout">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sign Out</span>
              </a>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>