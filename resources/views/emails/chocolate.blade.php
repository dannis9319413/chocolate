<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Content</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 20px;
            padding: 20px;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1,
        p {
            text-align: center;
        }

        .align-right {
            text-align: right;
        }

        p {
            font-size: 18px;
            color: #333333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        th,
        td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 12px;
        }

        th {
            background-color: #f2f2f2;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tbody tr:hover {
            background-color: #f1f1f1;
        }

        .im {
            color: #000000 !important;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>親愛的 {{ $order->name }}您好</h1>
        <h1>感謝您的購買</h1>
        <p>您在巧纖可可購買了以下商品</p>
        <table>
            <thead>
                <tr>
                    <th>產品名稱</th>
                    <th>數量</th>
                    <th>總價</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orderDetails as $detail)
                <tr>
                    <td>{{ $detail['product_name'] }}</td>
                    <td>{{ $detail['quantity'] }}</td>
                    <td>${{ $detail['price'] * $detail['quantity'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <p class="align-right">運費: ${{ $order->shipment }}</p>
        <p class="align-right">總價: ${{ $order->total + $order->shipment}}</p>
        <h2>匯款資訊:</h2>
        <h2>戶名: 張智凱</h2>
        <h2>銀行: 永豐銀行008</h2>
        <h2>帳戶號碼: 0240180031892</h2>
    </div>
</body>

</html>