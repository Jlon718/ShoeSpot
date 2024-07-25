<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">

        <!--product-->
        <li class="nav-item">
            <a class="nav-link" href="/products">
                <i class="mdi mdi-package menu-icon"></i>
                <span class="menu-title">Product</span>
                {{-- <i class="menu-arrow"></i> --}}
            </a>
            {{-- <div class="collapse" id="productMenu">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="">wala pa</a></li>
                    <li class="nav-item"> <a class="nav-link" href="pages/ui-features/typography.html">wala pa</a></li>
                </ul>
            </div> --}}
        </li>
        <!--Brand-->

        <li class="nav-item">
            <a class="nav-link" href="/brands">
                <i class="mdi mdi-tag menu-icon"></i>
                <span class="menu-title">Brand</span>
            </a>
        </li>

        {{-- <li class="nav-item">
            <a class="nav-link" href="/stocks">
                <i class="mdi mdi-tag menu-icon"></i>
                <span class="menu-title">Stock</span>
            </a>
        </li> --}}

        <li class="nav-item">
            <a class="nav-link" href="/suppliers">
                <i class="mdi mdi-emoticon menu-icon"></i>
                <span class="menu-title">Suppliers</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="/users">
                <i class="mdi mdi-emoticon menu-icon"></i>
                <span class="menu-title">Users</span>
            </a>
        </li>
        {{-- user list --}}
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
                <i class="mdi mdi-chart-pie menu-icon"></i>
                <span class="menu-title">Charts</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="auth">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="/chart1">
                            <i class="mdi mdi-chart-pie menu-icon"></i>
                            <span class="menu-title">Customer Chart</span>
                        </a>
                    </li>
            
                    <li class="nav-item">
                        <a class="nav-link" href="/chart2">
                            <i class="mdi mdi-chart-pie menu-icon"></i>
                            <span class="menu-title">Monthly Sales Chart</span>
                        </a>
                    </li>
            
                    <li class="nav-item">
                        <a class="nav-link" href="/chart3">
                            <i class="mdi mdi-chart-pie menu-icon"></i>
                            <span class="menu-title">Product Charts</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>
    </ul>
</nav>
