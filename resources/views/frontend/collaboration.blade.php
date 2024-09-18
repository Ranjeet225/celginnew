@extends('layouts.front')
@section('content')
@include('partials.global.common-header')
<!-- breadcrumb -->
<div class="full-row bg-light overlay-dark py-5"
   style="background-image: url({{ $gs->breadcrumb_banner ? asset('assets/images/'.$gs->breadcrumb_banner):asset('assets/images/noimage.png') }}); background-position: center center; background-size: cover;">
   <div class="container">
      <div class="row text-center text-white">
         <div class="col-12">
            <h3 class="mb-2 text-white">{{ __('Collaboration') }}</h3>
         </div>
         <div class="col-12">
            <nav aria-label="breadcrumb">
               <ol class="breadcrumb mb-0 d-inline-flex bg-transparent p-0">
                  <li class="breadcrumb-item"><a href="{{ route('front.index') }}">{{ __('Home') }}</a></li>
                  <li class="breadcrumb-item active" aria-current="page">{{ __('Collaboration') }}</li>
               </ol>
            </nav>
         </div>
      </div>
   </div>
</div>
<!-- breadcrumb -->

<!--==================== Blog Section Start ====================-->
<div class="full-row">
   <div class="container">
      <div class="row">
        
         <div class="col-lg-12 order-lg-1">
            <div class="single-post">
               <div class="single-post-title">
                  <h3 class="mb-2 text-secondary">{{ $collaboration[0]->title }}</h3>
               </div>
              
               <div class="post-content pt-4 mb-5">
                  <p>{!! clean($collaboration[0]->details ) !!}</p>
               </div>
                          
            </div>
         </div>
      </div>
   </div>
</div>
<!--==================== Blog Section End ====================-->
@includeIf('partials.global.common-footer')
@endsection