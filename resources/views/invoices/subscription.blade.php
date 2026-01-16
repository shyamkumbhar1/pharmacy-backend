<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice - Subscription</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .invoice-details {
            margin-bottom: 30px;
        }
        .invoice-details table {
            width: 100%;
            border-collapse: collapse;
        }
        .invoice-details td {
            padding: 8px;
        }
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .invoice-table th,
        .invoice-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        .invoice-table th {
            background-color: #f2f2f2;
        }
        .total {
            text-align: right;
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Pharmacy Management System</h1>
        <h2>Subscription Invoice</h2>
    </div>

    <div class="invoice-details">
        <table>
            <tr>
                <td><strong>Invoice Number:</strong></td>
                <td>INV-{{ str_pad($subscription->id, 6, '0', STR_PAD_LEFT) }}</td>
            </tr>
            <tr>
                <td><strong>Date:</strong></td>
                <td>{{ $subscription->created_at->format('d M Y') }}</td>
            </tr>
            <tr>
                <td><strong>User:</strong></td>
                <td>{{ $user->name }}</td>
            </tr>
            <tr>
                <td><strong>Email:</strong></td>
                <td>{{ $user->email }}</td>
            </tr>
            <tr>
                <td><strong>Plan:</strong></td>
                <td>{{ ucfirst($subscription->plan_type) }} Subscription</td>
            </tr>
        </table>
    </div>

    <table class="invoice-table">
        <thead>
            <tr>
                <th>Description</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Monthly Subscription Fee</td>
                <td>₹{{ number_format($subscription->amount, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="total">
        <p>Total Amount: ₹{{ number_format($subscription->amount, 2) }}</p>
    </div>

    <div style="margin-top: 40px; text-align: center; color: #666;">
        <p>Thank you for your subscription!</p>
        <p>Please make payment and upload proof for admin approval.</p>
    </div>
</body>
</html>

