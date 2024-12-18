<nav id="sidebar">
  <div class="sidebar-header">
    <h3><a href="{{ route('auth.admin.dashboard') }}">OnStory</a></h3>
    <strong>ON</strong>
  </div>

  <ul class="list-unstyled components" id="navbar-sidebar">
    <li>
      <a href="#bbs-sub-menu" data-bs-toggle="collapse" 
        aria-expanded="{{ request()->routeIs(['bbs*']) ? 'true' : 'false' }}"
        class="dropdown-toggle">
          <i class="fas fa-copy"></i>
          BBS
      </a>

      
      <ul class="collapse list-unstyled {{ request()->routeIs(['bbs.admin*']) ? 'show' : '' }}" id="bbs-sub-menu">
        
      <x-bbs-lists />
        <!-- <li class="{{ request()->is('bbs.admin.comments*') ? 'current-page' : '' }}">
          <a href="{{ route('bbs.admin.comments', ['story']) }}">comment(story)</a>
        </li> -->
        <hr/>
        <li class="{{ request()->routeIs(['bbs.admin.index']) ? 'current-page' : '' }}">
          <a href="{{ route('bbs.admin.index') }}">환경 설정</a>
        </li>
      </ul>
    </li>
  </ul>
</nav>
