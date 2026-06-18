<x-layout>
<x-slot:title>{{ $title }}</x-slot:title>

{{-- =============================================
     UPDATE K0 SPPG — Wizard 3 Langkah
     ============================================= --}}

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

    * { box-sizing: border-box; }

    #sppg-app {
        font-family: 'Inter', sans-serif;
        min-height: 100vh;
        background: linear-gradient(135deg, #0f2027 0%, #203a43 50%, #2c5364 100%);
        padding: 32px 16px 60px;
        color: #e2e8f0;
    }

    /* ---- HEADER ---- */
    .page-header {
        text-align: center;
        margin-bottom: 40px;
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

    /* ---- STEPPER ---- */
    .stepper {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0;
        margin-bottom: 40px;
        max-width: 560px;
        margin-left: auto;
        margin-right: auto;
    }
    .step-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        flex: 1;
        position: relative;
        z-index: 1;
    }
    .step-item::before {
        content: '';
        position: absolute;
        top: 20px;
        left: calc(50% + 20px);
        right: calc(-50% + 20px);
        height: 2px;
        background: #334155;
        z-index: 0;
    }
    .step-item:last-child::before { display: none; }
    .step-item.active::before,
    .step-item.done::before { background: linear-gradient(90deg, #38bdf8, #818cf8); }

    .step-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.9rem;
        border: 2px solid #334155;
        background: #1e293b;
        color: #64748b;
        transition: all 0.4s ease;
        position: relative;
        z-index: 1;
    }
    .step-item.active .step-circle {
        border-color: #38bdf8;
        background: linear-gradient(135deg, #38bdf8, #818cf8);
        color: white;
        box-shadow: 0 0 20px rgba(56,189,248,0.5);
    }
    .step-item.done .step-circle {
        border-color: #10b981;
        background: #10b981;
        color: white;
    }
    .step-label {
        margin-top: 8px;
        font-size: 0.72rem;
        color: #64748b;
        text-align: center;
        font-weight: 500;
        transition: color 0.4s;
    }
    .step-item.active .step-label,
    .step-item.done .step-label { color: #e2e8f0; }

    /* ---- CARD ---- */
    .card {
        background: rgba(30, 41, 59, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 20px;
        padding: 36px;
        max-width: 900px;
        margin: 0 auto;
        box-shadow: 0 25px 50px rgba(0,0,0,0.5);
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #f1f5f9;
        margin: 0 0 6px;
    }
    .card-subtitle {
        font-size: 0.85rem;
        color: #64748b;
        margin: 0 0 28px;
    }

    /* ---- FORM ELEMENTS ---- */
    .form-label {
        display: block;
        font-size: 0.82rem;
        font-weight: 600;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 8px;
    }
    .form-control {
        width: 100%;
        padding: 12px 16px;
        background: rgba(15, 23, 42, 0.8);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 10px;
        color: #e2e8f0;
        font-size: 0.95rem;
        font-family: 'Inter', sans-serif;
        transition: all 0.3s ease;
        outline: none;
    }
    .form-control:focus {
        border-color: #38bdf8;
        box-shadow: 0 0 0 3px rgba(56,189,248,0.15);
    }
    .form-control option {
        background: #1e293b;
        color: #e2e8f0;
    }

    /* ---- BUTTONS ---- */
    .btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        border: none;
        border-radius: 10px;
        font-size: 0.92rem;
        font-weight: 600;
        font-family: 'Inter', sans-serif;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none !important;
    }
    .btn-primary {
        background: linear-gradient(135deg, #38bdf8, #818cf8);
        color: white;
        box-shadow: 0 4px 15px rgba(56,189,248,0.3);
    }
    .btn-primary:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(56,189,248,0.45);
    }
    .btn-success {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        box-shadow: 0 4px 15px rgba(16,185,129,0.3);
    }
    .btn-success:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(16,185,129,0.45);
    }
    .btn-ghost {
        background: rgba(255,255,255,0.07);
        color: #94a3b8;
        border: 1px solid rgba(255,255,255,0.1);
    }
    .btn-ghost:hover {
        background: rgba(255,255,255,0.12);
        color: #e2e8f0;
    }
    .btn-sm {
        padding: 6px 14px;
        font-size: 0.82rem;
        border-radius: 7px;
    }

    /* ---- SEARCH ---- */
    .search-container { position: relative; }
    .search-container .form-control { padding-right: 40px; }
    .search-icon {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #64748b;
        pointer-events: none;
    }
    .search-dropdown {
        position: absolute;
        top: calc(100% + 6px);
        left: 0;
        right: 0;
        background: #1e293b;
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 10px;
        max-height: 220px;
        overflow-y: auto;
        z-index: 50;
        box-shadow: 0 10px 40px rgba(0,0,0,0.5);
    }
    .search-dropdown::-webkit-scrollbar { width: 6px; }
    .search-dropdown::-webkit-scrollbar-thumb { background: #334155; border-radius: 3px; }
    .search-option {
        padding: 11px 16px;
        cursor: pointer;
        font-size: 0.88rem;
        color: #cbd5e1;
        transition: background 0.2s;
    }
    .search-option:hover, .search-option.focused { background: rgba(56,189,248,0.15); color: #38bdf8; }
    .search-option.selected { background: rgba(56,189,248,0.2); color: #38bdf8; font-weight: 600; }

    /* ---- ALERT ---- */
    .alert {
        padding: 14px 18px;
        border-radius: 10px;
        font-size: 0.88rem;
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 20px;
    }
    .alert-error { background: rgba(239,68,68,0.12); border: 1px solid rgba(239,68,68,0.3); color: #fca5a5; }
    .alert-success { background: rgba(16,185,129,0.12); border: 1px solid rgba(16,185,129,0.3); color: #6ee7b7; }
    .alert-info { background: rgba(56,189,248,0.1); border: 1px solid rgba(56,189,248,0.2); color: #7dd3fc; }

    /* ---- LOADING ---- */
    .spinner {
        width: 20px; height: 20px;
        border: 2px solid rgba(255,255,255,0.2);
        border-top-color: #38bdf8;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
        display: inline-block;
        flex-shrink: 0;
    }
    .spinner-lg { width: 40px; height: 40px; border-width: 3px; }
    @keyframes spin { to { transform: rotate(360deg); } }

    .loading-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.6);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        gap: 16px;
        backdrop-filter: blur(4px);
    }
    .loading-overlay p { color: #e2e8f0; font-weight: 600; font-size: 0.95rem; }

    /* ---- TABLE ---- */
    .table-wrapper {
        overflow-x: auto;
        border-radius: 12px;
        border: 1px solid rgba(255,255,255,0.07);
        margin-top: 8px;
    }
    .data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.82rem;
        min-width: 800px;
    }
    .data-table thead th {
        background: rgba(15,23,42,0.9);
        color: #94a3b8;
        padding: 11px 12px;
        text-align: left;
        font-weight: 600;
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        border-bottom: 1px solid rgba(255,255,255,0.08);
        white-space: nowrap;
        position: sticky;
        top: 0;
        z-index: 5;
    }
    .data-table thead th.col-readonly { color: #475569; }
    .data-table thead th.col-editable { color: #7dd3fc; }

    .data-table tbody tr {
        border-bottom: 1px solid rgba(255,255,255,0.04);
        transition: background 0.2s;
    }
    .data-table tbody tr:hover { background: rgba(56,189,248,0.04); }
    .data-table tbody td {
        padding: 10px 12px;
        vertical-align: top;
        color: #cbd5e1;
    }
    .data-table tbody td.readonly-cell {
        color: #475569;
        font-size: 0.8rem;
    }

    .cell-input {
        width: 100%;
        min-width: 120px;
        padding: 6px 10px;
        background: rgba(15,23,42,0.8);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 6px;
        color: #e2e8f0;
        font-size: 0.82rem;
        font-family: 'Inter', sans-serif;
        transition: all 0.2s;
        outline: none;
        resize: vertical;
    }
    .cell-input:focus {
        border-color: #38bdf8;
        box-shadow: 0 0 0 2px rgba(56,189,248,0.15);
        background: rgba(15,23,42,1);
    }
    .cell-input.modified {
        border-color: #f59e0b;
        background: rgba(245,158,11,0.05);
    }

    /* ---- ROW HEADER ---- */
    .row-badge {
        display: inline-block;
        padding: 2px 8px;
        background: rgba(56,189,248,0.15);
        color: #38bdf8;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 600;
        margin-bottom: 8px;
    }

    /* ---- INFO PANEL ---- */
    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-bottom: 24px;
    }
    @media (max-width: 600px) { .info-grid { grid-template-columns: 1fr; } }
    .info-item {
        background: rgba(15,23,42,0.6);
        border: 1px solid rgba(255,255,255,0.07);
        border-radius: 10px;
        padding: 12px 16px;
    }
    .info-item label {
        font-size: 0.7rem;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        display: block;
        margin-bottom: 3px;
    }
    .info-item span {
        font-size: 0.88rem;
        color: #e2e8f0;
        font-weight: 500;
    }

    /* ---- TABS (untuk multi-row) ---- */
    .row-tabs {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 20px;
    }
    .row-tab {
        padding: 7px 16px;
        border-radius: 8px;
        font-size: 0.82rem;
        font-weight: 600;
        cursor: pointer;
        border: 1px solid rgba(255,255,255,0.1);
        background: rgba(15,23,42,0.7);
        color: #64748b;
        transition: all 0.25s;
    }
    .row-tab:hover { color: #e2e8f0; border-color: rgba(255,255,255,0.2); }
    .row-tab.active {
        background: linear-gradient(135deg, #38bdf8, #818cf8);
        border-color: transparent;
        color: white;
        box-shadow: 0 4px 12px rgba(56,189,248,0.3);
    }

    /* ---- ANIMATIONS ---- */
    .fade-in { animation: fadeIn 0.4s ease; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }

    #sppg-app .hidden, #loadingOverlay.hidden { display: none !important; }

    /* ---- RESPONSIVE ---- */
    @media (max-width: 640px) {
        .card { padding: 20px 16px; border-radius: 14px; }
        .page-header h1 { font-size: 1.5rem; }
    }
</style>

{{-- LOADING OVERLAY --}}
<div id="loadingOverlay" class="loading-overlay hidden">
    <div class="spinner spinner-lg"></div>
    <p id="loadingText">Memuat data...</p>
</div>

<div id="sppg-app">

    {{-- HEADER --}}
    <div class="page-header fade-in">
        <h1><i class="fa-solid fa-file-pen" style="margin-right:10px;"></i>Update K0 SPPG</h1>
        <p>Perbarui data SPPG Anda langsung dari spreadsheet Google</p>
    </div>

    {{-- STEPPER --}}
    <div class="stepper" id="stepper">
        <div class="step-item active" id="step-item-1">
            <div class="step-circle">1</div>
            <div class="step-label">Pilih Spreadsheet</div>
        </div>
        <div class="step-item" id="step-item-2">
            <div class="step-circle">2</div>
            <div class="step-label">Validasi</div>
        </div>
        <div class="step-item" id="step-item-3">
            <div class="step-circle">3</div>
            <div class="step-label">Edit & Simpan</div>
        </div>
    </div>

    {{-- ====================== STEP 1: PILIH SPREADSHEET ====================== --}}
    <div id="step-1" class="card fade-in">
        <div class="card-title"><i class="fa-solid fa-table-list" style="color:#38bdf8;margin-right:8px;"></i>Pilih Kabupaten/Kota K0 SPPG</div>
        <div class="card-subtitle">Pilih nomor SPPG yang ingin Anda perbarui datanya</div>

        <div id="step1-loading" style="display:flex;align-items:center;gap:12px;color:#64748b;font-size:0.9rem;margin-bottom:20px;">
            <div class="spinner"></div>
            Memuat daftar K0...
        </div>

        <div id="step1-form" class="hidden">
            <div style="margin-bottom:24px;">
                <label class="form-label">K0 SPPG</label>
                <select id="sheetSelect" class="form-control" style="cursor:pointer;">
                    <option value="">— Pilih Kabupaten/Kota —</option>
                </select>
            </div>

            <div id="step1-error" class="alert alert-error hidden">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span id="step1-error-text"></span>
            </div>

            <div style="display:flex;justify-content:flex-end;">
                <button id="step1-next" class="btn btn-primary" onclick="goToStep2()" disabled>
                    Lanjutkan <i class="fa-solid fa-arrow-right"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- ====================== STEP 2: VALIDASI ====================== --}}
    <div id="step-2" class="card fade-in hidden">
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:20px;">
            <button class="btn btn-ghost btn-sm" onclick="backToStep1()">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </button>
            <div>
                <div class="card-title" style="margin:0;"><i class="fa-solid fa-shield-halved" style="color:#818cf8;margin-right:8px;"></i>Pilih Nama & Validasi</div>
                <div class="card-subtitle" style="margin:0;">Pilih nama Anda dan masukkan password</div>
            </div>
        </div>

        <div id="step2-loading" style="display:flex;align-items:center;gap:12px;color:#64748b;font-size:0.9rem;margin-bottom:20px;">
            <div class="spinner"></div>
            Memuat daftar nama...
        </div>

        <div id="step2-form" class="hidden">
            <div style="margin-bottom:20px;">
                <label class="form-label">Nama PKB/PPPK Wilayah Binaan MBG</label>
                <div class="search-container">
                    <input type="text" id="namaSearch" class="form-control" placeholder="Ketik untuk mencari nama..."
                        autocomplete="off" oninput="filterNames()" onfocus="showDropdown()" onblur="hideDropdownDelayed()">
                    <i class="fa-solid fa-magnifying-glass search-icon"></i>
                    <div id="namaDropdown" class="search-dropdown hidden"></div>
                </div>
                <input type="hidden" id="namaSelected" value="">
            </div>

            <div style="margin-bottom:24px;">
                <label class="form-label">Password</label>
                <div style="position:relative;">
                    <input type="password" id="passwordInput" class="form-control" placeholder="Masukkan password..."
                        style="padding-right:48px;" onkeydown="if(event.key==='Enter')validateAndLoad()">
                    <button onclick="togglePassword()" style="position:absolute;right:14px;top:50%;transform:translateY(-50%);
                        background:none;border:none;color:#64748b;cursor:pointer;padding:4px;transition:color 0.2s;"
                        onmouseover="this.style.color='#94a3b8'" onmouseout="this.style.color='#64748b'">
                        <i class="fa-solid fa-eye" id="pwEyeIcon"></i>
                    </button>
                </div>
            </div>

            <div id="step2-error" class="alert alert-error hidden">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span id="step2-error-text"></span>
            </div>

            <div id="step2-warning" class="alert alert-info hidden" style="margin-bottom:15px;">
                <div class="spinner" style="width:14px;height:14px;border-width:2px;border-top-color:#1d4ed8;margin-right:8px;vertical-align:middle;"></div>
                <span>Tunggu sebentar untuk mengambil data (proses ini memakan waktu beberapa detik)...</span>
            </div>

            <div style="display:flex;justify-content:flex-end;">
                <button id="step2-btn" class="btn btn-primary" onclick="validateAndLoad()">
                    <i class="fa-solid fa-unlock-keyhole"></i> Validasi & Lihat Data
                </button>
            </div>
        </div>
    </div>

    {{-- ====================== STEP 3: EDIT & SIMPAN ====================== --}}
    <div id="step-3" class="card fade-in hidden">
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:20px;">
            <div style="display:flex;align-items:center;gap:12px;">
                <button class="btn btn-ghost btn-sm" onclick="backToStep2()">
                    <i class="fa-solid fa-arrow-left"></i> Kembali
                </button>
                <div>
                    <div class="card-title" style="margin:0;"><i class="fa-solid fa-pen-to-square" style="color:#f472b6;margin-right:8px;"></i>Edit Data SPPG</div>
                    <div class="card-subtitle" style="margin:0;"><span id="info-nama-header"></span></div>
                </div>
            </div>
            <button id="save-btn" class="btn btn-success" onclick="saveCurrentRow()">
                <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
            </button>
        </div>

        <div id="step3-alert" class="hidden"></div>

        {{-- INFO PANEL --}}
        <div class="info-grid" id="infoGrid"></div>

        {{-- ROW TABS (jika ada beberapa row) --}}
        <div id="rowTabsContainer" class="hidden">
            <div style="font-size:0.8rem;color:#64748b;margin-bottom:8px;">
                <i class="fa-solid fa-layer-group" style="margin-right:4px;"></i>
                Ditemukan beberapa entri untuk nama Anda. Pilih entri yang ingin diedit:
            </div>
            <div class="row-tabs" id="rowTabs"></div>
        </div>

        {{-- EDIT FORM PER ROW --}}
        <div id="editForms"></div>

        <div style="display:flex;justify-content:flex-end;margin-top:20px;">
            <button id="save-btn-bottom" class="btn btn-success" onclick="saveCurrentRow()">
                <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
            </button>
        </div>
    </div>

</div>{{-- end #sppg-app --}}

<script>
// ============================================================
// KONFIGURASI — Ganti dengan URL Apps Script Anda setelah deploy
// ============================================================
const APPS_SCRIPT_URL = 'https://script.google.com/macros/s/AKfycbxo3-JsaJFSgIDVgZ7qroiJjwTTUC7BU_U45KiUOwJuou2OHQ94cQU52eCfB2r0dzi8/exec';


// ============================================================
// STATE
// ============================================================
let state = {
    sheets: [],
    selectedSheetId: '',
    selectedSheetName: '',
    names: [],
    selectedNama: '',
    password: '',
    headers: [],
    rows: [],          // array of row objects dari Apps Script
    currentRowIdx: 0,  // index di array rows yang sedang aktif di tab
    dirtyMap: {},      // rowIndex -> { header: newValue }
    originalData: {},  // rowIndex -> { header: originalValue }
    formStructure: [], // struktur pertanyaan Google Form [{title, type, choices, helpText}]
    formMap: {},       // title -> formItem (untuk lookup cepat)
};

// Kolom readonly (tidak boleh diedit)
const READONLY_HEADERS = ['Timestamp', 'NAMA PKB/PPPK WILAYAH BINAAN MBG', 'PASSWORD'];

// ============================================================
// LOADING
// ============================================================
function showLoading(text = 'Memuat data...') {
    document.getElementById('loadingText').textContent = text;
    document.getElementById('loadingOverlay').classList.remove('hidden');
}
function hideLoading() {
    document.getElementById('loadingOverlay').classList.add('hidden');
}

// ============================================================
// STEP 1: LOAD SPREADSHEETS
// ============================================================
async function loadSheets() {
    try {
        const res  = await fetch(`${APPS_SCRIPT_URL}?action=listSheets`);
        const data = await res.json();

        document.getElementById('step1-loading').style.display = 'none';
        document.getElementById('step1-form').classList.remove('hidden');

        if (!data.success || !data.sheets) {
            showStep1Error('Gagal memuat daftar spreadsheet: ' + (data.error || 'Unknown error'));
            return;
        }

        // Mapping Kode File ke Nama Kabupaten/Kota (Sumber: KODE WILAYAH.json)
        const KAB_MAP = {
            1: "Kab. Aceh Selatan",
            2: "Kab. Aceh Tenggara",
            3: "Kab. Aceh Timur",
            4: "Kab. Aceh Tengah",
            5: "Kab. Aceh Barat",
            6: "Kab. Aceh Besar",
            7: "Kab. Pidie",
            8: "Kab. Aceh Utara",
            9: "Kab. Simeulue",
            10: "Kab. Aceh Singkil",
            11: "Kab. Bireuen",
            12: "Kab. Aceh Barat Daya",
            13: "Kab. Gayo Lues",
            14: "Kab. Aceh Jaya",
            15: "Kab. Nagan Raya",
            16: "Kab. Aceh Tamiang",
            17: "Kab. Bener Meriah",
            18: "Kab. Pidie Jaya",
            71: "Kota Banda Aceh",
            72: "Kota Sabang",
            73: "Kota Lhokseumawe",
            74: "Kota Langsa",
            75: "Kota Subulussalam"
        };

        state.sheets = data.sheets;
        const select = document.getElementById('sheetSelect');
        data.sheets.forEach(s => {
            const opt = document.createElement('option');
            opt.value = s.id;
            
            // Coba ambil angka dari nama file (misal: "K0 08 SPPG" -> ambil 8)
            const match = s.name.match(/K0\s*0*(\d+)/i);
            if (match) {
                const kodeAngka = parseInt(match[1], 10);
                const namaKabupaten = KAB_MAP[kodeAngka];
                // Tampilkan K0 XX - Nama Kabupaten
                opt.textContent = namaKabupaten ? `K0 ${match[1].padStart(2, '0')} - ${namaKabupaten}` : s.name;
            } else {
                opt.textContent = s.name;
            }
            
            select.appendChild(opt);
        });

        select.addEventListener('change', function() {
            state.selectedSheetId   = this.value;
            state.selectedSheetName = this.options[this.selectedIndex]?.text || '';
            document.getElementById('step1-next').disabled = !this.value;
            document.getElementById('step1-error').classList.add('hidden');
        });

    } catch (err) {
        document.getElementById('step1-loading').style.display = 'none';
        document.getElementById('step1-form').classList.remove('hidden');
        showStep1Error('Koneksi gagal. Pastikan Apps Script sudah di-deploy dengan benar.');
    }
}

function showStep1Error(msg) {
    const el = document.getElementById('step1-error');
    document.getElementById('step1-error-text').textContent = msg;
    el.classList.remove('hidden');
}

// ============================================================
// STEP 1 → STEP 2
// ============================================================
async function goToStep2() {
    if (!state.selectedSheetId) return;

    document.getElementById('step-1').classList.add('hidden');
    document.getElementById('step-2').classList.remove('hidden');
    setStepperState(2);

    // Reset step 2
    document.getElementById('step2-loading').style.display = 'flex';
    document.getElementById('step2-form').classList.add('hidden');
    document.getElementById('step2-error').classList.add('hidden');
    document.getElementById('namaSearch').value  = '';
    document.getElementById('namaSelected').value = '';
    document.getElementById('passwordInput').value = '';
    state.names = [];

    try {
        const res  = await fetch(`${APPS_SCRIPT_URL}?action=getNames&sheetId=${state.selectedSheetId}`);
        const data = await res.json();

        document.getElementById('step2-loading').style.display = 'none';
        document.getElementById('step2-form').classList.remove('hidden');

        if (!data.success) {
            showStep2Error('Gagal memuat daftar nama: ' + (data.error || 'Unknown error'));
            return;
        }

        state.names = data.names || [];
        renderDropdown(state.names);

    } catch (err) {
        document.getElementById('step2-loading').style.display = 'none';
        document.getElementById('step2-form').classList.remove('hidden');
        showStep2Error('Koneksi gagal saat memuat nama. Coba lagi.');
    }
}

function backToStep1() {
    document.getElementById('step-2').classList.add('hidden');
    document.getElementById('step-1').classList.remove('hidden');
    setStepperState(1);
}

// ============================================================
// NAMA SEARCH DROPDOWN
// ============================================================
function renderDropdown(names) {
    const dd = document.getElementById('namaDropdown');
    dd.innerHTML = '';
    if (!names.length) {
        dd.innerHTML = '<div class="search-option" style="color:#475569;cursor:default;">Tidak ada nama ditemukan</div>';
    } else {
        names.forEach(name => {
            const div = document.createElement('div');
            div.className = 'search-option';
            div.textContent = name;
            div.addEventListener('mousedown', () => selectName(name));
            dd.appendChild(div);
        });
    }
}

function filterNames() {
    const q     = document.getElementById('namaSearch').value.toLowerCase();
    const items = document.querySelectorAll('#namaDropdown .search-option');
    items.forEach(item => {
        const match = item.textContent.toLowerCase().includes(q);
        item.style.display = match ? '' : 'none';
    });
    document.getElementById('namaDropdown').classList.remove('hidden');
    // Clear selected if user is typing again
    document.getElementById('namaSelected').value = '';
}

function selectName(name) {
    document.getElementById('namaSearch').value   = name;
    document.getElementById('namaSelected').value = name;
    state.selectedNama = name;
    document.getElementById('namaDropdown').classList.add('hidden');
    document.getElementById('step2-error').classList.add('hidden');
}

function showDropdown() {
    filterNames();
    document.getElementById('namaDropdown').classList.remove('hidden');
}

function hideDropdownDelayed() {
    setTimeout(() => {
        document.getElementById('namaDropdown').classList.add('hidden');
    }, 200);
}

// ============================================================
// PASSWORD TOGGLE
// ============================================================
function togglePassword() {
    const input = document.getElementById('passwordInput');
    const icon  = document.getElementById('pwEyeIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'fa-solid fa-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'fa-solid fa-eye';
    }
}

function showStep2Error(msg) {
    const el = document.getElementById('step2-error');
    document.getElementById('step2-error-text').textContent = msg;
    el.classList.remove('hidden');
}

// ============================================================
// STEP 2: VALIDATE & LOAD DATA
// ============================================================
async function validateAndLoad() {
    const nama     = document.getElementById('namaSelected').value.trim()
                  || document.getElementById('namaSearch').value.trim();
    const password = document.getElementById('passwordInput').value.trim();

    if (!nama) { showStep2Error('Pilih nama Anda dari daftar terlebih dahulu.'); return; }
    if (!password) { showStep2Error('Password tidak boleh kosong.'); return; }

    state.selectedNama = nama;
    state.password     = password;

    document.getElementById('step2-error').classList.add('hidden');
    document.getElementById('step2-warning').classList.remove('hidden');
    
    const btn = document.getElementById('step2-btn');
    btn.disabled = true;
    btn.innerHTML = '<div class="spinner"></div> Memvalidasi...';

    try {
        const res = await fetch(APPS_SCRIPT_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'text/plain' },
            body: JSON.stringify({
                action:   'validate',
                sheetId:  state.selectedSheetId,
                nama:     nama,
                password: password
            })
        });
        const data = await res.json();

        if (!data.valid) {
            document.getElementById('step2-warning').classList.add('hidden');
            showStep2Error(data.error || 'Validasi gagal. Cek nama dan password Anda.');
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-unlock-keyhole"></i> Validasi & Lihat Data';
            return;
        }

        // Sukses
        document.getElementById('step2-warning').classList.add('hidden');
        state.headers       = data.headers || [];
        let rawRows         = data.rows    || [];
        
        // --- DEDUPLIKASI BERDASARKAN NAMA SPPG ---
        // Jika ada banyak data dengan NAMA SPPG yang sama, simpan yang paling baru (paling bawah/terakhir).
        const uniqueRowsMap = new Map();
        rawRows.forEach((row, idx) => {
            const namaSPPG = row['NAMA SPPG'] || row['NAMA MBG'] || `Entri ${idx + 1}`;
            // Karena forEach berjalan dari awal sampai akhir, 
            // entri dengan namaSPPG yang sama akan otomatis tertimpa dengan yang paling baru.
            uniqueRowsMap.set(namaSPPG, row);
        });
        state.rows = Array.from(uniqueRowsMap.values());
        // -----------------------------------------
        
        state.formStructure = data.formStructure || [];
        state.dirtyMap      = {};
        state.originalData  = {};

        // Buat formMap: title -> item (untuk lookup cepat saat render)
        state.formMap = {};
        state.formStructure.forEach(item => {
            if (item.title) state.formMap[item.title] = item;
        });

        // DEBUG — buka DevTools (F12) > Console untuk lihat status formStructure
        console.log('[K0 SPPG] formStructure items:', state.formStructure.length,
            state.formStructure.length ? '✅ Form terbaca!' : '❌ Kosong — Apps Script perlu di-redeploy');
        console.log('[K0 SPPG] formDebug:', data._formDebug || '(tidak ada info debug)');
        if (state.formStructure.length) {
            console.table(state.formStructure.map(i => ({ title: i.title, type: i.type, choices: i.choices?.length || 0 })));
        }

        state.rows.forEach(row => {
            const ri = row.__rowIndex;
            state.originalData[ri] = { ...row };
            state.dirtyMap[ri]     = {};
        });
        state.currentRowIdx = 0;

        goToStep3();

    } catch (err) {
        showStep2Error('Koneksi gagal. Coba lagi beberapa saat.');
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-unlock-keyhole"></i> Validasi & Lihat Data';
    }
}

function backToStep2() {
    document.getElementById('step-3').classList.add('hidden');
    document.getElementById('step-2').classList.remove('hidden');
    setStepperState(2);
}

// ============================================================
// STEP 3: EDIT & SIMPAN
// ============================================================
function goToStep3() {
    document.getElementById('step-2').classList.add('hidden');
    document.getElementById('step-3').classList.remove('hidden');
    setStepperState(3);

    document.getElementById('step3-alert').classList.add('hidden');
    document.getElementById('info-nama-header').textContent = `📋 ${state.selectedNama} — ${state.selectedSheetName}`;

    // Info grid
    const infoGrid = document.getElementById('infoGrid');
    infoGrid.innerHTML = `
        <div class="info-item">
            <label>Spreadsheet</label>
            <span>${state.selectedSheetName}</span>
        </div>
        <div class="info-item">
            <label>Nama PKB/PPPK</label>
            <span>${state.selectedNama}</span>
        </div>
        <div class="info-item">
            <label>Jumlah Entri Ditemukan</label>
            <span>${state.rows.length} entri</span>
        </div>
        <div class="info-item">
            <label>Total Kolom</label>
            <span>${state.headers.length} kolom (${READONLY_HEADERS.length} readonly)</span>
        </div>
    `;

    // Row tabs
    if (state.rows.length > 1) {
        document.getElementById('rowTabsContainer').classList.remove('hidden');
        const tabsEl = document.getElementById('rowTabs');
        tabsEl.innerHTML = '';
        state.rows.forEach((row, idx) => {
            const namaSPPG = row['NAMA SPPG'] || row['NAMA MBG'] || `Entri ${idx + 1}`;
            const tab = document.createElement('div');
            tab.className = 'row-tab' + (idx === 0 ? ' active' : '');
            tab.id = `tab-${idx}`;
            tab.textContent = namaSPPG;
            tab.onclick = () => switchTab(idx);
            tabsEl.appendChild(tab);
        });
    } else {
        document.getElementById('rowTabsContainer').classList.add('hidden');
    }

    renderEditForms();
}

function switchTab(idx) {
    state.currentRowIdx = idx;
    document.querySelectorAll('.row-tab').forEach((t, i) => {
        t.classList.toggle('active', i === idx);
    });
    renderEditForms();
}

// ============================================================
// HELPER: Buat input element sesuai tipe pertanyaan Google Form
// ============================================================
function createInputForHeader(header, currentVal, originalVal, rowIndex) {
    const dirty    = state.dirtyMap[rowIndex] || {};
    const isModified = dirty.hasOwnProperty(header);
    const formItem = state.formMap[header]; // bisa undefined jika tidak ada di form

    // --- FILE UPLOAD (FOTO) ---
    if (header.toUpperCase().includes('FOTO')) {
        const wrap = document.createElement('div');
        wrap.dataset.rowIndex = rowIndex; // Untuk trigger css selector jika diperlukan
        wrap.style.display = 'flex';
        wrap.style.flexDirection = 'column';
        wrap.style.gap = '8px';

        // Tampilkan link gambar lama jika ada
        if (originalVal && originalVal.startsWith('http')) {
            const a = document.createElement('a');
            a.href = originalVal;
            a.target = '_blank';
            a.innerHTML = '<i class="fa-solid fa-arrow-up-right-from-square"></i> Lihat Foto Tersimpan';
            a.style.fontSize = '0.85rem';
            a.style.color = '#3b82f6';
            a.style.textDecoration = 'none';
            wrap.appendChild(a);
        }

        const input = document.createElement('input');
        input.type = 'file';
        input.accept = 'image/*';
        input.className = 'form-control cell-input' + (isModified ? ' modified' : '');
        input.dataset.header = header;
        input.style.padding = '6px';
        input.style.fontSize = '0.85rem';

        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const ri = parseInt(rowIndex);
            
            if (!file) {
                if (state.dirtyMap[ri]) {
                    delete state.dirtyMap[ri][header];
                }
                this.classList.remove('modified');
                return;
            }
            
            if (file.size > 5 * 1024 * 1024) {
                alert('Ukuran foto terlalu besar. Maksimal 5MB.');
                this.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = (evt) => {
                const base64Data = evt.target.result;
                if (!state.dirtyMap[ri]) state.dirtyMap[ri] = {};
                state.dirtyMap[ri][header] = base64Data;
                this.classList.add('modified');

                let successLabel = this.parentNode.querySelector('.file-success-msg');
                if (!successLabel) {
                    successLabel = document.createElement('div');
                    successLabel.className = 'file-success-msg text-green-600 text-xs mt-1 font-bold';
                    this.parentNode.appendChild(successLabel);
                }
                successLabel.textContent = '✓ Foto "' + file.name + '" siap diupload. Jangan lupa klik Simpan Perubahan.';
            };
            reader.readAsDataURL(file);
        });

        wrap.appendChild(input);
        return wrap;
    }

    // --- MULTIPLE_CHOICE atau LIST → <select> ---
    if (formItem && (formItem.type === 'MULTIPLE_CHOICE' || formItem.type === 'LIST') && formItem.choices.length) {
        const select = document.createElement('select');
        select.className = 'cell-input' + (isModified ? ' modified' : '');
        select.dataset.header   = header;
        select.dataset.rowIndex = rowIndex;
        select.dataset.inputType = 'select';

        // Opsi kosong
        const emptyOpt = document.createElement('option');
        emptyOpt.value = ''; emptyOpt.textContent = '— Pilih —';
        select.appendChild(emptyOpt);

        formItem.choices.forEach(choice => {
            const opt = document.createElement('option');
            opt.value = choice;
            opt.textContent = choice;
            if (currentVal.trim() === choice.trim()) opt.selected = true;
            select.appendChild(opt);
        });

        // Jika nilai saat ini tidak ada di choices (nilai lama/bebas), tambahkan
        if (currentVal && !formItem.choices.includes(currentVal.trim())) {
            const customOpt = document.createElement('option');
            customOpt.value = currentVal;
            customOpt.textContent = currentVal + ' (nilai saat ini)';
            customOpt.selected = true;
            select.insertBefore(customOpt, select.children[1]);
        }

        select.addEventListener('change', function() {
            trackChange(this, header, rowIndex);
        });
        return select;
    }

    // --- CHECKBOX → grup checkbox (nilai disimpan koma-koma) ---
    if (formItem && formItem.type === 'CHECKBOX' && formItem.choices.length) {
        const selectedValues = currentVal.split(',').map(v => v.trim()).filter(Boolean);

        const wrap = document.createElement('div');
        wrap.dataset.header      = header;
        wrap.dataset.rowIndex    = rowIndex;
        wrap.dataset.inputType   = 'checkbox-group';
        wrap.style.cssText = 'display:flex;flex-direction:column;gap:6px;max-height:240px;overflow-y:auto;padding-right:4px;';
        if (isModified) wrap.dataset.modified = '1';

        formItem.choices.forEach(choice => {
            const label = document.createElement('label');
            label.style.cssText = 'display:flex;align-items:flex-start;gap:10px;cursor:pointer;padding:6px 8px;border-radius:6px;transition:background 0.15s;';
            label.onmouseover = () => label.style.background = 'rgba(56,189,248,0.08)';
            label.onmouseout  = () => label.style.background = '';

            const cb = document.createElement('input');
            cb.type    = 'checkbox';
            cb.value   = choice;
            cb.checked = selectedValues.some(v => v.toLowerCase() === choice.toLowerCase());
            cb.style.cssText = 'width:16px;height:16px;min-width:16px;accent-color:#38bdf8;cursor:pointer;margin-top:2px;';

            cb.addEventListener('change', function() {
                // Kumpulkan semua checked values dari grup ini
                const allCbs = wrap.querySelectorAll('input[type=checkbox]');
                const checked = Array.from(allCbs).filter(c => c.checked).map(c => c.value);
                const newVal  = checked.join(', ');
                const ri = parseInt(wrap.dataset.rowIndex);
                const h  = wrap.dataset.header;
                if (!state.dirtyMap[ri]) state.dirtyMap[ri] = {};
                if (newVal !== (state.originalData[ri][h] || '')) {
                    state.dirtyMap[ri][h] = newVal;
                    wrap.dataset.modified = '1';
                    wrap.style.outline = '2px solid #f59e0b';
                } else {
                    delete state.dirtyMap[ri][h];
                    delete wrap.dataset.modified;
                    wrap.style.outline = '';
                }
            });

            const span = document.createElement('span');
            span.textContent = choice;
            span.style.cssText = 'font-size:0.85rem;color:#cbd5e1;line-height:1.4;';

            label.appendChild(cb);
            label.appendChild(span);
            wrap.appendChild(label);
        });

        return wrap;
    }

    // --- PARAGRAPH_TEXT atau kolom panjang → <textarea> ---
    const isLong = (formItem && formItem.type === 'PARAGRAPH_TEXT')
                 || currentVal.length > 80
                 || header.toLowerCase().includes('keterangan');
    if (isLong) {
        const ta = document.createElement('textarea');
        ta.className = 'cell-input' + (isModified ? ' modified' : '');
        ta.dataset.header    = header;
        ta.dataset.rowIndex  = rowIndex;
        ta.dataset.inputType = 'textarea';
        ta.value = currentVal;
        ta.rows  = Math.max(2, Math.ceil(currentVal.length / 80));
        ta.style.minHeight = '60px';
        ta.addEventListener('input', function() { trackChange(this, header, rowIndex); });
        return ta;
    }

    // --- Default: <input type="text"> ---
    const input = document.createElement('input');
    input.type = 'text';
    input.className = 'cell-input' + (isModified ? ' modified' : '');
    input.dataset.header    = header;
    input.dataset.rowIndex  = rowIndex;
    input.dataset.inputType = 'text';
    input.value = currentVal;
    input.addEventListener('input', function() { trackChange(this, header, rowIndex); });
    return input;
}

// Helper: track perubahan di state.dirtyMap
function trackChange(el, header, rowIndex) {
    const ri  = parseInt(rowIndex);
    const val = el.value;
    if (!state.dirtyMap[ri]) state.dirtyMap[ri] = {};
    if (val !== (state.originalData[ri][header] || '')) {
        state.dirtyMap[ri][header] = val;
        el.classList.add('modified');
    } else {
        delete state.dirtyMap[ri][header];
        el.classList.remove('modified');
    }

    const cleanHeader = header.trim().toUpperCase();
    
    // Trigger filter jika yang diganti berkaitan dengan Kecamatan
    if (cleanHeader.includes('KECAMATAN')) {
        applyKecamatanFilter();
    }
    
    // Trigger filter jika yang diganti berkaitan dengan Distribusi MBG
    if (cleanHeader.includes('MENDISTRIBUSIKAN MBG 3B')) {
        applySppgDistribusiFilter();
    }
}

// ============================================================
// FILTER KECAMATAN -> DESA (Cascading Logic)
// ============================================================
function applyKecamatanFilter() {
    const trs = document.querySelectorAll('#editForms tr[data-header]');
    
    // Cari header yg mengandung kata KECAMATAN tapi bukan DESA
    const kecHeader = state.headers.find(h => h && h.toUpperCase().includes('KECAMATAN') && !h.toUpperCase().includes('DESA'));
    if (!kecHeader) return;
    
    trs.forEach(tr => {
        const header = tr.dataset.header.trim().toUpperCase();
        
        // Cari baris yang pertanyaannya tentang Desa di suatu Kecamatan
        if (header.includes('DESA') && header.includes('KECAMATAN')) {
            const input = tr.querySelector('[data-row-index]');
            if (!input) return;
            
            const rowIndex = parseInt(input.dataset.rowIndex);
            const dirty    = state.dirtyMap[rowIndex] || {};
            const original = state.originalData[rowIndex] || {};
            
            const currentKec = (dirty.hasOwnProperty(kecHeader) ? dirty[kecHeader] : original[kecHeader]) || '';
            const currentKecUpper = currentKec.trim().toUpperCase();
            
            // Ekstrak target kecamatan dari header
            // Cth: "DESA WILAYAH PENDISTRIBUSIAN MBG DI KECAMATAN KLUET UTARA" -> "KLUET UTARA"
            let expectedKec = '';
            const match = header.match(/KECAMATAN\s+(.+)$/);
            if (match) {
                expectedKec = match[1].trim();
            }
            
            if (currentKecUpper && expectedKec === currentKecUpper) {
                tr.style.display = '';
            } else {
                tr.style.display = 'none';
            }
        }
    });
}

// ============================================================
// FILTER SPPG DISTRIBUSI (Sudah / Belum)
// ============================================================
function applySppgDistribusiFilter() {
    const trs = document.querySelectorAll('#editForms tr[data-header]');
    
    // Cari nama header pemicu
    const triggerHeader = state.headers.find(h => h && h.toUpperCase().includes('MENDISTRIBUSIKAN MBG 3B') && !h.toUpperCase().includes('ALASAN'));
    if (!triggerHeader) return;
    
    const qSudahKeywords = [
        'SERTIFIKAT', 'FREKUENSI', 'UPF', 'INSENTIF', 'ANGGOTA TPK', 'KADER SELAIN TPK', 'KETERANGAN', 'FOTO'
    ];
    
    const qBelumKeywords = [
        'ALASAN'
    ];
    
    trs.forEach(tr => {
        const header = tr.dataset.header.trim().toUpperCase();
        
        const isGroupSudah = qSudahKeywords.some(k => header.includes(k));
        const isGroupBelum = qBelumKeywords.some(k => header.includes(k));
        
        if (isGroupSudah || isGroupBelum) {
            const input = tr.querySelector('[data-row-index]');
            if (!input) return;
            
            const rowIndex = parseInt(input.dataset.rowIndex);
            const dirty    = state.dirtyMap[rowIndex] || {};
            const original = state.originalData[rowIndex] || {};
            
            let val = (dirty.hasOwnProperty(triggerHeader) ? dirty[triggerHeader] : original[triggerHeader]) || '';
            val = val.trim().toUpperCase();
            
            if (val.includes('SUDAH')) {
                tr.style.display = isGroupSudah ? '' : 'none';
            } else if (val.includes('BELUM')) {
                tr.style.display = isGroupBelum ? '' : 'none';
            } else {
                tr.style.display = 'none';
            }
        }
    });
}

// ============================================================
// RENDER EDIT FORMS
// ============================================================
function renderEditForms() {
    const container = document.getElementById('editForms');
    container.innerHTML = '';

    const hasFormStructure = state.formStructure.length > 0;

    state.rows.forEach((row, rowArrIdx) => {
        const rowIndex = row.__rowIndex;
        const isActive = rowArrIdx === state.currentRowIdx;

        const formDiv = document.createElement('div');
        formDiv.id    = `form-${rowArrIdx}`;
        formDiv.style.display = isActive ? '' : 'none';

        if (state.rows.length > 1) {
            const badge = document.createElement('div');
            badge.className = 'row-badge';
            badge.textContent = `Entri ${rowArrIdx + 1} dari ${state.rows.length}`;
            formDiv.appendChild(badge);
        }

        // Info badge: apakah struktur form berhasil dibaca
        if (hasFormStructure) {
            const infoBadge = document.createElement('div');
            infoBadge.style.cssText = 'display:flex;align-items:center;gap:8px;font-size:0.78rem;color:#6ee7b7;background:rgba(16,185,129,0.1);border:1px solid rgba(16,185,129,0.25);border-radius:8px;padding:8px 14px;margin-bottom:16px;';
            infoBadge.innerHTML = '<i class="fa-solid fa-circle-check"></i> Input disesuaikan dengan pertanyaan Google Form aslinya';
            formDiv.appendChild(infoBadge);
        }

        // Info kolom readonly
        const roDiv = document.createElement('div');
        roDiv.style.cssText = 'display:flex;gap:12px;flex-wrap:wrap;margin-bottom:20px;';
        READONLY_HEADERS.forEach(h => {
            if (!state.headers.includes(h)) return;
            const item = document.createElement('div');
            item.className = 'info-item';
            item.style.flex = '1;min-width:200px;';
            item.innerHTML = `<label>${h}</label><span>${row[h] || '—'}</span>`;
            roDiv.appendChild(item);
        });
        formDiv.appendChild(roDiv);

        // Kolom editable
        const editableHeaders = state.headers.filter(h => h && !READONLY_HEADERS.includes(h));

        const tableWrapper = document.createElement('div');
        tableWrapper.className = 'table-wrapper';

        const table = document.createElement('table');
        table.className = 'data-table';

        const thead = document.createElement('thead');
        thead.innerHTML = `<tr>
            <th style="width:36px;">#</th>
            <th class="col-editable" style="width:220px;">Pertanyaan</th>
            <th class="col-editable">Isi / Jawaban <span style="color:#f59e0b;font-size:0.7rem;font-weight:400;margin-left:6px;">(dapat diedit — kolom kuning = ada perubahan)</span></th>
        </tr>`;
        table.appendChild(thead);

        const tbody = document.createElement('tbody');
        editableHeaders.forEach((header, hIdx) => {
            const originalVal = row[header] || '';
            const dirty       = state.dirtyMap[rowIndex] || {};
            const currentVal  = dirty.hasOwnProperty(header) ? dirty[header] : originalVal;
            const formItem    = state.formMap[header];

            const tr = document.createElement('tr');
            tr.dataset.header = header; // simpan header di tr untuk filter

            // Kolom nomor
            const tdNum = document.createElement('td');
            tdNum.style.cssText = 'color:#475569;font-size:0.75rem;text-align:center;vertical-align:top;padding-top:14px;';
            tdNum.textContent = hIdx + 1;
            tr.appendChild(tdNum);

            // Kolom label pertanyaan
            const tdLabel = document.createElement('td');
            tdLabel.style.cssText = 'color:#7dd3fc;font-weight:500;min-width:200px;max-width:240px;word-break:break-word;vertical-align:top;';

            const labelSpan = document.createElement('span');
            labelSpan.textContent = header;
            tdLabel.appendChild(labelSpan);

            // Badge tipe input (hanya jika ada formItem)
            if (formItem) {
                const typeMap = {
                    'MULTIPLE_CHOICE': { label: 'Pilihan', color: '#818cf8' },
                    'LIST':           { label: 'Dropdown', color: '#818cf8' },
                    'CHECKBOX':       { label: 'Centang', color: '#f472b6' },
                    'TEXT':           { label: 'Teks', color: '#94a3b8' },
                    'PARAGRAPH_TEXT': { label: 'Paragraf', color: '#94a3b8' },
                    'DATE':           { label: 'Tanggal', color: '#fb923c' },
                    'TIME':           { label: 'Waktu', color: '#fb923c' },
                };
                const t = typeMap[formItem.type];
                if (t) {
                    const typeBadge = document.createElement('div');
                    typeBadge.style.cssText = `display:inline-block;margin-top:5px;font-size:0.65rem;padding:2px 7px;border-radius:20px;background:rgba(255,255,255,0.06);color:${t.color};border:1px solid currentColor;font-weight:600;`;
                    typeBadge.textContent = t.label;
                    tdLabel.appendChild(document.createElement('br'));
                    tdLabel.appendChild(typeBadge);
                }
                // Tooltip helpText jika ada
                if (formItem.helpText) {
                    const help = document.createElement('div');
                    help.style.cssText = 'font-size:0.72rem;color:#64748b;margin-top:4px;font-style:italic;';
                    help.textContent = formItem.helpText;
                    tdLabel.appendChild(help);
                }
            }
            tr.appendChild(tdLabel);

            // Kolom input
            const tdInput = document.createElement('td');
            tdInput.style.verticalAlign = 'top';
            const inputEl = createInputForHeader(header, currentVal, originalVal, rowIndex);
            tdInput.appendChild(inputEl);
            tr.appendChild(tdInput);

            tbody.appendChild(tr);
        });

        table.appendChild(tbody);
        tableWrapper.appendChild(table);
        formDiv.appendChild(tableWrapper);
        container.appendChild(formDiv);
    });

    // Jalankan filter
    applyKecamatanFilter();
    applySppgDistribusiFilter();
}

// ============================================================
// SAVE
// ============================================================
async function saveCurrentRow() {
    // Ambil baris yang sedang aktif di tab
    const row      = state.rows[state.currentRowIdx];
    const rowIndex = row.__rowIndex;
    const dirty    = state.dirtyMap[rowIndex] || {};

    if (Object.keys(dirty).length === 0) {
        showStep3Alert('info', 'Tidak ada perubahan yang perlu disimpan.');
        return;
    }

    // === VALIDASI FORM ===
    const activeForm = document.getElementById(`form-${state.currentRowIdx}`);
    if (activeForm) {
        const triggerHeader = state.headers.find(h => h && h.toUpperCase().includes('MENDISTRIBUSIKAN MBG 3B') && !h.toUpperCase().includes('ALASAN'));
        let isSudah = false;
        let isBelum = false;
        
        if (triggerHeader) {
            let val = (dirty.hasOwnProperty(triggerHeader) ? dirty[triggerHeader] : row[triggerHeader]) || '';
            val = val.trim().toUpperCase();
            if (val.includes('SUDAH')) isSudah = true;
            else if (val.includes('BELUM')) isBelum = true;
        }

        const qSudahKeywords = ['SERTIFIKAT', 'FREKUENSI', 'UPF', 'INSENTIF', 'ANGGOTA TPK', 'KADER SELAIN TPK', 'KETERANGAN', 'FOTO'];
        const qBelumKeywords = ['ALASAN'];
        let validationError = null;

        const trs = activeForm.querySelectorAll('tr[data-header]');
        for (const tr of trs) {
            const header = tr.dataset.header;
            const isHidden = window.getComputedStyle(tr).display === 'none' || tr.style.display === 'none';
            
            if (!isHidden) {
                const headerUpper = header.toUpperCase();
                const isGroupSudah = qSudahKeywords.some(k => headerUpper.includes(k));
                const isGroupBelum = qBelumKeywords.some(k => headerUpper.includes(k));
                
                let currentVal = (dirty.hasOwnProperty(header) ? dirty[header] : row[header]) || '';
                
                if (isSudah && isGroupSudah && currentVal.toString().trim() === '') {
                    validationError = `Isian "${header}" wajib diisi karena status Distribusi adalah SUDAH.`;
                    break;
                } else if (isBelum && isGroupBelum && currentVal.toString().trim() === '') {
                    validationError = `Isian "${header}" wajib diisi karena status Distribusi adalah BELUM.`;
                    break;
                }
            }
        }

        if (validationError) {
            showStep3Alert('error', validationError);
            return;
        }
    }

    const saveBtns = document.querySelectorAll('#save-btn, #save-btn-bottom');
    saveBtns.forEach(b => { b.disabled = true; b.innerHTML = '<div class="spinner"></div> Menyimpan...'; });

    // Gabung: data asli + perubahan (untuk dikirim ke Apps Script)
    const dataToSend = { ...row, ...dirty };
    delete dataToSend.__rowIndex;

    // === AUTO-CLEAR HIDDEN FIELDS ===
    // Pastikan field yang disembunyikan oleh cascading nilainya dihapus
    if (activeForm) {
        const trs = activeForm.querySelectorAll('tr[data-header]');
        trs.forEach(tr => {
            // Cek apakah elemen ini tersembunyi (display none)
            const isHidden = window.getComputedStyle(tr).display === 'none' || tr.style.display === 'none';
            if (isHidden) {
                const header = tr.dataset.header;
                if (header.toUpperCase().includes('FOTO')) return;
                dataToSend[header] = ''; // timpa dengan kosong secara eksplisit
            }
        });
    }

    // === CEK FOTO DI DIRTY ===
    const fotoInDirty = Object.keys(dirty).filter(k => k.toUpperCase().includes('FOTO'));
    const fotoInSend  = Object.keys(dataToSend).filter(k => k.toUpperCase().includes('FOTO'));
    let hasFoto = false;

    for (let key in dataToSend) {
        const val = dataToSend[key];
        if (typeof val === 'string' && val.startsWith('data:')) {
            hasFoto = true;
            const sizeInBytes = val.length * 0.75;
            if (sizeInBytes > 5 * 1024 * 1024) {
                showStep3Alert('error', 'Ukuran foto terlalu besar. Maksimal 5MB.');
                saveBtns.forEach(b => { b.disabled = false; b.innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan'; });
                return;
            }
        }
    }


    try {
        const payload = {
            action:   'updateRow',
            sheetId:  state.selectedSheetId,
            rowIndex: rowIndex,
            data:     dataToSend,
            nama:     state.selectedNama,
            password: state.password
        };
        console.log('[SAVE] Payload size:', JSON.stringify(payload).length, 'bytes');
        console.log('[SAVE] hasFoto in payload:', Object.values(payload.data).some(v => typeof v === 'string' && v.startsWith('data:')));

        const res = await fetch(APPS_SCRIPT_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'text/plain' },
            body: JSON.stringify(payload)
        });
        const result = await res.json();
        console.log('[SAVE] Apps Script response:', result);

        if (result.success) {
            // Sinkronkan data asli dan baris dengan data final yang dikirim (termasuk auto-clear)
            Object.assign(state.originalData[rowIndex], dataToSend);
            Object.assign(state.rows[state.currentRowIdx], dataToSend);
            state.dirtyMap[rowIndex] = {};
            
            // Render ulang form agar input yang di-clear otomatis hilang juga dari layar
            renderEditForms();
            
            showStep3Alert('success', 'Data berhasil disimpan ke Google Spreadsheet!');
        } else {
            console.error('[SAVE] Error:', result.error);
            showStep3Alert('error', 'Gagal menyimpan: ' + (result.error || 'Unknown error'));
        }
    } catch (err) {
        console.error('[SAVE] Fetch error:', err);
        showStep3Alert('error', 'Koneksi gagal. Pastikan internet Anda aktif dan coba lagi.');
    } finally {
        saveBtns.forEach(b => { b.disabled = false; b.innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan'; });
    }
}

function showStep3Alert(type, message) {
    let toastContainer = document.getElementById('toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.style.cssText = 'position:fixed;bottom:30px;right:30px;z-index:9999;display:flex;flex-direction:column;gap:12px;';
        document.body.appendChild(toastContainer);
    }

    const toast = document.createElement('div');
    const colors = {
        success: { bg: '#10b981', icon: 'fa-check-circle' },
        error:   { bg: '#ef4444', icon: 'fa-triangle-exclamation' },
        info:    { bg: '#3b82f6', icon: 'fa-circle-info' }
    };
    const config = colors[type] || colors.info;

    toast.style.cssText = `
        background: ${config.bg};
        color: white;
        padding: 16px 24px;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.25);
        display: flex;
        align-items: center;
        gap: 14px;
        font-weight: 500;
        font-size: 0.95rem;
        transform: translateX(120%);
        transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    `;
    
    // Hapus emoji bawaan dari text agar tidak double dengan icon
    const cleanMsg = message.replace(/[✅❌]/g, '').trim();
    toast.innerHTML = `<i class="fa-solid ${config.icon} text-2xl"></i> <span>${cleanMsg}</span>`;
    
    toastContainer.appendChild(toast);
    
    // Animasi masuk
    setTimeout(() => { toast.style.transform = 'translateX(0)'; }, 50);
    
    // Animasi keluar otomatis (error agak lebih lama)
    setTimeout(() => {
        toast.style.transform = 'translateX(120%)';
        setTimeout(() => toast.remove(), 400);
    }, type === 'error' ? 6000 : 4000);
}

// ============================================================
// STEPPER HELPER
// ============================================================
function setStepperState(activeStep) {
    for (let i = 1; i <= 3; i++) {
        const item = document.getElementById(`step-item-${i}`);
        item.classList.remove('active', 'done');
        if (i < activeStep) item.classList.add('done');
        else if (i === activeStep) item.classList.add('active');
    }
}

// ============================================================
// INIT
// ============================================================
loadSheets();
</script>

</x-layout>
