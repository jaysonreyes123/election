 <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav"> 
      <li class="nav-item">
        <a class="nav-link {{$menu == "Dashboard" ? "" : "collapsed"}}" href="/dashboard">
          <span>Dashboard</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link {{$menu == "Dashboard Maintenance" ? "" : "collapsed"}}" href="/dashboard/maintenance">
          <span>Dashboard Maintenance</span>
        </a>
      </li>
      <li class="nav-heading">Module</li>
        @foreach (\App\Models\Tab::where('status',1)->orderBy('sort','asc')->get() as $module )
          <li class="nav-item">
            <a class="nav-link {{$menu == $module->name ? "" : "collapsed"}}" href="/view/module/{{$module->name}}">
              <span>{{$module->label}}</span>
            </a>
          </li>
        @endforeach
      {{-- <li class="nav-item">
        <a class="nav-link {{$menu == "module" ? "" : "collapsed"}}" href="/tab">
          <span>Voters</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link {{$menu == "module" ? "" : "collapsed"}}" href="/tab">
          <span>Barangays</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link {{$menu == "module" ? "" : "collapsed"}}" href="/tab">
          <span>Precinct</span>
        </a>
      </li> --}}

      <li class="nav-heading">Maps</li>
      <li class="nav-item">
        <a class="nav-link {{$menu == "Barangay Map" ? "" : "collapsed"}}" href="/barangay-map">
          <span>Barangay Map</span>
        </a>
      </li>

      @if (Auth::user()->role == 1)
      <li class="nav-heading">Admin</li>
      <li class="nav-item">
        <a class="nav-link
          @if ($menu != "User" && $menu != "Role" && $menu != "User Privileges")
            collapsed
          @endif
        " data-bs-target="#user-management" data-bs-toggle="collapse" href="#">
          <span>User Management</span>
          <i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="user-management" class="nav-content
        @if ($menu != "User" && $menu != "Role" && $menu != "User Privileges")
            collapse
        @endif
        " data-bs-parent="#sidebar-nav">
          <li >
            <a class="{{$menu == "User" ? "nav-link active " : "" }}" href="/users">
              <span>Users</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="{{$menu == "Role" ? "nav-link  active" : "" }}" href="/roles">
              <span>Roles</span>
            </a>
          </li>
          <li>
            <a class="{{$menu == "User Privileges" ? "nav-link active " : "" }}" href="/user-privileges">
              <span>User Privileges</span>
            </a>
          </li>
        </ul>
      </li>




      <li class="nav-item">
        <a class="nav-link
          @if ($menu != "Module" && $menu != "Fields")
            collapsed
          @endif
        " data-bs-target="#module-management" data-bs-toggle="collapse" href="#">
          <span>Module Management</span>
          <i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="module-management" class="nav-content
        @if ($menu != "Module" && $menu != "Fields")
            collapse
        @endif
        " data-bs-parent="#sidebar-nav">
          <li >
            <a class="{{$menu == "Module" ? "nav-link active " : "" }}" href="/tab">
              <span>Module</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="{{$menu == "Fields" ? "nav-link  active" : "" }}" href="/field#showViewLayout">
              <span>Fields</span>
            </a>
          </li>
        </ul>
      </li>

      <li class="nav-item">
        <a class="nav-link {{$menu == "Report" ? "" : "collapsed"}}" href="/report">
          <span>Report</span>
        </a>
      </li>
      @endif
    </ul>

  </aside><!-- End Sidebar-->