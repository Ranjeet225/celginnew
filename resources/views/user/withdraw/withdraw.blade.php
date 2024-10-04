@extends('layouts.front')
@section('content')
@include('partials.global.common-header')
<!-- breadcrumb -->
<div class="full-row bg-light overlay-dark py-5" style="background-image: url({{ $gs->breadcrumb_banner ? asset('assets/images/'.$gs->breadcrumb_banner):asset('assets/images/noimage.png') }}); background-position: center center; background-size: cover;">
   <div class="container">
      <div class="row text-center text-white">
         <div class="col-12">
            <h3 class="mb-2 text-white">{{ __('Withdraw') }}
            </h3>
         </div>
         <div class="col-12">
            <nav aria-label="breadcrumb">
               <ol class="breadcrumb mb-0 d-inline-flex bg-transparent p-0">
                  <li class="breadcrumb-item"><a href="{{ route('user-dashboard') }}">{{ __('Dashboard') }}</a></li>
                  <li class="breadcrumb-item active" aria-current="page">{{ __('Withdraw ') }}</li>
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
                     <h4 class="widget-title down-line mb-30">{{ __('My Withdraws') }}
                        <a class="mybtn1" href="{{route('user-wwt-create')}}"> <i class="fas fa-plus"></i> {{ __('Withdraw Now') }}</a>
                     </h4>
                     <hr>
                     <div class="gocover"
                        style="background: url({{ asset('assets/images/'.$gs->loader) }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                     </div>
                     <form id="userform" class="form-horizontal" action="{{route('user-wwt-store')}}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @include('alerts.admin.form-both')
                        @php
                           $balance = preg_replace('/[^\d.]/', '',App\Models\Product::vendorConvertPrice(Auth::user()->affilate_income)) + (preg_replace('/[^\d.]/', '',App\Models\Product::vendorConvertPrice(Auth::user()->referral_income)));
                        @endphp
                        <div class="form-group mb-4">
                           <label class="control-label col-sm-4" for="name">{{ __('Current Balance') }} {{'₹'.$balance }}</label>
                           <button type="button" class="btn-primary btn-sm add-balance-btn float-end" data-bs-toggle="modal" data-bs-target="#addWalletModal"><i class="fas fa-plus"></i> {{ __('Add to Wallet') }}</button>
                        </div>

                     
                        <div class="form-group">
                           <label class="control-label col-sm-4" for="name">{{ __('Withdraw Method') }} *
                           </label>
                           <div class="col-sm-12 mt-2">
                              <select class="form-control border " name="methods" id="withmethod" required>
                                 <option value="">{{ __('Select Withdraw Method') }}</option>
                                 <option value="Bank">{{ __('Bank') }}</option>
                                 <option value="upi">{{ __('UPI ID') }}</option>
                              </select>
                           </div>
                        </div>
                        <div class="form-group mt-4 mb-4" id="upi_id" style="display: none;">
                           <div class="form-group">
                              <label class="control-label col-sm-12" for="name">{{ __('Enter UPI ID') }} *
                              </label>
                              <div class="col-sm-12">
                                 <input name="upi_id" placeholder="{{ __('874309543@payment') }}"
                                    class="form-control border" value="" type="text">
                              </div>
                           </div>
                        </div>
                        <div class="form-group mt-4 mb-4" >
                           <label class="control-label col-sm-12 mb-2" for="name">{{ __('Withdraw Amount') }} *
                           </label>
                           <div class="col-sm-12">
                              <input name="amount" placeholder="{{ __('Withdraw Amount') }}" class="form-control border"
                                 type="text" value="" required>
                           </div>
                        </div>
                        <div class="" id="paypal" style="display: none;">
                           <div class="form-group">
                              <label class="control-label col-sm-12" for="name">{{ __('Enter Account Email') }} *
                              </label>
                              <div class="col-sm-12">
                                 <input name="acc_email" placeholder="{{ __('Enter Account Email') }}"
                                    class="form-control border" value="" type="email">
                              </div>
                           </div>
                        </div>
                        <div id="bank" style="display: none;">
                           <div class="form-group">
                              <label class="control-label col-sm-12" for="name">{{ __('Enter IBAN/Account No') }}
                              *
                              </label>
                              <div class="col-sm-12">
                                 <input name="iban" value="" placeholder="{{ __('Enter IBAN/Account No') }}"
                                    class="form-control" type="text">
                              </div>
                           </div>
                           <div class="form-group">
                              <label class="control-label col-sm-12" for="name">{{ __('Enter Account Name') }} *
                              </label>
                              <div class="col-sm-12">
                                 <input name="acc_name" value="" placeholder="{{ __('Enter Account Name') }}"
                                    class="form-control" type="text">
                              </div>
                           </div>
                           <div class="form-group">
                              <label class="control-label col-sm-12" for="name">{{ __('Enter Address') }} *
                              </label>
                              <div class="col-sm-12">
                                 <input name="address" value="" placeholder="{{ __('Enter Address') }}"
                                    class="form-control" type="text">
                              </div>
                           </div>
                           <div class="form-group">
                              <label class="control-label col-sm-12" for="name">{{ __('Enter Swift Code') }} *
                              </label>
                              <div class="col-sm-12">
                                 <input name="swift" value="" placeholder="{{ __('Enter Swift Code') }}"
                                    class="form-control" type="text">
                              </div>
                           </div>
                        </div>
                        <div class="form-group">
                           <label class="control-label col-sm-12 mb-2"
                              for="name">{{ __('Additional Reference(Optional)') }} *
                           </label>
                           <div class="col-sm-12">
                              <textarea class="form-control border" name="reference" rows="6"
                                 placeholder="{{ __('Additional Reference(Optional)') }}"></textarea>
                           </div>
                        </div>
                        <div id="resp" class="col-md-12 mt-4">
                           <span class="help-block">
                           <strong>{{ __('Withdraw Fee') }} {{ $sign->sign }}{{ $gs->withdraw_fee }}
                           {{ __('and') }} {{ $gs->withdraw_charge }}%
                           {{ __('will deduct from your account.') }}
                           </strong>
                           </span>
                        </div>
                        <hr>
                        <div class="add-product-footer">
                           <button name="addProduct_btn" type="submit" class="mybtn1">{{ __('Withdraw') }}</button>
                        </div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
   <!-- Add Wallet Modal -->
   <div class="modal fade" id="addWalletModal" tabindex="-1" aria-labelledby="addWalletModalLabel" aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="addWalletModalLabel">{{ __('Confirm') }}</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <p>{{ __('Are you sure you want to add your current balance to your wallet?') }}</p>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                  <form action="{{url('user/user-add-wallet')}}" method="POST">
                      @csrf
                      <input type="hidden" name="balance" value="{{ $balance }}">
                      <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                      <button type="submit" class="btn btn-primary">{{ __('Add to Wallet') }}</button>
                  </form>
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

       $("#withmethod").change(function () {
           var method = $(this).val();
           if (method == "Bank") {

               $("#bank").show();
               $("#bank").find('input, select').attr('required', true);

               $("#paypal").hide();
               $("#paypal").find('input').attr('required', false);
               $('#upi_id').hide();

           }
           if(method == "upi"){
               $("#bank").hide();
               $('#upi_id').show();
           }
           if (method != "Bank" ) {
               $("#bank").hide();
               $("#bank").find('input, select').attr('required', false);

               $("#paypal").show();
               $("#paypal").find('input').attr('required', true);
           }
           if (method == "") {
               $("#bank").hide();
               $("#paypal").hide();
               $('#upi_id').hide();
           }

       })

   })(jQuery);

</script>
@endsection
