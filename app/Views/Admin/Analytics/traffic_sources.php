<?= $this->extend('layout/admin') ?>

<?= $this->section('page_title') ?>Sumber Kunjungan<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<a href="<?= base_url('admin/analytics/overview') ?>" class="inline-flex items-center px-4 py-2 bg-slate-100 text-slate-600 font-bold text-[10px] uppercase tracking-[0.2em] rounded-lg hover:bg-slate-200 transition-all border border-slate-200">
    <i class="fas fa-arrow-left mr-2"></i>Kembali
</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div id="loading-spinner" class="py-20 flex flex-col items-center justify-center space-y-4">
    <div class="w-12 h-12 border-4 border-slate-100 border-t-emerald-600 rounded-full animate-spin"></div>
    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Melacak Jalur Akses...</span>
</div>

<div id="analytics-content" class="hidden">
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-8 py-6 bg-slate-50 border-b border-slate-200 flex items-center">
            <i class="fas fa-globe mr-3 text-emerald-600 opacity-50"></i>
            <h3 class="text-xs font-black text-slate-900 uppercase tracking-[0.2em]">Analisa Sumber Lalu Lintas</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[10px] font-black text-slate-500 uppercase tracking-widest">
                        <th class="px-8 py-5">Source (Sumber)</th>
                        <th class="px-8 py-5">Medium</th>
                        <th class="px-8 py-5">Sesi Aktif</th>
                        <th class="px-8 py-5">Hits</th>
                        <th class="px-8 py-5 text-right">Engagement</th>
                    </tr>
                </thead>
                <tbody id="traffic-sources-data" class="divide-y divide-slate-100"></tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const trafficSourcesData = document.getElementById('traffic-sources-data');

        fetch('<?= base_url('api/analytics/traffic-sources') ?>')
            .then(r => r.json())
            .then(data => {
                document.getElementById('loading-spinner').classList.add('hidden');
                document.getElementById('analytics-content').classList.remove('hidden');
                trafficSourcesData.innerHTML = '';

                data.forEach(item => {
                    const row = document.createElement('tr');
                    row.className = 'hover:bg-slate-50 transition-colors group';
                    row.innerHTML = `
                        <td class="px-8 py-6">
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-emerald-500 rounded-full mr-4 opacity-40 group-hover:opacity-100 transition-opacity"></div>
                                <span class="font-bold text-slate-900 tracking-tight uppercase text-xs">${item.sessionSource || 'Direct'}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <span class="px-2 py-1 bg-slate-100 text-slate-500 text-[9px] font-black uppercase tracking-widest rounded border border-slate-200">${item.sessionMedium || 'None'}</span>
                        </td>
                        <td class="px-8 py-6 text-sm font-bold text-slate-700">${parseInt(item.sessions).toLocaleString()}</td>
                        <td class="px-8 py-6 text-sm font-bold text-slate-700">${parseInt(item.screenPageViews).toLocaleString()}</td>
                        <td class="px-8 py-6 text-right">
                            <span class="px-3 py-1 bg-emerald-50 text-emerald-700 text-[10px] font-black rounded-lg border border-emerald-100">
                                ${parseInt(item.totalUsers).toLocaleString()} Users
                            </span>
                        </td>
                    `;
                    trafficSourcesData.appendChild(row);
                });
            })
            .catch(() => {
                trafficSourcesData.innerHTML = '<tr><td colspan="5" class="py-20 text-center text-red-500 font-bold">Gagal sinkronisasi data trafik.</td></tr>';
            });
    });
</script>

<?= $this->endSection() ?>