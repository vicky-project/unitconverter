@extends('layouts.miniapp')

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
        <label for="fromUnit" class="form-label fw-semibold">Dari Satuan</label>
        <select id="fromUnit" class="form-select form-select-lg"></select>
      </div>
      <div class="mb-2">
        <label for="toUnit" class="form-label fw-semibold">Ke Satuan</label>
        <select id="toUnit" class="form-select form-select-lg"></select>
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
          <div class="d-flex align-items-baseline flex-wrap">
            <span id="resultValue" class="fs-3 fw-bold text-success me-2"></span>
            <span id="resultUnitSymbol" class="text-secondary fs-6"></span>
          </div>
          <div class="text-muted small mt-1">
            <span id="resultFromInfo"></span>
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
  (function() {
  // ---------- State ----------
  let unitsData = null; // Grup sistem berisi array unit
  let currentResult = null;

  // ---------- DOM ----------
  const fromSelect = document.getElementById('fromUnit');
  const toSelect = document.getElementById('toUnit');
  const valueInput = document.getElementById('valueInput');
  const convertBtn = document.getElementById('convertBtn');
  const swapBtn = document.getElementById('swapUnitsBtn');
  const resultContainer = document.getElementById('resultContainer');
  const resultValue = document.getElementById('resultValue');
  const resultUnitSymbol = document.getElementById('resultUnitSymbol');
  const resultFromInfo = document.getElementById('resultFromInfo');
  const copyResultBtn = document.getElementById('copyResultBtn');
  const errorContainer = document.getElementById('errorContainer');
  const errorMessage = document.getElementById('errorMessage');
  const historyList = document.getElementById('historyList');

  // ---------- Helper: Render dropdown dengan optgroup ----------
  function populateSelect(selectEl, data) {
  selectEl.innerHTML = '';
  if (!data) return;
  for (const [system, units] of Object.entries(data)) {
  const optgroup = document.createElement('optgroup');
  optgroup.label = system;
  for (const unit of units) {
  const option = document.createElement('option');
  option.value = unit.id;
  option.textContent = `${unit.symbol} - ${unit.name}`;
  optgroup.appendChild(option);
  }
  selectEl.appendChild(optgroup);
  }
  }

  // ---------- Load Units ----------
  async function loadUnits() {
  try {
  const resp = await tgApp.fetchWithAuth('/api/units/all');
  unitsData = resp.data;
  populateSelect(fromSelect, unitsData);
  populateSelect(toSelect, unitsData);
  } catch (err) {
  tgApp.showToast('Gagal memuat daftar satuan', 'danger');
  errorContainer.classList.remove('d-none');
  errorMessage.textContent = 'Gagal memuat satuan. Silakan muat ulang.';
  }
  }

  // ---------- Validasi sederhana apakah dua unit bisa dikonversi ----------
  // Kita tidak bisa tahu domain tanpa API, tapi kita bisa tangani error dari server
  // ---------- Konversi ----------
  async function doConversion() {
  const value = parseFloat(valueInput.value);
  const fromId = fromSelect.value;
  const toId = toSelect.value;

  if (isNaN(value)) {
  tgApp.showToast('Masukkan nilai numerik', 'warning');
  return;
  }
  if (!fromId || !toId) {
  tgApp.showToast('Pilih satuan sumber dan tujuan', 'warning');
  return;
  }

  // Sembunyikan hasil sebelumnya
  resultContainer.classList.add('d-none');
  errorContainer.classList.add('d-none');

  tgApp.showLoading('Mengonversi...');
  try {
  const resp = await tgApp.fetchWithAuth('/api/units/convert', {
  method: 'POST',
  body: JSON.stringify({ value, from: fromId, to: toId })
  });
  tgApp.hideLoading();
  const data = resp.data;

  // Tampilkan hasil
  resultValue.textContent = data.result;
  resultUnitSymbol.textContent = toSelect.options[toSelect.selectedIndex].text.split(' - ')[0] || '';
  resultFromInfo.textContent = `${data.value} ${fromSelect.options[fromSelect.selectedIndex].text.split(' - ')[0]} =`;

  resultContainer.classList.remove('d-none');
  currentResult = data.result;

  // Simpan ke riwayat (localStorage)
  addToHistory(data);
  // Scroll ke hasil
  resultContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
  } catch (err) {
  tgApp.hideLoading();
  errorContainer.classList.remove('d-none');
  errorMessage.textContent = err.message || 'Konversi gagal';
  tgApp.showToast(err.message || 'Konversi gagal', 'danger');
  }
  }

  // ---------- Swap units ----------
  function swapUnits() {
  const fromVal = fromSelect.value;
  const toVal = toSelect.value;
  fromSelect.value = toVal;
  toSelect.value = fromVal;
  // Jika ada hasil, kosongkan
  resultContainer.classList.add('d-none');
  errorContainer.classList.add('d-none');
  }

  // ---------- Salin hasil ----------
  function copyResult() {
  if (currentResult) {
  tgApp.copyToClipboard(currentResult);
  }
  }

  // ---------- Riwayat di localStorage ----------
  function addToHistory(convData) {
  let history = JSON.parse(localStorage.getItem('unit_convert_history') || '[]');
  // Batasi 10 entri
  history.unshift(convData);
  if (history.length > 10) history.pop();
  localStorage.setItem('unit_convert_history', JSON.stringify(history));
  renderHistory();
  }

  function renderHistory() {
  const history = JSON.parse(localStorage.getItem('unit_convert_history') || '[]');
  historyList.innerHTML = '';
  if (history.length === 0) {
  historyList.innerHTML = '<div class="text-muted small">Belum ada riwayat</div>';
  return;
  }
  history.forEach(item => {
  const div = document.createElement('div');
  div.className = 'list-group-item d-flex justify-content-between align-items-center px-0 py-1';
  // Ambil simbol dari unit id dengan cara memotong
  let fromSymbol = item.from.split('.').pop();
  let toSymbol = item.to.split('.').pop();
  // Jika dari dan to adalah class name, ambil potongan setelah titik terakhir
  div.innerHTML = `
  <span class="text-truncate me-2">${item.value} ${fromSymbol} → <strong>${item.result} ${toSymbol}</strong></span>
  <button class="btn btn-link btn-sm p-0 text-decoration-none reuse-btn" data-from="${item.from}" data-to="${item.to}"><i class="bi bi-arrow-clockwise"></i></button>
  `;
  // Klik tombol reuse isi ulang dropdown dan nilai
  div.querySelector('.reuse-btn').addEventListener('click', function(e) {
  const fromId = this.dataset.from;
  const toId = this.dataset.to;
  fromSelect.value = fromId;
  toSelect.value = toId;
  valueInput.value = '';
  resultContainer.classList.add('d-none');
  errorContainer.classList.add('d-none');
  window.scrollTo({ top: 0, behavior: 'smooth' });
  });
  historyList.appendChild(div);
  });
  }

  // ---------- Event Listeners ----------
  convertBtn.addEventListener('click', doConversion);
  swapBtn.addEventListener('click', swapUnits);
  copyResultBtn.addEventListener('click', copyResult);

  // Enter di input value juga submit
  valueInput.addEventListener('keypress', function(e) {
  if (e.key === 'Enter') {
  e.preventDefault();
  doConversion();
  }
  });

  // ---------- Inisialisasi ----------
  loadUnits();
  renderHistory();

  // Agar dropdown searchable? Cukup pakai select standar, tapi bisa pakai tombol cari nanti jika perlu
  })();
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