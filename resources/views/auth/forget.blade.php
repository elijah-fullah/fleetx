<!DOCTYPE html>
<html
  lang="en"
  class="light-style customizer-hide"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="../assets/"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>Forgot password | Fleet XP</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ Vite::asset('resources/img/svg/radio.svg') }}" type="image/x-icon">

    <!-- Boxicons  Starts-->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />

    <!-- Icons. Uncomment required icon fonts -->
    @vite('resources/assets/css/boxicons.css')

    <!-- Core CSS -->
    @vite('resources/assets/css/core.css')
    @vite('resources/assets/css/theme-default.css')
    @vite('resources/assets/css/theme-demo.css')
    @vite('resources/assets/css/page-auth.css')
    @vite('resources/assets/css/perfect-scrollbar.css')
    @vite('resources/assets/js/helpers.js')
    @vite('resources/assets/js/config.js')

  </head>

  <body>
    <!-- Content -->

    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
          <div class="authentication-inner py-4">
            <!-- Forgot Password -->
            <div class="card">
              <div class="card-body">
                <!-- Logo -->
                <div class="app-brand justify-content-center">
                    <a href="#" class="app-brand-link gap-2">
                      <span class="app-brand-text demo text-body fw-bolder">Fleet XP</span>
                    </a>
                  </div>
                <!-- /Logo -->
                <h4 class="mb-2">Forgot Password? 🔒</h4>
                <p class="mb-4">Enter your email and we'll send you instructions to reset your password</p>
                <form id="formAuthentication" class="mb-3" action="index.html" method="POST">
                  <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input
                      type="text"
                      class="form-control"
                      id="email"
                      name="email"
                      placeholder="Enter your email"
                      autofocus
                    />
                  </div>
                  <button class="btn btn-primary d-grid w-100">Send Reset Link</button>
                </form>
                <div class="text-center">
                  <a href="{{ route('login') }}" class="d-flex align-items-center justify-content-center">
                    <i class="bx bx-chevron-left scaleX-n1-rtl bx-sm"></i>
                    Back to login
                  </a>
                </div>
              </div>
            </div>
            <!-- /Forgot Password -->
          </div>
        </div>
      </div>

    <!-- / Content -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    @vite('resources/assets/js/jquery.js')
    @vite('resources/assets/js/popper.js')
    @vite('resources/assets/js/bootstrap.js')
    @vite('resources/assets/js/perfect-scrollbar.js')
    @vite('resources/assets/js/menu.js')

    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    @vite('resources/assets/js/main.js')

    <!-- Page JS -->

  </body>
</html>
