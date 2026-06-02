<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>
    <div id="zoomdesk-container" style="font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;padding:20px;color:#2d3748;min-height:100vh;position:relative;">
        <header style="display:flex;justify-content:center;align-items:center;padding:0px 0;">
            <h1 style="display:flex;align-items:center;justify-content:center;color:#4a6fa5;font-size:2.5rem;margin-bottom:30px;text-shadow:1px 1px 2px rgba(0,0,0,0.1);width:100%">
                <span class="icon"><img src="/zoomdesk/logo.png" alt="Logo Anda" style="width:50px;height:auto;margin-right:10px;"></span>
                ZOOMDESK (Jadwal Zoom Meeting)
            </h1>
        </header>
         <div class="filter-container" style="display:flex;justify-content:space-between;margin-bottom:30px;background:white;padding:40px 20px 20px 20px;border-radius:10px;box-shadow:0 4px 6px rgba(0,0,0,0.1);align-items:flex-end;flex-wrap:wrap;gap:20px;">
            <div class="button-group" style="display:flex;gap:15px;align-items:flex-end;flex-wrap:wrap;">
                <label style="margin-right:0;font-weight:500;display:flex;flex-direction:column;height:44px;justify-content:flex-end;">Dari Tanggal:
                    <input type="date" id="startDate" style="padding:10px 12px;margin:0;border:1px solid #e2e8f0;border-radius:8px;font-size:1rem;height:44px;box-sizing:border-box;transition:all 0.3s ease;" onfocus="this.style.borderColor='#4a6fa5';this.style.boxShadow='0 0 0 3px rgba(74,111,165,0.2)'" onblur="this.style.borderColor='#e2e8f0';this.style.boxShadow='none'">
                </label>
                <label style="margin-right:0;font-weight:500;display:flex;flex-direction:column;height:44px;justify-content:flex-end;">Sampai Tanggal:
                    <input type="date" id="endDate" style="padding:10px 12px;margin:0;border:1px solid #e2e8f0;border-radius:8px;font-size:1rem;height:44px;box-sizing:border-box;transition:all 0.3s ease;" onfocus="this.style.borderColor='#4a6fa5';this.style.boxShadow='0 0 0 3px rgba(74,111,165,0.2)'" onblur="this.style.borderColor='#e2e8f0';this.style.boxShadow='none'">
                </label>
                <label style="margin-right:0;font-weight:500;display:flex;flex-direction:column;height:44px;justify-content:flex-end;">Cari:
                    <input type="text" id="searchInput" placeholder="Cari Tim Kerja atau PIC" style="padding:10px 12px;margin:0;border:1px solid #e2e8f0;border-radius:8px;font-size:1rem;height:44px;box-sizing:border-box;transition:all 0.3s ease;" onfocus="this.style.borderColor='#4a6fa5';this.style.boxShadow='0 0 0 3px rgba(74,111,165,0.2)'" onblur="this.style.borderColor='#e2e8f0';this.style.boxShadow='none'">
                </label>
                <label style="margin-right:0;font-weight:500;display:flex;flex-direction:column;justify-content:flex-end;">
                    <button onclick="resetFilter()" style="padding:0 15px;margin:0;border:none;border-radius:8px;font-size:1rem;background-color:#4a6fa5;color:white;cursor:pointer;font-weight:600;height:44px;display:flex;align-items:center;justify-content:center;transition:all 0.3s ease;" onmouseover="this.style.backgroundColor='#3a5a80';this.style.transform='translateY(-2px)';this.style.boxShadow='0 4px 8px rgba(0,0,0,0.1)'" onmouseout="this.style.backgroundColor='#4a6fa5';this.style.transform='translateY(0)';this.style.boxShadow='none'">Reset</button>
                </label>
            </div>
            <div class="button-group" style="display:flex;gap:10px;align-items:flex-end;">
                <label style="margin-right:0;font-weight:500;display:flex;flex-direction:column;justify-content:flex-end;">
                    <button onclick="resetRequest()" style="padding:0 15px;margin:0;border:none;border-radius:8px;font-size:1rem;background-color:#4a6fa5;color:white;cursor:pointer;font-weight:600;height:44px;display:flex;align-items:center;justify-content:center;transition:all 0.3s ease;" onmouseover="this.style.backgroundColor='#3a5a80';this.style.transform='translateY(-2px)';this.style.boxShadow='0 4px 8px rgba(0,0,0,0.1)'" onmouseout="this.style.backgroundColor='#4a6fa5';this.style.transform='translateY(0)';this.style.boxShadow='none'">Request Link Zoom</button>
                </label>
                <label style="margin-right:0;font-weight:500;display:flex;flex-direction:column;justify-content:flex-end;">
                    <button onclick="printToPDF()" style="padding:0 15px;margin:0;border:none;border-radius:8px;font-size:1rem;background-color:#e53e3e;color:white;cursor:pointer;font-weight:600;height:44px;display:flex;align-items:center;justify-content:center;transition:all 0.3s ease;" onmouseover="this.style.backgroundColor='#c53030';this.style.transform='translateY(-2px)';this.style.boxShadow='0 4px 8px rgba(0,0,0,0.1)'" onmouseout="this.style.backgroundColor='#e53e3e';this.style.transform='translateY(0)';this.style.boxShadow='none'">
                        <i class="fas fa-file-pdf" style="margin-right: 8px;"></i> Print ke PDF
                    </button>
                </label>
            </div>
        </div>
        <div id="loading" class="loading-spinner" style="position:fixed;top:0;left:0;width:100%;height:100%;background-color:rgba(255,255,255,0.8);display:flex;flex-direction:column;justify-content:center;align-items:center;z-index:1000;font-weight:bold;color:#4a6fa5;">
            <div class="spinner" style="border:5px solid #f3f3f3;border-top:5px solid #4a6fa5;border-radius:50%;width:50px;height:50px;animation:spin 1s linear infinite;margin-bottom:15px;"></div>
            <div>Loading data...</div>
        </div>
        <table id="jadwalTable" style="width:100%;border-collapse:separate;border-spacing:0;margin-top:30px;background:#fff;box-shadow:0 10px 15px -3px rgba(0,0,0,0.1),0 4px 6px -2px rgba(0,0,0,0.05);border-radius:10px;overflow:hidden;table-layout:fixed;">
            <thead>
                <tr>
                    <th onclick="sortTable(0)" style="width:11%;padding:15px;text-align:left;background:#4a6fa5;color:white;cursor:pointer;font-weight:600;position:sticky;top:0;transition:all 0.3s ease;position:relative;">Tanggal<span class="sort-icon" style="font-size:0.8em;margin-left:5px;opacity:0.7;">↕</span></th>
                    <th onclick="sortTable(3)" style="width:7%;padding:15px;text-align:left;background:#4a6fa5;color:white;cursor:pointer;font-weight:600;position:sticky;top:0;transition:all 0.3s ease;position:relative;">Jam<span class="sort-icon" style="font-size:0.8em;margin-left:5px;opacity:0.7;">↕</span></th>
                    <th onclick="sortTable(4)" style="width:8%;padding:15px;text-align:left;background:#4a6fa5;color:white;cursor:pointer;font-weight:600;position:sticky;top:0;transition:all 0.3s ease;position:relative;">Durasi Sampai<span class="sort-icon" style="font-size:0.8em;margin-left:5px;opacity:0.7;">↕</span></th>
                    <th onclick="sortTable(1)" style="width:12%;padding:15px;text-align:left;background:#4a6fa5;color:white;cursor:pointer;font-weight:600;position:sticky;top:0;transition:all 0.3s ease;position:relative;">Nama PIC<span class="sort-icon" style="font-size:0.8em;margin-left:5px;opacity:0.7;">↕</span></th>
                    <th onclick="sortTable(2)" style="width:17%;padding:15px;text-align:left;background:#4a6fa5;color:white;cursor:pointer;font-weight:600;position:sticky;top:0;transition:all 0.3s ease;position:relative;">Tim Kerja<span class="sort-icon" style="font-size:0.8em;margin-left:5px;opacity:0.7;">↕</span></th>
                    <th onclick="sortTable(5)" style="width:22%;padding:15px;text-align:left;background:#4a6fa5;color:white;cursor:pointer;font-weight:600;position:sticky;top:0;transition:all 0.3s ease;position:relative;">Topik<span class="sort-icon" style="font-size:0.8em;margin-left:5px;opacity:0.7;">↕</span></th>
                    <th onclick="sortTable(6)" style="width:13%;padding:15px;text-align:left;background:#4a6fa5;color:white;cursor:pointer;font-weight:600;position:sticky;top:0;transition:all 0.3s ease;position:relative;">Peserta<span class="sort-icon" style="font-size:0.8em;margin-left:5px;opacity:0.7;">↕</span></th>
                    <th onclick="sortTable(7)" style="width:10%;padding:15px;text-align:left;background:#4a6fa5;color:white;cursor:pointer;font-weight:600;position:sticky;top:0;transition:all 0.3s ease;position:relative;">Tipe Zoom<span class="sort-icon" style="font-size:0.8em;margin-left:5px;opacity:0.7;">↕</span></th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    .sort-icon { font-size:0.8em;margin-left:5px;opacity:0.7; }
    .resizer { position:absolute;top:0;right:0;width:5px;height:100%;background:rgba(255,255,255,0.3);cursor:col-resize;user-select:none;touch-action:none; }
    .resizer:hover,.resizer.resizing { background:rgba(255,255,255,0.8); }
    #jadwalTable th:hover { background:#3a5a80; }
    #jadwalTable tr:hover { background-color:#f8fafc; }
    #jadwalTable tr:nth-child(even) { background-color:#f7fafc; }
    #jadwalTable tr:last-child td { border-bottom:none; }
    .icon img { width:50px;height:auto;margin-right:10px; }
    #jadwalTable td { padding:15px;text-align:left;border-bottom:1px solid #e2e8f0; }
    #jadwalTable tr { page-break-inside: avoid !important; break-inside: avoid !important; }

    @media print {
        /* Sembunyikan navbar/menu atas dari layout utama */
        .min-h-screen > *:not(main) {
            display: none !important;
        }

        /* Matikan fungsi scroll dan flex pada layout karena merusak halaman PDF (membuat elemen hilang/terpotong) */
        body, html, .min-h-screen, main {
            display: block !important;
            height: auto !important;
            min-height: auto !important;
            overflow: visible !important;
            padding: 0 !important;
            margin: 0 !important;
            background: white !important;
        }

        .filter-container, .resizer {
            display: none !important;
        }

        #zoomdesk-container {
            background: white !important;
            min-height: auto !important;
            padding: 0 !important;
            display: block !important;
        }

        #jadwalTable {
            box-shadow: none !important;
        }

        /* Hapus efek sticky agar header tabel (thead) tidak hilang di halaman pertama */
        #jadwalTable th {
            position: static !important;
            border-bottom: 2px solid #000 !important;
            color: #000 !important;
        }

        #jadwalTable td {
            border-bottom: 1px solid #ccc !important;
            color: #000 !important;
        }

        h1 {
            color: #000 !important;
            text-shadow: none !important;
            display: block !important;
            text-align: center;
        }
        h1 .icon {
            display: inline-block;
            vertical-align: middle;
        }
    }
</style>
<script>
const SHEET_URL = 'https://script.google.com/macros/s/AKfycbx_JqXKxsNanPlK_M-IbQk-883hGKpm483PpMBlixWcEwhbhe5XJfxQAiLmJ4mvzsU8/exec';
let allData = [];
function normalizeDateTime(rawInput) {
    if (!rawInput) return { date: '', time: '' };
    if (/\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}/.test(rawInput)) {
        const d = new Date(rawInput);
        if (isNaN(d)) return { date: '', time: '' };
        const year = d.getFullYear();
        const month = (d.getMonth() + 1).toString().padStart(2, '0');
        const day = d.getDate().toString().padStart(2, '0');
        const hours = d.getHours().toString().padStart(2, '0');
        const minutes = d.getMinutes().toString().padStart(2, '0');
        return { date: `${year}-${month}-${day}`, time: `${hours}:${minutes}` };
    }
    const parts = rawInput.split(' ');
    if (!parts[0]) return { date: '', time: '' };
    const dateParts = parts[0].split('/');
    if (dateParts.length !== 3) return { date: '', time: '' };
    let [month, day, year] = dateParts;
    if (!month || !day || !year) return { date: '', time: '' };
    let time = parts[1]?.substring(0, 5) || '00:00';
    if (time.endsWith(':')) { time = time.slice(0, -1); }
    const tanggal = `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`;
    return { date: tanggal, time: time };
}
function normalizeTimeOnly(timeStr) {
    if (!timeStr) return '';
    if (/\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}/.test(timeStr)) {
        const d = new Date(timeStr);
        if (isNaN(d)) return '';
        const hours = d.getHours().toString().padStart(2, '0');
        const minutes = d.getMinutes().toString().padStart(2, '0');
        return `${hours}:${minutes}`;
    }
    const date = new Date(`1970-01-01 ${timeStr}`);
    if (isNaN(date)) return '';
    const hours = date.getHours().toString().padStart(2, '0');
    const minutes = date.getMinutes().toString().padStart(2, '0');
    return `${hours}:${minutes}`;
}
async function loadData() {
    document.getElementById('loading').style.display = 'flex';
    try {
        const res = await fetch(SHEET_URL);
        const data = await res.json();
        document.getElementById('loading').style.display = 'none';
        allData = data.filter(row => row["Jadwal Zoom"] && row["Nama PIC"]).map(row => {
            const dt = normalizeDateTime(row["Jadwal Zoom"]);
            let tipeZoomKey = Object.keys(row).find(k => k.replace(/\s+/g, '').toLowerCase() === 'tipezoom');
            return {
                tanggal: dt.date,
                jam: dt.time,
                durasi: normalizeTimeOnly(row["Durasi Zoom (Sampai Jam Berapa)"]),
                nama: row["Nama PIC"].trim(),
                tim: row["Tim Kerja"].trim(),
                topik: row["Topik/Judul Kegiatan"] || '',
                peserta: row["Peserta Zoom"] || '',
                tipe: tipeZoomKey ? row[tipeZoomKey] : '',
            };
        });
        allData.sort((a, b) => b.tanggal.localeCompare(a.tanggal));
        renderTable(allData);
    } catch (error) {
        document.getElementById('loading').style.display = 'none';
        console.error('Error loading data:', error);
        alert('Gagal memuat data. Silakan coba lagi.');
    }
}
function renderTable(data) {
    const tbody = document.querySelector('#jadwalTable tbody');
    tbody.innerHTML = '';
    const countByDate = {};
    data.forEach(row => { countByDate[row.tanggal] = (countByDate[row.tanggal] || 0) + 1; });
    data.forEach((row, idx) => {
        const tr = document.createElement('tr');
        if (countByDate[row.tanggal] > 1) { tr.style.backgroundColor = '#ffdddd'; } else { tr.style.backgroundColor = '#fff'; }
        tr.innerHTML = `
          <td>${row.tanggal}</td>
          <td>${row.jam}</td>
          <td>${row.durasi}</td>
          <td>${row.nama}</td>
          <td>${row.tim}</td>
          <td>${row.topik}</td>
          <td>${row.peserta}</td>
          <td>${row.tipe}</td>
        `;
        tbody.appendChild(tr);
    });
}
function filterData() {
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    const searchVal = document.getElementById('searchInput').value.toLowerCase();
    const filtered = allData.filter(row => {
        const matchDateRange = (!startDate || row.tanggal >= startDate) && (!endDate || row.tanggal <= endDate);
        const matchSearch = !searchVal || row.nama.toLowerCase().includes(searchVal) || row.tim.toLowerCase().includes(searchVal);
        return matchDateRange && matchSearch;
    });
    renderTable(filtered);
}
function resetFilter() {
    document.getElementById('startDate').value = '';
    document.getElementById('endDate').value = '';
    document.getElementById('searchInput').value = '';
    renderTable(allData);
}
function resetRequest() {
    const url = "https://docs.google.com/forms/d/e/1FAIpQLSc46r2nIZRhu4GPZFACW-f_QQ-wzDDLF1G58UkWF8QRHkamkw/viewform";
    window.open(url, "_blank");
}
function refreshPage() { location.reload(); }
function sortTable(columnIndex) {
    const tbody = document.querySelector('#jadwalTable tbody');
    const rows = Array.from(tbody.rows);
    const isAscending = tbody.dataset.sortOrder === 'asc';
    rows.sort((a, b) => {
        const aText = a.cells[columnIndex].textContent;
        const bText = b.cells[columnIndex].textContent;
        return isAscending ? aText.localeCompare(bText) : bText.localeCompare(aText);
    });
    tbody.dataset.sortOrder = isAscending ? 'desc' : 'asc';
    tbody.innerHTML = '';
    rows.forEach(row => tbody.appendChild(row));
}
document.getElementById('startDate').addEventListener('input', filterData);
document.getElementById('endDate').addEventListener('input', filterData);
document.getElementById('searchInput').addEventListener('input', filterData);
function setupResizableColumns() {
    const headers = document.querySelectorAll('th');
    headers.forEach(header => {
        const resizer = document.createElement('div');
        resizer.classList.add('resizer');
        header.appendChild(resizer);
        let x = 0;
        let w = 0;
        const mouseDownHandler = function (e) {
            x = e.clientX;
            w = header.offsetWidth;
            resizer.classList.add('resizing');
            document.addEventListener('mousemove', mouseMoveHandler);
            document.addEventListener('mouseup', mouseUpHandler);
        };
        const mouseMoveHandler = function (e) {
            const dx = e.clientX - x;
            header.style.width = `${w + dx}px`;
        };
        const mouseUpHandler = function () {
            resizer.classList.remove('resizing');
            document.removeEventListener('mousemove', mouseMoveHandler);
            document.removeEventListener('mouseup', mouseUpHandler);
        };
        resizer.addEventListener('mousedown', mouseDownHandler);
    });
}
loadData();
setupResizableColumns();

function printToPDF() {
    // Menggunakan print bawaan browser karena mesin PDF browser (Chrome/Edge/Safari/Firefox) 
    // memiliki rendering pemotongan halaman (page-break) tabel yang jauh lebih sempurna.
    const originalTitle = document.title;
    document.title = 'Jadwal_Zoom_Meeting'; // Mengubah nama file default saat disimpan
    window.print();
    document.title = originalTitle;
}
</script>
</x-layout>
