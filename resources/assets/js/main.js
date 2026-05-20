(function(ns) {
  document.addEventListener('DOMContentLoaded', function() {
    // ----- DOM element -----
    const domainSelect = document.getElementById('domainSelect');
    const fromSelect = document.getElementById('fromUnit');
    const toSelect = document.getElementById('toUnit');
    const valueInput = document.getElementById('valueInput');
    const convertBtn = document.getElementById('convertBtn');
    const swapBtn = document.getElementById('swapUnitsBtn');
    const resetBtn = document.getElementById('resetBtn');
    const resultContainer = document.getElementById('resultContainer');
    const resultValue = document.getElementById('resultValue');
    const resultUnitSymbol = document.getElementById('resultUnitSymbol');
    const resultFromInfo = document.getElementById('resultFromInfo');
    const copyResultBtn = document.getElementById('copyResultBtn');
    const reverseBtn = document.getElementById('reverseBtn');
    const errorContainer = document.getElementById('errorContainer');
    const errorMessage = document.getElementById('errorMessage');
    const fromUnitCount = document.getElementById('fromUnitCount');
    const toUnitCount = document.getElementById('toUnitCount');

    // ----- Helper UI -----
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
      const toOption = toSelect.options[toSelect.selectedIndex];
      resultUnitSymbol.textContent = toOption ? toOption.text.split(' – ')[0]: '';
      const fromOption = fromSelect.options[fromSelect.selectedIndex];
      resultFromInfo.textContent = `${data.value} ${fromOption ? fromOption.text.split(' – ')[0]: ''} =`;
      ns.currentResult = data.result;
    }

    // ----- Load Domains -----
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
        const lastDomain = localStorage.getItem('unit_last_domain');
        if (lastDomain && ns.domains.some(d => d.key === lastDomain)) {
          domainSelect.value = lastDomain;
          domainSelect.dispatchEvent(new Event('change'));
        }
      } catch (err) {
        tgApp.showToast('Gagal memuat domain', 'danger');
      }
    }

    // ----- Load Units (dengan indikator jumlah) -----
    async function loadUnitsForDomain(domain, targetSelect, countSpan) {
      targetSelect.innerHTML = '<option value="">Memuat...</option>';
      targetSelect.disabled = true;
      if (countSpan) countSpan.textContent = '';
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
        if (countSpan) countSpan.textContent = `(${units.length})`;
      } catch (err) {
        tgApp.showToast('Gagal memuat satuan', 'danger');
      }
    }

    // ----- Domain change -----
    domainSelect.addEventListener('change', function() {
      const domain = this.value;
      localStorage.setItem('unit_last_domain', domain);
      fromSelect.innerHTML = '<option value="">Pilih satuan</option>';
      toSelect.innerHTML = '<option value="">Pilih satuan</option>';
      fromSelect.disabled = !domain;
      toSelect.disabled = !domain;
      hideError();
      resultContainer.classList.add('d-none');
      if (domain) {
        loadUnitsForDomain(domain, fromSelect, fromUnitCount);
        loadUnitsForDomain(domain, toSelect, toUnitCount);
      } else {
        fromUnitCount.textContent = '';
        toUnitCount.textContent = '';
      }
      validateForm();
    });

    // ----- Validasi input halus -----
    function validateForm() {
      const domain = domainSelect.value;
      const fromId = fromSelect.value;
      const toId = toSelect.value;
      const rawValue = valueInput.value.trim();

      if (!domain || !fromId || !toId) {
        convertBtn.disabled = true;
        valueInput.classList.remove('is-invalid');
        return;
      }
      if (rawValue === '') {
        convertBtn.disabled = true;
        valueInput.classList.remove('is-invalid');
        return;
      }
      const parsed = parseFloat(rawValue);
      const isValidNumber = !isNaN(parsed) && /^-?\d+(\.\d+)?([eE][+-]?\d+)?$/.test(rawValue);
      if (!isValidNumber) {
        convertBtn.disabled = true;
        valueInput.classList.add('is-invalid');
        return;
      }
      valueInput.classList.remove('is-invalid');
      convertBtn.disabled = false;
    }

    domainSelect.addEventListener('change', validateForm);
    fromSelect.addEventListener('change', validateForm);
    toSelect.addEventListener('change', validateForm);
    valueInput.addEventListener('input', validateForm);
    convertBtn.disabled = true; // state awal

    // ----- Konversi -----
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

    convertBtn.addEventListener('click', doConversion);
    valueInput.addEventListener('keypress', function(e) {
      if (e.key === 'Enter') {
        e.preventDefault();
        if (!convertBtn.disabled) doConversion();
      }
    });

    // ----- Swap -----
    swapBtn.addEventListener('click',
      function() {
        const fromVal = fromSelect.value;
        const toVal = toSelect.value;
        fromSelect.value = toVal;
        toSelect.value = fromVal;
        hideError();
        resultContainer.classList.add('d-none');
        validateForm();
      });

    // ----- Balik Konversi (reverse) -----
    reverseBtn.addEventListener('click',
      function() {
        const fromVal = fromSelect.value;
        const toVal = toSelect.value;
        fromSelect.value = toVal;
        toSelect.value = fromVal;
        doConversion();
      });

    // ----- Salin hasil -----
    copyResultBtn.addEventListener('click',
      function() {
        if (ns.currentResult) {
          tgApp.copyToClipboard(ns.currentResult);
        }
      });

    // ----- Reset -----
    function resetForm() {
      domainSelect.value = '';
      localStorage.removeItem('unit_last_domain');
      fromSelect.innerHTML = '<option value="">Pilih satuan</option>';
      toSelect.innerHTML = '<option value="">Pilih satuan</option>';
      fromSelect.disabled = true;
      toSelect.disabled = true;
      fromUnitCount.textContent = '';
      toUnitCount.textContent = '';
      valueInput.value = '';
      hideError();
      resultContainer.classList.add('d-none');
      valueInput.blur();
      convertBtn.disabled = true;
      valueInput.classList.remove('is-invalid');
    }

    resetBtn.addEventListener('click',
      resetForm);

    // ----- Inisialisasi -----
    loadDomains();
  });
})(window.UnitConverter);