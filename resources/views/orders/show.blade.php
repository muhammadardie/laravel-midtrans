@extends('layouts.app') 
@section('content')
    <div class="container pb-5 pt-5" style="max-width: 90%;">
        <div class="row">
            <div class="col-12 col-md-8">
                <div class="card shadow">
                    <div class="card-header">
                        <h5>Detail Order</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-condensed">
                            <tr>
                                <td>Invoice</td>
                                <td><b>#{{ $order->invoice }}</b></td>
                            </tr>
                            <tr>
                                <td>Total Amount</td>
                                <td><b>Rp {{ number_format($order->gross_amount, 0, ',', '.') }}</b></td>
                            </tr>
                            <tr>
                                <td>Payment Type</td>
                                @if($order->payment_type === 'echannel')
                                    <td>
                                        <b>Bank Transfer</b>
                                    </td>
                                @elseif($order->payment_type === 'cstore')
                                    <td>
                                        <b>Indomart / Alfamart</b>
                                    </td>
                                @else
                                    <td>
                                        <b> {{ str_replace('_',' ', Str::title($order->payment_type)) }}</b>
                                    </td>
                                @endif
                            </tr>
                            @if($order->payment_type === 'bank_transfer')
                                <tr>
                                    <td>Bank</td>
                                    <td><b> {{ Str::upper($order->bank) }}</b></td>
                                </tr>
                                <tr>
                                    <td>VA Number</td>
                                    <td><b> {{ $order->va_number }}</b></td>
                                </tr>
                            @elseif($order->payment_type === 'echannel')
                                <tr>
                                    <td>Bank</td>
                                    <td><b> {{ Str::upper($order->bank) }}</b></td>
                                </tr>
                                <tr>
                                    <td>Bill Key</td>
                                    <td><b> {{ $order->bill_key }}</b></td>
                                </tr>
                                <tr>
                                    <td>Biller Code</td>
                                    <td><b> {{ $order->biller_code }}</b></td>
                                </tr>
                            @elseif($order->payment_type === 'cstore')
                                <tr>
                                    <td>Payment Code</td>
                                    <td><b> {{ $order->payment_code }}</b></td>
                                </tr>
                            @elseif($order->payment_type === 'credit_card')
                                <tr>
                                    <td>Bank</td>
                                    <td><b> {{ Str::upper($order->bank) }}</b></td>
                                </tr>
                                <tr>
                                    <td>Card Type</td>
                                    <td><b> {{ $order->card_type }}</b></td>
                                </tr>
                                <tr>
                                    <td>Masked Card</td>
                                    <td><b> {{ $order->masked_card }}</b></td>
                                </tr>
                            @endif

                            @if($order->payment_type === 'bank_transfer' || $order->payment_type === 'cstore')
                                <tr>
                                    <td>Payment Instruction URL</td>
                                    <td><a href="{{ $order->instruction_url }}"> URL</a></td>
                                </tr>
                            @endif
                            
                            <tr>
                                <td>Transaction Status</td>
                                <td>
                                    @if($order->transaction_status == 'pending')
                                        <span class="badge badge-pill badge-danger">
                                            Menunggu pembayaran
                                        </span>
                                    @elseif($order->transaction_status == 'settlement' || $order->transaction_status == 'capture')
                                        <span class="badge badge-pill badge-success">
                                            Pembayaran telah diterima
                                        </span>
                                    @elseif($order->transaction_status == 'expire')
                                        <span class="badge badge-pill badge-warning">
                                            Kadaluarsa
                                        </span>
                                    @else
                                        <span class="badge badge-pill badge-info">
                                            {{ $order->transaction_status }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @if($order->transaction_status == 'pending')
                                <tr>
                                    <td>Order Time</td>
                                    <td><b>{{ $order->transaction_time->format('d M Y H:i') }}</b></td>
                                </tr>
                            @elseif($order->transaction_status == 'settlement')
                                <tr>
                                    <td>Payment Time</td>
                                    <td><b>{{ $order->settlement_time->format('d M Y H:i') }}</b></td>
                                </tr>
                            @elseif($order->transaction_status == 'capture')
                                <tr>
                                    <td>Payment Time</td>
                                    <td><b>{{ $order->transaction_time->format('d M Y H:i') }}</b></td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card shadow">
                    <div class="card-header">
                        <h5>Item Details</h5>
                    </div>
                    <table class="table table-hover table-condensed">
                        @foreach($order->orderDetail as $orderDetail)
                            <tr>
                                <td>
                                    <b>{{ $orderDetail->product->name }}</b>
                                    &nbsp;
                                    <span style="font-size:12px">x{{ $orderDetail->quantity }}</span>
                                </td>
                                <td>
                                    <b>Rp. {{ number_format($orderDetail->price * $orderDetail->quantity, 0, ',', '.') }}</b>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection