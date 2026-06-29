<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slow Log Detailed Inspection</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen font-sans p-6">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-8 border-b border-slate-800 pb-5">
            <div>
                <h1 class="text-xl font-black text-rose-400">LOG INSPECTION #{{ $log->id }}</h1>
                <p class="text-xs text-slate-400">Deep performance trace analysis</p>
            </div>
            <a href="/slow-logs" class="bg-slate-800 text-slate-300 px-4 py-2 rounded-xl text-xs font-bold hover:bg-slate-700 transition">← Back Panel</a>
        </div>

        <div class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-slate-900 border border-slate-800 p-4 rounded-2xl">
                    <span class="text-xs text-slate-500 block mb-1 font-bold uppercase">Anomaly Type</span>
                    <span class="px-2.5 py-0.5 rounded-md text-xs font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">
                        {{ strtoupper($log->log_type ?? 'QUERY') }}
                    </span>
                </div>
                <div class="bg-slate-900 border border-slate-800 p-4 rounded-2xl">
                    <span class="text-xs text-slate-500 block mb-1 font-bold uppercase">Duration Time</span>
                    <span class="text-lg font-mono font-bold text-emerald-400">{{ number_format($log->time, 2) }} ms</span>
                </div>
                <div class="bg-slate-900 border border-slate-800 p-4 rounded-2xl">
                    <span class="text-xs text-slate-500 block mb-1 font-bold uppercase">Captured On</span>
                    <span class="text-sm font-semibold text-slate-300">{{ $log->created_at }}</span>
                </div>
            </div>

            <div class="bg-slate-900 border border-slate-800 p-5 rounded-2xl">
                <h4 class="text-xs text-slate-500 font-bold uppercase mb-3">Payload / Backtrace URL</h4>
                <div class="bg-black/40 border border-slate-800 p-4 rounded-xl font-mono text-xs text-slate-300 overflow-x-auto">
                    {{ $log->url ?? $log->route ?? 'N/A' }}
                </div>
            </div>

            <div class="bg-slate-900 border border-slate-800 p-5 rounded-2xl">
                <h4 class="text-xs text-slate-500 font-bold uppercase mb-3">Executed Query Expression</h4>
                <div class="bg-black/40 border border-slate-800 p-4 rounded-xl font-mono text-xs text-rose-400 overflow-x-auto leading-relaxed">
                    {{ $log->raw_sql ?? $log->sql ?? 'N/A' }}
                </div>
            </div>

            @if(isset($log->recommendation) && !empty($log->recommendation))
                <div class="bg-indigo-950/40 border border-indigo-500/20 p-5 rounded-2xl">
                    <h4 class="text-xs text-indigo-400 font-bold uppercase mb-2">⚡ Optimization Advisory</h4>
                    <p class="text-sm text-slate-300 leading-relaxed font-medium">{{ $log->recommendation }}</p>
                </div>
            @endif
        </div>
    </div>
</body>
</html>