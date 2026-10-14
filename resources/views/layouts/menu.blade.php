<li class="nav-item"><a href="{{route('home')}}" class="nav-link"><span class="pcoded-micon">
            <i class="fas fa-tachometer-alt"></i></span><span class="pcoded-mtext">Dashboard</span></a>
</li>
@can('View Advanced Dashboard')
<li class="nav-item"><a href="{{route('dashboard')}}" class="nav-link"><span class="pcoded-micon">
    <i class="fas fa-chart-area"></i></span><span class="pcoded-mtext">Dashboard II</span></a>
</li>
@endcan

@can('View Sales Management')
<li class="nav-item pcoded-hasmenu">
    <a href="#!" class="nav-link"><span class="pcoded-micon"><i class="fas fa-money-check-alt"></i></span>
        <span class="pcoded-mtext">Sales</span>
    </a>
    <ul class="pcoded-submenu">
        @can('Point of Sales')
        <li class=""><a href="{{route('sales.index')}}" class="">Point of Sale</a></li>
        @endcan

        @can('View Sales History')
        <li class=""><a href="{{route('sales.history')}}" class="">Sales History</a></li>
        @endcan
        @can('View Daily Report')
        <li class=""><a href="{{route('sales.daily-index')}}" class="">Daily Sale Report</a></li>
        @endcan
        @can('View Staff Loss')
        <li class=""><a href="{{route('losses.index')}}" class="">Staff Losses</a></li>
        @endcan


</ul>
</li>
@endcan



<li class="nav-item pcoded-hasmenu">
    @can('View Inventory Management')
    <a href="#!" class="nav-link"><span class="pcoded-micon"><i class="fas fa-dolly"></i></span>
        <span class="pcoded-mtext">Inventory</span>
    </a>
    <ul class="pcoded-submenu">
        @can('View Current Stock')
        <li class=""><a href="{{ route('current-stock.index') }}" class="">Current Stock</a></li>
        @endcan

        @can('View Adjustment History')
        <li class=""><a href="{{ route('stock-adjustment.index') }}" class="">Adjustment History</a></li>
        @endcan

        @can('View Stock Count Sheet')
        <li class=""><a href="{{ route('daily-stock-count.index') }}" class="">Stock Count Sheet</a></li>
        @endcan
        <li class=""><a href="{{ route('monthly-closing-stock.index') }}" class="">Monthly Closing Stock</a></li>


    </ul>
    @endcan
</li>

<li class="nav-item pcoded-hasmenu">
    @can('View Purchase Management')

    <a href="#!" class="nav-link"><span class="pcoded-micon"><i class="fas fa-shopping-cart"></i></span>
        <span class="pcoded-mtext">Purchases</span>
    </a>
    <ul class="pcoded-submenu">
        @can('Receive Goods')
        <li class=""><a href="{{route('goods-receiving.index')}}" class="">Purchase</a></li>
        @endcan
        @can('Purchase History')
        <li class=""><a href="{{route('goods-receiving.history')}}" class="">Purchase History</a></li>
        @endcan


    </ul>
    @endcan

</li>

<li class="nav-item">
    @can('View Expense Management')
    <a href="{{route('expense.index')}}" class="nav-link"><span class="pcoded-micon"><i class="fas fa-dollar-sign"></i></span>
        <span class="pcoded-mtext">Expenses</span>
    </a>
    {{-- <ul class="pcoded-submenu">
        @can('View Expenses')
        <li class=""><a href="{{route('expense.index')}}" class="">Expenses</a></li>
        @endcan
      
    </ul> --}}
    @endcan
</li>
@can('View Lodge Module')
<li class="nav-item pcoded-hasmenu">
    <a href="#!" class="nav-link"><span class="pcoded-micon"><i class="fas fa-bed"></i></span>
        <span class="pcoded-mtext">Rooms</span>
    </a>
    <ul class="pcoded-submenu">
        @can('View Lodge Dashboard')
        <li class=""><a href="{{route('lodge.index')}}" class="">Dashboard</a></li>
        @endcan
     
        @can('Manage Lodge Sales')
        <li class=""><a href="{{route('lodge-sales.index')}}" class="">Sales</a></li>
        @endcan

        @can('Manage Lodge Expenses')
        <li class=""><a href="{{route('lodge-expense.index')}}" class="">Expenses</a></li>
        @endcan
   

</ul>
</li>
@endcan
<li class="nav-item">
    @can('View Reports')
    <a href="{{route('reports.index')}}" class="nav-link"><span class="pcoded-micon"><i class="fas fa-file-pdf"></i></span><span
            class="pcoded-mtext">Reports</span></a>

    @endcan
</li>


<li class="nav-item">
    @can('View Customers')

    <a href="{{ route('customers.index') }}" class="nav-link"><span class="pcoded-micon"><i class="fas fa-user-friends"></i></span>
        <span class="pcoded-mtext">Customers</span>
    </a>

    @endcan

</li>

<li class="nav-item pcoded-hasmenu">
    @can('View User Management')

    <a href="#!" class="nav-link"><span class="pcoded-micon"><i class="fas fa-users"></i></span>
        <span class="pcoded-mtext">Users</span>
    </a>
    <ul class="pcoded-submenu">
        @can('View Roles')
            <li class=""><a href="{{route('roles.index')}}" class="">Roles</a></li>
        @endcan
        @can('View Users')
        <li class=""><a href="{{route('users.index')}}" class="">Users</a></li>
        @endcan

    </ul>
    @endcan

</li>
<li class="nav-item pcoded-hasmenu">
    @can('View Masters')

    <a href="#!" class="nav-link"><span class="pcoded-micon"><i class="fas fa-stream"></i></span>
        <span class="pcoded-mtext">Masters</span>
    </a>
    <ul class="pcoded-submenu">
        @can('View Products Categories')
        <li class=""><a href="{{route('categories.index')}}" class="">Product Categories</a></li>
        @endcan
        @can('View Products')
        <li class=""><a href="{{route('products.index')}}" class="">Products</a></li>
        @endcan
        @can('View Price Categories')
        <li class=""><a href="{{route('price-categories.index')}}" class="">Price Categories</a></li>
        @endcan
        @can('View Expense Categories')
        <li class=""><a href="{{route('expense-categories.index')}}" class="">Expense Categories</a></li>
        @endcan

        @can('View Suppliers')
        <li class=""><a href="{{route('suppliers.index')}}" class="">Suppliers</a></li>
        @endcan
        @can('View Stores')
        <li class=""><a href="{{route('stores.index')}}" class="">Stores</a></li>
        @endcan
        @can('View Reminders')
            <li class=""><a href="{{route('reminders.index')}}" class="">Reminders</a></li>
        @endcan
    </ul>
    @endcan

</li>
<li class="nav-item"><a href="{{route('configurations.index')}}" class="nav-link"><span class="pcoded-micon">
            @can('View Settings')
            <i class="feather icon-settings"></i></span><span class="pcoded-mtext">Settings</span></a>
    @endcan

</li>