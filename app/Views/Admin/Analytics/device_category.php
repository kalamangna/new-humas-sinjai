<?= $this->extend('layout/admin') ?>

<?= $this->section('page_title') ?>Analisa Perangkat<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<a href="<?= base_url('admin/analytics/overview') ?>" class="inline-flex items-center px-4 py-2 bg-slate-100 text-slate-600 font-bold text-[10px] uppercase tracking-[0.2em] rounded-lg hover:bg-slate-200 transition-all border border-slate-200">
    <i class="fas fa-arrow-left mr-2"></i>Kembali
</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div id="loading-spinner" class="py-20 flex flex-col items-center justify-center space-y-4">
    <div class="w-12 h-12 border-4 border-slate-100 border-t-amber-500 rounded-full animate-spin"></div>
    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Menganalisa Spesifikasi Akses...</span>
</div>

<div id="analytics-content" class="hidden space-y-10">
    <!-- Charts -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-200">
            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6 text-center">Device Category</h3>
            <div class="h-48 relative"><canvas id="deviceChart"></canvas></div>
        </div>
        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-200">
            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6 text-center">Operating System</h3>
            <div class="h-48 relative"><canvas id="osChart"></canvas></div>
        </div>
        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-200">
            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6 text-center">Web Browser</h3>
            <div class="h-48 relative"><canvas id="browserChart"></canvas></div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-8 py-6 bg-slate-50 border-b border-slate-200 flex items-center">
            <i class="fas fa-microchip mr-3 text-amber-500 opacity-50"></i>
            <h3 class="text-xs font-black text-slate-900 uppercase tracking-[0.2em]">Rincian Teknis Kunjungan</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[10px] font-black text-slate-500 uppercase tracking-widest">
                        <th class="px-8 py-5">Platform / Device</th>
                        <th class="px-8 py-5">OS</th>
                        <th class="px-8 py-5">Browser</th>
                        <th class="px-8 py-5 text-right">Unique Users</th>
                    </tr>
                </thead>
                <tbody id="device-data" class="divide-y divide-slate-100"></tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deviceData = document.getElementById('device-data');

        fetch('<?= base_url('api/analytics/device-category') ?>')
            .then(r => r.json())
            .then(data => {
                document.getElementById('loading-spinner').classList.add('hidden');
                document.getElementById('analytics-content').classList.remove('hidden');
                
                const stats = { device: {}, os: {}, browser: {} };
                data.forEach(i => {
                    stats.device[i.deviceCategory] = (stats.device[i.deviceCategory] || 0) + parseInt(i.totalUsers);
                    stats.os[i.operatingSystem] = (stats.os[i.operatingSystem] || 0) + parseInt(i.totalUsers);
                    stats.browser[i.browser] = (stats.browser[i.browser] || 0) + parseInt(i.totalUsers);
                });

                const cfg = (l, d, c) => ({ type: 'doughnut', data: { labels: l, datasets: [{ data: d, backgroundColor: c, borderWidth: 0 }] }, options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, cutout: '80%' } });
                
                new Chart(document.getElementById('deviceChart'), cfg(Object.keys(stats.device), Object.values(stats.device), ['#f59e0b', '#fbbf24', '#fcd34d']));
                new Chart(document.getElementById('osChart'), cfg(Object.keys(stats.os), Object.values(stats.os), ['#10b981', '#34d399', '#6ee7b7']));
                new Chart(document.getElementById('browserChart'), cfg(Object.keys(stats.browser), Object.values(stats.browser), ['#3b82f6', '#60a5fa', '#93c5fd']));

                deviceData.innerHTML = '';
                data.forEach(item => {
                    const row = document.createElement('tr');
                    row.className = 'hover:bg-slate-50 transition-colors group';
                    row.innerHTML = `
                        <td class="px-8 py-6">
                            <div class="flex items-center">
                                <i class="fas ${getDeviceIcon(item.deviceCategory)} mr-4 text-slate-300 group-hover:text-amber-500 transition-colors"></i>
                                <span class="font-bold text-slate-900 text-xs uppercase tracking-tight">${item.deviceCategory || 'Unknown'}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-[10px] font-black text-slate-500 uppercase tracking-widest">${item.operatingSystem || '-'}</td>
                        <td class="px-8 py-6 text-[10px] font-bold text-slate-400 italic">${item.browser || '-'}</td>
                        <td class="px-8 py-6 text-right">
                            <span class="px-3 py-1 bg-amber-50 text-amber-700 text-[10px] font-black rounded-lg border border-amber-100">
                                ${parseInt(item.totalUsers).toLocaleString()}
                            </span>
                        </td>
                    `;
                    deviceData.appendChild(row);
                });
            });

        function getDeviceIcon(d) {
            d = (d||'').toLowerCase();
            return d.includes('mobile') ? 'fa-mobile-alt' : (d.includes('tablet') ? 'fa-tablet-alt' : 'fa-desktop');
        }
    });
</script>

<?= $this->endSection() ?>