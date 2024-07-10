<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <!--Dashboard-->
        <li class="nav-item">
            <a class="nav-link" href="{{ url('admin/dashboard/') }}">
                <i class="mdi mdi-home menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        <!--chart-->
        <li class="nav-item">
            <a class="nav-link" href="pages/charts/chartjs.html">
                <i class="mdi mdi-chart-pie menu-icon"></i>
                <span class="menu-title">Charts</span>
            </a>
        </li>

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

        <li class="nav-item">
            <a class="nav-link" href="/stocks">
                <i class="mdi mdi-tag menu-icon"></i>
                <span class="menu-title">Stock</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="pages/icons/mdi.html">
                <i class="mdi mdi-emoticon menu-icon"></i>
                <span class="menu-title">Icons</span>
            </a>
        </li>
        {{-- user list --}}
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
                <i class="mdi mdi-account menu-icon"></i>
                <span class="menu-title">User Pages</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="auth">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="pages/samples/login.html"> Login </a></li>
                    <li class="nav-item"> <a class="nav-link" href="pages/samples/login-2.html"> Login 2 </a></li>
                    <li class="nav-item"> <a class="nav-link" href="pages/samples/register.html"> Register </a></li>
                    <li class="nav-item"> <a class="nav-link" href="pages/samples/register-2.html"> Register 2 </a></li>
                    <li class="nav-item"> <a class="nav-link" href="pages/samples/lock-screen.html"> Lockscreen </a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="documentation/documentation.html">
                <i class="mdi mdi-file-document-box-outline menu-icon"></i>
                <span class="menu-title">Documentation</span>
            </a>
        </li>
    </ul>
</nav>
