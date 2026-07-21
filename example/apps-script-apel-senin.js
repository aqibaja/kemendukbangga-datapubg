/**
 * Google Apps Script - Presensi Apel Senin (COMBINED)
 * =====================================================
 * Menggabungkan fungsi GET (baca data untuk dashboard) dan
 * POST (tulis data dari fitur presensi pegawai).
 *
 * Deploy sebagai Web App:
 *   - Execute as: "Me"
 *   - Who has access: "Anyone"
 * Lalu copy URL-nya ke .env sebagai APEL_SENIN_SCRIPT_URL
 *
 * Struktur Spreadsheet:
 *   Kolom 0 : Timestamp
 *   Kolom 1 : Email Address
 *   Kolom 2 : SILAHKAN PILIH TIM KERJA
 *   Kolom 3-14 : Nama peserta per tim (sesuai mapping di bawah)
 *   Kolom 15: APAKAH IKUT APEL SENIN (opsional)
 *   Kolom 16: KETERANGAN (opsional)
 */

// =========================================================
// KONFIGURASI — sesuaikan jika nama sheet / kolom berubah
// =========================================================
const CONFIG = {
  // Sheet yang dibaca untuk GET (dashboard)
  SHEET_NAMES: ['Form Responses 1', 'Form Responses 2'],

  // Sheet aktif yang dituju saat POST (tulis presensi baru)
  ACTIVE_SHEET_NAME: 'Form Responses 2',

  COL_TIMESTAMP: 0,
  COL_EMAIL: 1,
  COL_TIM_KERJA: 2,

  // Map: Nama Tim Kerja (uppercase) → index kolom nama peserta (0-indexed)
  TIM_KOLOM_MAP: {
    'PENGELOLAAN KEPENDUDUKAN': 6,
    'KELUARGA BERENCANA DAN KESEHATAN REPRODUKSI': 12,
    'PEMBANGUNAN KELUARGA': 4,
    'PENGGERAKKAN MASYARAKAT DAN PENGELOLAAN LINI LAPANGAN': 8,
    'PERENCANAAN DAN KEUANGAN': 3,
    'PENGELOLAAN SDM, ORGANISASI, DAN HUKUM': 10,
    'PENGELOLAAN MANAJEMEN KINERJA': 9,
    'UMUM, HUMAS, DAN PROTOKOL': 5,
    'DATA DAN INFORMASI': 13,
    'PERWAKILAN BKKBN PROVINSI ACEH': 14,
  },

  COL_IKUT_APEL: 15,
  COL_KETERANGAN: 16,
};

// =========================================================
// ROUTER UTAMA
// =========================================================

/**
 * GET → Kembalikan semua data presensi sebagai JSON array.
 * Dipakai oleh dashboard Laravel untuk menampilkan statistik.
 */
function doGet(e) {
  try {
    const data = getAllAttendanceData();
    return ContentService
      .createTextOutput(JSON.stringify(data))
      .setMimeType(ContentService.MimeType.JSON);
  } catch (error) {
    return ContentService
      .createTextOutput(JSON.stringify({ error: error.message, stack: error.stack }))
      .setMimeType(ContentService.MimeType.JSON);
  }
}

/**
 * POST → Tulis / perbarui satu baris presensi pegawai.
 * Dipakai oleh fitur lain (misal: QR presensi, form web, dsb).
 *
 * Body JSON yang diharapkan:
 * {
 *   "action":        "add_attendance",
 *   "employee_name": "NAMA PEGAWAI",
 *   "employee_unsur":"NAMA TIM KERJA",   ← dipakai sebagai nama kolom target
 *   "timestamp":     "2026-01-26T07:30:00" (ISO / string apapun yang bisa di-new Date())
 * }
 *
 * Perilaku:
 *  1. Cari baris pegawai yang sudah ada → timpa Timestamp-nya saja.
 *  2. Jika pegawai belum ada sama sekali → tambah baris baru di bawah.
 */
function doPost(e) {
  try {
    const ss = SpreadsheetApp.getActiveSpreadsheet();
    const sheet = ss.getSheetByName(CONFIG.ACTIVE_SHEET_NAME) || ss.getActiveSheet();

    const data = JSON.parse(e.postData.contents);

    if (data.action !== 'add_attendance') {
      return jsonResponse({ status: 'error', message: 'Unknown action: ' + data.action });
    }

    // ── Header ──────────────────────────────────────────────
    const headers = sheet.getRange(1, 1, 1, sheet.getLastColumn()).getValues()[0];

    // Kolom Timestamp (1-based)
    const timestampIdx = headers.findIndex(h => h.toString().trim().toLowerCase() === 'timestamp');
    const timeCol = timestampIdx !== -1 ? (timestampIdx + 1) : 1;

    // Kolom nama peserta berdasarkan Tim Kerja
    const targetColIdx = headers.findIndex(
      h => h.toString().trim().toUpperCase() === data.employee_unsur.toString().trim().toUpperCase()
    );

    // Format timestamp ke WIB
    const dateObj = new Date(data.timestamp);
    const formattedDate = Utilities.formatDate(dateObj, 'Asia/Jakarta', 'M/d/yyyy H:mm:ss');

    // ── Cari baris pegawai yang sudah ada ───────────────────
    if (targetColIdx !== -1) {
      const nameCol = targetColIdx + 1;  // 1-based
      const lastRow = sheet.getLastRow();
      const nameVals = sheet.getRange(1, nameCol, lastRow, 1).getValues();
      const timeVals = sheet.getRange(1, timeCol, lastRow, 1).getValues();

      let foundRow = -1;
      let latestMs = 0;

      for (let i = 1; i < nameVals.length; i++) {   // mulai 1 → lewati header
        if (nameVals[i][0].toString().trim().toUpperCase() !== data.employee_name.toString().trim().toUpperCase()) continue;

        const raw = timeVals[i][0];
        let ms = 0;
        if (raw instanceof Date) {
          ms = raw.getTime();
        } else {
          ms = new Date(raw.toString()).getTime();
          if (isNaN(ms)) {
            // format dd/MM/yyyy → parse manual
            const parts = raw.toString().split(/[\s/:]+/);
            if (parts.length >= 3) ms = new Date(parts[2], parts[1] - 1, parts[0]).getTime();
          }
        }

        if (ms >= latestMs || latestMs === 0) { latestMs = ms; foundRow = i + 1; }
      }

      if (foundRow !== -1) {
        // ─ Timpa Timestamp saja, data lain tidak disentuh ─
        sheet.getRange(foundRow, timeCol).setValue(formattedDate);
        return jsonResponse({ status: 'success', message: 'Updated existing latest row' });
      }
    }

    // ── Pegawai belum ada → tambah baris baru ───────────────
    const rowData = new Array(headers.length).fill('');
    rowData[timeCol - 1] = formattedDate;

    const timKerjaIdx = headers.findIndex(
      h => h.toString().trim().toUpperCase() === 'SILAHKAN PILIH TIM KERJA'
    );
    if (timKerjaIdx !== -1) rowData[timKerjaIdx] = data.employee_unsur;
    if (targetColIdx !== -1) rowData[targetColIdx] = data.employee_name;

    sheet.appendRow(rowData);
    return jsonResponse({ status: 'success', message: 'Added new row' });

  } catch (err) {
    return jsonResponse({ status: 'error', message: err.message });
  }
}

// =========================================================
// BACA SEMUA DATA PRESENSI (untuk doGet / dashboard)
// =========================================================
function getAllAttendanceData() {
  const ss = SpreadsheetApp.getActiveSpreadsheet();
  const allRows = [];

  CONFIG.SHEET_NAMES.forEach(sheetName => {
    const sheet = ss.getSheetByName(sheetName);
    if (!sheet) return;

    const lastRow = sheet.getLastRow();
    if (lastRow < 2) return;

    const data = sheet.getRange(1, 1, lastRow, sheet.getLastColumn()).getValues();
    const headers = data[0];

    for (let i = 1; i < data.length; i++) {
      const row = data[i];
      const rawTimestamp = row[CONFIG.COL_TIMESTAMP];
      if (!rawTimestamp) continue;

      const tim = String(row[CONFIG.COL_TIM_KERJA] || '').trim().toUpperCase();
      if (!tim) continue;

      // Cari nama dari kolom tim yang sesuai
      let nama = '';
      const timNorm = normalizeTeamName(tim);

      for (const [timKey, colIdx] of Object.entries(CONFIG.TIM_KOLOM_MAP)) {
        if (normalizeTeamName(timKey) === timNorm) {
          const val = String(row[colIdx] || '').trim();
          if (val) { nama = val; }
          break;
        }
      }

      // Fallback: cek semua kolom tim
      if (!nama) {
        for (const colIdx of Object.values(CONFIG.TIM_KOLOM_MAP)) {
          if (colIdx < row.length) {
            const val = String(row[colIdx] || '').trim();
            if (val) { nama = val; break; }
          }
        }
      }

      if (!nama) continue;

      // Format timestamp → string
      let timestampStr = '';
      try {
        timestampStr = rawTimestamp instanceof Date
          ? Utilities.formatDate(rawTimestamp, Session.getScriptTimeZone(), 'yyyy-MM-dd HH:mm:ss')
          : String(rawTimestamp);
      } catch (_) {
        timestampStr = String(rawTimestamp);
      }

      const ikutApel = (CONFIG.COL_IKUT_APEL >= 0 && CONFIG.COL_IKUT_APEL < row.length)
        ? String(row[CONFIG.COL_IKUT_APEL] || 'Ya').trim() || 'Ya'
        : 'Ya';
      const keterangan = (CONFIG.COL_KETERANGAN >= 0 && CONFIG.COL_KETERANGAN < row.length)
        ? String(row[CONFIG.COL_KETERANGAN] || '').trim()
        : '';
      const email = String(row[CONFIG.COL_EMAIL] || '').trim();

      allRows.push({
        timestamp: timestampStr,
        email: email,
        tim_kerja: tim,
        nama: nama,
        ikut_apel: ikutApel,
        keterangan: keterangan,
        source_sheet: sheetName,
      });
    }
  });

  return allRows;
}

// =========================================================
// HELPERS
// =========================================================
function normalizeTeamName(name) {
  return String(name).trim().toUpperCase().replace(/\s+/g, ' ').replace(/[,.]+$/, '').trim();
}

function jsonResponse(obj) {
  return ContentService
    .createTextOutput(JSON.stringify(obj))
    .setMimeType(ContentService.MimeType.JSON);
}

// =========================================================
// TEST FUNCTIONS (jalankan manual dari editor Apps Script)
// =========================================================
function testGetData() {
  const data = getAllAttendanceData();
  Logger.log('Total rows: ' + data.length);
  data.slice(0, 5).forEach(r => Logger.log(JSON.stringify(r)));

  const teams = {};
  data.forEach(r => { teams[r.tim_kerja] = (teams[r.tim_kerja] || 0) + 1; });
  Logger.log('By team:');
  Object.entries(teams).forEach(([t, c]) => Logger.log(t + ': ' + c));
}

function testPostData() {
  const mockEvent = {
    postData: {
      contents: JSON.stringify({
        action: 'add_attendance',
        employee_name: 'WAHYU RIZKI, ST',
        employee_unsur: 'PELAPORAN STATISTIK DAN PENGELOLAAN TIK',
        timestamp: new Date().toISOString(),
      })
    }
  };
  const result = doPost(mockEvent);
  Logger.log(result.getContent());
}
