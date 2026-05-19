@extends('telegram::layouts.mini-app')

@section('title', 'Konversi Satuan')

@section('content')
<div class="container py-3">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0"><i class="bi bi-arrow-left-right me-2"></i>Konversi Satuan</h5>
    <button class="btn btn-outline-secondary btn-sm" id="swapUnitsBtn" title="Tukar satuan">
      <i class="bi bi-arrow-repeat"></i>
    </button>
  </div>

  <!-- Card: Input Nilai & Pilih Satuan -->
  <div class="card mb-3">
    <div class="card-body pb-2">
      <div class="mb-3">
        <label for="valueInput" class="form-label fw-semibold">Nilai</label>
        <input type="number" id="valueInput" class="form-control form-control-lg" placeholder="Masukkan nilai" step="any" autofocus>
      </div>
      <div class="mb-3">
        <label for="domainSelect" class="form-label fw-semibold">
          <i class="bi bi-diagram-3 me-1"></i>Domain
        </label>
        <select id="domainSelect" class="form-select form-select-lg">
          <option value="">Pilih domain</option>
        </select>
      </div>
      <div class="row mb-3">
        <div class="col-6">
          <label for="fromUnit" class="form-label fw-semibold">Dari Satuan</label>
          <select id="fromUnit" class="form-select form-select-lg" disabled>
            <option value="">Pilih satuan</option>
          </select>
        </div>
        <div class="col-6">
          <label for="toUnit" class="form-label fw-semibold">Ke Satuan</label>
          <select id="toUnit" class="form-select form-select-lg" disabled>
            <option value="">Pilih satuan</option>
          </select>
        </div>
      </div>
      <button id="convertBtn" class="btn btn-primary w-100 btn-lg">
        <i class="bi bi-calculator me-2"></i>Konversi
      </button>
    </div>
  </div>

  <!-- Hasil Konversi -->
  <div id="resultContainer" class="d-none">
    <div class="card border-success">
      <div class="card-body d-flex justify-content-between align-items-start">
        <div>
          <div class="text-muted small">
            Hasil Konversi
          </div>
          <div class="text-muted small mt-1">
            <span id="resultFromInfo"></span>
          </div>
          <div class="d-flex align-items-baseline flex-wrap">
            <span id="resultValue" class="fs-3 fw-bold text-success me-2"></span>
            <span id="resultUnitSymbol" class="text-secondary fs-6"></span>
          </div>
        </div>
        <button class="btn btn-outline-secondary btn-sm" id="copyResultBtn" title="Salin hasil">
          <i class="bi bi-clipboard"></i>
        </button>
      </div>
    </div>
  </div>

  <!-- Error -->
  <div id="errorContainer" class="d-none">
    <div class="alert alert-danger d-flex align-items-center" role="alert">
      <i class="bi bi-exclamation-triangle-fill me-2"></i>
      <div id="errorMessage"></div>
    </div>
  </div>

  <!-- Riwayat Konversi (opsional) -->
  <div class="mt-4" id="historyContainer">
    <h6 class="text-muted mb-2"><i class="bi bi-clock-history me-1"></i>Riwayat</h6>
    <div id="historyList" class="list-group list-group-flush small"></div>
  </div>
</div>
@endsection

@push('scripts')
<script src="//cdn.jsdelivr.net/npm/eruda"></script>
<script>
  eruda.init();
</script>
<script>
  // Pastikan namespace global
  window.UnitConverter = window.UnitConverter || {};
  window.UnitConverter.BASE_URL = '{{ rtrim(config("app.url"), "/") }}';

  {!! file_get_contents(module_path('unitconverter', 'resources/assets/js/core.js')); !!}
  {!! file_get_contents(module_path('unitconverter', 'resources/assets/js/main.js')); !!}
</script>
@endpush

@push('styles')
<style>
  /* Gunakan tema Telegram */
  body {
    background-color: var(--tg-theme-bg-color, #fff);
    color: var(--tg-theme-text-color, #000);
    }
    .card {
    background-color: var(--tg-theme-bg-color);
    border-color: var(--tg-theme-section-separator-color);
    }
    .form-control, .form-select {
    background-color: var(--tg-theme-secondary-bg-color);
    color: var(--tg-theme-text-color);
    border-color: var(--tg-theme-section-separator-color);
    }
    .form-control:focus, .form-select:focus {
    background-color: var(--tg-theme-secondary-bg-color);
    color: var(--tg-theme-text-color);
    border-color: var(--tg-theme-link-color);
    box-shadow: 0 0 0 0.2rem rgba(var(--tg-theme-link-color), 0.25);
    }
    .btn-primary {
    background-color: var(--tg-theme-button-color);
    border-color: var(--tg-theme-button-color);
    color: var(--tg-theme-button-text-color);
    }
    .btn-primary:hover {
    opacity: 0.9;
    background-color: var(--tg-theme-button-color);
    border-color: var(--tg-theme-button-color);
    }
    .text-success {
    color: var(--tg-theme-link-color, #198754) !important;
    }
    .alert-danger {
    background-color: rgba(220,53,69,0.1);
    border-color: rgba(220,53,69,0.3);
    color: var(--tg-theme-text-color);
    }
    select optgroup {
    font-weight: bold;
    color: var(--tg-theme-hint-color);
    }
    #historyList .list-group-item {
    background-color: transparent;
    border-color: var(--tg-theme-section-separator-color);
    }
    </style>
    @endpush