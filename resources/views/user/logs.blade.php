@extends('layouts.front')
@section('css')
    <link rel="stylesheet" href="{{asset('assets/front/css/datatables.css')}}">
@endsection
@section('content')
@include('partials.global.common-header')
<!-- breadcrumb -->
<div class="full-row bg-light overlay-dark py-5" style="background-image: url({{ $gs->breadcrumb_banner ? asset('assets/images/'.$gs->breadcrumb_banner):asset('assets/images/noimage.png') }}); background-position: center center; background-size: cover;">
   <div class="container">
      <div class="row text-center text-white">
         <div class="col-12">
            <h3 class="mb-2 text-white">{{ __('User History') }}</h3>
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
            <div class="widget border-0 p-30 widget_categories bg-light account-info">
                <h4 class="widget-title down-line mb-30">{{ __('User History') }}</h4>
                <div class="table-responsive">
                    <table class="table order-table" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>{{ __('#Id') }}</th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Email') }}</th>
                                <th>{{ __('Number') }}</th>
                                <th>{{ __('Address') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($refferel_user as $key => $data)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ucwords($data->name)}}</td>
                                    <td>{{$data->email}}</td>
                                    <td>{{$data->phone}}</td>
                                    <td>{{$data->address}}</td>
                                </tr>
                            @empty
                            @endforelse
                        </tbody>
                    </table>
                  {{ $refferel_user->links() }}
                </div>
            </div>
        </div>
      </div>
       {{-- Statistic section End--}}
      
   </div>
</div>
<!--==================== Blog Section End ====================-->
@includeIf('partials.global.common-footer')
@endsection