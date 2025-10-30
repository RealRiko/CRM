<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice</title>
    <style>
        @font-face {
            font-family: 'DejaVu Sans';
            src: url('/path/to/fonts/DejaVuSans.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        @font-face {
            font-family: 'DejaVu Sans';
            src: url('/path/to/fonts/DejaVuSans-Bold.ttf') format('truetype');
            font-weight: bold;
            font-style: normal;
        }
        body {
            font-family: "DejaVu Sans", "Noto Sans", sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #444;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .logo {
            max-width: 120px;
            max-height: 80px;
        }
        .logo-placeholder {
            width: 120px;
            height: 80px;
            background-color: #eee;
            color: #888;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            border: 1px solid #ccc;
        }
        .invoice-meta {
            text-align: right;
        }
        .invoice-meta h1 {
            margin: 0;
            font-size: 18px;
            color: #222;
        }
        .invoice-meta p {
            margin: 2px 0;
        }
        
        /* --- THIS SECTION WAS MODIFIED --- */
        .addresses {
            display: flex;
            justify-content: space-between; /* Pushes blocks to far left and right */
            flex-wrap: nowrap; /* Ensures they stay on one line */
            margin-bottom: 20px;
        }

        .address-block {
            /* flex-grow: 0 (DON'T STRETCH)
              flex-shrink: 1 (Shrink if page is too small)
              flex-basis: auto (Base width on the content inside)
            */
            flex: 0 1 auto; 
            max-width: 48%; /* Safety: won't get wider than 48% */
            box-sizing: border-box;
        }
        /* --- END OF MODIFIED SECTION --- */

        .address-block h2 {
            font-size: 14px;
            margin-bottom: 5px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 3px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            font-size: 11px;
        }
        .info-table td {
            padding: 3px 5px;
            border-bottom: 1px solid #eee;
        }
        .items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 12px;
        }
        .items th {
            background-color: #f2f2f2;
            text-align: left;
            padding: 8px;
            border: 1px solid #ddd;
        }
        .items td {
            padding: 8px;
            border: 1px solid #ddd;
            vertical-align: top;
        }
        .items tbody tr:nth-child(even) {
            background-color: #fafafa;
        }
        .items .qty,
        .items .price,
        .items .total {
            text-align: right;
            white-space: nowrap;
        }
        .summary {
            width: 40%;
            margin-left: auto;
            margin-bottom: 30px;
        }
        .summary table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        .summary td {
            padding: 6px;
            border-bottom: 1px solid #ddd;
            text-align: right;
        }
        .summary td:first-child {
            text-align: left;
        }
        .summary .total-row td {
            font-weight: bold;
            font-size: 13px;
            border-top: 2px solid #333;
        }
        footer {
            border-top: 2px solid #444;
            padding-top: 10px;
            font-size: 11px;
            color: #666;
        }
        .footer-note {
            margin-bottom: 10px;
            font-style: italic;
        }
        .footer-bar {
            display: flex;
            justify-content: center;
            background: #f5f5f5;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-align: center;
        }
        .footer-bar span {
            margin: 0 5px;
        }
    </style>
</head>
<body>
    <header class="invoice-header">
        <div class="logo-container">
            @if ($logoBase64)
                <img src="{{ $logoBase64 }}" alt="Logo" class="logo" />
            @else
                <div class="logo-placeholder">LOGO</div>
            @endif
        </div>
        <div class="invoice-meta">
            <h1>INVOICE No. {{ $document->id }}</h1>
            <p><strong>Invoice Date:</strong> {{ $document->invoice_date?->format('d.m.Y') ?? 'N/A' }}</p>
            <p><strong>Due Date:</strong> {{ $document->due_date?->format('d.m.Y') ?? 'N/A' }}</p>
            <p><strong>Payment Terms:</strong> {{ $document->delivery_days ?? 'N/A' }} days</p>
        </div>
    </header>

<section class="addresses">
    <div class="address-block">
        <h2>Sender</h2>
        <p><strong>{{ $company->name ?? 'Your Company Name' }}</strong><br>
           {{ $company->address ?? 'Your Address' }}<br>
           {{ $company->city ?? 'City' }}, {{ $company->postal_code ?? 'Postal Code' }}</p>
        <table class="info-table">
            <tr><td>Registration No.:</td><td>{{ $company->registration_number ?? 'N/A' }}</td></tr>
            <tr><td>VAT No.:</td><td>{{ $company->vat_number ?? 'N/A' }}</td></tr>
            <tr><td>Bank:</td><td>{{ $company->bank_name ?? 'N/A' }}</td></tr>
            <tr><td>Bank Account No.:</td><td>{{ $company->account_number ?? 'N/A' }}</td></tr>
        </table>
    </div>

    <div class="address-block">
        <h2>Recipient</h2>
        <p><strong>{{ $document->client->name ?? 'N/A' }}</strong><br>
           {{ $document->client->address ?: 'Client address not provided' }}<br>
           {{ $document->client->city ?: 'City' }}, {{ $document->client->postal_code ?: 'Postal Code' }}</p>
        <table class="info-table">
            <tr><td>Registration No.:</td><td>{{ $document->client->registration_number ?: 'N/A' }}</td></tr>
            <tr><td>VAT No.:</td><td>{{ $document->client->vat_number ?: 'N/A' }}</td></tr>
            <tr><td>Bank:</td><td>{{ $document->client->bank ?: 'N/A' }}</td></tr>
            <tr><td>Bank Account No.:</td><td>{{ $document->client->bank_account ?: 'N/A' }}</td></tr>
        </table>
    </div>
</section>

    <main>
        <table class="items">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Description</th>
                    <th class="qty">Quantity</th>
                    <th class="price">Price</th>
                    <th class="total">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($document->lineItems as $item)
                    <tr>
                        <td>{{ $item->product->name ?? 'N/A' }}</td>
                        <td>{{ $item->product->description ?? 'No description' }}</td>
                        <td class="qty">{{ $item->quantity }}</td>
                        <td class="price">€{{ number_format($item->price, 2, ',', ' ') }}</td>
                        <td class="total">€{{ number_format($item->subtotal, 2, ',', ' ') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </main>

<section class="summary">
    <table>
        <tbody>
            @php
                $subtotal = $document->total;
                $vatAmount = $subtotal * 0.21;
                $totalWithVAT = $subtotal + $vatAmount;
            @endphp
            <tr><td>Subtotal:</td><td>€{{ number_format($subtotal, 2, ',', ' ') }}</td></tr>
            <tr><td>VAT 21%:</td><td>€{{ number_format($vatAmount, 2, ',', ' ') }}</td></tr>
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td><strong>Total:</strong></td>
                <td><strong>€{{ number_format($totalWithVAT, 2, ',', ' ') }}</strong></td>
            </tr>
        </tfoot>
    </table>
</section>


    <footer>
        <p class="footer-note">This invoice is generated electronically and is valid without a signature.</p>
        <div class="footer-bar">
            <span>{!! nl2br(e($company->footer_contacts ?? 'Contacts not provided: [Tel.: xxx | Email: xxx | www.xxx]')) !!}</span>
        </div>
    </footer>
</body>
</html>