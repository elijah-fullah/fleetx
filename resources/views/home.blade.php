<!--  TOP STARTS -->

@include('layouts/include.top')
      
<!--  TOP ENDS -->

  <div class="layer"></div>

  <!--  BODY STARTS -->

    <a class="skip-link sr-only" href="#skip-target">Skip to content</a>

    <div class="page-flex">

      <!--  SIDEBAR STARTS -->

      @include('layouts/include.sidebar')
      
      <!--  SIDEBAR ENDS -->

      <!--  MAIN STARTS -->

        <div class="main-wrapper">

      <!--  NAV STARTS -->

        @include('layouts/include.nav')

      <!--  NAV ENDS -->

      <!--  MAIN DETAIL STARTS -->
      
      
      <main class="main users chart-page" id="skip-target">
        
        <div class="container">
            
          @yield('content')
        
        </div>
    
     </main>

      <!--  MAIN DETAIL STARTS -->

      <!--  FOOTER STARTS -->

        @include('layouts/include.footer')

      <!--  FOOTER ENDS -->

    </div>

  </div>

  <!--  BODY STARTS -->

<!--  BOTTOM STARTS -->

@include('layouts/include.bottom')
      
<!--  BOTTOM ENDS -->