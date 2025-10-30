<header class="invoice-header">
  <div class="logo-container">
    @if ($logoBase64)
      <!-- Ja ir pieejams uzņēmuma logo kā Base64 attēls, tas tiek ielādēts -->
      <img src="{{ $logoBase64 }}" alt="Logo" class="logo" />
    @else
      <!-- Ja logo nav pieejams, tiek rādīts placeholder teksts -->
      <div class="logo-placeholder">LOGO</div>
    @endif
  </div>

  <div class="invoice-meta">
    <!-- Rēķina pamatinformācija -->
    <h1>RĒĶINS Nr. {{ $document->id }}</h1>
    <p><strong>Rēķina datums:</strong> {{ $document->invoice_date->format('d.m.Y') ?? 'N/A' }}</p>
    <p><strong>Apmaksāt līdz:</strong> {{ $document->due_date->format('d.m.Y') ?? 'N/A' }}</p>
    <p><strong>Apmaksas termiņš:</strong> {{ $document->delivery_days ?? 'N/A' }} dienas</p>
  </div>
</header>

<section class="addresses">
  <div class="address-block">
    <h2>Sūtītājs</h2>
    <!-- Uzņēmuma informācija – dinamiski padota no Laravel backend -->
    <p><strong>{{ $company->name ?? 'Jūsu uzņēmuma nosaukums' }}</strong><br>
       {{ $company->address ?? 'Jūsu adrese' }}<br>
       {{ $company->city ?? 'Pilsēta' }}, {{ $company->postal_code ?? 'Pasta indekss' }}</p>

    <table class="info-table">
      <!-- Uzņēmuma reģistrācijas un nodokļu dati -->
      <tr><td>Reģistrācijas numurs:</td><td>{{ $company->registration_number ?? 'N/A' }}</td></tr>
      <tr><td>PVN numurs:</td><td>{{ $company->vat_number ?? 'N/A' }}</td></tr>
      <tr><td>Banka:</td><td>{{ $company->bank_name ?? 'N/A' }}</td></tr>
      <tr><td>Bankas konta numurs:</td><td>{{ $company->account_number ?? 'N/A' }}</td></tr>
    </table>
  </div>
</section>