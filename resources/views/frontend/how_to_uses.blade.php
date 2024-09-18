@extends('layouts.front')
@section('content')
@include('partials.global.common-header')

<style>
            * {
               margin: 0;
               padding: 0;
            }

            /* banner */
            .banner-img img {
               height: 100%;
               margin-bottom: -80px;
               width: 100%;
               border-bottom-left-radius: 100px;
            }

            .banner {
               position: relative;
            }

            .banner-title {
               position: absolute;
               right: 60%;
               top: 80%;
            }

            .banner-title h1 {
               font-size: 50px;
               background: #BB8A8A;
               color: #fff;
               text-transform: uppercase;
            }

            .container {
               width: 80%;
               margin: 0 auto;
            }

            .how-to-use {
               margin-top: 150px;
            }
   </style>
<!-- <body> -->
   <section class="header">
      <!-- banner -->
      <div class="banner">
         <div class="banner-img">
            <img src="{{asset('assets/brand/image1.png')}}" alt="" />
         </div>
         <div class="banner-title">
            <h1>How to Use</h1>
         </div>
      </div>
   </section>
   <!-- how to use -->
   <section class="how-to-use">
      <div class="container">
         <img src="{{asset('assets/brand/image.png')}}" alt="">
      </div>
   </section>
   <!-- js file -->
   <!-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
   <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>

</html> -->
@includeIf('partials.global.common-footer')
@endsection