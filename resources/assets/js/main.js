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
    const resultSmartFormat = document.getElementById('resultSmartFormat');

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

      // Hasil utama (tetap asli dari server)
      resultValue.textContent = data.result;

      const toOption = toSelect.options[toSelect.selectedIndex];
      const toSymbol = toOption ? toOption.text.split(' – ')[0]: '';
      resultUnitSymbol.textContent = toSymbol;

      const fromOption = fromSelect.options[fromSelect.selectedIndex];
      const fromSymbol = fromOption ? fromOption.text.split(' – ')[0]: '';
      resultFromInfo.textContent = `${data.value} ${fromSymbol} =`;

      // Format cerdas
      if (resultSmartFormat) {
        const formatted = smartFormat(data.result);
        resultSmartFormat.textContent = `≈ ${formatted} ${toSymbol}`;
      }

      // Simpan untuk salin (teks lengkap)
      ns.currentResult = `${data.value} ${fromSymbol} = ${data.result} ${toSymbol}`;
      ns.lastConversionData = {
        value: data.value,
        from: fromOption?.value,
        to: toOption?.value,
        fromSymbol,
        toSymbol,
        result: data.result
      };

      const saveBtnContainer = document.getElementById('saveToNotesContainer');
      if (saveBtnContainer) {
        if (window.NotesConfig?.notesAvailable) {
          saveBtnContainer.innerHTML = `
          <button id="saveToNotesBtn" class="btn btn-outline-warning btn-sm">
          <i class="bi bi-journal-plus me-1"></i> Simpan ke Notes
          </button>`;
        } else {
          saveBtnContainer.innerHTML = '';
        }
      }
    }

    function smartFormat(value) {
      const num = parseFloat(value);
      if (isNaN(num)) return value;

      // Jika nilai sangat kecil atau besar, gunakan notasi ilmiah
      if (Math.abs(num) < 1e-6 || Math.abs(num) >= 1e15) {
        return num.toExponential(6);
      }

      // Jika desimal, batasi 6 digit di belakang koma
      if (Number.isInteger(num)) {
        return num.toLocaleString('id-ID');
      }

      // Format dengan pemisah ribuan dan maks 6 desimal
      return num.toLocaleString('id-ID', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 6
      });
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

    domainSelect.addEventListener('change', validateForm);
    fromSelect.addEventListener('change', validateForm);
    toSelect.addEventListener('change', validateForm);
    valueInput.addEventListener('input', validateForm);
    convertBtn.disabled = true; // state awal
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

  // Event delegation untuk tombol Simpan ke Notes
  document.body.addEventListener('click',
    async (e) => {
      if (e.target.id === 'saveToNotesBtn' || e.target.closest('#saveToNotesBtn')) {
        e.preventDefault();
        const data = ns.lastConversionData;
        if (!data) return;

        const payload = {
          title: `Konversi: ${data.value} ${data.fromSymbol} = ${data.result} ${data.toSymbol}`,
          content: `<p><strong>${data.value} ${data.fromSymbol}</strong> = <strong>${data.result} ${data.toSymbol}</strong></p>
          <p><small>Domain: ${domainSelect.options[domainSelect.selectedIndex]?.text || ''}</small></p>`,
          type: 'text',
          tags: ['konversi',
            'unitconverter',
            data.fromSymbol,
            data.toSymbol],
          source_module: 'UnitConverter',
          source_id: `${data.from}-${data.to}-${Date.now()}`,
          metadata: {
            from_unit: data.from,
            to_unit: data.to,
            value: data.value,
            result: data.result,
            domain: domainSelect.value
          }
        };

        const btn = document.getElementById('saveToNotesBtn');
        const origHTML = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Menyimpan...';

        try {
          await fetchWithAuth(window.NotesConfig.notesEndpoint, {
            method: 'POST',
            body: JSON.stringify(payload)
          });
          tgApp.showToast('✅ Berhasil disimpan ke Notes!', 'success');
          btn.innerHTML = '<i class="bi bi-check-lg me-1"></i> Tersimpan';
          setTimeout(() => {
            btn.disabled = false;
            btn.innerHTML = origHTML;
          }, 2000);
        } catch (err) {
          tgApp.showToast('❌ Gagal menyimpan: ' + err.message, 'danger');
          btn.disabled = false;
          btn.innerHTML = origHTML;
        }
      }
    });
})(window.UnitConverter);