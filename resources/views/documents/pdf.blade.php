<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <title>Rēķins</title>
    <style>
        /* ... (Your existing CSS styles remain here) ... */
        body {
            font-family: DejaVu Sans, sans-serif;
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

        .addresses {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .address-block {
            width: 48%;
        }

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
            justify-content: space-between;
            background: #f5f5f5;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
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
            <h1>RĒĶINS Nr. {{ $document->id }}</h1>
            <p><strong>Rēķina datums:</strong> {{ $document->invoice_date ? $document->invoice_date->format('d.m.Y') : 'N/A' }}</p>
            <p><strong>Apmaksāt līdz:</strong> {{ $document->due_date ? $document->due_date->format('d.m.Y') : 'N/A' }}</p>
            <p><strong>Apmaksas termiņš:</strong> {{ $document->delivery_days }} dienas</p>
        </div>
    </header>

    <section class="addresses">
        <div class="address-block">
            <h2>Sūtītājs</h2>
            <p><strong>Jūsu uzņēmuma nosaukums</strong><br>
            Jūsu adrese<br>
            Pilsēta, Pasta indekss</p>
            <table class="info-table">
                <tr><td>Reģistrācijas numurs:</td><td>...</td></tr>
                <tr><td>PVN numurs:</td><td>...</td></tr>
                <tr><td>Banka:</td><td>...</td></tr>
                <tr><td>Bankas konta numurs:</td><td>...</td></tr>
            </table>
        </div>

        <div class="address-block">
            <h2>Saņēmējs</h2>
            <p><strong>{{ $document->client->name ?? 'N/A' }}</strong><br>
            {{ $document->client->address ?? 'Klienta adrese nav norādīta' }}<br>
            {{ $document->client->city ?? 'Pilsēta' }}, {{ $document->client->postal_code ?? 'Pasta indekss' }}</p>
            <table class="info-table">
                <tr><td>Reģistrācijas numurs:</td><td>{{ $document->client->registration_number ?? 'N/A' }}</td></tr>
                <tr><td>PVN numurs:</td><td>{{ $document->client->vat_number ?? 'N/A' }}</td></tr>
                <tr><td>Banka:</td><td>{{ $document->client->bank ?? 'N/A' }}</td></tr>
                <tr><td>Bankas konta numurs:</td><td>{{ $document->client->account_number ?? 'N/A' }}</td></tr>
            </table>
        </div>
    </section>

    <main>
        <table class="items">
            <thead>
                <tr>
                    <th>Prece</th>
                    <th>Apraksts</th>
                    <th class="qty">Daudzums</th>
                    <th class="price">Cena</th>
                    <th class="total">Summa</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($document->lineItems as $item)
                    <tr>
                        <td>{{ $item->product->name ?? 'N/A' }}</td>
                        <td>{{ $item->product->description ?? 'Nav apraksta' }}</td>
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
                <tr><td>Apakšsumma:</td><td>€{{ number_format($document->total, 2, ',', ' ') }}</td></tr>
                <tr><td>PVN (ja attiecināms):</td><td>€0,00</td></tr>
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td><strong>Kopā:</strong></td>
                    <td><strong>€{{ number_format($document->total, 2, ',', ' ') }}</strong></td>
                </tr>
            </tfoot>
        </table>
    </section>

    <footer>
        <p class="footer-note">Rēķins sagatavots elektroniski un derīgs bez paraksta.</p>
        <div class="footer-bar">
            <span>Tel.: 25766209</span>
            <span>E-pasts: enriko.camans1@gmail.com</span>
            <span>www.majslapa.lv</span>
        </div>
    </footer>
</body>
</html>