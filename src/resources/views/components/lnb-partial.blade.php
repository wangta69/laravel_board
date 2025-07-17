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
          <a href="{{ route('bbs.admin.index') }}">환경 설정1</a>
        </li>
      </ul>
    </li>
