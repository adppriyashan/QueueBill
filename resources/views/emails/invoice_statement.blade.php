<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice Statement</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333333;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f8fafc;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
        }
        .header {
            background-color: #4f46e5;
            color: #ffffff;
            padding: 30px 40px;
            text-align: center;
        }
        .header h2 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }
        .content {
            padding: 40px;
            font-size: 16px;
        }
        .footer {
            background-color: #f1f5f9;
            color: #64748b;
            text-align: center;
            padding: 20px;
            font-size: 12px;
            border-top: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>QueueBill Statement Dispatch</h2>
        </div>
        <div class="content">
            {!! nl2br(e($bodyText)) !!}
        </div>
        <div class="footer">
            This is an automated delivery from your QueueBill billing workspace.
        </div>
    </div>
</body>
</html>
