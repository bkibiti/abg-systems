@php
    $salesActive = request()->routeIs('sales.index', 'sales.history*');
    $inventoryActive = request()->routeIs('current-stock.*', 'current-stock-filter', 'out-of-stock', 'below-min-level', 'stock-adjustment.*', 'adjustment.search');
    $purchasesActive = request()->routeIs('goods-receiving.*');
    $usersActive = request()->routeIs('roles.*', 'users.*');
    $mastersActive = request()->routeIs('products.*', 'categories.*', 'expense-categories.*', 'suppliers.*', 'reminders.*');
@endphp

<li class="nav-item">
    <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
        <i class="nav-icon bi bi-speedometer2"></i>
        <p>Dashboard</p>
    </a>
</li>

@canany(['View Sales Management', 'Point of Sales', 'View Sales History'])
    <li class="nav-item {{ $salesActive ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ $salesActive ? 'active' : '' }}">
            <i class="nav-icon bi bi-cash-coin"></i>
            <p>Sales <i class="nav-arrow bi bi-chevron-right"></i></p>
        </a>
        <ul class="nav nav-treeview">
            @can('Point of Sales')
                <li class="nav-item">
                    <a href="{{ route('sales.index') }}" class="nav-link {{ request()->routeIs('sales.index') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-circle"></i>
                        <p>New sale</p>
                    </a>
                </li>
            @endcan
            @can('View Sales History')
                <li class="nav-item">
                    <a href="{{ route('sales.history') }}" class="nav-link {{ request()->routeIs('sales.history*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-circle"></i>
                        <p>Sales History</p>
                    </a>
                </li>
            @endcan
        </ul>
    </li>
@endcanany

@canany(['View Inventory Management', 'View Current Stock', 'View Adjustment History', 'Adjust Stock', 'Stock Transfer'])
    <li class="nav-item {{ $inventoryActive ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ $inventoryActive ? 'active' : '' }}">
            <i class="nav-icon bi bi-box-seam"></i>
            <p>Inventory <i class="nav-arrow bi bi-chevron-right"></i></p>
        </a>
        <ul class="nav nav-treeview">
            @can('View Current Stock')
                <li class="nav-item">
                    <a href="{{ route('current-stock.index') }}" class="nav-link {{ request()->routeIs('current-stock.*', 'current-stock-filter', 'out-of-stock', 'below-min-level') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-circle"></i>
                        <p>Current Stock</p>
                    </a>
                </li>
            @endcan
            @can('View Adjustment History')
                <li class="nav-item">
                    <a href="{{ route('stock-adjustment.index') }}" class="nav-link {{ request()->routeIs('stock-adjustment.*', 'adjustment.search') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-circle"></i>
                        <p>Adjustment History</p>
                    </a>
                </li>
            @endcan
        </ul>
    </li>
@endcanany

@canany(['View Purchase Management', 'Receive Goods', 'Purchase History', 'Edit Purchase'])
    <li class="nav-item {{ $purchasesActive ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ $purchasesActive ? 'active' : '' }}">
            <i class="nav-icon bi bi-cart"></i>
            <p>Purchases <i class="nav-arrow bi bi-chevron-right"></i></p>
        </a>
        <ul class="nav nav-treeview">
            @can('Receive Goods')
                <li class="nav-item">
                    <a href="{{ route('goods-receiving.index') }}" class="nav-link {{ request()->routeIs('goods-receiving.index') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-circle"></i>
                        <p>Purchase</p>
                    </a>
                </li>
            @endcan
            @can('Purchase History')
                <li class="nav-item">
                    <a href="{{ route('goods-receiving.history') }}" class="nav-link {{ request()->routeIs('goods-receiving.history', 'goods-receiving.search') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-circle"></i>
                        <p>Purchase History</p>
                    </a>
                </li>
            @endcan
        </ul>
    </li>
@endcanany

@canany(['View Expense Management', 'View Expenses', 'Add Expenses', 'Edit Expenses'])
    <li class="nav-item">
        <a href="{{ route('expense.index') }}" class="nav-link {{ request()->routeIs('expense.*') ? 'active' : '' }}">
            <i class="nav-icon bi bi-wallet2"></i>
            <p>Expenses</p>
        </a>
    </li>
@endcanany

@can('View Reports')
    <li class="nav-item">
        <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
            <i class="nav-icon bi bi-file-earmark-bar-graph"></i>
            <p>Reports</p>
        </a>
    </li>
@endcan

@canany(['View Customers', 'Manage Customers'])
    <li class="nav-item">
        <a href="{{ route('customers.index') }}" class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}">
            <i class="nav-icon bi bi-people"></i>
            <p>Customers</p>
        </a>
    </li>
@endcanany

@canany(['View User Management', 'View Users', 'Manage Users', 'View Roles', 'Manage Roles'])
    <li class="nav-item {{ $usersActive ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ $usersActive ? 'active' : '' }}">
            <i class="nav-icon bi bi-person-gear"></i>
            <p>Users <i class="nav-arrow bi bi-chevron-right"></i></p>
        </a>
        <ul class="nav nav-treeview">
            @can('View Roles')
                <li class="nav-item">
                    <a href="{{ route('roles.index') }}" class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-circle"></i>
                        <p>Roles</p>
                    </a>
                </li>
            @endcan
            @can('View Users')
                <li class="nav-item">
                    <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-circle"></i>
                        <p>Users</p>
                    </a>
                </li>
            @endcan
        </ul>
    </li>
@endcanany

@canany(['View Masters', 'View Products', 'Manage Products', 'View Products Categories', 'Manage Products Categories', 'View Expense Categories', 'Manage Expense Categories', 'View Suppliers', 'Manage Suppliers', 'View Reminders'])
    <li class="nav-item {{ $mastersActive ? 'menu-open' : '' }}">
        <a href="#" class="nav-link {{ $mastersActive ? 'active' : '' }}">
            <i class="nav-icon bi bi-list-ul"></i>
            <p>Masters <i class="nav-arrow bi bi-chevron-right"></i></p>
        </a>
        <ul class="nav nav-treeview">
            @can('View Products')
                <li class="nav-item">
                    <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-circle"></i>
                        <p>Products</p>
                    </a>
                </li>
            @endcan
            @can('View Products Categories')
                <li class="nav-item">
                    <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-circle"></i>
                        <p>Product Categories</p>
                    </a>
                </li>
            @endcan
            @can('View Expense Categories')
                <li class="nav-item">
                    <a href="{{ route('expense-categories.index') }}" class="nav-link {{ request()->routeIs('expense-categories.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-circle"></i>
                        <p>Expense Categories</p>
                    </a>
                </li>
            @endcan
            @can('View Suppliers')
                <li class="nav-item">
                    <a href="{{ route('suppliers.index') }}" class="nav-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-circle"></i>
                        <p>Suppliers</p>
                    </a>
                </li>
            @endcan
            @can('View Reminders')
                <li class="nav-item">
                    <a href="{{ route('reminders.index') }}" class="nav-link {{ request()->routeIs('reminders.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-circle"></i>
                        <p>Reminders</p>
                    </a>
                </li>
            @endcan
        </ul>
    </li>
@endcanany

@can('View Settings')
    <li class="nav-item">
        <a href="{{ route('configurations.index') }}" class="nav-link {{ request()->routeIs('configurations.*') ? 'active' : '' }}">
            <i class="nav-icon bi bi-gear"></i>
            <p>Settings</p>
        </a>
    </li>
@endcan
