@extends('layouts.front')
@section('content')
@include('partials.global.common-header')

<style>
   * {
      margin: 0;
      padding: 0;
   }

   .banner-img {
      margin: 80px 115px;
      width: 70%;
   }

   .banner-img img {
      width: 90%;
      margin-bottom: -80px;
   }

   .banner {
      position: relative;
   }

   .banner-title {
      position: absolute;
      left: 50%;
      top: 20%;
   }

   .banner-title h1 {
      font-size: 50px;
      background: #BB8A8A;
      color: #fff;
      line-height: 70px;
      padding: 0 10px 0 10px;
      text-transform: uppercase;
   }

   /* Fashion trends */
   .fashion-trends {
      padding: 100px 0;
   }

   .fashion-box {
      margin: 80px 0;
   }

   .title-style h1 {
      padding: 40px 0;
   }

   .title-style {
      margin: 0 auto 80px;
      height: 120px;
      width: 80%;
      min-width: 700px;
      background: #fff;
      position: relative;
      box-shadow: 0 4px 5px 0 #BB8A8A;
   }

   .title-style::after {
      content: "";
      height: 100px;
      width: 200px;
      background-color: #BB8A8A;
      position: absolute;
      top: -10px;
      left: -10px;
      z-index: -1;
   }

   .title-style::before {
      content: "";
      height: 100px;
      width: 200px;
      background-color: #BB8A8A;
      position: absolute;
      bottom: -10px;
      right: -10px;
      z-index: -1;
   }

   .trending-img {
      position: relative;
      margin-bottom: 15px;
   }

   .trending-img img {
      width: 100%;
   }

   .btn-buy {
      width: 150px;
      padding: 10px 0;
      outline: none !important;
      border: 0;
      border-radius: 2px;
      position: absolute;
      background: #fff;
      left: 50%;
      bottom: 0;
      transform: translate(-50%, 0);
      transition: 0.6s;
      opacity: 0;
   }

   .trending-img:hover .btn-buy {
      transform: translate(-50%, 50%);
      bottom: 50%;
      opacity: 1;
      z-index: 1;
   }

   .overlay {
      height: 0;
      width: 100%;
      background: #333;
      position: absolute;
      top: 0;
      opacity: 0;
      transition: 0.5s;
   }

   .trending-img:hover .overlay {
      opacity: 0.5;
      height: 100%;
   }

   /* offer */
   .offer {
      background-image: url({{asset('assets/brand/bread.png')}});
      background-size: cover;
      background-position: center;
   }

   .row {
      margin: initial !important;
   }

   .subscribe {
      max-width: 500px;
      margin-top: 80px;
      margin-bottom: 20px;
      padding: 60px;
      background: #ffffff8c;
   }

   .subscribe a {
      width: 100px;
      display: block;
      color: #FFFFFF !important;
      background: #BB8A8A;
      text-decoration: none !important;
      padding: 5px;
      text-align: center;
   }

   .offer img {
      width: 400px;
      height: 350px;
      margin-bottom: -100px;
      margin-top: 100px;
   }
</style>
   <section class="offer">
      <div class="row">
         <div class="col-md-6 text-center">
            <div class="subscribe">
               <h4>Brand Story</h4>
               <p>
                  Skin, Brand, and the Earth For all consumers to have tomorrow to look forward to. Celigin's Tomorrow refers to the three types of future for the customers. First, a future for the remarkable change of the skin Second, a future for the continuous growth of our brand Third, a future for the well-being of the Earth CELIGIN will continue the endless effort to pursue our tomorrow.
               </p>
            </div>
         </div>
        
      </div>
      </div>
   </section>

   <section class="header">
      <!-- navbar -->
      <!-- banner -->
      <div class="banner">
         <div class="banner-img">
            <img src="{{asset('assets/brand/brand1.jpeg')}}" alt="" />
         </div>
         <div class="banner-title">
            <h1>Celigin CosCor</h1>
            
            <!--<h1>Women's Lifestyle</h1>-->
         </div>
      </div>
   </section>
   <!-- fashion trends -->
   <section class="fashion-trends">
      <div class="container">
         <div class="fashion-box">
            <div class="title-style text-center">
               <h1>Safety Standards</h1>
            </div>
            <p class="text-center sm-bt">
              Skin, Brand, and the Earth For all consumers to have tomorrow to look forward to. Celigin's Tomorrow refers to the three types of future for the customers. First, a future for the remarkable change of the skin Second, a future for the continuous growth of our brand Third, a future for the well-being of the Earth CELIGIN will continue the endless effort to pursue our tomorrow.
            </p>
         </div>
         <div class="row">
            <div class="col-md-4">
               <div class="trending-img">
                  <img src="{{asset('brand/brand1.png')}}" alt="" />
                  <!-- <button type="button" class="btn-buy">Buy Now</button> -->
                  <div class="overlay"></div>
               </div>
            </div>
            <div class="col-md-4">
               <div class="trending-img">
                  <img src="{{asset('brand/brand1.png')}}" alt="" />
                  <!-- <button type="button" class="btn-buy">Buy Now</button> -->
                  <div class="overlay"></div>
               </div>
            </div>
            <div class="col-md-4">
               <div class="trending-img">
                  <img src="{{asset('brand/brand1.png')}}" alt="" />
                  <!-- <button type="button" class="btn-buy">Buy Now</button> -->
                  <!-- <p class="btn-buy">Limited Stock</p> -->
                  <div class="overlay"></div>
               </div>
            </div>
         </div>
      </div>
   </section>
   <!-- Offer section -->
  
@includeIf('partials.global.common-footer')
@endsection