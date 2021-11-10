<div class="d-flex align-items-stretch">
    <div class="sidebar py-3" id="sidebar">
      <h6 class="sidebar-heading">Main</h6>
      <ul class="list-unstyled">
          @if(Request::segment(1)=="dashboard")
          <li class="sidebar-list-item"><a class="sidebar-link text-muted active" href="/dashboard">
                    <svg class="svg-icon svg-icon-md me-3">
                      <use xlink:href="/assets/icons/orion-svg-sprite.57a86639.svg#real-estate-1"> </use>
                    </svg><span class="sidebar-link-title">Dashboard</span></a></li>
          @else
          <li class="sidebar-list-item"><a class="sidebar-link text-muted" href="/dashboard">
                    <svg class="svg-icon svg-icon-md me-3">
                      <use xlink:href="/assets/icons/orion-svg-sprite.57a86639.svg#real-estate-1"> </use>
                    </svg><span class="sidebar-link-title">Dashboard</span></a></li>
          @endif

          @if(Request::segment(1)=="holding")
          <li class="sidebar-list-item"><a class="sidebar-link text-muted active" href="/holding">
                    <svg class="svg-icon svg-icon-md me-3">
                      <use xlink:href="/assets/icons/orion-svg-sprite.57a86639.svg#security-shield-1"> </use>
                    </svg><span class="sidebar-link-title">Holding</span></a></li>
          @else
          <li class="sidebar-list-item"><a class="sidebar-link text-muted" href="/holding">
                    <svg class="svg-icon svg-icon-md me-3">
                      <use xlink:href="/assets/icons/orion-svg-sprite.57a86639.svg#security-shield-1"> </use>
                    </svg><span class="sidebar-link-title">Holding</span></a></li>
          @endif

          @if(Request::segment(1)=="market-depth")
          <li class="sidebar-list-item"><a class="sidebar-link text-muted active" href="/market-depth">
                    <svg class="svg-icon svg-icon-md me-3">
                      <use xlink:href="/assets/icons/orion-svg-sprite.57a86639.svg#more-1"> </use>
                    </svg><span class="sidebar-link-title">Market Depth</span></a></li>
          @else
          <li class="sidebar-list-item"><a class="sidebar-link text-muted" href="/market-depth">
                    <svg class="svg-icon svg-icon-md me-3">
                      <use xlink:href="/assets/icons/orion-svg-sprite.57a86639.svg#more-1"> </use>
                    </svg><span class="sidebar-link-title">Market Depth</span></a></li>
          @endif

          
          @if(Request::segment(1)=="portfolio")
          <li class="sidebar-list-item"><a class="sidebar-link text-muted active" href="/portfolio">
                    <svg class="svg-icon svg-icon-md me-3">
                      <use xlink:href="/assets/icons/orion-svg-sprite.57a86639.svg#portfolio-grid-1"> </use>
                    </svg><span class="sidebar-link-title">Portfolio</span></a></li>
          @else
          <li class="sidebar-list-item"><a class="sidebar-link text-muted" href="/portfolio">
                    <svg class="svg-icon svg-icon-md me-3">
                      <use xlink:href="/assets/icons/orion-svg-sprite.57a86639.svg#portfolio-grid-1"> </use>
                    </svg><span class="sidebar-link-title">Portfolio</span></a></li>
          @endif
          
            @if(Request::segment(1)=="trading")
            <li class="sidebar-list-item"><a class="sidebar-link text-muted active" href="#" data-bs-target="#cmsDropdown1" role="button" aria-expanded="false" data-bs-toggle="collapse"> 
                    <svg class="svg-icon svg-icon-md me-3">
                      <use xlink:href="/assets/icons/orion-svg-sprite.57a86639.svg#user-1"> </use>
                    </svg><span class="sidebar-link-title">Trading Account </span></a>
              <ul class="sidebar-menu list-unstyled collapse " id="cmsDropdown1">
                <li class="sidebar-list-item"><a class="sidebar-link text-muted" href="/trading/list">Account List</a></li>
                <li class="sidebar-list-item"><a class="sidebar-link text-muted" href="/trading/create">Create New</a></li>
              </ul>
            </li>
            @else
            <li class="sidebar-list-item"><a class="sidebar-link text-muted " href="#" data-bs-target="#cmsDropdown1" role="button" aria-expanded="false" data-bs-toggle="collapse"> 
                    <svg class="svg-icon svg-icon-md me-3">
                      <use xlink:href="/assets/icons/orion-svg-sprite.57a86639.svg#user-1"> </use>
                    </svg><span class="sidebar-link-title">Trading Account </span></a>
              <ul class="sidebar-menu list-unstyled collapse " id="cmsDropdown1">
                <li class="sidebar-list-item"><a class="sidebar-link text-muted" href="/trading/list">Account List</a></li>
                <li class="sidebar-list-item"><a class="sidebar-link text-muted" href="/trading/create">Create New</a></li>
              </ul>
            </li>
            @endif
            @if(Request::segment(1)=="orders" || Request::segment(1)=="trade")
            <li class="sidebar-list-item"><a class="sidebar-link text-muted active" href="#" data-bs-target="#cmsDropdown2" role="button" aria-expanded="false" data-bs-toggle="collapse"> 
                    <svg class="svg-icon svg-icon-md me-3">
                      <use xlink:href="/assets/icons/orion-svg-sprite.57a86639.svg#cart-1"> </use>
                    </svg><span class="sidebar-link-title">Trading </span></a>
              <ul class="sidebar-menu list-unstyled collapse " id="cmsDropdown2">
                <li class="sidebar-list-item"><a class="sidebar-link text-muted" href="/trade/create">Trade</a></li>
                <li class="sidebar-list-item"><a class="sidebar-link text-muted" href="/orders/list">Orders</a></li>
                <li class="sidebar-list-item"><a class="sidebar-link text-muted" href="/orders/position">Position</a></li>
              </ul>
            </li>
            @else
            <li class="sidebar-list-item"><a class="sidebar-link text-muted " href="#" data-bs-target="#cmsDropdown2" role="button" aria-expanded="false" data-bs-toggle="collapse"> 
                    <svg class="svg-icon svg-icon-md me-3">
                      <use xlink:href="/assets/icons/orion-svg-sprite.57a86639.svg#cart-1"> </use>
                    </svg><span class="sidebar-link-title">Trading </span></a>
              <ul class="sidebar-menu list-unstyled collapse " id="cmsDropdown2">
                <li class="sidebar-list-item"><a class="sidebar-link text-muted" href="/trade/create">Trade</a></li>
                <li class="sidebar-list-item"><a class="sidebar-link text-muted" href="/orders/list">Orders</a></li>
                <li class="sidebar-list-item"><a class="sidebar-link text-muted" href="/orders/position">Position</a></li>
              </ul>
            </li>
            @endif
            @if(Request::segment(1)=="group" || Request::segment(1)=="general-setting")
            <li class="sidebar-list-item"><a class="sidebar-link text-muted active" href="#" data-bs-target="#cmsDropdown3" role="button" aria-expanded="false" data-bs-toggle="collapse"> 
                    <svg class="svg-icon svg-icon-md me-3">
                      <use xlink:href="/assets/icons/orion-svg-sprite.57a86639.svg#gear-1"> </use>
                    </svg><span class="sidebar-link-title">Setting </span></a>
              <ul class="sidebar-menu list-unstyled collapse " id="cmsDropdown3">
                <li class="sidebar-list-item"><a class="sidebar-link text-muted" href="/general-setting">General</a></li>
                <li class="sidebar-list-item"><a class="sidebar-link text-muted" href="/group">Group Setting</a></li>
              </ul>
            </li>
            @else
            
            <li class="sidebar-list-item"><a class="sidebar-link text-muted" href="#" data-bs-target="#cmsDropdown3" role="button" aria-expanded="false" data-bs-toggle="collapse"> 
                    <svg class="svg-icon svg-icon-md me-3">
                      <use xlink:href="/assets/icons/orion-svg-sprite.57a86639.svg#gear-1"> </use>
                    </svg><span class="sidebar-link-title">Setting </span></a>
              <ul class="sidebar-menu list-unstyled collapse " id="cmsDropdown3">
                <li class="sidebar-list-item"><a class="sidebar-link text-muted" href="/general-setting">General</a></li>
                <li class="sidebar-list-item"><a class="sidebar-link text-muted" href="/group">Group Setting</a></li>
              </ul>
            </li>
            @endif
            
      </ul>
    </div>