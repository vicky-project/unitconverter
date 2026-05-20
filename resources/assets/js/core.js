(function(ns) {
  // State aplikasi
  ns.currentResult = null;
  ns.unitsByDomain = {}; // { 'Area': [ {id, name, symbol}, ... ], 'Length': [...] }
  ns.unitsData = null; // cache daftar satuan per domain
  ns.domains = []; // daftar domain
  ns.lastConversionData = null;

  // Fetch dengan token Sanctum (mengandalkan tgApp.fetchWithAuth dari layout)
  ns.fetchWithAuth = async function(url, options = {}) {
    // Manfaatkan global TelegramApp.fetchWithAuth
    if (tgApp && typeof tgApp.fetchWithAuth === 'function') {
      return tgApp.fetchWithAuth(url, options);
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
})(window.UnitConverter);