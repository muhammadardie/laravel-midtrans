<?php

namespace App\Http\Controllers;

use App\Models\{ Order, OrderDetail, Product };
use App\Services\Midtrans\SnapTokenService;
use App\Helpers\NumberHelper;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::all();
        $products = Product::all();

        return view('orders.index', compact('orders', 'products'));
    }

    public function generateSnapToken(Request $request)
    {
        $order = [
            'transaction_details' => [
                'order_id' => NumberHelper::getRandomNumber(),
                'gross_amount' => $request->total,
            ],
            'item_details' => [
                [
                    'id' => $request->productId,
                    'price' => $request->productPrice,
                    'quantity' => $request->qty,
                    'name' => $request->productName,
                ]
            ]
        ];

        $snapTokenService = new SnapTokenService();
        $snapToken = $snapTokenService->getSnapToken($order);

        return ['snap_token' => $snapToken, 'order_detail' => $order];
    }

    /**
     * map response from midtrans payment into orders table
     * this function actually need to break down into each service
     * 
     * @return \Illuminate\Http\Response
     */
    public function saveOrder(Request $request)
    {
        $resMidtrans = $request->resMidtrans;
        $itemDetails = $request->orderDetail['item_details'];

        $newOrder                       = [];
        $newOrder['invoice']            = $resMidtrans['order_id'];
        $newOrder['snap_token']         = $resMidtrans['transaction_id'];
        $newOrder['gross_amount']       = $resMidtrans['gross_amount'];
        $newOrder['payment_type']       = $resMidtrans['payment_type'];
        $newOrder['status_code']        = $resMidtrans['status_code'];
        $newOrder['status_message']     = $resMidtrans['status_message'];
        $newOrder['transaction_status'] = $resMidtrans['transaction_status'];
        $newOrder['transaction_time']   = $resMidtrans['transaction_time'];

        if(array_key_exists('pdf_url', $resMidtrans))
        {
            $newOrder['instruction_url'] = $resMidtrans['pdf_url'];
        }

        if(array_key_exists('fraud_status', $resMidtrans))
        {
            $newOrder['fraud_status'] = $resMidtrans['fraud_status'];
        }

        // capture payment type bank_transfer
        if($resMidtrans['payment_type'] === 'bank_transfer')
        {
            if(array_key_exists('permata_va_number', $resMidtrans))
            {
                $newOrder['bank']      = 'permata';
                $newOrder['va_number'] = $resMidtrans['permata_va_number'];
            }

            if(array_key_exists('va_numbers', $resMidtrans))
            {
                $transferDetail        = $resMidtrans['va_numbers'][0];
                $newOrder['bank']      = $transferDetail['bank'];
                $newOrder['va_number'] = $transferDetail['va_number'];
            }
        }

        // capture payment type "echannel" (mandiri transfer)
        if($resMidtrans['payment_type'] === 'echannel')
        {
            $newOrder['bank']        = 'mandiri';
            $newOrder['bill_key']    = $resMidtrans['bill_key'];
            $newOrder['biller_code'] = $resMidtrans['biller_code'];
        }

        // capture payment type "cstore" (alfamart/indomart)
        if($resMidtrans['payment_type'] === 'cstore')
        {
            $newOrder['payment_code'] = $resMidtrans['payment_code'];
        }

        // capture payment type "credit_card"
        if($resMidtrans['payment_type'] === 'credit_card')
        {
            $newOrder['approval_code'] = $resMidtrans['approval_code'];
            $newOrder['bank']          = $resMidtrans['bank'];
            $newOrder['card_type']     = $resMidtrans['card_type'];
            $newOrder['masked_card']   = $resMidtrans['masked_card'];
        }

        
        $order = Order::create($newOrder);
        
        foreach($itemDetails as $item) {
            $orderDetail = new OrderDetail;
            $orderDetail->order_id   = $order->id;
            $orderDetail->product_id = $item['id'];
            $orderDetail->price      = $item['price'];
            $orderDetail->quantity   = $item['quantity'];
            $orderDetail->save();
        }
        

        return ['status' => $order];  
    }

    public function generateInvoice() 
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        return view('orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function updateOrder(Request $request)
    {
        \DB::beginTransaction();
        $transaction = false;

        try {
            $order = Order::where('snap_token', $request->transaction_id)
                            ->where('invoice', $request->order_id)
                            ->first();
            
            if(is_null($order)) return response()->json(['status' => false, 'msg' => 'Order not found'],403);
            
            $updateData                       = [];
            $updateData['updated_at']         = $request->transaction_time;
            $updateData['transaction_status'] = $request->transaction_status;
            $updateData['status_message']     = $request->status_message;
            $updateData['status_code']        = $request->status_code;
            $updateData['signature_key']      = $request->signature_key;
            $updateData['transaction_time']   = $request->transaction_time;
            $updateData['settlement_time']    = $request->transaction_status == 'settlement' ? $request->settlement_time : null;
            $updateData['fraud_status']       = $request->fraud_status;
            $order->update($updateData);

            \DB::commit();
            $transaction = true;

        } catch (\Exception $e) {
            \DB::rollback();

            return response()->json(['status' => false, 'msg' => $e->getMessage()],403);
        }

        return response()->json(['status' => true, 'msg' => 'Order updated successfully']);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }
}