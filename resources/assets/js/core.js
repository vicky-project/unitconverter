(function(ns) {
  // State aplikasi
  ns.currentResult = null;
  ns.unitsData = null; // cache daftar satuan per domain
  ns.domains = []; // daftar domain

  // Fetch dengan token Sanctum (mengandalkan tgApp.fetchWithAuth dari layout)
  ns.fetchWithAuth = async function(url, options = {}) {
    // Manfaatkan global TelegramApp.fetchWithAuth
    if (window.tgApp && typeof window.tgApp.fetchWithAuth === 'function') {
      return window.tgApp.fetchWithAuth(url, options);
    }
    // Fallback manual jika tgApp tidak tersedia (untuk pengembangan lokal)
    const token = localStorage.getItem('telegram_token');
    const headers = {
      'Accept': 'application/json',
      ...options.headers
    };
    if (token) {
      headers['Authorization'] = `Bearer ${token}`;
    }
    if (options.body && !headers['Content-Type']) {
      headers['Content-Type'] = 'application/json';
    }
    const response = await fetch(url, {
      ...options, headers
    });
    const data = await response.json();
    if (!response.ok) {
      const error = new Error(data?.message || data?.error || `HTTP ${response.status}`);
      error.status = response.status;
      error.data = data;
      throw error;
    }
    return data;
  };

  // Riwayat (localStorage)
  ns.addToHistory = function(convData) {
    let history = JSON.parse(localStorage.getItem('unit_convert_history') || '[]');
    history.unshift(convData);
    if (history.length > 10) history.pop();
    localStorage.setItem('unit_convert_history', JSON.stringify(history));
    ns.renderHistory();
  };

  ns.renderHistory = function() {
    const container = document.getElementById('historyList');
    if (!container) return;
    const history = JSON.parse(localStorage.getItem('unit_convert_history') || '[]');
    container.innerHTML = '';
    if (history.length === 0) {
      container.innerHTML = '<div class="text-muted small">Belum ada riwayat</div>';
      return;
    }
    history.forEach(item => {
      const div = document.createElement('div');
      div.className = 'list-group-item d-flex justify-content-between align-items-center px-0 py-1';
      const fromSymbol = item.from.split('.').pop();
      const toSymbol = item.to.split('.').pop();
      div.innerHTML = `
      <span class="text-truncate me-2">${item.value} ${fromSymbol} → <strong>${item.result} ${toSymbol}</strong></span>
      <button class="btn btn-link btn-sm p-0 text-decoration-none reuse-btn" data-from="${item.from}" data-to="${item.to}">
      <i class="bi bi-arrow-clockwise"></i>
      </button>`;
      div.querySelector('.reuse-btn').addEventListener('click', function(e) {
        const btn = e.currentTarget;
        ns.reuseConversion(btn.dataset.from, btn.dataset.to);
      });
      container.appendChild(div);
    });
  };

  ns.reuseConversion = function(fromId, toId) {
    // Fungsi ini akan di-overwrite oleh main.js setelah UI siap
    if (typeof ns._reuseConversionUI === 'function') {
      ns._reuseConversionUI(fromId, toId);
    }
  };

})(window.UnitConverter);