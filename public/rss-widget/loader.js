(function () {
  const containerId =
    document.currentScript.getAttribute("data-container") || "rss-widget";
  const limit =
    parseInt(document.currentScript.getAttribute("data-limit")) || 5;
  const title =
    document.currentScript.getAttribute("data-title") ||
    "Berita Sinjai";
  const theme = document.currentScript.getAttribute("data-theme") || "light";
  const apiUrl = "https://humas.sinjaikab.go.id/v1/rss-widget/index.php";

  fetch(apiUrl)
    .then((res) => res.json())
    .then((data) => {
      const el = document.getElementById(containerId);
      if (!el) return;

      if (data.error) {
        el.innerHTML = `<p style="text-align:center; padding:20px; font-size:12px; color:#666;">${data.error}</p>`;
        return;
      }

      // Load FontAwesome if not already present
      if (!document.querySelector('link[href*="font-awesome"]')) {
        const fa = document.createElement('link');
        fa.rel = 'stylesheet';
        fa.href = 'https://humas.sinjaikab.go.id/assets/fontawesome/css/all.min.css';
        document.head.appendChild(fa);
      }

      // struktur widget
      let html = `
        <div class="humas-sinjai-widget humas-sinjai-${theme}">
          <div class="humas-sinjai-title">
            <i class="fas fa-fw fa-newspaper"></i>
            <span>${title}</span>
          </div>
          <ul>
      `;

      // daftar berita
      data.slice(0, limit).forEach((item) => {
        html += `
          <li>
            <a href="${item.link}" target="_blank" class="humas-sinjai-item">
              ${
                item.thumbnail
                  ? `
                <div class="humas-sinjai-thumb-wrapper">
                  <img src="${item.thumbnail}" alt="${item.title}" class="humas-sinjai-thumb">
                </div>`
                  : `
                <div class="humas-sinjai-thumb-wrapper" style="display:flex; align-items:center; justify-content:center; background:#f1f5f9;">
                  <i class="fas fa-fw fa-image" style="color:#cbd5e1; font-size:20px;"></i>
                </div>`
              }
              <div class="humas-sinjai-content">
                <span class="humas-sinjai-content-title">${item.title}</span>
                <small><i class="far fa-fw fa-calendar-alt" style="font-size:10px;"></i> ${item.pubDate}</small>
              </div>
            </a>
          </li>
        `;
      });

      // footer widget
      html += `
          </ul>
            <div class="humas-sinjai-footer">
              <img src="https://humas.sinjaikab.go.id/v1/humas.png" alt="Logo Sinjai" class="humas-sinjai-footer-logo">
              <a href="https://humas.sinjaikab.go.id" target="_blank">Humas Sinjai <i class="fas fa-fw fa-external-link-alt" style="font-size:8px;"></i></a>
            </div>
        </div>
      `;

      el.innerHTML = html;
    })
    .catch(() => {
      const el = document.getElementById(containerId);
      if (el) el.innerHTML = `<p style="text-align:center; padding:20px; font-size:12px; color:#666;">Gagal memuat berita.</p>`;
    });
})();