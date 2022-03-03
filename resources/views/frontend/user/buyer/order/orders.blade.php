@extends('frontend.user.buyer.buyer-master')
@section('site-title')
    {{ __('Orders') }}
@endsection
@section('content')

    <x-frontend.seller-buyer-preloader/>

    <!-- Dashboard area Starts -->
    <div class="body-overlay"></div>
    <div class="dashboard-area dashboard-padding">
        <div class="container-fluid">
            <div class="dashboard-contents-wrapper">
                <div class="dashboard-icon">
                    <div class="sidebar-icon">
                        <i class="las la-bars"></i>
                    </div>
                </div>
                @include('frontend.user.buyer.partials.sidebar')
                @if($orders->count() >= 1)
                    <div class="dashboard-right">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="dashboard-settings margin-top-40">
                                    <h2 class="dashboards-title">{{ __('All Orders') }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 margin-top-40">
                                <div>
                                    <div class="table-responsive table-responsive--md">
                                        <table id="all_order_table" class="custom--table">
                                            <thead>
                                                <tr>
                                                    <th> {{ __('Order ID') }} </th>
                                                    <th> {{ __('Customer Name') }} </th>
                                                    <th> {{ __('Service Date') }} </th>
                                                    <th> {{ __('Service Time') }} </th>
                                                    <th> {{ __('Order Pricing') }} </th>
                                                    <th> {{ __('Order Status') }} </th>
                                                    <th> {{ __('Action') }} </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($orders as $order)
                                                    <tr>
                                                        <td data-label="Order ID"> {{ $order->id }} </td>
                                                        <td data-label="Customer Name"> {{ $order->name }} </td>
                                                        <td data-label="Service Date"> {{ $order->date }} </td>
                                                        <td data-label="Service Time"> {{ $order->schedule }}</td>
                                                        <td data-label="Order Pricing"> {{ float_amount_with_currency_symbol($order->total) }}</td>

                                                        @if ($order->status == 0) <td data-label="Order Status" class="pending"><span>{{ __('Pending') }}</span></td>@endif
                                                        @if ($order->status == 1) <td data-label="Order Status" class="order-active"><span>{{ __('Active') }}</span></td>@endif
                                                        @if ($order->status == 2) <td data-label="Order Status" class="completed"><span>{{ __('Completed') }}</span></td>@endif
                                                        @if ($order->status == 3) <td data-label="Order Status" class="order-deliver"><span>{{ __('Delivered') }}</span></td>@endif
                                                        @if ($order->status == 4) <td data-label="Order Status" class="canceled"><span>{{ __('Cancelled') }}</span></td>@endif

                                                        <td data-label="Action">
                                                            <a href="{{ route('buyer.order.details', $order->id) }}">
                                                                <span class="icon eye-icon" data-toggle="tooltip" data-placement="top" title="{{ __('View Details') }}">
                                                                    <i class="las la-eye"></i>
                                                                </span>
                                                            </a>
                                                            <a href="{{ route('buyer.support.ticket.new', $order->id) }}">
                                                                <span class="icon eye-icon" data-toggle="tooltip" data-placement="top" title="{{ __('New Ticket') }}">
                                                                    <i class="las la-ticket-alt"></i>
                                                                </span>
                                                            </a>
                                                            <a href="#0" class="edit_status_modal" 
                                                                data-toggle="modal"
                                                                data-target="#editStatusModal" 
                                                                data-id="{{ $order->id }}"
                                                                data-status="{{ $order->status }}"
                                                                >
                                                                <span class="dash-icon color-1" data-toggle="tooltip" data-placement="top" title="{{ __('Change Status') }}"> 
                                                                    <i class="las la-edit"></i>
                                                                </span>
                                                            </a>
                                                            <a href="{{ route('buyer.order.invoice.details',$order->id) }}">
                                                                <span class="icon print-icon" data-toggle="tooltip" data-placement="top" title="{{ __('Print Pdf') }}"> 
                                                                    <i class="las la-print"></i>
                                                                </span>
                                                            </a>
                                                        </td>    
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="blog-pagination margin-top-55">
                                        <div class="custom-pagination mt-4 mt-lg-5">
                                            {!! $orders->links() !!}
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                @else 
                <h2 class="no_data_found">{{ __('No Orders Found') }}</h2>
                @endif
            </div>
        </div>
    </div>

    <!--Status Modal -->
    <div class="modal fade" id="editStatusModal" tabindex="-1" role="dialog" aria-labelledby="editModal"
        aria-hidden="true">
        <form action="{{ route('buyer.order.status') }}" method="post">
            <input type="hidden" id="order_id" name="order_id">
            @csrf
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModal">{{ __('Change Status') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="up_day_id">{{ __('Select Status') }}</label>
                            <select name="status" id="status" class="form-control nice-select">
                                <option value="">{{ __('Select Status') }}</option>
                                <option value="0">{{ __('Pending') }}</option>
                                <option value="1">{{ __('Active') }}</option>
                                <option value="2">{{ __('Completed') }}</option>
                                <option value="3">{{ __('Delivered') }}</option>
                                <option value="4">{{ __('Cancelled') }}</option>
                            </select>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Save changes') }}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection


@section('scripts')
    <script src="{{ asset('assets/backend/js/sweetalert2.js') }}"></script>
    <script>
        (function($) {
            "use strict";

            $(document).ready(function() {

                $(document).on('click', '.edit_status_modal', function(e) {
                    e.preventDefault();
                    let order_id = $(this).data('id');
                    let status = $(this).data('status');

                    $('#order_id').val(order_id);
                    $('#status').val(status);
                    $('.nice-select').niceSelect('update');
                });

            });

        })(jQuery);
    </script>
@endsection
