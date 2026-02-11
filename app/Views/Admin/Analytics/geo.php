<?= $this->extend('layout/admin') ?>

<?= $this->section('page_title') ?>Distribusi Geografis<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<a href="<?= base_url('admin/analytics/overview') ?>" class="inline-flex items-center px-4 py-2 bg-slate-100 text-slate-600 font-bold text-[10px] uppercase tracking-[0.2em] rounded-lg hover:bg-slate-200 transition-all border border-slate-200">
    <i class="fas fa-arrow-left mr-2"></i>Kembali
</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div id="loading-spinner" class="py-20 flex flex-col items-center justify-center space-y-4">
    <div class="w-12 h-12 border-4 border-slate-100 border-t-sky-600 rounded-full animate-spin"></div>
    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Memetakan Lokasi Akses...</span>
</div>

<div id="analytics-content" class="hidden space-y-10">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        <!-- Chart -->
        <div class="lg:col-span-5 bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-200">
            <h3 class="text-xs font-black text-slate-900 uppercase tracking-[0.2em] mb-10 flex items-center">
                <i class="fas fa-chart-pie mr-3 text-sky-600"></i>Proporsi Per Negara
            </h3>
            <div id="countryChart" class="h-80"></div>
        </div>

        <!-- Table -->
        <div class="lg:col-span-7 bg-white rounded-[2.5rem] shadow-sm border border-slate-200 overflow-hidden flex flex-col">
            <div class="px-8 py-6 bg-slate-50 border-b border-slate-200 flex items-center">
                <i class="fas fa-map-marker-alt mr-3 text-sky-600 opacity-50"></i>
                <h3 class="text-xs font-black text-slate-900 uppercase tracking-[0.2em]">Detail Sebaran Pengunjung</h3>
            </div>
            <div class="overflow-x-auto flex-1">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-[10px] font-black text-slate-500 uppercase tracking-widest">
                            <th class="px-8 py-5">Negara</th>
                            <th class="px-8 py-5">Wilayah / Kota</th>
                            <th class="px-8 py-5 text-right">User Base</th>
                        </tr>
                    </thead>
                    <tbody id="geo-data" class="divide-y divide-slate-100"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const geoData = document.getElementById('geo-data');
        const isDark = document.documentElement.classList.contains('dark');

        fetch('<?= base_url('api/analytics/geo') ?>')
            .then(r => r.json())
            .then(data => {
                document.getElementById('loading-spinner').classList.add('hidden');
                document.getElementById('analytics-content').classList.remove('hidden');
                
                const countryUsers = {};
                data.forEach(item => {
                    const c = item.country || 'Unknown';
                    countryUsers[c] = (countryUsers[c] || 0) + parseInt(item.totalUsers);
                });

                const options = {
                    chart: { type: 'donut', height: 320, fontFamily: 'inherit' },
                    theme: { mode: isDark ? 'dark' : 'light' },
                    series: Object.values(countryUsers),
                    labels: Object.keys(countryUsers),
                    colors: ['#0369a1', '#0ea5e9', '#7dd3fc', '#e0f2fe', '#bae6fd'],
                    legend: { position: 'bottom', fontSize: '10px', fontWeight: 700 },
                    dataLabels: { enabled: false },
                    stroke: { show: false },
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '70%',
                                labels: {
                                    show: true,
                                    name: { show: true, fontSize: '12px', fontWeight: 700 },
                                    value: { show: true, fontSize: '20px', fontWeight: 900, formatter: (val) => parseInt(val).toLocaleString() },
                                    total: { show: true, label: 'TOTAL' }
                                }
                            }
                        }
                    }
                };

                new ApexCharts(document.getElementById('countryChart'), options).render();

                geoData.innerHTML = '';
                data.forEach(item => {
                    const row = document.createElement('tr');
                    row.className = 'hover:bg-slate-50 transition-colors group';
                    row.innerHTML = `
                        <td class="px-8 py-6">
                            <div class="flex items-center">
                                <span class="font-bold text-slate-900 tracking-tight text-xs">${item.country || 'Direct'}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="text-[10px] font-bold text-slate-500 uppercase tracking-tighter">${item.region || '-'}</div>
                            <div class="text-[9px] text-slate-400 font-medium italic">${item.city || '-'}</div>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <span class="px-3 py-1 bg-sky-50 text-sky-700 text-[10px] font-black rounded-lg border border-sky-100">
                                ${parseInt(item.totalUsers).toLocaleString()} Users
                            </span>
                        </td>
                    `;
                    geoData.appendChild(row);
                });
            });
    });
</script>

<?= $this->endSection() ?>