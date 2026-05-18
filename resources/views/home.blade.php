@extends('layouts.layouts_app')

@section('content')

<!-- Content Header -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2 align-items-center">
            <div class="col-sm-6">
                <h1>Dashboard</h1>
                <p class="page-subtitle">Sistem Monitoring Operasi UIP2B JAMALI</p>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#"><i class="fas fa-home mr-1"></i>Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">

        {{-- ═══ TOP STATUS BAR ═══ --}}
        <div class="status-bar">
            <div class="sb-item">
                <div class="sb-ico sb-ico-sky"><i class="fas fa-server"></i></div>
                <div class="sb-content">
                    <span class="sb-label">System Status</span>
                    <span class="sb-value">
                        <span class="dot dot-green"></span> Operational
                    </span>
                </div>
            </div>
            <div class="sb-divider"></div>
            <div class="sb-item">
                <div class="sb-ico sb-ico-navy"><i class="fas fa-user-shield"></i></div>
                <div class="sb-content">
                    <span class="sb-label">Logged in as</span>
                    <span class="sb-value">{{ $user['username'] ?? 'User' }}</span>
                </div>
            </div>
            <div class="sb-divider"></div>
            <div class="sb-item">
                <div class="sb-ico sb-ico-gold"><i class="fas fa-clock"></i></div>
                <div class="sb-content">
                    <span class="sb-label">Current Time (WIB)</span>
                    <span class="sb-value" id="sb-clock">--:--:--</span>
                </div>
            </div>
            <div class="sb-divider"></div>
            <div class="sb-item">
                <div class="sb-ico sb-ico-green"><i class="fas fa-database"></i></div>
                <div class="sb-content">
                    <span class="sb-label">Database Mode</span>
                    <span class="sb-value">Offline / Local Cache</span>
                </div>
            </div>
            <div class="sb-spacer"></div>
            <div class="sb-action">
                <button class="btn btn-sm btn-outline-primary" type="button">
                    <i class="fas fa-sync-alt mr-1"></i> Refresh
                </button>
            </div>
        </div>

        {{-- ═══ KPI METRICS ═══ --}}
        <div class="row">
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="kpi-card kpi-sky">
                    <div class="kpi-row">
                        <div class="kpi-icon"><i class="fas fa-building"></i></div>
                        <span class="kpi-badge kpi-badge-up"><i class="fas fa-arrow-up"></i> 2.4%</span>
                    </div>
                    <div class="kpi-value" data-count="156">0</div>
                    <div class="kpi-label">Total Gardu Induk</div>
                    <div class="kpi-foot">
                        <span class="kpi-progress"><span class="kpi-progress-bar" style="width:78%;background:#00a9ce;"></span></span>
                        <span class="kpi-meta">78% terpetakan</span>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="kpi-card kpi-navy">
                    <div class="kpi-row">
                        <div class="kpi-icon"><i class="fas fa-exchange-alt"></i></div>
                        <span class="kpi-badge kpi-badge-up"><i class="fas fa-check"></i> Aktif</span>
                    </div>
                    <div class="kpi-value" data-count="384">0</div>
                    <div class="kpi-label">Trafo Tenaga Terdaftar</div>
                    <div class="kpi-foot">
                        <span class="kpi-progress"><span class="kpi-progress-bar" style="width:92%;background:#003366;"></span></span>
                        <span class="kpi-meta">92% tersinkron</span>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="kpi-card kpi-gold">
                    <div class="kpi-row">
                        <div class="kpi-icon"><i class="fas fa-toggle-on"></i></div>
                        <span class="kpi-badge kpi-badge-up"><i class="fas fa-bolt"></i> Normal</span>
                    </div>
                    <div class="kpi-value" data-count="1284">0</div>
                    <div class="kpi-label">PMT / Circuit Breaker</div>
                    <div class="kpi-foot">
                        <span class="kpi-progress"><span class="kpi-progress-bar" style="width:98%;background:#e0a800;"></span></span>
                        <span class="kpi-meta">98.7% uptime</span>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="kpi-card kpi-red">
                    <div class="kpi-row">
                        <div class="kpi-icon"><i class="fas fa-bell"></i></div>
                        <span class="kpi-badge kpi-badge-warn"><i class="fas fa-exclamation-circle"></i> Review</span>
                    </div>
                    <div class="kpi-value" data-count="7">0</div>
                    <div class="kpi-label">Notifikasi Pending</div>
                    <div class="kpi-foot">
                        <span class="kpi-progress"><span class="kpi-progress-bar" style="width:35%;background:#e30613;"></span></span>
                        <span class="kpi-meta">Memerlukan tindakan</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══ SYSTEM HEALTH & ACTIVITY ═══ --}}
        <div class="row">
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header bg-info">
                        <h3 class="card-title"><i class="fas fa-heartbeat mr-2"></i>System Health Monitor</h3>
                        <div class="card-tools">
                            <span class="badge" style="background:rgba(255,255,255,0.2);color:#fff;">Real-time</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="health-grid">

                            <div class="health-item">
                                <div class="health-head">
                                    <div class="health-name">
                                        <i class="fas fa-microchip text-sky"></i>
                                        CPU Usage
                                    </div>
                                    <span class="health-val text-success">42%</span>
                                </div>
                                <div class="health-bar">
                                    <div class="health-fill" style="width:42%;background:linear-gradient(90deg,#00a651,#00d96b);"></div>
                                </div>
                                <div class="health-meta">8 cores • 2.4 GHz avg</div>
                            </div>

                            <div class="health-item">
                                <div class="health-head">
                                    <div class="health-name">
                                        <i class="fas fa-memory text-sky"></i>
                                        Memory
                                    </div>
                                    <span class="health-val text-success">3.2 / 16 GB</span>
                                </div>
                                <div class="health-bar">
                                    <div class="health-fill" style="width:20%;background:linear-gradient(90deg,#00a651,#00d96b);"></div>
                                </div>
                                <div class="health-meta">20% terpakai • Optimal</div>
                            </div>

                            <div class="health-item">
                                <div class="health-head">
                                    <div class="health-name">
                                        <i class="fas fa-database text-sky"></i>
                                        Database
                                    </div>
                                    <span class="health-val text-success">Connected</span>
                                </div>
                                <div class="health-bar">
                                    <div class="health-fill" style="width:96%;background:linear-gradient(90deg,#00a9ce,#007a99);"></div>
                                </div>
                                <div class="health-meta">96% query success rate</div>
                            </div>

                            <div class="health-item">
                                <div class="health-head">
                                    <div class="health-name">
                                        <i class="fas fa-hdd text-sky"></i>
                                        Disk Storage
                                    </div>
                                    <span class="health-val text-warning">68%</span>
                                </div>
                                <div class="health-bar">
                                    <div class="health-fill" style="width:68%;background:linear-gradient(90deg,#ffc107,#e0a800);"></div>
                                </div>
                                <div class="health-meta">340 GB / 500 GB digunakan</div>
                            </div>

                            <div class="health-item">
                                <div class="health-head">
                                    <div class="health-name">
                                        <i class="fas fa-network-wired text-sky"></i>
                                        Network Latency
                                    </div>
                                    <span class="health-val text-success">12 ms</span>
                                </div>
                                <div class="health-bar">
                                    <div class="health-fill" style="width:15%;background:linear-gradient(90deg,#00a651,#00d96b);"></div>
                                </div>
                                <div class="health-meta">Stabil • Avg 14 ms</div>
                            </div>

                            <div class="health-item">
                                <div class="health-head">
                                    <div class="health-name">
                                        <i class="fas fa-shield-alt text-sky"></i>
                                        Security
                                    </div>
                                    <span class="health-val text-success">Secured</span>
                                </div>
                                <div class="health-bar">
                                    <div class="health-fill" style="width:100%;background:linear-gradient(90deg,#003366,#00a9ce);"></div>
                                </div>
                                <div class="health-meta">TLS 1.3 • Firewall aktif</div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-stream mr-2 text-sky"></i>Recent Activity</h3>
                        <div class="card-tools">
                            <a href="#" class="btn-link-sm">Lihat Semua <i class="fas fa-arrow-right ml-1"></i></a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <ul class="activity-feed">
                            <li>
                                <span class="af-dot af-sky"></span>
                                <div class="af-body">
                                    <div class="af-title">User <strong>{{ $user['username'] ?? 'admin' }}</strong> login ke sistem</div>
                                    <div class="af-meta"><i class="fas fa-clock"></i> Beberapa detik lalu</div>
                                </div>
                            </li>
                            <li>
                                <span class="af-dot af-green"></span>
                                <div class="af-body">
                                    <div class="af-title">Sinkronisasi data <strong>Trafo Tenaga</strong> selesai</div>
                                    <div class="af-meta"><i class="fas fa-clock"></i> 14:23 WIB</div>
                                </div>
                            </li>
                            <li>
                                <span class="af-dot af-gold"></span>
                                <div class="af-body">
                                    <div class="af-title">Backup database harian dijalankan</div>
                                    <div class="af-meta"><i class="fas fa-clock"></i> 12:00 WIB</div>
                                </div>
                            </li>
                            <li>
                                <span class="af-dot af-navy"></span>
                                <div class="af-body">
                                    <div class="af-title">Update master data <strong>Gardu Induk</strong></div>
                                    <div class="af-meta"><i class="fas fa-clock"></i> 09:45 WIB</div>
                                </div>
                            </li>
                            <li>
                                <span class="af-dot af-red"></span>
                                <div class="af-body">
                                    <div class="af-title">Notifikasi: Pemeliharaan terjadwal</div>
                                    <div class="af-meta"><i class="fas fa-clock"></i> 08:00 WIB</div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══ QUICK ACCESS MODULES ═══ --}}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-th-large mr-2 text-sky"></i>Akses Modul Cepat</h3>
                        <div class="card-tools">
                            <span class="text-muted" style="font-size:12px;">6 modul tersedia</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="modules-grid">
                            <a href="{{ url('/admin/gardu-induk') }}" class="module-card">
                                <div class="mc-icon mc-sky"><i class="fas fa-building"></i></div>
                                <div class="mc-body">
                                    <div class="mc-title">Gardu Induk</div>
                                    <div class="mc-desc">Data master & monitoring</div>
                                </div>
                                <i class="fas fa-chevron-right mc-arrow"></i>
                            </a>
                            <a href="{{ url('/admin/trafo') }}" class="module-card">
                                <div class="mc-icon mc-navy"><i class="fas fa-exchange-alt"></i></div>
                                <div class="mc-body">
                                    <div class="mc-title">Trafo Tenaga</div>
                                    <div class="mc-desc">Manajemen trafo & beban</div>
                                </div>
                                <i class="fas fa-chevron-right mc-arrow"></i>
                            </a>
                            <a href="{{ url('/admin/pmt') }}" class="module-card">
                                <div class="mc-icon mc-green"><i class="fas fa-toggle-on"></i></div>
                                <div class="mc-body">
                                    <div class="mc-title">PMT / Switchgear</div>
                                    <div class="mc-desc">Status &amp; histori operasi</div>
                                </div>
                                <i class="fas fa-chevron-right mc-arrow"></i>
                            </a>
                            <a href="{{ url('/admin/kinerja') }}" class="module-card">
                                <div class="mc-icon mc-gold"><i class="fas fa-chart-line"></i></div>
                                <div class="mc-body">
                                    <div class="mc-title">Kinerja</div>
                                    <div class="mc-desc">SCADA bulanan &amp; KPI</div>
                                </div>
                                <i class="fas fa-chevron-right mc-arrow"></i>
                            </a>
                            <a href="{{ url('/admin/histori') }}" class="module-card">
                                <div class="mc-icon mc-red"><i class="fas fa-history"></i></div>
                                <div class="mc-body">
                                    <div class="mc-title">Histori Operasi</div>
                                    <div class="mc-desc">Log &amp; audit trail</div>
                                </div>
                                <i class="fas fa-chevron-right mc-arrow"></i>
                            </a>
                            <a href="{{ url('/admin/akun') }}" class="module-card">
                                <div class="mc-icon mc-slate"><i class="fas fa-user-cog"></i></div>
                                <div class="mc-body">
                                    <div class="mc-title">Manajemen Akun</div>
                                    <div class="mc-desc">Pengguna &amp; hak akses</div>
                                </div>
                                <i class="fas fa-chevron-right mc-arrow"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══ FOOTER INFO ═══ --}}
        <div class="footer-info">
            <div class="fi-left">
                <i class="fas fa-info-circle text-sky mr-2"></i>
                <strong>Offline Database MCC v1.0</strong>
                <span class="fi-sep">|</span>
                Sistem dioperasikan oleh PT. PLN (Persero) UIP2B Jamali
            </div>
            <div class="fi-right">
                <span class="fi-dot"><span class="dot dot-green"></span> All systems operational</span>
                <span class="fi-sep">|</span>
                <span id="fi-date">—</span>
            </div>
        </div>

    </div>
</section>

<style>
/* ── Page subtitle ── */
.page-subtitle {
    font-size: 12.5px;
    color: var(--txt-muted, #64748b);
    margin: 4px 0 0 14px;
    font-weight: 500;
}

/* ══════════════════════════════════════════════════════
   STATUS BAR
   ══════════════════════════════════════════════════════ */
.status-bar {
    display: flex;
    align-items: center;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 14px 20px;
    box-shadow: 0 1px 3px rgba(15,23,42,0.04);
    margin-bottom: 20px;
    gap: 18px;
    flex-wrap: wrap;
}
.sb-item { display: flex; align-items: center; gap: 12px; }
.sb-ico {
    width: 42px; height: 42px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 16px;
    flex-shrink: 0;
}
.sb-ico-sky   { background: rgba(0,169,206,0.10); color: #007a99; }
.sb-ico-navy  { background: rgba(0,51,102,0.10); color: #003366; }
.sb-ico-gold  { background: rgba(255,193,7,0.13); color: #d39e00; }
.sb-ico-green { background: rgba(0,166,81,0.10); color: #00a651; }
.sb-content { display: flex; flex-direction: column; }
.sb-label {
    font-size: 10.5px;
    font-weight: 700;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    line-height: 1.2;
}
.sb-value {
    font-size: 13.5px;
    font-weight: 600;
    color: #0f172a;
    margin-top: 2px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-variant-numeric: tabular-nums;
}
.sb-divider {
    width: 1px;
    height: 36px;
    background: #e2e8f0;
}
.sb-spacer { flex: 1; }

.dot {
    display: inline-block;
    width: 8px; height: 8px;
    border-radius: 50%;
}
.dot-green {
    background: #00a651;
    box-shadow: 0 0 0 3px rgba(0,166,81,0.18);
    animation: dotPulse 2s ease-in-out infinite;
}
.dot-red { background: #e30613; box-shadow: 0 0 0 3px rgba(227,6,19,0.18); }
.dot-gold { background: #ffc107; box-shadow: 0 0 0 3px rgba(255,193,7,0.22); }
@keyframes dotPulse {
    0%,100% { box-shadow: 0 0 0 3px rgba(0,166,81,0.18); }
    50% { box-shadow: 0 0 0 6px rgba(0,166,81,0.1); }
}

/* ══════════════════════════════════════════════════════
   KPI CARDS — Enterprise
   ══════════════════════════════════════════════════════ */
.kpi-card {
    position: relative;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 18px 20px;
    margin-bottom: 18px;
    box-shadow: 0 1px 3px rgba(15,23,42,0.04);
    transition: box-shadow 0.2s, transform 0.2s, border-color 0.2s;
    border-left: 4px solid #e2e8f0;
}
.kpi-card:hover {
    box-shadow: 0 8px 24px rgba(15,23,42,0.08);
    transform: translateY(-2px);
}
.kpi-sky   { border-left-color: #00a9ce; }
.kpi-navy  { border-left-color: #003366; }
.kpi-gold  { border-left-color: #ffc107; }
.kpi-red   { border-left-color: #e30613; }

.kpi-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; }
.kpi-icon {
    width: 44px; height: 44px;
    border-radius: 11px;
    display: flex; align-items: center; justify-content: center;
    font-size: 18px;
}
.kpi-sky .kpi-icon  { background: rgba(0,169,206,0.10); color: #007a99; }
.kpi-navy .kpi-icon { background: rgba(0,51,102,0.10); color: #003366; }
.kpi-gold .kpi-icon { background: rgba(255,193,7,0.14); color: #d39e00; }
.kpi-red .kpi-icon  { background: rgba(227,6,19,0.10); color: #e30613; }

.kpi-badge {
    font-size: 10.5px;
    font-weight: 700;
    padding: 3px 9px;
    border-radius: 12px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    letter-spacing: 0.3px;
}
.kpi-badge-up   { background: rgba(0,166,81,0.10); color: #00a651; }
.kpi-badge-down { background: rgba(227,6,19,0.10); color: #e30613; }
.kpi-badge-warn { background: rgba(255,193,7,0.15); color: #b58400; }
.kpi-badge i { font-size: 9px; }

.kpi-value {
    font-size: 28px;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.1;
    letter-spacing: -0.6px;
    font-variant-numeric: tabular-nums;
    margin-bottom: 4px;
}
.kpi-label {
    font-size: 12px;
    color: #64748b;
    font-weight: 500;
    margin-bottom: 12px;
}
.kpi-foot { display: flex; flex-direction: column; gap: 6px; }
.kpi-progress {
    display: block;
    width: 100%;
    height: 5px;
    background: #f1f4f9;
    border-radius: 3px;
    overflow: hidden;
}
.kpi-progress-bar {
    display: block;
    height: 100%;
    border-radius: 3px;
    transition: width 1s ease;
}
.kpi-meta {
    font-size: 11px;
    color: #94a3b8;
    font-weight: 500;
}

/* ══════════════════════════════════════════════════════
   SYSTEM HEALTH
   ══════════════════════════════════════════════════════ */
.health-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px 24px;
}
.health-item { display: flex; flex-direction: column; gap: 6px; }
.health-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.health-name {
    font-size: 12.5px;
    font-weight: 600;
    color: #475569;
    display: flex;
    align-items: center;
    gap: 8px;
}
.health-name i { font-size: 12px; }
.text-sky { color: #007a99 !important; }
.health-val {
    font-size: 12.5px;
    font-weight: 700;
    font-variant-numeric: tabular-nums;
}
.health-bar {
    height: 6px;
    background: #f1f4f9;
    border-radius: 4px;
    overflow: hidden;
}
.health-fill {
    height: 100%;
    border-radius: 4px;
    transition: width 1s ease;
}
.health-meta {
    font-size: 11px;
    color: #94a3b8;
    font-weight: 500;
}

/* ══════════════════════════════════════════════════════
   ACTIVITY FEED
   ══════════════════════════════════════════════════════ */
.activity-feed {
    list-style: none;
    padding: 0; margin: 0;
}
.activity-feed > li {
    position: relative;
    padding: 14px 20px 14px 44px;
    border-bottom: 1px solid #f1f4f9;
    transition: background 0.15s;
}
.activity-feed > li:last-child { border-bottom: none; }
.activity-feed > li:hover { background: #f7f9fc; }
.activity-feed > li::before {
    content: '';
    position: absolute;
    left: 24px; top: 0; bottom: 0;
    width: 2px;
    background: #e2e8f0;
}
.activity-feed > li:first-child::before { top: 14px; }
.activity-feed > li:last-child::before { bottom: 14px; }

.af-dot {
    position: absolute;
    left: 19px; top: 17px;
    width: 12px; height: 12px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px currentColor;
    z-index: 1;
}
.af-sky   { background: #00a9ce; color: #00a9ce; }
.af-green { background: #00a651; color: #00a651; }
.af-gold  { background: #ffc107; color: #ffc107; }
.af-navy  { background: #003366; color: #003366; }
.af-red   { background: #e30613; color: #e30613; }

.af-body { }
.af-title {
    font-size: 13px;
    color: #1e293b;
    line-height: 1.45;
    margin-bottom: 2px;
}
.af-title strong { color: #003366; font-weight: 600; }
.af-meta {
    font-size: 11px;
    color: #94a3b8;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}
.af-meta i { font-size: 10px; }

.btn-link-sm {
    font-size: 12px;
    font-weight: 600;
    color: #007a99;
    text-decoration: none;
    transition: color 0.15s;
}
.btn-link-sm:hover { color: #003366; text-decoration: none; }
.btn-link-sm i { font-size: 10px; }

/* ══════════════════════════════════════════════════════
   MODULE CARDS — Quick Access
   ══════════════════════════════════════════════════════ */
.modules-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
}
.module-card {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 14px 16px;
    border-radius: 10px;
    background: #fff;
    border: 1px solid #e2e8f0;
    text-decoration: none;
    transition: all 0.2s ease;
    position: relative;
    overflow: hidden;
}
.module-card::before {
    content: '';
    position: absolute;
    left: 0; top: 0; bottom: 0;
    width: 3px;
    background: transparent;
    transition: background 0.2s;
}
.module-card:hover {
    border-color: #00a9ce;
    background: #f7fcfe;
    box-shadow: 0 4px 14px rgba(0,42,82,0.08);
    text-decoration: none;
    transform: translateY(-1px);
}
.module-card:hover::before { background: #00a9ce; }
.module-card:hover .mc-arrow { color: #00a9ce; transform: translateX(3px); }

.mc-icon {
    width: 46px; height: 46px;
    border-radius: 11px;
    display: flex; align-items: center; justify-content: center;
    font-size: 19px;
    color: #fff;
    flex-shrink: 0;
}
.mc-sky    { background: linear-gradient(135deg, #00a9ce, #007a99); }
.mc-navy   { background: linear-gradient(135deg, #003366, #001a3d); }
.mc-green  { background: linear-gradient(135deg, #00a651, #007a3d); }
.mc-gold   { background: linear-gradient(135deg, #ffc107, #e0a800); color: #003366; }
.mc-red    { background: linear-gradient(135deg, #e30613, #b50410); }
.mc-slate  { background: linear-gradient(135deg, #64748b, #334155); }

.mc-body { flex: 1; min-width: 0; }
.mc-title {
    font-size: 13.5px;
    font-weight: 700;
    color: #0f172a;
    line-height: 1.2;
}
.mc-desc {
    font-size: 11.5px;
    color: #64748b;
    margin-top: 2px;
    font-weight: 500;
}
.mc-arrow {
    color: #cbd5e1;
    font-size: 11px;
    transition: all 0.2s;
    flex-shrink: 0;
}

/* ══════════════════════════════════════════════════════
   FOOTER INFO
   ══════════════════════════════════════════════════════ */
.footer-info {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 18px;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    margin-top: 6px;
    margin-bottom: 14px;
    box-shadow: 0 1px 3px rgba(15,23,42,0.04);
    font-size: 12px;
    color: #475569;
    flex-wrap: wrap;
    gap: 10px;
}
.fi-left strong { color: #003366; font-weight: 700; }
.fi-sep { color: #cbd5e1; margin: 0 8px; }
.fi-dot { display: inline-flex; align-items: center; gap: 6px; font-weight: 600; color: #00a651; }
.fi-right { display: inline-flex; align-items: center; gap: 4px; font-weight: 500; }

/* ══════════════════════════════════════════════════════
   RESPONSIVE
   ══════════════════════════════════════════════════════ */
@media (max-width: 1199px) {
    .modules-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 991px) {
    .health-grid { grid-template-columns: 1fr; }
    .status-bar { gap: 14px; }
    .sb-divider { display: none; }
}
@media (max-width: 575px) {
    .modules-grid { grid-template-columns: 1fr; }
    .status-bar { flex-direction: column; align-items: stretch; }
    .sb-item { width: 100%; }
    .footer-info { flex-direction: column; align-items: flex-start; }
}
</style>

@push('scripts')
<script>
    // ── Live clock for status bar ──
    function updateClock() {
        const now = new Date();
        const days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
        const months = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        const h = String(now.getHours()).padStart(2,'0');
        const m = String(now.getMinutes()).padStart(2,'0');
        const s = String(now.getSeconds()).padStart(2,'0');
        $('#sb-clock').text(h + ':' + m + ':' + s);
        $('#fi-date').text(days[now.getDay()] + ', ' + now.getDate() + ' ' + months[now.getMonth()] + ' ' + now.getFullYear() + ' ' + h + ':' + m + ' WIB');
    }
    updateClock();
    setInterval(updateClock, 1000);

    // ── Animated counter for KPI values ──
    function animateCounter(el, target, duration) {
        const start = 0;
        const startTime = performance.now();
        function tick(now) {
            const progress = Math.min((now - startTime) / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3);
            const val = Math.floor(start + (target - start) * eased);
            el.textContent = val.toLocaleString('id-ID');
            if (progress < 1) requestAnimationFrame(tick);
        }
        requestAnimationFrame(tick);
    }
    $(document).ready(function() {
        $('.kpi-value[data-count]').each(function() {
            const target = parseInt($(this).data('count'), 10);
            animateCounter(this, target, 1200);
        });
    });
</script>
@endpush

@endsection
