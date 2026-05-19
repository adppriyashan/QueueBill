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
            color: black;
            padding: 30px 40px;
            text-align: center;
        }

        hr {
            border: none;
            height: 1px;
            background-color: rgb(214, 214, 214);
        }

        .header p {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .content {
            padding-left: 40px;
            padding-right: 40px;
            font-size: 12px;
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
            @if($companyLogo)
                @php
                    $logoPath = $companyLogo;
                    if (!file_exists($logoPath) && file_exists(public_path($logoPath))) {
                        $logoPath = public_path($logoPath);
                    } elseif (!file_exists($logoPath) && file_exists(storage_path('app/public/' . str_replace('storage/', '', $logoPath)))) {
                        $logoPath = storage_path('app/public/' . str_replace('storage/', '', $logoPath));
                    }
                @endphp
                <div style="margin-bottom: 5px;">
                    @if(file_exists($logoPath))
                        <img src="{{ $message->embed($logoPath) }}" alt="Logo"
                            style="height: 150px; max-width: 200px; object-fit: contain; display: inline-block;">
                    @endif

                    <p>{{ $companyName }}</p>

                    @if ($companyAddress)
                        <small style="color: black">{{ $companyAddress }}</small>
                    @endif

                    @if ($companyEmail)
                        <br>
                        <small style="color: black">{{ $companyEmail }}</small>
                    @endif

                    <hr>
                </div>
            @endif
        </div>
        <div class="content">
            {!! nl2br(e($bodyText)) !!}
        </div>
        <div class="footer">
            This is an automated delivery.
        </div>
    </div>
</body>

</html>