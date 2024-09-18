@extends('layouts.front')

@section('content')
@include('partials.global.common-header')

 <!-- breadcrumb -->
 <div class="full-row bg-light overlay-dark py-5" style="background-image: url({{ $gs->breadcrumb_banner ? asset('assets/images/'.$gs->breadcrumb_banner):asset('assets/images/noimage.png') }}); background-position: center center; background-size: cover;">
    <div class="container">
        <div class="row text-center text-white">
            <div class="col-12">
                <h3 class="mb-2 text-white">{{ __('Reward') }}
                </h3>
            </div>
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 d-inline-flex bg-transparent p-0">
                        <li class="breadcrumb-item"><a href="{{ route('user-dashboard') }}">{{ __('Dashboard') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('Reward ') }}</li>
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
        <div class="mb-4 d-xl-none">
            <button class="dashboard-sidebar-btn btn bg-primary rounded">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        <div class="row">
            <div class="col-xl-4">
                @include('partials.user.dashboard-sidebar')
            </div>
            <div class="col-xl-8">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="widget border-0 p-40 widget_categories bg-light account-info">

                            <h4 class="widget-title down-line mb-30">{{ __('Referral link') }}
                                {{-- <a class="mybtn1" href="{{route('user-affilate-history')}}"> <i class="fas fa-history"></i> {{ __('Referral History') }}</a> --}}
                            </h4>

                            <div class="edit-info-area">

                                <div class="body">
                                        <div class="edit-info-area-form">
                                                <div class="gocover" style="background: url({{ asset('assets/images/'.$gs->loader) }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
                                                <form>
                                                    @include('alerts.admin.form-both')

                                                    <div class="row mb-4">
                                                        <div class="col-lg-4 text-right pt-2 f-14">
                                                            <label>{{ __('Your Referral Link *') }} <a id="affilate_click" data-toggle="tooltip" data-placement="top" title="Copy"  href="javascript:;" class="mybtn1 copy border"><i class="fas fa-copy"></i></a></label>
                                                            <br>
                                                            <small>{{ __('This is your referral link just copy the link and paste anywhere you want.') }}</small>
                                                        </div>
                                                        <div class="col-lg-8 pt-2">
                                                             <textarea id="refferel_address" class="input-field affilate form-control border h--150" name="address" readonly="" row="5">{{ url('/').'/?refferel_code='.$user->refferel_code}}</textarea>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<!--==================== Blog Section End ====================-->

@includeIf('partials.global.common-footer')

@endsection

@section('script')

<script type="text/javascript">

(function($) {
		"use strict";

    $('#affilate_click').on('click',function(){
       var copyText =  document.getElementById("refferel_address");

        /* Select the text field */
        copyText.select();
        copyText.setSelectionRange(0, 99999); /*For mobile devices*/

        /* Copy the text inside the text field */
        document.execCommand("copy");

          });

          $('#affilate_html_click').on('click',function(){
            var copyText =  document.getElementById("affilate_html");
        /* Select the text field */
        copyText.select();
        copyText.setSelectionRange(0, 99999); /*For mobile devices*/

        /* Copy the text inside the text field */
        document.execCommand("copy");

    });

})(jQuery);

</script>

@endsection
