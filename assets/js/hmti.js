/* HMTI UNDIP — Shared UI Interactions */
document.addEventListener('DOMContentLoaded', function () {
  // 1. Password visibility toggles
  document.querySelectorAll('[data-toggle-password]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var targetId = btn.getAttribute('data-toggle-password');
      var input = document.getElementById(targetId);
      if (!input) return;
      var isPassword = input.type === 'password';
      input.type = isPassword ? 'text' : 'password';
      var icon = btn.querySelector('i');
      if (icon) {
        icon.className = isPassword ? 'bi bi-eye-slash' : 'bi bi-eye';
      }
      btn.setAttribute('aria-label', isPassword ? 'Sembunyikan password' : 'Tampilkan password');
    });
  });

  // 2. Table search filter (works across all surat-*.php pages)
  var searchInput = document.getElementById('search');
  var tableBody = document.querySelector('table.data-table tbody');
  if (searchInput && tableBody) {
    searchInput.addEventListener('input', function () {
      var query = this.value.trim().toLowerCase();
      var rows = tableBody.querySelectorAll('tr:not(.js-empty-search)');
      var visibleCount = 0;

      rows.forEach(function (row) {
        if (row.classList.contains('js-static-empty')) return;
        var match = row.textContent.toLowerCase().indexOf(query) !== -1;
        row.style.display = match ? '' : 'none';
        if (match) visibleCount++;
      });

      var emptyRow = tableBody.querySelector('tr.js-empty-search');
      var hasStaticEmpty = tableBody.querySelector('tr.js-static-empty');
      if (!hasStaticEmpty) {
        if (visibleCount === 0 && query !== '') {
          if (!emptyRow) {
            var colCount = tableBody.closest('table').querySelectorAll('thead th').length || 4;
            emptyRow = document.createElement('tr');
            emptyRow.className = 'js-empty-search';
            emptyRow.innerHTML =
              '<td colspan="' + colCount + '" class="empty-state">' +
              '<i class="bi bi-search"></i>Tidak ada surat yang cocok dengan "<strong></strong>".</td>';
            tableBody.appendChild(emptyRow);
          }
          emptyRow.querySelector('strong').textContent = searchInput.value.trim();
          emptyRow.style.display = '';
        } else if (emptyRow) {
          emptyRow.style.display = 'none';
        }
      }
    });
  }

  // 3. Populate date selects & live Nomor Surat preview on form step 1
  var tanggalSelect = document.getElementById('tanggal');
  var bulanSelect = document.getElementById('bulan');
  var tahunSelect = document.getElementById('tahun');

  if (tanggalSelect && bulanSelect && tahunSelect && tanggalSelect.options.length <= 1) {
    for (var d = 1; d <= 31; d++) {
      var optD = document.createElement('option');
      optD.value = d;
      optD.textContent = d;
      tanggalSelect.appendChild(optD);
    }

    var bulanList = [
      'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
      'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    bulanList.forEach(function (bulan, idx) {
      var optM = document.createElement('option');
      optM.value = idx + 1;
      optM.textContent = bulan;
      bulanSelect.appendChild(optM);
    });

    var currentYear = new Date().getFullYear();
    for (var y = currentYear - 5; y <= currentYear + 5; y++) {
      var optY = document.createElement('option');
      optY.value = y;
      optY.textContent = y;
      tahunSelect.appendChild(optY);
    }
  }

  var previewEl = document.getElementById('nomorPreviewCode');
  if (previewEl) {
    var nomorEl = document.getElementById('nomor');
    var prokerEl = document.getElementById('programKerja');
    var romawiEl = document.getElementById('bulanRomawi');
    var tahunEl = document.getElementById('tahunInput');

    var updatePreview = function () {
      var n = (nomorEl && nomorEl.value.trim()) || '001';
      var p = (prokerEl && prokerEl.value.trim()) || 'PROKER';
      var r = (romawiEl && romawiEl.value.trim()) || 'XI';
      var t = (tahunEl && tahunEl.value.trim()) || String(new Date().getFullYear());
      previewEl.textContent = n + '/' + p + '/HMTI/' + r + '/' + t;
    };

    [nomorEl, prokerEl, romawiEl, tahunEl].forEach(function (el) {
      if (el) el.addEventListener('input', updatePreview);
    });
    updatePreview();
  }
});
