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
        $query = DB::table('orders')
            ->leftJoin('order_details', 'orders.id', '=', 'order_details.order_id');

        if (isset($request->status_id) && !is_null($request->status_id)) {
            $query = $query->where('status_id', '=', $request->input('status_id'));
        }
        if (isset($request->distributor_id) && !is_null($request->distributor_id)) {
            $query = $query->where('distributor_id', '=', $request->input('distributor_id'));
        }
        if (isset($request->email) && !is_null($request->email)) {
            $query = $query->where('email', 'LIKE', "%" . $request->input('email') . "%");
        }
        if (isset($request->name) && !is_null($request->name)) {
            $query = $query->where('name', 'LIKE', "%" . $request->input('name') . "%");
        }
        if (isset($request->phone) && !is_null($request->phone)) {
            $query = $query->where('phone', 'LIKE', "%" . $request->input('phone') . "%");
        }
        if (isset($request->address) && !is_null($request->address)) {
            $query = $query->where('address', 'LIKE', "%" . $request->input('address') . "%");
        }
        if (isset($request->bank_account) && !is_null($request->bank_account)) {
            $query = $query->where('bank_account', 'LIKE', "%" . $request->input('bank_account') . "%");
        }

        $records = $query->select('orders.*', 'order_details.product_id', 'order_details.quantity')->get();

        $transformedRecords = $records->map(function ($record) {
            return [
                'status_id' => $record->status_id,
                'distributor_id' => $record->distributor_id,
                'name' => $record->name,
                'address' => $record->address,
                'phone' => $record->phone,
                'email' => $record->email,
                'bank_account' => $record->bank_account,
                'order_details' => [
                    'product_id' => $record->product_id,
                    'quantity' => $record->quantity,
                ],
            ];
        });

        return response()->json($transformedRecords);
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
            'order_details.*.product_id' => 'required|string',
            'order_details.*.quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 400, 'errors' => $validator->errors()], 400);
        }

        $validatedData = $validator->validated();
        $products = Product::all();
        $total = 0;

        foreach ($validatedData['order_details'] as &$detail) {
            $product = $products->where('name', $detail['product_id'])->first();
            if (!$product) {
                return response()->json(['status' => 400, 'errors' => '找不到商品'], 400);
            }
            $detail['name'] = $product->name;
            $detail['price'] = $product->price;
            $total += $detail['quantity'] * $detail['price'];
        }

        $shipment = $total > 1000 ? 0 : 60;

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

        $email = $validatedData['email'];

        Mail::send('emails.test', ['email' => $email], function ($message) use ($email) {
            $message->from('Slimcocotw@gmail.com', 'Slimcoco');
            $message->to($email);
            $message->subject('感謝您的購買');
        });

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
            'status_id' => 'required|integer',
            'distributor_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 400, 'errors' => $validator->errors()], 400);
        }

        $validatedData = $validator->validated();

        $order->status_id = $validatedData['status_id'];
        $order->distributor_id = $validatedData['distributor_id'];

        try {
            $order->save();
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['status' => 500, 'message' => '訂單更新失敗'], 500);
        }

        return response()->json(['status' => 200, 'message' => '訂單更新成功'], 200);
    }
}
