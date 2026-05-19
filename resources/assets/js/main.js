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
        // Jika ada nilai sebelumnya di localStorage? (opsional)
        const lastDomain = localStorage.getItem('unit_last_domain');
        if (lastDomain && ns.domains.some(d => d.key === lastDomain)) {
          domainSelect.value = lastDomain;
          domainSelect.dispatchEvent(new Event('change'));
        }
      } catch (err) {
        window.tgApp?.showToast('Gagal memuat domain', 'danger');
      }
    }

    // Muat satuan ke dropdown target
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
      } catch (err) {
        window.tgApp?.showToast('Gagal memuat satuan', 'danger');
      }
    }

    // Saat domain berubah
    domainSelect.addEventListener('change', function() {
      const domain = this.value;
      localStorage.setItem('unit_last_domain', domain); // simpan preferensi
      fromSelect.innerHTML = '<option value="">Pilih satuan</option>';
      toSelect.innerHTML = '<option value="">Pilih satuan</option>';
      fromSelect.disabled = !domain;
      toSelect.disabled = !domain;
      hideError();
      resultContainer.classList.add('d-none');

      if (domain) {
        loadUnitsForDomain(domain, fromSelect);
        loadUnitsForDomain(domain, toSelect);
      }
    });

    // Konversi
    async function doConversion() {
      const value = parseFloat(valueInput.value);
      const fromId = fromSelect.value;
      const toId = toSelect.value;

      if (isNaN(value)) {
        window.tgApp?.showToast('Masukkan nilai numerik', 'warning');
        return;
      }
      if (!fromId || !toId) {
        window.tgApp?.showToast('Pilih satuan sumber dan tujuan', 'warning');
        return;
      }

      hideError();
      resultContainer.classList.add('d-none');
      window.tgApp?.showLoading?.('Mengonversi...');

      try {
        const resp = await ns.fetchWithAuth(ns.BASE_URL + '/api/units/convert', {
          method: 'POST',
          body: JSON.stringify({
            value, from: fromId, to: toId
          })
        });
        window.tgApp?.hideLoading?.();
        showResult(resp.data);
        // Simpan riwayat
        ns.addToHistory(resp.data);
        // Scroll ke hasil
        resultContainer.scrollIntoView({
          behavior: 'smooth', block: 'nearest'
        });
      } catch (err) {
        window.tgApp?.hideLoading?.();
        showError(err.message || 'Konversi gagal');
        window.tgApp?.showToast(err.message || 'Konversi gagal', 'danger');
      }
    }

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
      });

    // Salin hasil
    copyResultBtn.addEventListener('click',
      function() {
        if (ns.currentResult) {
          window.tgApp?.copyToClipboard(ns.currentResult);
        }
      });

    // Fungsi reuse dari riwayat (dipanggil dari core)
    ns._reuseConversionUI = function(fromId,
      toId) {
      // Cari domain dari fromId
      const parts = fromId.split('.');
      // PhpUnitConversion.Unit.Area.Acre -> Area
      const domain = parts[2] || '';
      if (domain && ns.domains.some(d => d.key === domain)) {
        domainSelect.value = domain;
        domainSelect.dispatchEvent(new Event('change'));
        // Tunggu sebentar sampai dropdown terisi lalu set nilai
        setTimeout(() => {
          fromSelect.value = fromId;
          toSelect.value = toId;
          valueInput.value = '';
          hideError();
          resultContainer.classList.add('d-none');
          window.scrollTo({
            top: 0, behavior: 'smooth'
          });
        }, 200);
      } else {
        // fallback: tidak bisa reuse
        window.tgApp?.showToast('Domain tidak ditemukan', 'warning');
      }
    };

    // Render riwayat saat inisialisasi
    ns.renderHistory();

    // Muat domain pertama kali
    loadDomains();
  });
})(window.UnitConverter);