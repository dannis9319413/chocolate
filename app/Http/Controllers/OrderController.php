<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $model = new Order();

        if (isset($request->status_id) && !is_null($request->status_id)) {
            $model = $model->where('status_id', '=', $request->input('status_id'));
        }
        if (isset($request->distributor_id) && !is_null($request->distributor_id)) {
            $model = $model->where('distributor_id', '=', $request->input('distributor_id'));
        }

        $records = $model->get();

        return response()->json($records);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'back_account' => 'required|string',
            'distributor_id' => 'required|integer',
            'order_details' => 'required|array',
            'order_details.*.product_id' => 'required|integer',
            'order_details.*.quantity' => 'required|integer',
        ]);

        $order = Order::create([
            'status_id' => 1,
            'distributor_id' => $validatedData['distributor_id'],
        ]);

        foreach ($validatedData['order_details'] as $detail) {
            OrderDetail::create([
                'order_id' => $order->id,
                'product_id' => $detail['product_id'],
                'quantity' => $detail['quantity'],
            ]);
        }

        return response()->json(['status' => 201, 'message' => '訂單新增成功'], 201);
    }

    public function update(Request $request, $id)
    {
        try {
            $order = Order::findOrFail($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['status' => 404, 'message' => '訂單未找到'], 404);
        }
        if (isset($request->status_id) && !is_null($request->status_id)) {
            $order->status_id = $request->input('status_id');
        }
        if (isset($request->distributor_id) && !is_null($request->distributor_id)) {
            $order->distributor_id = $request->input('distributor_id');
        }
        $order->save();
        return response()->json(['status' => 201, 'message' => '訂單狀態已更新'], 201);
    }
}
