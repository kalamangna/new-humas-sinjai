(function () {
  function escapeHtml(val) {
    return String(val ?? "")
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#39;");
  }

  function sanitizeUrl(url) {
    try {
      const parsed = new URL(String(url ?? ""), window.location.origin);
      if (parsed.protocol === "http:" || parsed.protocol === "https:") {
        return parsed.href;
      }
    } catch (e) {}
    return "#";
  }

  const containerId =
    document.currentScript.getAttribute("data-container") || "rss-widget";
  const limit =
    parseInt(document.currentScript.getAttribute("data-limit"), 10) || 5;
  const title =
    document.currentScript.getAttribute("data-title") ||
    "Berita Sinjai";
  const theme = document.currentScript.getAttribute("data-theme") || "light";
  const safeTheme = theme === "dark" ? "dark" : "light";
  const apiUrl = "https://humas.sinjaikab.go.id/v1/rss-widget/index.php";

  fetch(apiUrl)
    .then((res) => res.json())
    .then((data) => {
      const el = document.getElementById(containerId);
      if (!el) return;

      if (data.error) {
        el.innerHTML = `<p style="text-align:center; padding:20px; font-size:12px; color:#666;">${escapeHtml(data.error)}</p>`;
        return;
      }

      // Load FontAwesome if not already present
      if (!document.querySelector('link[href*="font-awesome"]') && !document.querySelector('link[href*="fontawesome"]')) {
        const fa = document.createElement('link');
        fa.rel = 'stylesheet';
        fa.href = 'https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@7.2.0/css/all.min.css';
        document.head.appendChild(fa);
      }

      // struktur widget
      let html = `
        <div class="humas-sinjai-widget humas-sinjai-${safeTheme}">
          <div class="humas-sinjai-title">
            <i class="fa-solid fa-fw fa-newspaper"></i>
            <span>${escapeHtml(title)}</span>
          </div>
          <ul>
      `;

      // daftar berita
      data.slice(0, limit).forEach((item) => {
        const safeLink = escapeHtml(sanitizeUrl(item.link));
        const safeTitle = escapeHtml(item.title);
        const safeDate = escapeHtml(item.pubDate);
        const safeThumb = item.thumbnail ? escapeHtml(sanitizeUrl(item.thumbnail)) : '';

        html += `
          <li>
            <a href="${safeLink}" target="_blank" rel="noopener noreferrer" class="humas-sinjai-item">
              ${
                safeThumb && safeThumb !== '#'
                  ? `
                <div class="humas-sinjai-thumb-wrapper">
                  <img src="${safeThumb}" alt="${safeTitle}" class="humas-sinjai-thumb">
                </div>`
                  : `
                <div class="humas-sinjai-thumb-wrapper" style="display:flex; align-items:center; justify-content:center; background:#f1f5f9;">
                  <i class="fa-solid fa-fw fa-image" style="color:#cbd5e1; font-size:20px;"></i>
                </div>`
              }
              <div class="humas-sinjai-content">
                <span class="humas-sinjai-content-title">${safeTitle}</span>
                <small><i class="fa-regular fa-fw fa-calendar-days" style="font-size:10px;"></i> ${safeDate}</small>
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
              <a href="https://humas.sinjaikab.go.id" target="_blank" rel="noopener noreferrer">Humas Sinjai <i class="fa-solid fa-fw fa-up-right-from-square" style="font-size:8px;"></i></a>
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
