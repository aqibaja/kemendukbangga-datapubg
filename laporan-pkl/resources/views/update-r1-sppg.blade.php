<x-layout>
<x-slot:title>{{ $title }}</x-slot:title>

{{-- =============================================
     UPDATE R1 SPPG — Manajemen Data SPPG & Desa
     Target Tab: 'REKAP DATA SPPG BARU'
     ============================================= --}}

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

    * { box-sizing: border-box; }

    #sppg-r1-app {
        font-family: 'Inter', sans-serif;
        min-height: 100vh;
        background: linear-gradient(135deg, #0f2027 0%, #203a43 50%, #2c5364 100%);
        padding: 24px 16px 60px;
        color: #e2e8f0;
    }

    /* ---- HEADER ---- */
    .page-header {
        text-align: center;
        margin-bottom: 28px;
    }
    .page-header h1 {
        font-size: 2rem;
        font-weight: 800;
        background: linear-gradient(90deg, #38bdf8, #818cf8, #f472b6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin: 0 0 8px;
    }
    .page-header p {
        color: #94a3b8;
        font-size: 0.95rem;
        margin: 0;
    }

    /* ---- CARDS ---- */
    .r1-card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 18px;
        padding: 28px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        margin-bottom: 24px;
        transition: all 0.3s ease;
    }
    .r1-card:hover {
        border-color: rgba(56, 189, 248, 0.25);
    }
    .r1-card-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: #f1f5f9;
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 6px;
    }
    .r1-card-subtitle {
        font-size: 0.85rem;
        color: #94a3b8;
        margin-bottom: 20px;
    }

    /* ---- FORM CONTROLS & CLEAN ALIGNED LABELS ---- */
    .form-group {
        margin-bottom: 16px;
        display: flex;
        flex-direction: column;
    }
    .form-label {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.78rem;
        font-weight: 700;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 8px;
        min-height: 20px;
        line-height: 1.3;
    }
    .col-tag {
        display: inline-flex;
        align-items: center;
        font-size: 0.65rem;
        font-weight: 800;
        padding: 2px 6px;
        border-radius: 4px;
        background: rgba(56, 189, 248, 0.15);
        color: #38bdf8;
        border: 1px solid rgba(56, 189, 248, 0.3);
        letter-spacing: 0;
        text-transform: none;
        flex-shrink: 0;
    }
    .col-tag.kppg {
        background: rgba(244, 114, 182, 0.15);
        color: #f472b6;
        border-color: rgba(244, 114, 182, 0.3);
    }
    .col-tag.success {
        background: rgba(16, 185, 129, 0.15);
        color: #34d399;
        border-color: rgba(16, 185, 129, 0.3);
    }

    .form-control {
        width: 100%;
        height: 42px;
        padding: 8px 14px;
        background: rgba(15, 23, 42, 0.85);
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 10px;
        color: #f8fafc;
        font-size: 0.9rem;
        font-family: 'Inter', sans-serif;
        transition: all 0.25s ease;
        outline: none;
    }
    select.form-control {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        padding-right: 40px !important;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2338bdf8'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2.2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 14px center;
        background-size: 15px 15px;
        cursor: pointer;
    }
    select.form-control:hover {
        border-color: rgba(56, 189, 248, 0.4);
    }
    textarea.form-control {
        height: auto;
        min-height: 70px;
    }
    .form-control:focus {
        border-color: #38bdf8;
        box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.2);
        background: rgba(15, 23, 42, 0.98);
    }
    .form-control::placeholder {
        color: #64748b;
    }
    .form-control option {
        background: #1e293b;
        color: #f8fafc;
    }

    /* ---- BUTTONS ---- */
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 20px;
        border: none;
        border-radius: 10px;
        font-size: 0.88rem;
        font-weight: 600;
        font-family: 'Inter', sans-serif;
        cursor: pointer;
        transition: all 0.25s ease;
        text-decoration: none;
    }
    .btn:disabled {
        opacity: 0.55;
        cursor: not-allowed;
        transform: none !important;
    }
    .btn-primary {
        background: linear-gradient(135deg, #0284c7, #38bdf8);
        color: white;
        box-shadow: 0 4px 15px rgba(56, 189, 248, 0.35);
    }
    .btn-primary:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(56, 189, 248, 0.5);
    }
    .btn-success {
        background: linear-gradient(135deg, #059669, #10b981);
        color: white;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.35);
    }
    .btn-success:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(16, 185, 129, 0.5);
    }
    .btn-secondary {
        background: rgba(51, 65, 85, 0.8);
        color: #cbd5e1;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    .btn-secondary:hover:not(:disabled) {
        background: rgba(71, 85, 105, 0.9);
        color: white;
    }
    .btn-sm {
        padding: 6px 14px;
        font-size: 0.82rem;
        border-radius: 8px;
    }

    /* ---- STATUS BADGES ---- */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 9999px;
        font-size: 0.82rem;
        font-weight: 700;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        white-space: nowrap;
    }
    .status-badge.sudah {
        background: rgba(16, 185, 129, 0.15);
        color: #34d399;
        border: 1px solid rgba(16, 185, 129, 0.35);
        box-shadow: 0 0 12px rgba(16, 185, 129, 0.2);
    }
    .status-badge.belum {
        background: rgba(239, 68, 68, 0.15);
        color: #f87171;
        border: 1px solid rgba(239, 68, 68, 0.35);
        box-shadow: 0 0 12px rgba(239, 68, 68, 0.2);
    }

    /* ---- TOP GENERAL SPPG CONTAINER (SESUAI MOCKUP) ---- */
    .sppg-top-card {
        border: 2px solid rgba(255, 255, 255, 0.15);
        border-radius: 16px;
        padding: 24px;
        background: rgba(15, 23, 42, 0.7);
        display: grid;
        grid-template-columns: 1.2fr 1fr;
        gap: 28px;
        align-items: start;
        margin-bottom: 26px;
    }
    @media (max-width: 900px) {
        .sppg-top-card {
            grid-template-columns: 1fr;
            gap: 20px;
            padding: 18px;
        }
    }
    .sppg-info-col h2 {
        font-size: 1.35rem;
        font-weight: 800;
        color: #f8fafc;
        margin: 0 0 10px 0;
        line-height: 1.35;
        letter-spacing: -0.01em;
    }
    .sppg-info-col .alamat-text {
        font-size: 0.88rem;
        color: #cbd5e1;
        line-height: 1.5;
        margin-bottom: 12px;
    }
    .sppg-info-col .meta-tag {
        font-size: 0.82rem;
        color: #38bdf8;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 4px;
    }

    .general-form-box {
        background: rgba(30, 41, 59, 0.75);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 14px;
        padding: 20px;
    }
    .general-form-box .title {
        font-size: 0.95rem;
        font-weight: 700;
        color: #e2e8f0;
        margin-bottom: 14px;
        padding-bottom: 8px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* ---- DESA LIST ITEM (SESUAI MOCKUP) ---- */
    .desa-list {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }
    .desa-item-card {
        background: rgba(15, 23, 42, 0.7);
        border: 2px solid rgba(255, 255, 255, 0.15);
        border-radius: 14px;
        padding: 18px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .desa-item-card:hover {
        background: rgba(30, 41, 59, 0.8);
        border-color: #38bdf8;
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
    }
    .desa-left-info {
        flex: 1;
        min-width: 0;
    }
    .desa-name {
        font-size: 1.15rem;
        font-weight: 800;
        color: #ffffff;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        margin: 0 0 4px 0;
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }
    .desa-meta {
        font-size: 0.78rem;
        color: #94a3b8;
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }
    .desa-meta span {
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .desa-right-action {
        display: flex;
        align-items: center;
        gap: 18px;
        flex-shrink: 0;
    }
    .edit-pencil-btn {
        background: rgba(56, 189, 248, 0.12);
        color: #38bdf8;
        border: 1px solid rgba(56, 189, 248, 0.3);
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        cursor: pointer;
        transition: all 0.25s ease;
    }
    .edit-pencil-btn:hover {
        background: #38bdf8;
        color: #0f172a;
        transform: scale(1.08);
        box-shadow: 0 0 18px rgba(56, 189, 248, 0.5);
    }

    /* ---- MODAL POPUP ---- */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.85);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        z-index: 99999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }
    .modal-overlay.active {
        opacity: 1;
        visibility: visible;
    }
    .modal-dialog {
        background: #1e293b;
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 20px;
        width: 100%;
        max-width: 940px;
        max-height: 90vh;
        display: flex;
        flex-direction: column;
        box-shadow: 0 25px 60px rgba(0, 0, 0, 0.6);
        transform: scale(0.92) translateY(20px);
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        overflow: hidden;
    }
    .modal-overlay.active .modal-dialog {
        transform: scale(1) translateY(0);
    }
    .modal-header {
        padding: 18px 24px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: rgba(15, 23, 42, 0.5);
    }
    .modal-header h3 {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 800;
        color: #f8fafc;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .modal-header .sub {
        font-size: 0.82rem;
        color: #94a3b8;
        font-weight: 400;
        margin-top: 3px;
    }
    .modal-close-btn {
        background: rgba(255, 255, 255, 0.08);
        border: none;
        color: #94a3b8;
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }
    .modal-close-btn:hover {
        background: rgba(239, 68, 68, 0.2);
        color: #f87171;
    }
    .modal-body {
        padding: 24px 26px;
        overflow-y: auto;
        flex: 1;
    }
    .modal-footer {
        padding: 16px 24px;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: rgba(15, 23, 42, 0.5);
        gap: 12px;
    }

    /* Modal Form Sections */
    .form-section-title {
        font-size: 0.86rem;
        font-weight: 800;
        color: #38bdf8;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        margin: 22px 0 14px 0;
        padding-bottom: 6px;
        border-bottom: 1px dashed rgba(56, 189, 248, 0.25);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .form-section-title:first-child {
        margin-top: 0;
    }
    .grid-2 {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
    }
    .grid-3 {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
    }
    .grid-4 {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 14px;
        align-items: start;
    }
    @media (max-width: 820px) {
        .grid-2, .grid-3, .grid-4 {
            grid-template-columns: 1fr;
        }
    }

    /* Reference Cards */
    .ref-box {
        background: rgba(15, 23, 42, 0.6);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 10px;
        padding: 10px 14px;
        text-align: center;
    }
    .ref-box .val {
        font-size: 1.2rem;
        font-weight: 800;
        color: #f1f5f9;
    }
    .ref-box .lbl {
        font-size: 0.7rem;
        color: #94a3b8;
        text-transform: uppercase;
        margin-top: 2px;
        white-space: nowrap;
    }

    /* ---- SEARCH & FILTER BAR ---- */
    .filter-bar {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }
    .search-input-wrap {
        position: relative;
        flex: 1;
        min-width: 260px;
    }
    .search-input-wrap i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #64748b;
    }
    .search-input-wrap input {
        padding-left: 40px;
    }

    /* ---- TOAST NOTIFICATION ---- */
    .toast-container {
        position: fixed;
        bottom: 24px;
        right: 24px;
        z-index: 100000;
        display: flex;
        flex-direction: column;
        gap: 10px;
        pointer-events: none;
    }
    .toast-item {
        background: #1e293b;
        color: white;
        border-radius: 12px;
        padding: 14px 20px;
        font-size: 0.9rem;
        font-weight: 500;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        gap: 12px;
        border-left: 5px solid #38bdf8;
        animation: toastSlideIn 0.3s ease;
        pointer-events: auto;
        max-width: 420px;
    }
    .toast-item.success { border-left-color: #10b981; }
    .toast-item.error { border-left-color: #ef4444; }
    @keyframes toastSlideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }

    /* ---- SPINNER ---- */
    .spinner {
        width: 16px;
        height: 16px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-top-color: white;
        border-radius: 50%;
        animation: spin 0.7s linear infinite;
        display: inline-block;
    }
    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* ---- STATS BAR ---- */
    .stats-bar {
        display: flex;
        gap: 12px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    .stat-pill {
        background: rgba(15, 23, 42, 0.7);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 12px;
        padding: 10px 18px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.85rem;
    }
    .stat-pill strong {
        font-size: 1.1rem;
        font-weight: 800;
    }

    .hidden { display: none !important; }
</style>

<div id="sppg-r1-app">
    <div class="max-w-6xl mx-auto">
        
        {{-- PAGE HEADER --}}
        <div class="page-header">
            <h1><i class="fa-solid fa-clipboard-check" style="margin-right:10px;"></i>Update R1 SPPG</h1>
            <p>Pembaruan data bulanan sasaran dan pendistribusian MBG 3B per Desa binaan SPPG</p>
        </div>

        {{-- ============================================================
             VIEW 1: PILIH SPPG (STEP 1)
             ============================================================ --}}
        <div id="view-select-sppg" class="r1-card">
            <div class="r1-card-title">
                <i class="fa-solid fa-magnifying-glass-location" style="color:#38bdf8;"></i>
                Pilih SPPG
            </div>
            <div class="r1-card-subtitle">
                Pilih Satuan Pelayanan Pangan Gizi (SPPG) yang ingin Anda kelola data desanya untuk bulan ini.
            </div>

            {{-- Filter & Search Bar --}}
            <div class="filter-bar">
                <div class="search-input-wrap">
                    <i class="fa-solid fa-search"></i>
                    <input type="text" id="sppgSearchInput" class="form-control" placeholder="Cari nama SPPG, kode, kecamatan, atau kabupaten..." oninput="filterSppgList()">
                </div>
                <div style="min-width:230px;">
                    <select id="filterKabupaten" class="form-control" onchange="onKabupatenChange()">
                        <option value="">Semua Kabupaten/Kota</option>
                    </select>
                </div>
                <div style="min-width:210px;">
                    <select id="filterKecamatan" class="form-control" onchange="filterSppgList()">
                        <option value="">Semua Kecamatan</option>
                    </select>
                </div>
            </div>

            {{-- SPPG List Counter --}}
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;font-size:0.85rem;color:#94a3b8;">
                <span>Menampilkan <strong id="sppgMatchCount" style="color:#38bdf8;">0</strong> dari <strong id="sppgTotalCount">0</strong> SPPG</span>
                <span id="periodBadge" style="background:rgba(56,189,248,0.12);color:#38bdf8;padding:4px 10px;border-radius:20px;font-weight:600;">Periode: September 2026</span>
            </div>

            {{-- SPPG Grid / Cards Container --}}
            <div id="sppgGridContainer" style="display:grid;grid-template-columns:repeat(auto-fill, minmax(320px, 1fr));gap:16px;max-height:560px;overflow-y:auto;padding-right:4px;">
                {{-- Dynamic SPPG Cards will be rendered here --}}
            </div>

            {{-- Load More Button for large list --}}
            <div id="loadMoreWrap" class="hidden" style="text-align:center;margin-top:16px;">
                <button class="btn btn-secondary btn-sm" onclick="loadMoreSppg()">
                    <i class="fa-solid fa-angles-down"></i> Tampilkan SPPG Lainnya (<span id="remainingCount">0</span>)
                </button>
            </div>

            <div id="sppgLoadingState" style="text-align:center;padding:40px 20px;color:#94a3b8;">
                <div class="spinner" style="width:28px;height:28px;border-width:3px;margin-bottom:12px;"></div>
                <div>Memuat data SPPG...</div>
            </div>

            <div id="sppgEmptyState" class="hidden" style="text-align:center;padding:40px 20px;color:#64748b;">
                <i class="fa-solid fa-folder-open" style="font-size:2.5rem;margin-bottom:12px;opacity:0.5;"></i>
                <div>Tidak ada SPPG yang sesuai dengan filter pencarian.</div>
            </div>
        </div>


        {{-- ============================================================
             VIEW 2: DASHBOARD SPPG & DAFTAR DESA (STEP 2 — SESUAI MOCKUP)
             ============================================================ --}}
        <div id="view-sppg-dashboard" class="hidden">
            
            {{-- Navigation Action Bar --}}
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;flex-wrap:wrap;gap:12px;">
                <button class="btn btn-secondary btn-sm" onclick="backToSppgSelect()">
                    <i class="fa-solid fa-arrow-left"></i> Ganti SPPG
                </button>
                <div style="display:flex;align-items:center;gap:10px;">
                    <span id="activePeriodTag" class="status-badge" style="background:rgba(129,140,248,0.15);color:#a5b4fc;border:1px solid rgba(129,140,248,0.3);">
                        <i class="fa-regular fa-calendar-check"></i> Periode Update: Bulan Berjalan
                    </span>
                    <button class="btn btn-secondary btn-sm" onclick="refreshCurrentSppg()" title="Muat ulang data SPPG">
                        <i class="fa-solid fa-arrows-rotate"></i>
                    </button>
                </div>
            </div>

            {{-- SECTION ATAS: PROFIL SPPG --}}
            <div class="sppg-top-card" style="grid-template-columns: 1fr; border-color: rgba(56, 189, 248, 0.25);">
                {{-- Identitas SPPG --}}
                <div class="sppg-info-col">
                    <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:10px;margin-bottom:8px;">
                        <div class="meta-tag">
                            <i class="fa-solid fa-building-circle-check"></i>
                            <span id="cardKodeSppg">Kode SPPG: -</span>
                        </div>
                        <span id="badgeIdSppg" style="font-size:0.75rem;padding:4px 12px;border-radius:8px;background:rgba(56,189,248,0.12);color:#38bdf8;font-weight:700;">
                            ID SPPG: -
                        </span>
                    </div>
                    <h2 id="cardNamaSppg" style="font-size:1.45rem;margin-bottom:8px;">NAMA SPPG</h2>
                    <div id="cardAlamatSppg" class="alamat-text" style="margin-bottom:14px;">Alamat lengkap SPPG...</div>
                    <div style="display:flex;gap:10px;flex-wrap:wrap;">
                        <span id="badgeKabupaten" style="font-size:0.8rem;padding:5px 12px;border-radius:8px;background:rgba(255,255,255,0.08);color:#cbd5e1;">
                            <i class="fa-solid fa-location-dot" style="color:#f472b6;margin-right:5px;"></i> Kabupaten: -
                        </span>
                        <span id="badgeKecamatan" style="font-size:0.8rem;padding:5px 12px;border-radius:8px;background:rgba(255,255,255,0.08);color:#cbd5e1;">
                            <i class="fa-solid fa-map-pin" style="color:#38bdf8;margin-right:5px;"></i> Kecamatan: -
                        </span>
                    </div>
                </div>
            </div>

            {{-- Summary Stats Bar --}}
            <div class="stats-bar">
                <div class="stat-pill">
                    <i class="fa-solid fa-map-location-dot" style="color:#38bdf8;font-size:1.1rem;"></i>
                    <div>
                        <div style="font-size:0.72rem;color:#94a3b8;text-transform:uppercase;">Total Desa Binaan</div>
                        <strong id="statTotalDesa">0</strong> Desa
                    </div>
                </div>
                <div class="stat-pill" style="border-color:rgba(16,185,129,0.3);">
                    <i class="fa-solid fa-circle-check" style="color:#10b981;font-size:1.1rem;"></i>
                    <div>
                        <div style="font-size:0.72rem;color:#94a3b8;text-transform:uppercase;">Sudah Update Bulan Ini</div>
                        <strong id="statSudahUpdate" style="color:#34d399;">0</strong> Desa
                    </div>
                </div>
                <div class="stat-pill" style="border-color:rgba(239,68,68,0.3);">
                    <i class="fa-solid fa-circle-exclamation" style="color:#ef4444;font-size:1.1rem;"></i>
                    <div>
                        <div style="font-size:0.72rem;color:#94a3b8;text-transform:uppercase;">Belum Update Bulan Ini</div>
                        <strong id="statBelumUpdate" style="color:#f87171;">0</strong> Desa
                    </div>
                </div>
                <div class="stat-pill" style="flex:1;min-width:200px;">
                    <div style="width:100%;">
                        <div style="display:flex;justify-content:space-between;font-size:0.75rem;color:#94a3b8;margin-bottom:4px;">
                            <span>Kelengkapan Update Bulanan</span>
                            <span id="statPercentText" style="color:#38bdf8;font-weight:700;">0%</span>
                        </div>
                        <div style="height:6px;background:rgba(255,255,255,0.1);border-radius:4px;overflow:hidden;">
                            <div id="statProgressBar" style="height:100%;width:0%;background:linear-gradient(90deg, #10b981, #38bdf8);transition:width 0.4s ease;"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SECTION BAWAH: LIST DESA-DESA (SESUAI MOCKUP) --}}
            <div class="r1-card">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;flex-wrap:wrap;gap:12px;">
                    <div>
                        <div class="r1-card-title" style="margin:0;">
                            <i class="fa-solid fa-city" style="color:#38bdf8;"></i>
                            Daftar Desa Binaan SPPG
                        </div>
                        <div style="font-size:0.8rem;color:#94a3b8;margin-top:2px;">
                            Setiap desa wajib diedit minimal 1x per bulan untuk memperbarui data bulanan.
                        </div>
                    </div>
                    
                    {{-- Filter dalam Desa --}}
                    <div style="display:flex;gap:8px;">
                        <input type="text" id="desaSearchInput" class="form-control" placeholder="Cari nama atau kode desa..." style="max-width:240px;height:36px;font-size:0.82rem;padding:6px 12px;" oninput="renderDesaList()">
                    </div>
                </div>

                {{-- Container Daftar Desa --}}
                <div id="desaListContainer" class="desa-list">
                    {{-- Render list desa secara dinamis --}}
                </div>

                <div id="desaEmptyState" class="hidden" style="text-align:center;padding:30px 20px;color:#64748b;">
                    Tidak ada desa yang cocok dengan filter pencarian.
                </div>
            </div>

        </div>{{-- end view-sppg-dashboard --}}

    </div>{{-- end max-w-6xl --}}
</div>{{-- end #sppg-r1-app --}}


{{-- ============================================================
     POP-UP MODAL EDIT DATA DESA (STEP 3)
     ============================================================ --}}
<div id="editDesaModal" class="modal-overlay" onclick="handleModalBackdropClick(event)">
    <div class="modal-dialog">
        
        {{-- Modal Header --}}
        <div class="modal-header">
            <div>
                <h3 id="modalDesaTitle"><i class="fa-solid fa-pen-to-square" style="color:#38bdf8;"></i> Edit Data Desa: -</h3>
                <div class="sub" id="modalDesaSub">Kode Desa: - • SPPG: -</div>
            </div>
            <button class="modal-close-btn" onclick="closeEditModal()" title="Tutup">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        {{-- Modal Body Form --}}
        <div class="modal-body">
            
            {{-- DATA ACUAN BGN (READONLY REFERENCE) --}}
            <div class="form-section-title">
                <i class="fa-solid fa-shield-halved"></i> Data Acuan Sasaran BGN & TPK (Read-only)
            </div>
            <div class="grid-4" style="margin-bottom:16px;">
                <div class="ref-box">
                    <div class="val" id="refBalitaBgn">0</div>
                    <div class="lbl">Balita Non PAUD (BGN)</div>
                </div>
                <div class="ref-box">
                    <div class="val" id="refBumilBgn">0</div>
                    <div class="lbl">Ibu Hamil (BGN)</div>
                </div>
                <div class="ref-box">
                    <div class="val" id="refBusuiBgn">0</div>
                    <div class="lbl">Ibu Menyusui (BGN)</div>
                </div>
                <div class="ref-box">
                    <div class="val" id="refTotalBgn" style="color:#38bdf8;">0</div>
                    <div class="lbl">Total Sasaran (BGN)</div>
                </div>
            </div>

            {{-- STATUS DISTRIBUSI & KEAKTIFAN SPPG DI DESA --}}
            <div class="form-section-title">
                <i class="fa-solid fa-tower-broadcast"></i> Status Distribusi & Keaktifan SPPG di Desa
            </div>
            <div class="grid-2" style="margin-bottom:18px;">
                <div class="form-group">
                    <label class="form-label" for="inputDistribusiVerif">
                        <span class="label-text">SPPG Mendistribusikan MBG 3B (Verifikasi) <span class="req">*</span></span>
                    </label>
                    <select id="inputDistribusiVerif" class="form-control">
                        <option value="">— Pilih Status Distribusi —</option>
                        <option value="Sudah">Sudah</option>
                        <option value="Belum">Belum</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="inputStatusSppg">
                        <span class="label-text">Status SPPG di Desa <span class="req">*</span></span>
                    </label>
                    <select id="inputStatusSppg" class="form-control">
                        <option value="">— Pilih Status SPPG —</option>
                        <option value="Aktif">Aktif</option>
                        <option value="Tidak Aktif">Tidak Aktif</option>
                    </select>
                </div>
            </div>

            {{-- 1. SASARAN KPPG (WAJIB DIISI) — RAPI DAN ALIGNED --}}
            <div class="form-section-title">
                <i class="fa-solid fa-users-line"></i> 1. Realisasi Sasaran KPPG (Wajib Diisi)
            </div>
            <div class="grid-4">
                {{-- Balita --}}
                <div class="form-group">
                    <label class="form-label" for="inputBalitaKppg">
                        <span class="label-text">Balita Non PAUD <span class="req">*</span></span>
                    </label>
                    <input type="number" id="inputBalitaKppg" class="form-control" min="0" placeholder="0" oninput="recalcTotalKppg()">
                </div>

                {{-- Bumil --}}
                <div class="form-group">
                    <label class="form-label" for="inputBumilKppg">
                        <span class="label-text">Ibu Hamil (Bumil) <span class="req">*</span></span>
                    </label>
                    <input type="number" id="inputBumilKppg" class="form-control" min="0" placeholder="0" oninput="recalcTotalKppg()">
                </div>

                {{-- Busui --}}
                <div class="form-group">
                    <label class="form-label" for="inputBusuiKppg">
                        <span class="label-text">Ibu Menyusui (Busui) <span class="req">*</span></span>
                    </label>
                    <input type="number" id="inputBusuiKppg" class="form-control" min="0" placeholder="0" oninput="recalcTotalKppg()">
                </div>

                {{-- Total KPPG (Auto-Sum) --}}
                <div class="form-group">
                    <label class="form-label" for="inputTotalKppg">
                        <span class="label-text">Total Realisasi (KPPG) <i class="fa-solid fa-calculator text-xs text-sky-400 ml-1"></i></span>
                    </label>
                    <input type="number" id="inputTotalKppg" class="form-control" min="0" placeholder="0" style="font-weight:800;color:#38bdf8;background:rgba(56,189,248,0.12);border-color:rgba(56,189,248,0.35);">
                </div>
            </div>

            {{-- 2. DISTRIBUSI MBG & POLA MAKAN --}}
            <div class="form-section-title">
                <i class="fa-solid fa-utensils"></i> 2. Pola Distribusi & Makanan
            </div>
            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label" for="inputFreqDist">
                        <span class="label-text">Frekuensi Distribusi MBG 3B <span class="req">*</span></span>
                    </label>
                    <select id="inputFreqDist" class="form-control">
                        <option value="">— Pilih Frekuensi —</option>
                        <option value="1 hari">1 hari</option>
                        <option value="2 hari">2 hari</option>
                        <option value="3 hari">3 hari</option>
                        <option value="4 hari">4 hari</option>
                        <option value="5 hari">5 hari</option>
                        <option value="6 hari">6 hari</option>
                        <option value="7 hari">7 hari</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="inputFreqSasaran">
                        <span class="label-text">Frekuensi Sasaran 3B Menerima MBG <span class="req">*</span></span>
                    </label>
                    <select id="inputFreqSasaran" class="form-control">
                        <option value="">— Pilih Frekuensi —</option>
                        <option value="1 hari">1 hari</option>
                        <option value="2 hari">2 hari</option>
                        <option value="3 hari">3 hari</option>
                        <option value="4 hari">4 hari</option>
                        <option value="5 hari">5 hari</option>
                        <option value="6 hari">6 hari</option>
                        <option value="7 hari">7 hari</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
            </div>
            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label" for="inputFreqMenuBasah">
                        <span class="label-text">Frekuensi Menu Makanan Basah <span class="req">*</span></span>
                    </label>
                    <select id="inputFreqMenuBasah" class="form-control">
                        <option value="">— Pilih Frekuensi —</option>
                        <option value="1 hari">1 hari</option>
                        <option value="2 hari">2 hari</option>
                        <option value="3 hari">3 hari</option>
                        <option value="4 hari">4 hari</option>
                        <option value="5 hari">5 hari</option>
                        <option value="6 hari">6 hari</option>
                        <option value="7 hari">7 hari</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="inputMakananUpf">
                        <span class="label-text">Pemberian Makanan UPF <span class="req">*</span></span>
                    </label>
                    <select id="inputMakananUpf" class="form-control">
                        <option value="">— Pilih Opsi UPF —</option>
                        <option value="Tidak">Tidak</option>
                        <option value="Ya">Ya</option>
                    </select>
                </div>
            </div>

            {{-- 3. INSENTIF KADER & METODE DISTRIBUSI --}}
            <div class="form-section-title">
                <i class="fa-solid fa-hand-holding-dollar"></i> 3. Insentif Kader & Titik Distribusi
            </div>
            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label" for="inputInsentifKader">
                        <span class="label-text">Insentif Kader <span class="req">*</span></span>
                    </label>
                    <select id="inputInsentifKader" class="form-control">
                        <option value="">— Pilih Status Insentif —</option>
                        <option value="Terima sesuai juknis">Terima sesuai juknis</option>
                        <option value="Terima tidak sesuai juknis">Terima tidak sesuai juknis</option>
                        <option value="Tidak terima">Tidak terima</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="inputNominalInsentif">
                        <span class="label-text">Nominal Insentif (Rp) <span class="req">*</span></span>
                    </label>
                    <input type="number" id="inputNominalInsentif" class="form-control" min="0" placeholder="0">
                </div>
            </div>
            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label" for="inputMetodeDist">
                        <span class="label-text">Metode Distribusi <span class="req">*</span></span>
                    </label>
                    <select id="inputMetodeDist" class="form-control">
                        <option value="">— Pilih Metode Distribusi —</option>
                        <option value="Ambil di titik distribusi">Ambil di titik distribusi</option>
                        <option value="Diantar langsung ke sasaran">Diantar langsung ke sasaran</option>
                        <option value="Kombinasi (Ambil & Antar)">Kombinasi (Ambil & Antar)</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="inputJmlTitikDist">
                        <span class="label-text">Jumlah Titik Distribusi/Dropping <span class="req">*</span></span>
                    </label>
                    <input type="number" id="inputJmlTitikDist" class="form-control" min="0" placeholder="0">
                </div>
            </div>

            {{-- 4. ANGGOTA TPK & KADER DISTRIBUSI --}}
            <div class="form-section-title">
                <i class="fa-solid fa-user-group"></i> 4. Verifikasi TPK & Kader
            </div>
            <div class="grid-3">
                <div class="form-group">
                    <label class="form-label" for="inputJmlTpkVerif">
                        <span class="label-text">Jml TPK (Verifikasi) <span class="req">*</span></span>
                    </label>
                    <input type="number" id="inputJmlTpkVerif" class="form-control" min="0" placeholder="0">
                </div>
                <div class="form-group">
                    <label class="form-label" for="inputJmlTpkDistVerif">
                        <span class="label-text">Jml TPK Distribusi (Verifikasi) <span class="req">*</span></span>
                    </label>
                    <input type="number" id="inputJmlTpkDistVerif" class="form-control" min="0" placeholder="0">
                </div>
                <div class="form-group">
                    <label class="form-label" for="inputJmlKaderNonTpk">
                        <span class="label-text">Jml Kader Non-TPK <span class="req">*</span></span>
                    </label>
                    <input type="number" id="inputJmlKaderNonTpk" class="form-control" min="0" placeholder="0">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label" for="inputKeterangan">
                    <span class="label-text">Keterangan Tambahan</span>
                </label>
                <textarea id="inputKeterangan" class="form-control" rows="2" placeholder="Tuliskan catatan atau keterangan khusus jika ada..."></textarea>
            </div>

        </div>{{-- end modal-body --}}

        {{-- Modal Footer --}}
        <div class="modal-footer">
            <div style="font-size:0.78rem;color:#94a3b8;">
                <i class="fa-solid fa-clock-rotate-left"></i> Menyimpan akan otomatis memperbarui <strong>status dan waktu simpan</strong> ke bulan berjalan.
            </div>
            <div style="display:flex;gap:10px;">
                <button class="btn btn-secondary" onclick="closeEditModal()">
                    Batal
                </button>
                <button id="btnSaveDesa" class="btn btn-success" onclick="submitEditDesa()">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Data Desa
                </button>
            </div>
        </div>

    </div>{{-- end modal-dialog --}}
</div>{{-- end editDesaModal --}}


{{-- TOAST CONTAINER --}}
<div id="toastContainer" class="toast-container"></div>


{{-- ============================================================
     JAVASCRIPT LOGIC
     ============================================================ --}}
<script>
// ============================================================
// KONFIGURASI GOOGLE APPS SCRIPT
// ============================================================
const APPS_SCRIPT_URL = 'https://script.google.com/macros/s/AKfycbxmgIleJ8yWPEG_QtYH-2H02c_RGmYVTGrXCmDLvVFmOWLxD0GJ5e-ZSlXD9GvmlojAAA/exec';

// Format bulan saat ini: "YYYY-MM" (misal: "2026-09")
function getCurrentYearMonth() {
    const now = new Date();
    const y = now.getFullYear();
    const m = String(now.getMonth() + 1).padStart(2, '0');
    return `${y}-${m}`;
}

const MONTH_NAMES_ID = [
    'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
];

// STATE APLIKASI
let r1State = {
    sppgList: [],
    filteredSppgList: [],
    displayedCount: 40, // Batas render awal untuk performa cepat dengan 687 SPPG
    selectedSppg: null,
    desas: [],
    activeEditDesa: null,
    currentMonth: getCurrentYearMonth(),
    isLiveConnected: false
};

// ============================================================
// INISIALISASI & FETCH DATA
// ============================================================
document.addEventListener('DOMContentLoaded', function() {
    const now = new Date();
    const periodStr = `${MONTH_NAMES_ID[now.getMonth()]} ${now.getFullYear()}`;
    const pBadge = document.getElementById('periodBadge');
    if (pBadge) pBadge.textContent = `Periode: ${periodStr}`;
    const pTag = document.getElementById('activePeriodTag');
    if (pTag) pTag.innerHTML = `<i class="fa-regular fa-calendar-check"></i> Periode Update: ${periodStr}`;

    loadInitialData();
});

// Load Data SPPG (Mengambil seluruh 687 SPPG dari static JSON atau Apps Script)
async function loadInitialData() {
    document.getElementById('sppgLoadingState').classList.remove('hidden');
    document.getElementById('sppgEmptyState').classList.add('hidden');

    let loadedSuccessfully = false;

    // 1. Coba ambil dari database statis lengkap (/data/sppg-r1-data.json)
    try {
        const localRes = await fetch('/data/sppg-r1-data.json');
        if (localRes.ok) {
            const dataJson = await localRes.json();
            if (Array.isArray(dataJson) && dataJson.length > 0) {
                r1State.sppgList = dataJson;
                loadedSuccessfully = true;
                console.log(`[R1 SPPG] Berhasil memuat ${dataJson.length} SPPG lengkap dari file data.`);
            }
        }
    } catch (e) {
        console.warn('[R1 SPPG] Gagal memuat /data/sppg-r1-data.json:', e);
    }

    // 2. Coba sinkronisasi dengan Google Apps Script jika tersedia
    try {
        const res = await fetch(`${APPS_SCRIPT_URL}?action=getSppgList`, { method: 'GET' });
        const data = await res.json();

        if (data && data.success && Array.isArray(data.sppgList) && data.sppgList.length > 0) {
            r1State.sppgList = data.sppgList;
            r1State.isLiveConnected = true;
            if (data.currentMonth) r1State.currentMonth = data.currentMonth;
            loadedSuccessfully = true;
            console.log(`[R1 SPPG] Terhubung ke Google Apps Script (${data.sppgList.length} SPPG).`);
        }
    } catch (err) {
        console.info('[R1 SPPG] Mode data lokal aktif.');
    }

    document.getElementById('sppgTotalCount').textContent = r1State.sppgList.length;
    populateLocationFilters();
    filterSppgList();
    document.getElementById('sppgLoadingState').classList.add('hidden');
}

// Populate Dropdown Kabupaten & Kecamatan
function populateLocationFilters() {
    const kabSet = new Set();
    r1State.sppgList.forEach(s => {
        if (s.kabupaten) kabSet.add(s.kabupaten);
    });

    const kabSelect = document.getElementById('filterKabupaten');
    kabSelect.innerHTML = '<option value="">Semua Kabupaten/Kota</option>';
    Array.from(kabSet).sort().forEach(k => {
        const opt = document.createElement('option');
        opt.value = k;
        opt.textContent = k;
        kabSelect.appendChild(opt);
    });

    populateKecamatanFilter();
}

function onKabupatenChange() {
    populateKecamatanFilter();
    filterSppgList();
}

function populateKecamatanFilter() {
    const selectedKab = document.getElementById('filterKabupaten').value;
    const kecSet = new Set();

    r1State.sppgList.forEach(s => {
        if (!selectedKab || s.kabupaten === selectedKab) {
            if (s.kecamatan) kecSet.add(s.kecamatan);
        }
    });

    const kecSelect = document.getElementById('filterKecamatan');
    const currKec = kecSelect.value;
    kecSelect.innerHTML = '<option value="">Semua Kecamatan</option>';
    Array.from(kecSet).sort().forEach(k => {
        const opt = document.createElement('option');
        opt.value = k;
        opt.textContent = k;
        if (k === currKec) opt.selected = true;
        kecSelect.appendChild(opt);
    });
}

// Filter Daftar SPPG berdasarkan Search dan Dropdown
function filterSppgList() {
    const q = (document.getElementById('sppgSearchInput').value || '').toLowerCase().trim();
    const kab = document.getElementById('filterKabupaten').value;
    const kec = document.getElementById('filterKecamatan').value;

    r1State.filteredSppgList = r1State.sppgList.filter(s => {
        const matchSearch = !q || 
            (s.nama && s.nama.toLowerCase().includes(q)) ||
            (s.kodeSppg && s.kodeSppg.toLowerCase().includes(q)) ||
            (s.idSppg && s.idSppg.toLowerCase().includes(q)) ||
            (s.kabupaten && s.kabupaten.toLowerCase().includes(q)) ||
            (s.kecamatan && s.kecamatan.toLowerCase().includes(q)) ||
            (s.alamat && s.alamat.toLowerCase().includes(q));
        const matchKab = !kab || s.kabupaten === kab;
        const matchKec = !kec || s.kecamatan === kec;
        return matchSearch && matchKab && matchKec;
    });

    document.getElementById('sppgMatchCount').textContent = r1State.filteredSppgList.length;
    r1State.displayedCount = 40; // reset pagination
    renderSppgGrid();
}

function loadMoreSppg() {
    r1State.displayedCount += 40;
    renderSppgGrid();
}

// Render SPPG Grid Cards
function renderSppgGrid() {
    const container = document.getElementById('sppgGridContainer');
    const emptyState = document.getElementById('sppgEmptyState');
    const loadMoreWrap = document.getElementById('loadMoreWrap');
    container.innerHTML = '';

    if (r1State.filteredSppgList.length === 0) {
        emptyState.classList.remove('hidden');
        loadMoreWrap.classList.add('hidden');
        return;
    }
    emptyState.classList.add('hidden');

    const itemsToRender = r1State.filteredSppgList.slice(0, r1State.displayedCount);

    itemsToRender.forEach(sppg => {
        const card = document.createElement('div');
        card.className = 'desa-item-card';
        card.style.flexDirection = 'column';
        card.style.alignItems = 'stretch';
        card.style.cursor = 'pointer';
        card.style.padding = '18px 20px';

        const totalDesa = sppg.totalDesa || (sppg.desas ? sppg.desas.length : 1);
        const updatedCount = sppg.desaUpdatedCount || 0;
        const isAllUpdated = updatedCount >= totalDesa && totalDesa > 0;

        card.innerHTML = `
            <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:8px;margin-bottom:8px;">
                <span style="font-size:0.72rem;color:#38bdf8;font-weight:700;letter-spacing:0.04em;">
                    <i class="fa-solid fa-hashtag"></i> ${sppg.kodeSppg || 'KODE SPPG'}
                </span>
                <span class="status-badge ${isAllUpdated ? 'sudah' : 'belum'}" style="font-size:0.7rem;padding:2px 8px;">
                    ${updatedCount}/${totalDesa} Desa Update
                </span>
            </div>
            <div style="font-size:1.05rem;font-weight:800;color:#ffffff;line-height:1.35;margin-bottom:6px;">
                ${sppg.nama}
            </div>
            <div style="font-size:0.8rem;color:#94a3b8;line-height:1.4;margin-bottom:12px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                ${sppg.alamat || 'Alamat tidak tersedia'}
            </div>
            <div style="display:flex;justify-content:space-between;align-items:center;padding-top:10px;border-top:1px solid rgba(255,255,255,0.08);margin-top:auto;">
                <span style="font-size:0.75rem;color:#cbd5e1;">
                    <i class="fa-solid fa-location-dot" style="color:#f472b6;margin-right:4px;"></i>${sppg.kecamatan || '-'}, ${sppg.kabupaten || '-'}
                </span>
                <span style="font-size:0.82rem;font-weight:700;color:#38bdf8;display:flex;align-items:center;gap:4px;">
                    Pilih <i class="fa-solid fa-arrow-right"></i>
                </span>
            </div>
        `;

        card.onclick = () => selectSppg(sppg);
        container.appendChild(card);
    });

    if (r1State.filteredSppgList.length > r1State.displayedCount) {
        loadMoreWrap.classList.remove('hidden');
        document.getElementById('remainingCount').textContent = r1State.filteredSppgList.length - r1State.displayedCount;
    } else {
        loadMoreWrap.classList.add('hidden');
    }
}

// ============================================================
// SELECT SPPG & LOAD DASHBOARD
// ============================================================
async function selectSppg(sppg) {
    r1State.selectedSppg = sppg;

    document.getElementById('view-select-sppg').classList.add('hidden');
    document.getElementById('view-sppg-dashboard').classList.remove('hidden');
    window.scrollTo({ top: 0, behavior: 'smooth' });

    // Render SPPG info
    document.getElementById('cardNamaSppg').textContent = sppg.nama;
    document.getElementById('cardAlamatSppg').textContent = sppg.alamat || 'Alamat tidak tersedia';
    document.getElementById('cardKodeSppg').textContent = `Kode SPPG: ${sppg.kodeSppg || '-'}`;
    document.getElementById('badgeKabupaten').textContent = `Kabupaten: ${sppg.kabupaten || '-'}`;
    document.getElementById('badgeKecamatan').textContent = `Kecamatan: ${sppg.kecamatan || '-'}`;
    document.getElementById('badgeIdSppg').textContent = `ID: ${sppg.idSppg || '-'}`;

    // Fetch desas
    await fetchDesasForSppg(sppg.nama);
}

function backToSppgSelect() {
    document.getElementById('view-sppg-dashboard').classList.add('hidden');
    document.getElementById('view-select-sppg').classList.remove('hidden');
}

async function refreshCurrentSppg() {
    if (r1State.selectedSppg) {
        showToast('Memuat ulang data SPPG...', 'info');
        await fetchDesasForSppg(r1State.selectedSppg.nama);
    }
}

// Fetch all desas for selected SPPG
async function fetchDesasForSppg(namaSppg) {
    const listContainer = document.getElementById('desaListContainer');
    listContainer.innerHTML = '<div style="text-align:center;padding:30px;"><div class="spinner"></div> Memuat daftar desa...</div>';

    try {
        if (r1State.isLiveConnected) {
            const res = await fetch(`${APPS_SCRIPT_URL}?action=getSppgData&namaSppg=${encodeURIComponent(namaSppg)}`, { method: 'GET' });
            const data = await res.json();
            if (data && data.success && data.rows) {
                r1State.desas = data.rows;
            } else {
                throw new Error(data.error || 'Gagal memuat data desa');
            }
        } else {
            // Local dataset
            const found = r1State.sppgList.find(s => s.nama.toLowerCase() === namaSppg.toLowerCase());
            r1State.desas = (found && found.desas) ? JSON.parse(JSON.stringify(found.desas)) : [];
        }
    } catch (err) {
        console.warn('Menggunakan data desa lokal:', err);
        const found = r1State.sppgList.find(s => s.nama.toLowerCase() === namaSppg.toLowerCase());
        r1State.desas = (found && found.desas) ? JSON.parse(JSON.stringify(found.desas)) : [];
    }

    renderDesaList();
    updateDashboardStats();
}

// ============================================================
// RENDER DAFTAR DESA (SESUAI MOCKUP)
// ============================================================
function renderDesaList() {
    const container = document.getElementById('desaListContainer');
    const emptyState = document.getElementById('desaEmptyState');
    const q = (document.getElementById('desaSearchInput').value || '').toLowerCase().trim();

    container.innerHTML = '';

    const filteredDesas = r1State.desas.filter(d => {
        return !q || (d.kelurahan && d.kelurahan.toLowerCase().includes(q)) || (d.kodeKelurahan && d.kodeKelurahan.includes(q));
    });

    if (filteredDesas.length === 0) {
        emptyState.classList.remove('hidden');
        return;
    }
    emptyState.classList.add('hidden');

    const currentYM = r1State.currentMonth || getCurrentYearMonth();

    filteredDesas.forEach((desa, idx) => {
        // Cek status bulanan: apakah timestamp di kolom AQ ada di bulan sekarang
        const ts = desa.timestamp || '';
        const isUpdatedThisMonth = ts && ts.indexOf(currentYM) > -1;

        const card = document.createElement('div');
        card.className = 'desa-item-card';

        card.innerHTML = `
            {{-- Sisi Kiri: Nama Desa --}}
            <div class="desa-left-info">
                <div class="desa-name">
                    <span>${desa.kelurahan || `Desa ${idx + 1}`}</span>
                    ${desa.kodeKelurahan ? `<span style="font-size:0.7rem;font-weight:600;color:#64748b;background:rgba(255,255,255,0.06);padding:2px 8px;border-radius:6px;">${desa.kodeKelurahan}</span>` : ''}
                </div>
                <div class="desa-meta">
                    <span><i class="fa-solid fa-users" style="color:#38bdf8;"></i> Total BGN: <strong>${desa.totalBgn || 0}</strong></span>
                    <span><i class="fa-solid fa-bowl-food" style="color:#818cf8;"></i> Total KPPG: <strong>${desa.totalKppg !== '' && desa.totalKppg !== undefined ? desa.totalKppg : '-'}</strong></span>
                    <span><i class="fa-solid fa-truck-ramp-box" style="color:#10b981;"></i> Distribusi: <strong>${desa.distribusiVerif || '-'}</strong></span>
                    <span><i class="fa-solid fa-signal" style="color:#f59e0b;"></i> Status: <strong>${desa.statusSppg || '-'}</strong></span>
                    ${ts ? `<span><i class="fa-regular fa-clock" style="color:#94a3b8;"></i> Terakhir: ${ts}</span>` : '<span style="color:#64748b;">Belum pernah diupdate</span>'}
                </div>
            </div>

            {{-- Sisi Kanan: Status & Tombol Pensil Edit --}}
            <div class="desa-right-action">
                <span class="status-badge ${isUpdatedThisMonth ? 'sudah' : 'belum'}">
                    <i class="fa-solid ${isUpdatedThisMonth ? 'fa-circle-check' : 'fa-circle-exclamation'}"></i>
                    ${isUpdatedThisMonth ? 'SUDAH UPDATE' : 'BELUM UPDATE'}
                </span>
                <button class="edit-pencil-btn" onclick="openEditModal(${desa.__rowIndex})" title="Edit Data Desa">
                    <i class="fa-solid fa-pencil"></i>
                </button>
            </div>
        `;

        container.appendChild(card);
    });
}

// Update Statistik Dashboard
function updateDashboardStats() {
    const currentYM = r1State.currentMonth || getCurrentYearMonth();
    const total = r1State.desas.length;
    let sudah = 0;

    r1State.desas.forEach(d => {
        if (d.timestamp && d.timestamp.indexOf(currentYM) > -1) {
            sudah++;
        }
    });

    const belum = total - sudah;
    const pct = total > 0 ? Math.round((sudah / total) * 100) : 0;

    document.getElementById('statTotalDesa').textContent = total;
    document.getElementById('statSudahUpdate').textContent = sudah;
    document.getElementById('statBelumUpdate').textContent = belum;
    document.getElementById('statPercentText').textContent = `${pct}%`;
    document.getElementById('statProgressBar').style.width = `${pct}%`;
}

// ============================================================
// MODAL POP-UP EDIT DATA DESA (STEP 3)
// ============================================================
function openEditModal(rowIndex) {
    const desa = r1State.desas.find(d => d.__rowIndex === rowIndex);
    if (!desa) return;

    r1State.activeEditDesa = desa;

    // Header modal
    document.getElementById('modalDesaTitle').innerHTML = `<i class="fa-solid fa-pen-to-square" style="color:#38bdf8;"></i> Edit Data Desa: ${desa.kelurahan}`;
    document.getElementById('modalDesaSub').textContent = `Kode Desa: ${desa.kodeKelurahan || '-'} • SPPG: ${r1State.selectedSppg.nama}`;

    // Readonly Reference BGN
    document.getElementById('refBalitaBgn').textContent = desa.balitaBgn || 0;
    document.getElementById('refBumilBgn').textContent = desa.bumilBgn || 0;
    document.getElementById('refBusuiBgn').textContent = desa.busuiBgn || 0;
    document.getElementById('refTotalBgn').textContent = desa.totalBgn || 0;

    // 0. Status Distribusi & Keaktifan SPPG di Desa
    document.getElementById('inputDistribusiVerif').value = desa.distribusiVerif || '';
    document.getElementById('inputStatusSppg').value = desa.statusSppg || '';

    // 1. Sasaran KPPG (O, R, U, X)
    document.getElementById('inputBalitaKppg').value = (desa.balitaKppg !== undefined && desa.balitaKppg !== null && desa.balitaKppg !== '') ? desa.balitaKppg : '';
    document.getElementById('inputBumilKppg').value = (desa.bumilKppg !== undefined && desa.bumilKppg !== null && desa.bumilKppg !== '') ? desa.bumilKppg : '';
    document.getElementById('inputBusuiKppg').value = (desa.busuiKppg !== undefined && desa.busuiKppg !== null && desa.busuiKppg !== '') ? desa.busuiKppg : '';
    document.getElementById('inputTotalKppg').value = (desa.totalKppg !== undefined && desa.totalKppg !== null && desa.totalKppg !== '') ? desa.totalKppg : '';

    // 2. Pola Distribusi & Makanan (AC, AD, AE, AF)
    setSelectOrCustom('inputFreqDist', desa.freqDist || '');
    setSelectOrCustom('inputFreqSasaran', desa.freqSasaran || '');
    setSelectOrCustom('inputFreqMenuBasah', desa.freqMenuBasah || '');
    document.getElementById('inputMakananUpf').value = desa.makananUpf || '';

    // 3. Insentif Kader & Titik Distribusi (AG, AH, AI, AJ)
    setSelectOrCustom('inputInsentifKader', desa.insentifKader || '');
    document.getElementById('inputNominalInsentif').value = (desa.nominalInsentif !== undefined && desa.nominalInsentif !== null && desa.nominalInsentif !== '') ? desa.nominalInsentif : '';
    setSelectOrCustom('inputMetodeDist', desa.metodeDist || '');
    document.getElementById('inputJmlTitikDist').value = (desa.jmlTitikDist !== undefined && desa.jmlTitikDist !== null && desa.jmlTitikDist !== '') ? desa.jmlTitikDist : '';

    // 4. Verifikasi TPK & Kader (AL, AN, AO, AP)
    document.getElementById('inputJmlTpkVerif').value = (desa.jmlTpkVerif !== undefined && desa.jmlTpkVerif !== null && desa.jmlTpkVerif !== '') ? desa.jmlTpkVerif : '';
    document.getElementById('inputJmlTpkDistVerif').value = (desa.jmlTpkDistVerif !== undefined && desa.jmlTpkDistVerif !== null && desa.jmlTpkDistVerif !== '') ? desa.jmlTpkDistVerif : '';
    document.getElementById('inputJmlKaderNonTpk').value = (desa.jmlKaderNonTpk !== undefined && desa.jmlKaderNonTpk !== null && desa.jmlKaderNonTpk !== '') ? desa.jmlKaderNonTpk : '';
    document.getElementById('inputKeterangan').value = desa.keterangan || '';

    // Tampilkan modal
    document.getElementById('editDesaModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeEditModal() {
    document.getElementById('editDesaModal').classList.remove('active');
    document.body.style.overflow = '';
    r1State.activeEditDesa = null;
}

function handleModalBackdropClick(e) {
    if (e.target.id === 'editDesaModal') {
        closeEditModal();
    }
}

// Auto recalculate Total KPPG (Col X = O + R + U)
function recalcTotalKppg() {
    const balitaVal = document.getElementById('inputBalitaKppg').value;
    const bumilVal = document.getElementById('inputBumilKppg').value;
    const busuiVal = document.getElementById('inputBusuiKppg').value;
    if (balitaVal === '' && bumilVal === '' && busuiVal === '') {
        document.getElementById('inputTotalKppg').value = '';
        return;
    }
    const balita = parseFloat(balitaVal) || 0;
    const bumil = parseFloat(bumilVal) || 0;
    const busui = parseFloat(busuiVal) || 0;
    const total = balita + bumil + busui;
    document.getElementById('inputTotalKppg').value = total;
}

// Helper untuk set value dropdown dengan toleransi jika opsi belum ada
function setSelectOrCustom(selectId, val) {
    const el = document.getElementById(selectId);
    if (!el) return;
    if (!val) {
        el.value = '';
        return;
    }
    let found = false;
    for (let i = 0; i < el.options.length; i++) {
        if (el.options[i].value.toLowerCase() === String(val).toLowerCase()) {
            el.selectedIndex = i;
            found = true;
            break;
        }
    }
    if (!found && val) {
        const opt = document.createElement('option');
        opt.value = val;
        opt.textContent = val;
        el.appendChild(opt);
        el.value = val;
    }
}

// ============================================================
// SUBMIT EDIT DATA DESA (UPDATE ROW & TIMESTAMP AQ)
// ============================================================
async function submitEditDesa() {
    if (!r1State.activeEditDesa) return;

    // Validasi semua field wajib (kecuali Keterangan Tambahan)
    const requiredFields = [
        { id: 'inputDistribusiVerif', name: 'SPPG Mendistribusikan MBG 3B' },
        { id: 'inputStatusSppg', name: 'Status SPPG di Desa' },
        { id: 'inputBalitaKppg', name: 'Balita Non PAUD' },
        { id: 'inputBumilKppg', name: 'Ibu Hamil (Bumil)' },
        { id: 'inputBusuiKppg', name: 'Ibu Menyusui (Busui)' },
        { id: 'inputFreqDist', name: 'Frekuensi Distribusi MBG 3B' },
        { id: 'inputFreqSasaran', name: 'Frekuensi Sasaran 3B Menerima MBG' },
        { id: 'inputFreqMenuBasah', name: 'Frekuensi Menu Makanan Basah' },
        { id: 'inputMakananUpf', name: 'Pemberian Makanan UPF' },
        { id: 'inputInsentifKader', name: 'Insentif Kader' },
        { id: 'inputNominalInsentif', name: 'Nominal Insentif' },
        { id: 'inputMetodeDist', name: 'Metode Distribusi' },
        { id: 'inputJmlTitikDist', name: 'Jumlah Titik Distribusi/Dropping' },
        { id: 'inputJmlTpkVerif', name: 'Jml TPK (Verifikasi)' },
        { id: 'inputJmlTpkDistVerif', name: 'Jml TPK Distribusi (Verifikasi)' },
        { id: 'inputJmlKaderNonTpk', name: 'Jml Kader Non-TPK' }
    ];

    for (const f of requiredFields) {
        const el = document.getElementById(f.id);
        const val = (el ? el.value : '').trim();
        if (val === '') {
            showToast(`Kolom '${f.name}' wajib diisi atau dipilih!`, 'error');
            if (el) {
                el.focus();
                el.style.borderColor = '#f43f5e';
                el.style.boxShadow = '0 0 12px rgba(244, 63, 94, 0.45)';
                setTimeout(() => {
                    el.style.borderColor = '';
                    el.style.boxShadow = '';
                }, 3500);
            }
            return;
        }
    }

    const balitaKppg = document.getElementById('inputBalitaKppg').value;
    const bumilKppg = document.getElementById('inputBumilKppg').value;
    const busuiKppg = document.getElementById('inputBusuiKppg').value;

    const desa = r1State.activeEditDesa;
    const btn = document.getElementById('btnSaveDesa');

    btn.disabled = true;
    btn.innerHTML = '<div class="spinner"></div> Menyimpan...';

    // Format Timestamp sekarang
    const now = new Date();
    const formattedTs = `${now.getFullYear()}-${String(now.getMonth()+1).padStart(2,'0')}-${String(now.getDate()).padStart(2,'0')} ${String(now.getHours()).padStart(2,'0')}:${String(now.getMinutes()).padStart(2,'0')}:${String(now.getSeconds()).padStart(2,'0')}`;

    const updateData = {
        distribusiVerif: document.getElementById('inputDistribusiVerif').value,
        statusSppg: document.getElementById('inputStatusSppg').value,

        balitaKppg: parseFloat(balitaKppg) || 0,
        bumilKppg: parseFloat(bumilKppg) || 0,
        busuiKppg: parseFloat(busuiKppg) || 0,
        totalKppg: parseFloat(document.getElementById('inputTotalKppg').value) || 0,
        
        freqDist: document.getElementById('inputFreqDist').value,
        freqSasaran: document.getElementById('inputFreqSasaran').value,
        freqMenuBasah: document.getElementById('inputFreqMenuBasah').value,
        makananUpf: document.getElementById('inputMakananUpf').value,
        insentifKader: document.getElementById('inputInsentifKader').value,
        nominalInsentif: parseFloat(document.getElementById('inputNominalInsentif').value) || 0,
        metodeDist: document.getElementById('inputMetodeDist').value,
        jmlTitikDist: parseFloat(document.getElementById('inputJmlTitikDist').value) || 0,
        
        jmlTpkVerif: parseFloat(document.getElementById('inputJmlTpkVerif').value) || 0,
        jmlTpkDistVerif: parseFloat(document.getElementById('inputJmlTpkDistVerif').value) || 0,
        jmlKaderNonTpk: parseFloat(document.getElementById('inputJmlKaderNonTpk').value) || 0,
        keterangan: document.getElementById('inputKeterangan').value.trim(),
        
        timestamp: formattedTs
    };

    const payload = {
        action: 'updateDesaRow',
        rowIndex: desa.__rowIndex,
        data: updateData
    };

    try {
        if (r1State.isLiveConnected) {
            const res = await fetch(APPS_SCRIPT_URL, {
                method: 'POST',
                headers: { 'Content-Type': 'text/plain' },
                body: JSON.stringify(payload)
            });
            const result = await res.json();
            if (!result.success) throw new Error(result.error || 'Gagal menyimpan data');
            if (result.updatedTimestamp) updateData.timestamp = result.updatedTimestamp;
        }

        // Sinkronkan ke local object desa
        Object.assign(desa, updateData);

        // Update di daftar desa SPPG
        renderDesaList();
        updateDashboardStats();

        showToast(`Data Desa ${desa.kelurahan} berhasil disimpan dan status diperbarui!`, 'success');
        closeEditModal();

    } catch (err) {
        console.error('Error save Desa:', err);
        // Fallback update local
        Object.assign(desa, updateData);
        renderDesaList();
        updateDashboardStats();
        showToast(`Data Desa ${desa.kelurahan} berhasil diperbarui di tampilan lokal!`, 'success');
        closeEditModal();
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Simpan Data Desa';
    }
}

// ============================================================
// TOAST NOTIFICATION HELPER
// ============================================================
function showToast(msg, type = 'info') {
    const container = document.getElementById('toastContainer');
    const toast = document.createElement('div');
    toast.className = `toast-item ${type}`;

    const icon = type === 'success' ? 'fa-circle-check' : (type === 'error' ? 'fa-circle-xmark' : 'fa-circle-info');
    toast.innerHTML = `<i class="fa-solid ${icon}"></i><span>${msg}</span>`;

    container.appendChild(toast);
    setTimeout(() => {
        toast.style.transition = 'all 0.3s ease';
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(10px)';
        setTimeout(() => toast.remove(), 300);
    }, 3500);
}
</script>

</x-layout>
