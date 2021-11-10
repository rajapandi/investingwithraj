<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>WhizzAct</title>
    <link rel="preconnect" href="https://fonts.gstatic.com/">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;700&amp;display=swap" rel="stylesheet">
    <!-- Prism Syntax Highlighting-->
    <link rel="stylesheet" href="/assets/vendor/prismjs/plugins/toolbar/prism-toolbar.css">
    <link rel="stylesheet" href="/assets/vendor/prismjs/themes/prism-okaidia.css">
    <!-- The Main Theme stylesheet (Contains also Bootstrap CSS)-->
    <link rel="stylesheet" href="/assets/css/style.default.cdea6c5a.css" id="theme-stylesheet">
    <!-- Custom stylesheet - for your changes-->
    <link rel="stylesheet" href="/assets/css/custom.0a822280.css">
    <!-- Favicon-->
    <link rel="shortcut icon" href="/assets/img/favicon.png">
    <script src="https://use.fontawesome.com/9e00f8bd62.js"></script>
</head>
<body>
    @include('inc.navigation')
    @include('inc.sidenavigation')
       
       @yield('content')
       
        <div class="toast-container position-fixed z-index-50 bottom-0 end-0 p-3">
          <div class="toast hide" id="liveToast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header"><span class="dot bg-success me-2"></span>
              <div class="card-heading text-dark me-auto">Bubbly </div><small>11 mins ago</small>
              <button class="btn-close" type="button" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body text-muted">Hello, world! This is a toast message.</div>
          </div>
          <div class="toast hide" id="liveToast2" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header"><span class="dot bg-danger me-2"></span>
              <div class="card-heading text-dark me-auto">Bubbly </div><small>11 mins ago</small>
              <button class="btn-close" type="button" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body text-muted">Hello, world! This is a toast message.</div>
          </div>
          <div class="toast hide" id="liveToast3" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header"><span class="dot bg-info me-2"></span>
              <div class="card-heading text-dark me-auto">Bubbly </div><small>11 mins ago</small>
              <button class="btn-close" type="button" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body text-muted">Hello, world! This is a toast message.</div>
          </div>
        </div>
        
 <!-- JavaScript files-->
 <script src="/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
 <!-- Init Charts on Homepage-->
 <script src="/assets/vendor/chart.js/Chart.min.js"></script>
 <script src="/assets/js/charts-defaults.4032ce71.js"></script>
 <script src="/assets/js/charts-home.a757f7e5.js"></script>
 <!-- Main Theme JS File-->
 <script src="/assets/js/theme.7033a95b.js"></script>
 <!-- Prism for syntax highlighting-->
 <script src="/assets/js/components-notifications.1b9c8c2c.js"> </script>
 <script src="/assets/vendor/prismjs/prism.js"></script>
 <script src="/assets/vendor/prismjs/plugins/normalize-whitespace/prism-normalize-whitespace.min.js"></script>
 <script src="/assets/vendor/prismjs/plugins/toolbar/prism-toolbar.min.js"></script>
 <script src="/assets/vendor/prismjs/plugins/copy-to-clipboard/prism-copy-to-clipboard.min.js"></script>
 
 <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
 <script src="/js/index.js"></script>
 
 <script type="text/javascript">
 "use strict";

document.addEventListener("DOMContentLoaded", function () {
    var toastElList = [].slice.call(document.querySelectorAll(".toast"));

    var toastList = toastElList.map(function (toastEl) {
        return new bootstrap.Toast(toastEl);
    });

    var toastButtonList = [].slice.call(document.querySelectorAll(".toast-btn"));

    toastButtonList.map(function (toastButtonEl) {
        toastButtonEl.addEventListener("click", function () {
            var toastToTrigger = document.getElementById(toastButtonEl.dataset.target);

            if (toastToTrigger) {
                var toast = bootstrap.Toast.getInstance(toastToTrigger);
                toast.show();
            }
        });
    });
});

   // Optional
   Prism.plugins.NormalizeWhitespace.setDefaults({
   'remove-trailing': true,
   'remove-indent': true,
   'left-trim': true,
   'right-trim': true,
   });

       
 </script>
 
        @yield('script')


</body>
</html>
