@extends('telegram::layouts.mini-app')

@section('title', 'Konversi Satuan')

@section('content')
<div class="container py-3">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0"><i class="bi bi-arrow-left-right me-2"></i>Konversi Satuan</h5>
    <div class="d-flex gap-2">
      <button class="btn btn-outline-secondary btn-sm" id="resetBtn" title="Reset">
        <i class="bi bi-x-circle"></i>
      </button>
      <button class="btn btn-outline-secondary btn-sm" id="swapUnitsBtn" title="Tukar satuan">
        <i class="bi bi-arrow-repeat"></i>
      </button>
    </div>
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
          <label for="fromUnit" class="form-label fw-semibold">Dari Satuan <span id="fromUnitCount" class="text-muted small"></span></label>
          <select id="fromUnit" class="form-select form-select-lg" disabled>
            <option value="">Pilih satuan</option>
          </select>
        </div>
        <div class="col-6">
          <label for="toUnit" class="form-label fw-semibold">Ke Satuan <span id="toUnitCount" class="text-muted small"></span></label>
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
    <div class="card border-success overflow-hidden">

      <!-- Baris header: judul + tombol aksi -->
      <div class="card-header d-flex justify-content-between align-items-center py-2 px-3"
        style="background-color: var(--tg-theme-secondary-bg-color); border-bottom: 1px solid var(--tg-theme-section-separator-color);">
        <span class="fw-semibold small">Hasil Konversi</span>
        <div class="d-flex gap-2">
          <button class="btn btn-outline-secondary btn-sm" id="reverseBtn" title="Balik konversi">
            <i class="bi bi-arrow-left-right"></i>
          </button>
          <button class="btn btn-outline-secondary btn-sm" id="copyResultBtn" title="Salin hasil">
            <i class="bi bi-clipboard"></i>
          </button>
          <div id="saveToNotesContainer"></div>
        </div>
      </div>

      <!-- Body: konten hasil -->
      <div class="card-body" style="min-width: 0;">
        <!-- Info dari satuan asal -->
        <div class="text-muted small mb-2">
          <span id="resultFromInfo"></span>
        </div>

        <!-- Angka hasil utama + simbol -->
        <div class="d-flex align-items-baseline flex-wrap mb-1" style="overflow-wrap: anywhere;">
          <span id="resultValue" class="fw-bold text-success me-2 text-break"
            style="font-size: 2.5rem; line-height: 1.2; word-break: break-all;"></span>
          <span id="resultUnitSymbol" class="text-secondary flex-shrink-0"
            style="font-size: 1.25rem;"></span>
        </div>

        <!-- Format cerdas (italic) -->
        <div class="text-muted small fst-italic text-break" id="resultSmartFormat"></div>
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

</div>
@endsection

@push('scripts')
<script>
  window.NotesConfig = @json($notesConfig);
</script>
<script>
  window.UnitConverter = window.UnitConverter || {};
  window.UnitConverter.BASE_URL = '{{ rtrim(config("app.url"), "/") }}';

  {!! file_get_contents(module_path('unitconverter', 'resources/assets/js/core.js')); !!}
  {!! file_get_contents(module_path('unitconverter', 'resources/assets/js/main.js')); !!}
</script>
@endpush

@push('styles')
<style>
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
    #fromUnitCount, #toUnitCount {
    font-weight: normal;
    margin-left: 2px;
    }
    </style>
    @endpush