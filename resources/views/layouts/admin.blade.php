<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Account</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{asset('admin/images/logo/favicon.png')}}">

    <!-- page css -->

    <!-- Core css -->
    <link href="{{asset('admin/css/app.min.css')}}" rel="stylesheet">
    <link href="{{asset('admin/vendors/datatables/dataTables.bootstrap.min.css')}}" rel="stylesheet">

    <!-- Sweet Alert 2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <!-- CK Editro -->
    <!-- CKEditor 5 Script (add in the head or before the closing body tag) -->
    <script src="https://cdn.ckeditor.com/ckeditor5/35.0.1/classic/ckeditor.js"></script>

    <!-- Chart -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Include jQuery and Select2 libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <!-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> -->

</head>

<body>
    <div class="app">
        <div class="layout">

            @php
            $logo = App\Models\GeneralSetting::where("name","logo")->first();
            @endphp
            <!-- Header START -->
            <div class="header">
                <div class="logo logo-dark" style="margin-top:15px !important">
                    <a href="/" target="_blank">
                        <img src="{{ asset('images/general_settings/' . $logo->value) }}" style="width:50px;height:50px"
                            alt="Logo">
                        <img class="logo-fold" src="{{ asset('images/general_settings/' . $logo->value) }}"
                            style="width:50px;height:50px" alt="Logo">
                    </a>
                </div>
                <div class="logo logo-white" style="margin-top:15px !important">
                    <a href="/" target="_blank">
                        <img src="{{ asset('images/general_settings/' . $logo->value) }}" style="width:50px;height:50px"
                            alt="Logo">
                        <img class="logo-fold" src="{{ asset('images/general_settings/' . $logo->value) }}"
                            style="width:50px;height:50px" alt="Logo">
                    </a>
                </div>
                <div class="nav-wrap">
                    <ul class="nav-left">
                        <li class="desktop-toggle">
                            <a href="javascript:void(0);">
                                <i class="anticon"></i>
                            </a>
                        </li>
                        <li class="mobile-toggle">
                            <a href="javascript:void(0);">
                                <i class="anticon"></i>
                            </a>
                        </li>
                    </ul>

                    <ul class="nav-right">
                        <!-- Notification Icon -->
                        <li class="notification-dropdown">
                            <a href="javascript:void(0);" class="notification-icon">
                                <i class="anticon anticon-bell" style="font-size: 24px;"></i>
                                <span class="badge_noti" id="notification-count">0</span>
                            </a>
                            <!-- Notification Dropdown Content -->
                            <div class="notification-menu" id="notification-menu">
                                <div class="notification-header">
                                    <span>Notifications</span>
                                    <a href="#" class="clear-all" id="clear-notifications">Clear All</a>
                                </div>
                            </div>
                        </li>
                    </ul>

                </div>
            </div>
            <!-- Header END -->

            <!-- Pusher -->
            <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
            <script>
                Pusher.logToConsole = true;

                var pusher = new Pusher('ca0b7ef0d3a4edcc8853', {
                    cluster: 'ap1'
                });

                var notificationCount = 0;

                // Subscribe to user registration notifications
                var userRegistrationChannel = pusher.subscribe('user-registrations');
                userRegistrationChannel.bind('new-user.registered', function(data) {
                    console.log("Received Data:", data);
                    if (data && data.message && data.type) {
                        addNotification(data.message, data.type);
                    }
                });

                // Subscribe to order notifications
                var orderChannel = pusher.subscribe('new-order');
                orderChannel.bind('new-order.created', function(data) {
                    console.log("Received Order Data:", data);
                    if (data && data.message && data.type) {
                        addNotification(data.message, data.type);
                    }
                });

                // Add a notification to the UI
                function addNotification(message, type) {
                    notificationCount++;
                    document.getElementById('notification-count').textContent = notificationCount;

                    const notificationMenu = document.getElementById('notification-menu');

                    const notificationItem = document.createElement('div');
                    notificationItem.classList.add('notification-item');

                    let iconClass, link;

                    switch (type) {
                        case 'user':
                            iconClass = 'anticon anticon-user';
                            link = '/admin/customers';
                            break;
                        case 'order':
                            iconClass = 'anticon anticon-shopping-cart';
                            link = '/admin/orders';
                            break;
                        default:
                            iconClass = 'anticon anticon-info-circle';
                            link = '#';
                    }

                    notificationItem.innerHTML = `
                <a href="${link}" style="text-decoration: none; color: inherit;">
                    <i class="${iconClass}"></i>
                    <div class="notification-text">
                        <p>${message}</p>
                        <small>${new Date().toLocaleTimeString()}</small>
                    </div>
                </a>
            `;

                    notificationMenu.appendChild(notificationItem);
                }

                // Clear all notifications
                document.getElementById('clear-notifications').addEventListener('click', clearNotifications);

                function clearNotifications(event) {
                    event.preventDefault();

                    const notificationMenu = document.getElementById('notification-menu');
                    notificationMenu.innerHTML = `
                <div class="notification-header">
                    <span>Notifications</span>
                    <a href="#" class="clear-all" id="clear-notifications">Clear All</a>
                </div>
            `;

                    notificationCount = 0;
                    document.getElementById('notification-count').textContent = notificationCount;

                    // Reattach the event listener for "Clear All"
                    document.getElementById('clear-notifications').addEventListener('click', clearNotifications);
                }
            </script>



            <style>
            .notification-dropdown {
                position: relative;
            }

            .notification-icon {
                position: relative;
                cursor: pointer;
            }

            .badge_noti {
                position: absolute;
                top: 0;
                right: -5px;
                background: red;
                color: white;
                border-radius: 50%;
                padding: 3px 6px;
                font-size: 12px;
            }

            .notification-menu {
                display: none;
                position: absolute;
                top: 30px;
                right: 0;
                background: white;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                width: 350px;
                border-radius: 8px;
                z-index: 100;
            }

            .notification-dropdown:hover .notification-menu {
                display: block;
            }

            .notification-header {
                padding: 10px;
                font-weight: bold;
                border-bottom: 1px solid #ddd;
                display: flex;
                justify-content: space-between;
            }

            .notification-item {
                display: flex;
                align-items: center;
                padding: 10px;
                border-bottom: 1px solid #f1f1f1;
            }

            .notification-item:last-child {
                border-bottom: none;
            }

            .notification-text {
                margin-left: 10px;
            }

            .notification-text p {
                margin: 0;
            }

            .clear-all {
                font-size: 12px;
                color: #007bff;
                cursor: pointer;
            }
            </style>


            <!-- Side Nav START -->
            <div class="side-nav">
                <div class="side-nav-inner">
                    @if(Auth::user()->role == 1)
                    <ul class="side-nav-menu scrollable">
                        <li class="nav-item dropdown open">
                            <a href="{{route('admin.dashboard')}}">
                                <span class="icon-holder">
                                    <i class="anticon anticon-dashboard"></i>
                                </span>
                                <span class="title">Dashboard</span>
                            </a>
                        </li>

                        <li class="nav-item dropdown open">
                            <a href="/">
                                <span class="icon-holder">
                                    <i class="anticon anticon-global"></i>
                                </span>
                                <span class="title">Visit Shop</span>
                            </a>
                        </li>

                        <li class="nav-item dropdown open">
                            <a href="/admin/sales">
                                <span class="icon-holder">
                                    <i class="anticon anticon-line-chart"></i>
                                </span>
                                <span class="title">Sale Report</span>
                            </a>
                        </li>

                        <li class="nav-item dropdown open">
                            <a href="/admin/orders">
                                <span class="icon-holder">
                                    <i class="anticon anticon-shopping-cart"></i>
                                </span>
                                <span class="title">Order</span>
                            </a>
                        </li>

                        <li class="nav-item dropdown open">
                            <a href="/admin/product_categories">
                                <span class="icon-holder">
                                    <i class="anticon anticon-tags"></i>
                                </span>
                                <span class="title">Category</span>
                            </a>
                        </li>

                        <li class="nav-item dropdown open">
                            <a href="/admin/brand">
                                <span class="icon-holder">
                                    <i class="anticon anticon-appstore"></i>
                                </span>
                                <span class="title">Brand</span>
                            </a>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="dropdown-toggle" href="javascript:void(0);">
                                <span class="icon-holder">
                                    <i class="anticon anticon-shop"></i>
                                </span>
                                <span class="title">Product</span>
                                <span class="arrow">
                                    <i class="arrow-icon"></i>
                                </span>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="{{route('admin.products.create')}}">Single Product Create</a>
                                </li>
                                <li>
                                    <a href="{{route('admin.products.create.varaint')}}">Variant Product Create</a>
                                </li>
                                <li>
                                    <a href="{{route('admin.products.index')}}">View</a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item dropdown open">
                            <a href="/admin/product_feedback">
                                <span class="icon-holder">
                                    <i class="anticon anticon-star"></i>
                                </span>
                                <span class="title">Product Feedback</span>
                            </a>
                        </li>

                        <li class="nav-item dropdown open">
                            <a href="/admin/supply">
                                <span class="icon-holder">
                                    <i class="anticon anticon-box-plot"></i>
                                </span>
                                <span class="title">Supply Log</span>
                            </a>
                        </li>

                        <li class="nav-item dropdown open">
                            <a href="/admin/cupon">
                                <span class="icon-holder">
                                    <i class="anticon anticon-gift"></i>
                                </span>
                                <span class="title">Coupon</span>
                            </a>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="dropdown-toggle" href="javascript:void(0);">
                                <span class="icon-holder">
                                    <i class="anticon anticon-user"></i>
                                </span>
                                <span class="title">Account</span>
                                <span class="arrow">
                                    <i class="arrow-icon"></i>
                                </span>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="/admin/admins">Admin</a>
                                </li>
                                <li>
                                    <a href="/admin/customers">User</a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item dropdown open">
                            <a href="/admin/general_settings">
                                <span class="icon-holder">
                                    <i class="anticon anticon-setting"></i>
                                </span>
                                <span class="title">Setting</span>
                            </a>
                        </li>

                        <li class="nav-item dropdown open">
                            <a href="/admin/faq">
                                <span class="icon-holder">
                                    <i class="anticon anticon-question-circle"></i>
                                </span>
                                <span class="title">FAQ</span>
                            </a>
                        </li>

                        <li class="nav-item dropdown open">
                            <a href="/admin/feedback">
                                <span class="icon-holder">
                                    <i class="anticon anticon-message"></i>
                                </span>
                                <span class="title">Contact Message</span>
                            </a>
                        </li>



                        <li class="nav-item dropdown open">
                            <a href="/admin/currency">
                                <span class="icon-holder">
                                    <i class="anticon anticon-dollar"></i>
                                </span>
                                <span class="title">Currency Setting</span>
                            </a>
                        </li>

                        <li class="nav-item dropdown open">
                            <a href="/admin/delivery">
                                <span class="icon-holder">
                                    <i class="anticon anticon-car"></i>
                                </span>
                                <span class="title">Delivery Setting</span>
                            </a>
                        </li>



                        <li class="nav-item dropdown open">
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                            <a href="#"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <span class="icon-holder">
                                    <i class="anticon anticon-logout"></i>
                                </span>
                                <span class="title">Logout</span>
                            </a>
                        </li>
                    </ul>

                    @endif
                    @if(Auth::user()->role == 3)
                    <ul class="side-nav-menu scrollable">
                        <li class="nav-item dropdown open">
                            <a href="{{route('admin.dashboard')}}">
                                <span class="icon-holder">
                                    <i class="anticon anticon-dashboard"></i>
                                </span>
                                <span class="title">Dashboard</span>
                            </a>
                        </li>

                        <li class="nav-item dropdown open">
                            <a href="/">
                                <span class="icon-holder">
                                    <i class="anticon anticon-home"></i>
                                </span>
                                <span class="title">Visit Website</span>
                            </a>
                        </li>

                        <li class="nav-item dropdown open">
                            <a href="/admin/sales">
                                <span class="icon-holder">
                                    <i class="anticon anticon-line-chart"></i>
                                </span>
                                <span class="title">Sale Report</span>
                            </a>
                        </li>

                        <li class="nav-item dropdown open">
                            <a href="/admin/orders">
                                <span class="icon-holder">
                                    <i class="anticon anticon-shopping-cart"></i>
                                </span>
                                <span class="title">Order</span>
                            </a>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="dropdown-toggle" href="javascript:void(0);">
                                <span class="icon-holder">
                                    <i class="anticon anticon-shopping"></i>
                                </span>
                                <span class="title">Product</span>
                                <span class="arrow">
                                    <i class="arrow-icon"></i>
                                </span>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="{{route('admin.products.create')}}">Single Product Create</a>
                                </li>
                                <li>
                                    <a href="{{route('admin.products.create.varaint')}}">Variant Product Create</a>
                                </li>
                                <li>
                                    <a href="{{route('admin.products.index')}}">View</a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item dropdown open">
                            <a href="/admin/product/feedback">
                                <span class="icon-holder">
                                    <i class="anticon anticon-star"></i>
                                </span>
                                <span class="title">Product Feedback</span>
                            </a>
                        </li>


                        <li class="nav-item dropdown open">
                            <a href="/admin/supply">
                                <span class="icon-holder">
                                    <i class="anticon anticon-box-plot"></i>
                                </span>
                                <span class="title">Supply</span>
                            </a>
                        </li>

                        <li class="nav-item dropdown open">
                            <a href="/admin/feedback">
                                <span class="icon-holder">
                                    <i class="anticon anticon-message"></i>
                                </span>
                                <span class="title">Customer Feedback</span>
                            </a>
                        </li>

                        <li class="nav-item dropdown open">
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                            <a href="#"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <span class="icon-holder">
                                    <i class="anticon anticon-logout"></i>
                                </span>
                                <span class="title">Logout</span>
                            </a>
                        </li>
                    </ul>

                    @endif
                </div>
            </div>
            <!-- Side Nav END -->

            <!-- Page Container START -->
            <div class="page-container">

                <!-- Content Wrapper START -->
                <div class="main-content">

                    @yield('body')
                </div>
                <!-- Content Wrapper END -->

                <!-- Footer START -->
                <footer class="footer">
                    <div class="footer-content">
                        <p class="m-b-0"> Copyright © 2024 </p>

                    </div>
                </footer>
                <!-- Footer END -->

            </div>
            <!-- Page Container END -->


            <!-- Quick View START -->
            <div class="modal modal-right fade quick-view" id="quick-view">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header justify-content-between align-items-center">
                            <h5 class="modal-title">Theme Config</h5>
                        </div>
                        <div class="modal-body scrollable">
                            <div class="m-b-30">
                                <h5 class="m-b-0">Header Color</h5>
                                <p>Config header background color</p>
                                <div class="theme-configurator d-flex m-t-10">
                                    <div class="radio">
                                        <input id="header-default" name="header-theme" type="radio" checked
                                            value="default">
                                        <label for="header-default"></label>
                                    </div>
                                    <div class="radio">
                                        <input id="header-primary" name="header-theme" type="radio" value="primary">
                                        <label for="header-primary"></label>
                                    </div>
                                    <div class="radio">
                                        <input id="header-success" name="header-theme" type="radio" value="success">
                                        <label for="header-success"></label>
                                    </div>
                                    <div class="radio">
                                        <input id="header-secondary" name="header-theme" type="radio" value="secondary">
                                        <label for="header-secondary"></label>
                                    </div>
                                    <div class="radio">
                                        <input id="header-danger" name="header-theme" type="radio" value="danger">
                                        <label for="header-danger"></label>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div>
                                <h5 class="m-b-0">Side Nav Dark</h5>
                                <p>Change Side Nav to dark</p>
                                <div class="switch d-inline">
                                    <input type="checkbox" name="side-nav-theme-toogle" id="side-nav-theme-toogle">
                                    <label for="side-nav-theme-toogle"></label>
                                </div>
                            </div>
                            <hr>
                            <div>
                                <h5 class="m-b-0">Folded Menu</h5>
                                <p>Toggle Folded Menu</p>
                                <div class="switch d-inline">
                                    <input type="checkbox" name="side-nav-fold-toogle" id="side-nav-fold-toogle">
                                    <label for="side-nav-fold-toogle"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Quick View END -->
        </div>
    </div>




    <!-- Core Vendors JS -->
    <script src="{{asset('admin/js/vendors.min.js')}}"></script>

    <!-- page js -->
    <script src="{{asset('admin/vendors/chartjs/Chart.min.js')}}"></script>
    <script src="{{asset('admin/js/pages/dashboard-default.js')}}"></script>
    <script src="{{asset('admin/vendors/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('admin/vendors/datatables/dataTables.bootstrap.min.js')}}"></script>
    <script src="{{asset('admin/js/pages/datatables.js')}}"></script>
    <!-- Core JS -->
    <script src="{{asset('admin/js/app.min.js')}}"></script>
    <!-- SweetAlert2 for error display -->
    @if ($errors->any())

    <script>
    Swal.fire({
        icon: 'error',
        title: 'Login Failed',
        html: `
                <ul style="text-align: left;padding-left:20px !important;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            `,
        confirmButtonText: 'OK',
    });
    </script>
    @endif
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        ClassicEditor
            .create(document.querySelector('textarea[name="shortdescription"]'), {
                toolbar: [
                    'bold', 'italic', 'underline', 'strikethrough',
                    'link', 'bulletedList', 'numberedList',
                    'blockQuote', 'insertTable', 'undo', 'redo'
                ]
            })
            .catch(error => {
                console.error(error);
            });

        ClassicEditor
            .create(document.querySelector('textarea[name="description"]'), {
                toolbar: [
                    'bold', 'italic', 'underline', 'strikethrough',
                    'link', 'bulletedList', 'numberedList',
                    'blockQuote', 'insertTable', 'undo', 'redo'
                ]
            })
            .catch(error => {
                console.error(error);
            });
    });
    </script>

</body>

</html>