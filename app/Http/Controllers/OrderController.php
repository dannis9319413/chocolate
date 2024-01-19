<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status_id' => 'integer',
            'distributor_id' => 'integer',
            'email' => 'string',
            'name' => 'string',
            'phone' => 'string',
            'address' => 'string',
            'bank_account' => 'string',
            'total' => 'integer',
            'shipment' => 'integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 400, 'errors' => $validator->errors()], 400);
        }

        $validatedData = $validator->validated();

        $query = DB::table('orders');

        if (isset($validatedData['status_id']) && !is_null($validatedData['status_id'])) {
            $query = $query->where('status_id', '=', $validatedData['status_id']);
        }
        if (isset($validatedData['distributor_id']) && !is_null($validatedData['distributor_id'])) {
            $query = $query->where('distributor_id', '=', $validatedData['distributor_id']);
        }
        if (isset($validatedData['email']) && !is_null($validatedData['email'])) {
            $query = $query->where('email', 'LIKE', "%" . $validatedData['email'] . "%");
        }
        if (isset($validatedData['name']) && !is_null($validatedData['name'])) {
            $query = $query->where('name', 'LIKE', "%" . $validatedData['name'] . "%");
        }
        if (isset($validatedData['phone']) && !is_null($validatedData['phone'])) {
            $query = $query->where('phone', 'LIKE', "%" . $validatedData['phone'] . "%");
        }
        if (isset($validatedData['address']) && !is_null($validatedData['address'])) {
            $query = $query->where('address', 'LIKE', "%" . $validatedData['address'] . "%");
        }
        if (isset($validatedData['bank_account']) && !is_null($validatedData['bank_account'])) {
            $query = $query->where('bank_account', 'LIKE', "%" . $validatedData['bank_account'] . "%");
        }
        if (isset($validatedData['total']) && !is_null($validatedData['total'])) {
            $query = $query->where('total', '=', $validatedData['total']);
        }
        if (isset($validatedData['shipment']) && !is_null($validatedData['shipment'])) {
            $query = $query->where('shipment', '=', $validatedData['shipment']);
        }

        $orders = $query->get();

        $records = $orders->map(function ($order) {
            $orderDetails = DB::table('order_details')->where('order_id', '=', $order->id)->get();
            return [
                'order_id' => $order->id,
                'status_id' => $order->status_id,
                'distributor_id' => $order->distributor_id,
                'email' => $order->email,
                'name' => $order->name,
                'phone' => $order->phone,
                'address' => $order->address,
                'bank_account' => $order->bank_account,
                'total' => $order->total,
                'shipment' => $order->shipment,
                'order_details' => $orderDetails->map(function ($orderDetail) {
                    return [
                        'product_name' => $orderDetail->product_name,
                        'price' => $orderDetail->price,
                        'quantity' => $orderDetail->quantity,
                    ];
                })->toArray(),
            ];
        });

        return response()->json($records);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'distributor_id' => 'required|integer',
            'email' => 'required|string',
            'name' => 'required|string',
            'phone' => 'required|string',
            'address' => 'required|string',
            'bank_account' => 'required|string',
            'order_details' => 'required|array',
            'order_details.*.product_id' => 'required|integer',
            'order_details.*.quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 400, 'errors' => $validator->errors()], 400);
        }

        $validatedData = $validator->validated();
        $products = Product::all();
        $total = 0;

        foreach ($validatedData['order_details'] as &$detail) {
            $product = $products->where('id', $detail['product_id'])->first();
            if (!$product) {
                return response()->json(['status' => 400, 'errors' => '找不到商品'], 400);
            }
            $detail['name'] = $product->name;
            $detail['price'] = $product->price;
            $total += $detail['quantity'] * $detail['price'];
        }

        $shipment = $total > 1000 ? 0 : 60;

        try {
            DB::beginTransaction();

            $order = Order::create([
                'status_id' => 1,
                'distributor_id' => $validatedData['distributor_id'],
                'email' => $validatedData['email'],
                'name' => $validatedData['name'],
                'phone' => $validatedData['phone'],
                'address' => $validatedData['address'],
                'bank_account' => $validatedData['bank_account'],
                'total' => $total,
                'shipment' => $shipment,
            ]);

            $orderDetails = [];

            foreach ($validatedData['order_details'] as &$detail) {
                $orderDetails[] = [
                    'order_id' => $order->id,
                    'product_name' => $detail['name'],
                    'price' => $detail['price'],
                    'quantity' => $detail['quantity'],
                ];
            }

            OrderDetail::insert($orderDetails);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => '訂單新增失敗'], 500);
        }

        $recipients = [$validatedData['email'], 'Slimcocotw@gmail.com'];

        Mail::send(
            'emails.chocolate',
            [
                'email' => $validatedData['email'],
                'order' => $order,
                'orderDetails' => $orderDetails
            ],
            function ($message) use ($recipients) {
                $message->from('Slimcocotw@gmail.com', 'Slimcoco');
                $message->to($recipients);
                $message->subject('感謝您的購買');
            }
        );

        return response()->json(['status' => 201, 'message' => '訂單新增成功'], 201);
    }

    public function update(Request $request, $id)
    {
        try {
            $order = Order::findOrFail($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['status' => 404, 'message' => '訂單未找到'], 404);
        }

        $validator = Validator::make($request->all(), [
            'status_id' => 'integer',
            'distributor_id' => 'integer',
            'bank_account' => 'string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 400, 'errors' => $validator->errors()], 400);
        }

        $validatedData = $validator->validated();

        if (isset($validatedData['status_id']) && !is_null($validatedData['status_id'])) {
            $order->status_id = $validatedData['status_id'];
        }
        if (isset($validatedData['distributor_id']) && !is_null($validatedData['distributor_id'])) {
            $order->distributor_id = $validatedData['distributor_id'];
        }
        if (isset($validatedData['bank_account']) && !is_null($validatedData['bank_account'])) {
            $order->bank_account = $validatedData['bank_account'];
        }

        try {
            $order->save();
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 500, 'message' => '訂單更新失敗'], 500);
        }

        return response()->json(['status' => 200, 'message' => '訂單更新成功'], 200);
    }
}
