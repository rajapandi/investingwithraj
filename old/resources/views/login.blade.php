
<!DOCTYPE html>
<html>
<head><meta charset="windows-1252">
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>WhizzAct</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <!-- Google fonts - Popppins for copy-->
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
  </head>
  <body>
    <div class="page-holder align-items-center py-4 bg-gray-100 vh-100">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-3 px-lg-4"></div>
            <div class="col-lg-6 px-lg-4">
            <div class="card">
              <div class="card-header px-lg-5">
                <div class="card-heading text-primary">WhizzAct Dashboard</div>
              </div>
              <div class="card-body p-lg-5">
                <h3 class="mb-4">Hi, welcome back! 👋👋</h3>
                <form id="loginForm" action="/admin/login" method="POST">
                    @csrf
                  <div class="form-floating mb-3">
                    <input class="form-control" id="floatingInput" type="email" name="txtEmail" placeholder="name@example.com">
                    <label for="floatingInput">Email address</label>
                  </div>
                  <div class="form-floating mb-3">
                    <input class="form-control" id="floatingPassword" type="password" name="txtPassword" placeholder="Password">
                    <label for="floatingPassword">Password</label>
                  </div>
                  <button class="btn btn-primary btn-lg" type="submit">Login</button>
                </form>
              </div>
             
            </div>
          </div>
          <div class="col-lg-3 col-xl-5 ms-xl-auto px-lg-4 text-center text-primary">
            
          </div>
        </div>
      </div>
    </div>
    <!-- JavaScript files-->
    <script src="/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Prism for syntax highlighting-->
    <script src="/assets/vendor/prismjs/prism.js"></script>
    <script src="/assets/vendor/prismjs/plugins/normalize-whitespace/prism-normalize-whitespace.min.js"></script>
    <script src="/assets/vendor/prismjs/plugins/toolbar/prism-toolbar.min.js"></script>
    <script src="/assets/vendor/prismjs/plugins/copy-to-clipboard/prism-copy-to-clipboard.min.js"></script>
    <script type="text/javascript">
      // Optional
      Prism.plugins.NormalizeWhitespace.setDefaults({
      'remove-trailing': true,
      'remove-indent': true,
      'left-trim': true,
      'right-trim': true,
      });
          
    </script>
  </body>

</html>