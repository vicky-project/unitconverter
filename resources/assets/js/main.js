(function(ns) {
  // Tunggu DOM siap
  document.addEventListener('DOMContentLoaded', function() {
    // ----- DOM element -----
    const domainSelect = document.getElementById('domainSelect');
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
    const conversionTableContainer = document.getElementById('conversionTableContainer');
    const domainNameSpan = document.getElementById('domainName');
    const conversionTableBody = document.getElementById('conversionTableBody');

    // ----- Fungsi helper untuk UI -----
    function showError(msg) {
      errorContainer.classList.remove('d-none');
      errorMessage.textContent = msg;
    }

    function hideError() {
      errorContainer.classList.add('d-none');
    }

    function showResult(data) {
      resultContainer.classList.remove('d-none');
      resultValue.textContent = data.result;
      // Simbol unit tujuan
      const toOption = toSelect.options[toSelect.selectedIndex];
      resultUnitSymbol.textContent = toOption ? toOption.text.split(' – ')[0]: '';
      // Info asal
      const fromOption = fromSelect.options[fromSelect.selectedIndex];
      resultFromInfo.textContent = `${data.value} ${fromOption ? fromOption.text.split(' – ')[0]: ''} =`;
      ns.currentResult = data.result;
    }

    // Muat daftar domain ke domainSelect
    async function loadDomains() {
      try {
        const resp = await ns.fetchWithAuth(ns.BASE_URL + '/api/units/domains');
        ns.domains = resp.data;
        domainSelect.innerHTML = '<option value="">Pilih domain</option>';
        ns.domains.forEach(d => {
          const option = document.createElement('option');
          option.value = d.key;
          option.textContent = d.name;
          domainSelect.appendChild(option);
        });
        // Preferensi domain terakhir
        const lastDomain = localStorage.getItem('unit_last_domain');
        if (lastDomain && ns.domains.some(d => d.key === lastDomain)) {
          domainSelect.value = lastDomain;
          domainSelect.dispatchEvent(new Event('change'));
        }
      } catch (err) {
        tgApp.showToast('Gagal memuat domain', 'danger');
      }
    }

    // Muat satuan ke dropdown target, lalu simpan di ns.unitsByDomain
    async function loadUnitsForDomain(domain, targetSelect) {
      targetSelect.innerHTML = '<option value="">Memuat...</option>';
      targetSelect.disabled = true;
      if (!domain) {
        targetSelect.innerHTML = '<option value="">Pilih satuan</option>';
        return;
      }
      try {
        const resp = await ns.fetchWithAuth(ns.BASE_URL + `/api/units/${domain}`);
        const units = resp.data;
        targetSelect.innerHTML = '<option value="">Pilih satuan</option>';
        units.forEach(u => {
          const option = document.createElement('option');
          option.value = u.id;
          option.textContent = `${u.symbol} – ${u.name}`;
          targetSelect.appendChild(option);
        });
        targetSelect.disabled = false;

        // Simpan cache unit untuk domain (hanya jika belum ada atau paksa refresh)
        if (!ns.unitsByDomain[domain]) {
          ns.unitsByDomain[domain] = units.map(u => ({
            id: u.id,
            name: u.name,
            symbol: u.symbol
          }));
        }
      } catch (err) {
        tgApp.showToast('Gagal memuat satuan', 'danger');
      }
    }

    // Saat domain berubah
    domainSelect.addEventListener('change', function() {
      const domain = this.value;
      localStorage.setItem('unit_last_domain', domain);
      fromSelect.innerHTML = '<option value="">Pilih satuan</option>';
      toSelect.innerHTML = '<option value="">Pilih satuan</option>';
      fromSelect.disabled = !domain;
      toSelect.disabled = !domain;
      hideError();
      resultContainer.classList.add('d-none');
      if (conversionTableContainer) conversionTableContainer.style.display = 'none';

      if (domain) {
        loadUnitsForDomain(domain, fromSelect);
        loadUnitsForDomain(domain, toSelect);
      }
    });

    // Tombol Konversi (satu-ke-satu)
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

      hideError();
      resultContainer.classList.add('d-none');
      tgApp.showLoading?.('Mengonversi...');

      try {
        const resp = await ns.fetchWithAuth(ns.BASE_URL + '/api/units/convert', {
          method: 'POST',
          body: JSON.stringify({
            value, from: fromId, to: toId
          })
        });
        tgApp.hideLoading?.();
        showResult(resp.data);
        resultContainer.scrollIntoView({
          behavior: 'smooth', block: 'nearest'
        });
      } catch (err) {
        tgApp.hideLoading?.();
        showError(err.message || 'Konversi gagal');
        tgApp.showToast(err.message || 'Konversi gagal', 'danger');
      }
    }

    // Tabel konversi otomatis (debounce)
    let debounceTimer = null;

    async function updateConversionTable() {
      const domain = domainSelect.value;
      const fromId = fromSelect.value;
      const value = parseFloat(valueInput.value);

      if (!domain || !fromId || isNaN(value)) {
        if (conversionTableContainer) conversionTableContainer.style.display = 'none';
        return;
      }

      const units = ns.unitsByDomain[domain];
      if (!units || units.length <= 1) {
        if (conversionTableContainer) conversionTableContainer.style.display = 'none';
        return;
      }

      // Tampilkan loading di tabel
      if (domainNameSpan) domainNameSpan.textContent = domain;
      if (conversionTableBody) {
        conversionTableBody.innerHTML = '<tr><td colspan="3" class="text-center text-muted">Memuat...</td></tr>';
      }
      if (conversionTableContainer) conversionTableContainer.style.display = 'block';

      // Filter unit selain sumber
      const targetUnits = units.filter(u => u.id !== fromId);
      const promises = targetUnits.map(async (unit) => {
        try {
          const resp = await ns.fetchWithAuth(ns.BASE_URL + '/api/units/convert', {
            method: 'POST',
            body: JSON.stringify({
              value, from: fromId, to: unit.id
            })
          });
          return {
            name: unit.name, symbol: unit.symbol, result: resp.data.result
          };
        } catch (err) {
          return {
            name: unit.name, symbol: unit.symbol, result: 'Error'
          };
        }
      });

      const results = await Promise.all(promises);
      if (conversionTableBody) {
        conversionTableBody.innerHTML = '';
        results.forEach(row => {
          const tr = document.createElement('tr');
          tr.innerHTML = `
          <td>${row.name}</td>
          <td>${row.symbol}</td>
          <td class="text-end fw-bold">${row.result}</td>
          `;
          conversionTableBody.appendChild(tr);
        });
      }
    }

    // Event: fromSelect berubah -> update tabel (debounce)
    fromSelect.addEventListener('change', function() {
      hideError();
      resultContainer.classList.add('d-none');
      clearTimeout(debounceTimer);
      debounceTimer = setTimeout(updateConversionTable, 300);
    });

    // Event: nilai input berubah -> debounce update tabel
    valueInput.addEventListener('input', function() {
      hideError();
      resultContainer.classList.add('d-none');
      clearTimeout(debounceTimer);
      debounceTimer = setTimeout(updateConversionTable, 300);
    });

    // Tombol Konversi
    convertBtn.addEventListener('click', doConversion);
    valueInput.addEventListener('keypress', function(e) {
      if (e.key === 'Enter') {
        e.preventDefault();
        doConversion();
      }
    });

    // Swap satuan
    swapBtn.addEventListener('click',
      function() {
        const fromVal = fromSelect.value;
        const toVal = toSelect.value;
        fromSelect.value = toVal;
        toSelect.value = fromVal;
        hideError();
        resultContainer.classList.add('d-none');
        // Setelah swap, update tabel jika data lengkap
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(updateConversionTable, 300);
      });

    // Salin hasil
    copyResultBtn.addEventListener('click',
      function() {
        if (ns.currentResult) {
          tgApp.copyToClipboard(ns.currentResult);
        }
      });

    // Muat domain pertama kali
    loadDomains();
  });
})(window.UnitConverter);