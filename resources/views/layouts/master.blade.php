<!DOCTYPE html>
<html lang="en">

<head>
    <title>Smart Pub</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- Favicon icon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{asset("silantro_sys.ico")}}">

    <!-- fontawesome icon -->
    <link rel="stylesheet" href="{{asset("assets/fonts/fontawesome/css/fontawesome-all.min.css")}}">
    <!-- animation css -->
    <link rel="stylesheet" href="{{asset("assets/plugins/animation/css/animate.min.css")}}">
    <!-- notification css -->
    <link rel="stylesheet" href="{{asset("assets/plugins/notification/css/notification.min.css")}}">
    <!-- data tables css -->
    <link rel="stylesheet" href="{{asset("assets/plugins/data-tables/css/datatables.min.css")}}">
    <!-- vendor css -->
    <link rel="stylesheet" href="{{asset("assets/css/style.css")}}">
    <!-- select2 css -->
    <link rel="stylesheet" href="{{asset("assets/plugins/select2/css/select2.min.css")}}">

    <!-- tel-input css -->
    <link rel="stylesheet" href="{{asset("assets/plugins/intl-tel-input/css/intlTelInput.css")}}">
   <!-- Bootstrap Datepicker css -->
    <link href="{{asset("assets/plugins/bootstrap-datetimepicker/css/prettify.css")}}" rel="stylesheet">
    <link href="{{asset("assets/plugins/bootstrap-datetimepicker/css/bootstrap-datepicker3.min.css")}}" rel="stylesheet">
    <link href="{{asset("assets/plugins/daterangepicker-master/css/daterangepicker.css")}}" rel="stylesheet">

    @yield("page_css")

    <style>
        .notification-count {
        position: relative;
        top: -12px;
        right: -28px;
        }
    </style>


</head>

<body>


    <!-- [ navigation menu ] start -->
    <nav class="pcoded-navbar brand-red active-red menu-item-icon-style4 icon-colored navbar-collapsed">
        <div class="navbar-wrapper" >
            <div class="navbar-brand header-logo">
                <a href="{{route('home')}}" class="b-brand">
                    {{-- <div class="b-bg">
                        <img src="{{asset('silantro_sys.ico')}}"  alt="Silantro">
                    </div> --}}
                    <div class="b-bg">
                        <i class="fas fa-wine-glass"></i>
                    </div>
                    <span class="b-title">Smart Pub</span>
                </a>
                <a class="mobile-menu" id="mobile-collapse" href="#!"><span></span></a>
            </div>
            <div class="navbar-content scroll-div">
                <ul class="nav pcoded-inner-navbar">

                    @include('layouts.menu')

                </ul>
            </div>
        </div>
    </nav>
    <!-- [ navigation menu ] end -->

    <!-- [ Header ] start -->
    <header class="navbar pcoded-header navbar-expand-lg navbar-light header-blue">
        <div class="m-header">
            <a class="mobile-menu" id="mobile-collapse1" href="#!"><span></span></a>
                <a href="{{route('home')}}" class="b-brand">
                    <div class="b-bg">
                        <i class="fas fa-wine-glass"></i>
                    </div>
                   <span class="b-title">Smart Pub</span>
               </a>
        </div>
        <a class="mobile-menu" id="mobile-header" href="#!">
            <i class="feather icon-more-horizontal"></i>
        </a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav mr-auto">
                <li><a href="#!" class="full-screen" onclick="javascript:toggleFullScreen()"><i class="feather icon-maximize"></i></a></li>

            </ul>
            <ul class="navbar-nav ml-auto">
                <li>
                    <div class="dropdown">

                        @if (notifications()>0)
                            <span class="badge badge-pill badge-warning notification-count">{{ notifications() }}</span>
                        @endif

                        <a class="dropdown-toggle" href="#" data-toggle="dropdown"><i class="icon feather icon-bell"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right notification">
                            <div class="noti-head">
                                <h6 class="d-inline-block m-b-0">Notifications</h6>
                                <div class="float-right">
                                    <a href="#!" class="m-r-10">Mark as read</a>
                                    <a href="#!">Clear all</a>
                                </div>
                            </div>
                            <ul class="noti-body">
                                    @if ( outofStock() > 0)
                                        <li class="notification">
                                            <div class="media">
                                                <div class="media-body">
                                                    <p ><strong> <span class="text-c-red"> Out of Stock </span></strong></p>
                                                    <p> <a href="{{route('out-of-stock')}}"> {{ outofStock() }} item(s) out of stock  </a> </p>

                                                </div>
                                            </div>
                                        </li>

                                    @endif
                                    @if ( belowMin() > 0)
                                        <li class="notification">
                                            <div class="media">
                                                <div class="media-body">
                                                    <p><strong> <span class="text-c-blue"> Minimum Stock </span></strong></p>
                                                    <p> <a href="{{route('below-min-level')}}"> {{ belowMin() }} item(s) below minimum  </a></p>
                                                </div>
                                            </div>
                                        </li>

                                    @endif
                                    @can('View Reminders')
                                        @foreach (getReminders() as $r)
                                            <li class="notification">
                                                <div class="media">
                                                    <div class="media-body">
                                                        <p><strong> <span class="text-warning mb-1"> Reminder </span></strong></p>
                                                        <p> <a href="{{route('reminders.index')}}"> {{ $r }}  </a></p>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    @endcan


                            </ul>

                        </div>
                    </div>
                </li>

                <li>
                    <div class="dropdown drp-user">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="icon feather icon-settings"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right profile-notification">
                            <div class="pro-head">
                                {{-- <img src="assets/images/user/avatar-1.jpg" class="img-radius" alt="User-Profile-Image"> --}}
                                <span>
                                    {{Auth::user()->name}}
                                </span>

                                <a href="{{ route('logout') }}" class="dud-logout" title="Logout"
                                    onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                                    <i class="feather icon-log-out"></i>

                                </a>

                            </div>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                            <ul class="pro-body">
                                {{-- <li><a href="#!" class="dropdown-item"><i class="feather icon-settings"></i> Settings</a></li> --}}
                                <li><a href="{{ route('change-pass-form') }}" class="dropdown-item"><i class="feather icon-user"></i> Change Password</a></li>
                                {{-- <li><a href="message.html" class="dropdown-item"><i class="feather icon-mail"></i> My Messages</a></li> --}}
                                <li><a href="{{ route('logout') }}" class="dropdown-item"
                                    onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                                    <i class="feather icon-lock"></i> Lock Screen</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </header>
    <!-- [ Header ] end -->
    <!-- [ chat user list ] start -->
        <section class="header-user-list">
            <div class="h-list-header">
                <div class="input-group">
                    <input type="text" id="search-friends" class="form-control" placeholder="Search Friend . . .">
                </div>
            </div>
            <div class="h-list-body">
                <a href="#!" class="h-close-text"><i class="feather icon-chevrons-right"></i></a>
                <div class="main-friend-cont scroll-div">
                    <div class="main-friend-list">

                    </div>
                </div>
            </div>
        </section>
        <!-- [ chat user list ] end -->

        <!-- [ chat message ] start -->
        <section class="header-chat">
            <div class="h-list-header">
                <h6>Beatus K</h6>
                <a href="#!" class="h-back-user-list"><i class="feather icon-chevron-left"></i></a>
            </div>
            <div class="h-list-body">
                <div class="main-chat-cont scroll-div">

                </div>
            </div>

        </section>
        <!-- [ chat message ] end -->


    <!-- [ Main Content ] start -->
    <div class="pcoded-main-container">
        <div class="pcoded-wrapper">
            <div class="pcoded-content">
                <div class="pcoded-inner-content">
                    <!-- [ breadcrumb ] start -->
                    <div class="page-header">
                        <div class="page-block">
                            <div class="row align-items-center">
                                <div class="col-md-12">
                                    <div class="page-header-title">
                                        <h5 class="m-b-10">
                                                @yield("content-title")
                                        </h5>
                                    </div>
                                    <ul class="breadcrumb">
                                            @yield("content-sub-title")

                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="page-wrapper">
                        <!-- [ Main Content ] start -->
                        <div class="row">
                            <!-- [ static-layout ] start -->
                                @yield("content")
                            <!-- [ static-layout ] end -->
                        </div>
                        <!-- [ Main Content ] end -->
                    </div>
                    <!-- [ breadcrumb ] end -->

                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->



    <!-- Required Js -->
    <script src="{{asset("assets/js/vendor-all.min.js")}}"></script>
    <script src="{{asset("assets/plugins/bootstrap/js/bootstrap.min.js")}}"></script>
    <script src="{{asset("assets/js/pcoded.min.js")}}"></script>


    <!-- notification Js -->
    <script src="{{asset("assets/plugins/notification/js/bootstrap-growl.min.js")}}"></script>

    <!-- datatable Js -->
    <script src="{{asset("assets/plugins/data-tables/js/datatables.min.js")}}"></script>
    <script src="{{asset("assets/js/pages/tbl-datatable-custom.js")}}"></script>

      <!-- select2 Js -->
    <script src="{{asset("assets/plugins/select2/js/select2.full.min.js")}}"></script>

      <!-- Input mask Js -->
    <script src="{{asset("assets/plugins/inputmask/js/inputmask.min.js")}}"></script>
    <script src="{{asset("assets/plugins/inputmask/js/jquery.inputmask.min.js")}}"></script>
    <script src="{{asset("assets/plugins/inputmask/js/autoNumeric.js")}}"></script>
      <!-- tel-input js -->
    <script src="{{asset("assets/plugins/intl-tel-input/js/intlTelInput.js")}}"></script>

    <script src="{{asset("assets/plugins/moment/js/moment.js")}}"></script>
    <!-- boostrap js -->
    <script src="{{asset("assets/plugins/daterangepicker-master/js/daterangepicker.js")}}"></script>


   {{-- custom java scripts for the page --}}
    @stack("page_scripts")

</body>
</html>
