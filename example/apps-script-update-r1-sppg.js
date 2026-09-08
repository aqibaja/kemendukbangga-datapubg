/**
 * ============================================================
 * UPDATE R1 SPPG — Google Apps Script Web App
 * Target Sheet: Tab 'REKAP DATA SPPG BARU'
 * ============================================================
 * CARA DEPLOY:
 * 1. Buka spreadsheet Google Anda (atau script.google.com)
 * 2. Masuk ke Extensions > Apps Script
 * 3. Copy-paste seluruh kode ini ke editor Apps Script
 * 4. Klik Deploy > New Deployment > Web App
 *    - Execute as: Me
 *    - Who has access: Anyone (Siapa saja)
 * 5. Copy URL deployment (Web App URL), paste ke APPS_SCRIPT_URL di blade view
 * ============================================================
 */

// ============================================================
// KONFIGURASI
// ============================================================
const SHEET_TAB_NAME = 'REKAP DATA SPPG BARU';
const FOLDER_ID = '1MdzfmSdANnHPd3CbGvXEpzu3mgGq8rvZ'; // Opsional jika multi-file spreadsheet

// Pemetaan Indeks Kolom Penting (0-indexed)
const COL = {
  NO: 0,            // A (1)
  ID_SPPG: 1,       // B (2)
  KODE_SPPG: 2,     // C (3)
  NAMA_SPPG: 3,     // D (4)
  ALAMAT_SPPG: 4,   // E (5)
  KODE_PROV: 5,     // F (6)
  PROVINSI: 6,      // G (7)
  KODE_KAB: 7,      // H (8)
  KABUPATEN: 8,     // I (9)
  KODE_KEC: 9,      // J (10)
  KECAMATAN: 10,    // K (11)
  KODE_DESA: 11,    // L (12)
  DESA: 12,         // M (13) - Kelurahan/Desa
  
  // BGN (Readonly Acuan)
  BALITA_BGN: 13,   // N (14)
  BALITA_KPPG: 14,  // O (15) - EDITABLE
  BALITA_VERIF: 15, // P (16)
  BUMIL_BGN: 16,    // Q (17)
  BUMIL_KPPG: 17,   // R (18) - EDITABLE
  BUMIL_VERIF: 18,  // S (19)
  BUSUI_BGN: 19,    // T (20)
  BUSUI_KPPG: 20,   // U (21) - EDITABLE
  BUSUI_VERIF: 21,  // V (22)
  TOTAL_BGN: 22,    // W (23)
  TOTAL_KPPG: 23,   // X (24) - EDITABLE
  TOTAL_VERIF: 24,  // Y (25)
  
  // General SPPG (AA & AB)
  MBG_DIST_DEFAULT: 25, // Z (26)
  MBG_DIST_VERIF: 26,   // AA (27) - EDITABLE GENERAL
  STATUS_SPPG: 27,      // AB (28) - EDITABLE GENERAL
  
  // Desa Fields
  FREQ_DIST: 28,        // AC (29) - EDITABLE
  FREQ_SASARAN: 29,     // AD (30) - EDITABLE
  FREQ_MENU_BASAH: 30,  // AE (31) - EDITABLE
  MAKANAN_UPF: 31,      // AF (32) - EDITABLE
  INSENTIF_KADER: 32,   // AG (33) - EDITABLE
  NOMINAL_INSENTIF: 33, // AH (34) - EDITABLE
  METODE_DIST: 34,      // AI (35) - EDITABLE
  JML_TITIK_DIST: 35,   // AJ (36) - EDITABLE
  JML_TPK: 36,          // AK (37)
  JML_TPK_VERIF: 37,    // AL (38) - EDITABLE
  JML_TPK_DIST: 38,     // AM (39)
  JML_TPK_DIST_VERIF: 39,// AN (40) - EDITABLE
  JML_KADER_NON_TPK: 40,// AO (41) - EDITABLE
  KETERANGAN: 41,       // AP (42) - EDITABLE
  TIMESTAMP: 42         // AQ (43) - AUTO TIMESTAMP
};

// ============================================================
// HELPER RESPONSE
// ============================================================
function jsonResponse(obj) {
  return ContentService
    .createTextOutput(JSON.stringify(obj))
    .setMimeType(ContentService.MimeType.JSON);
}

function getTargetSheet(sheetId) {
  var ss;
  if (sheetId) {
    ss = SpreadsheetApp.openById(sheetId);
  } else {
    try {
      ss = SpreadsheetApp.getActiveSpreadsheet();
    } catch(e) {
      // Jika stand-alone web app tanpa active sheet, buka spreadsheet pertama di folder atau ID default
      var folder = DriveApp.getFolderById(FOLDER_ID);
      var files = folder.getFilesByType(MimeType.GOOGLE_SHEETS);
      if (files.hasNext()) {
        ss = SpreadsheetApp.open(files.next());
      }
    }
  }
  
  if (!ss) throw new Error("Spreadsheet tidak ditemukan.");
  
  var sheet = ss.getSheetByName(SHEET_TAB_NAME);
  if (!sheet) {
    // Fallback jika nama tab berbeda sedikit
    var allSheets = ss.getSheets();
    for (var i = 0; i < allSheets.length; i++) {
      if (allSheets[i].getName().toUpperCase().indexOf('SPPG BARU') > -1 || 
          allSheets[i].getName().toUpperCase().indexOf('REKAP DATA SPPG') > -1) {
        sheet = allSheets[i];
        break;
      }
    }
    if (!sheet) sheet = ss.getSheets()[0];
  }
  return sheet;
}

// Format Timestamp ke YYYY-MM-DD HH:mm:ss (WIB)
function getFormattedTimestamp() {
  var now = new Date();
  return Utilities.formatDate(now, "GMT+7", "yyyy-MM-dd HH:mm:ss");
}

// ============================================================
// ROUTER GET
// ============================================================
function doGet(e) {
  var action = (e && e.parameter && e.parameter.action) || 'getSppgList';
  var sheetId = e && e.parameter && e.parameter.sheetId;
  var namaSppg = e && e.parameter && e.parameter.namaSppg;
  var result;

  try {
    switch (action) {
      case 'listSheets':
        result = listSheets();
        break;
      case 'getSppgList':
        result = getSppgList(sheetId);
        break;
      case 'getSppgData':
        result = getSppgData(sheetId, namaSppg);
        break;
      default:
        result = { success: false, error: 'Unknown GET action: ' + action };
    }
  } catch (err) {
    result = { success: false, error: err.message, stack: err.stack };
  }

  return jsonResponse(result);
}

// ============================================================
// ROUTER POST
// ============================================================
function doPost(e) {
  var body;
  try {
    body = JSON.parse(e.postData.contents);
  } catch (err) {
    return jsonResponse({ success: false, error: 'Invalid JSON body' });
  }

  var action = body.action || '';
  var sheetId = body.sheetId || '';
  var result;

  try {
    switch (action) {
      case 'updateGeneralSppg':
        result = updateGeneralSppg(sheetId, body.namaSppg, body.data);
        break;
      case 'updateDesaRow':
        result = updateDesaRow(sheetId, body.rowIndex, body.data);
        break;
      default:
        result = { success: false, error: 'Unknown POST action: ' + action };
    }
  } catch (err) {
    result = { success: false, error: err.message, stack: err.stack };
  }

  return jsonResponse(result);
}

// ============================================================
// ACTION: listSheets
// ============================================================
function listSheets() {
  var folder = DriveApp.getFolderById(FOLDER_ID);
  var files = folder.getFilesByType(MimeType.GOOGLE_SHEETS);
  var sheets = [];

  while (files.hasNext()) {
    var file = files.next();
    sheets.push({
      id: file.getId(),
      name: file.getName()
    });
  }

  sheets.sort(function(a, b) { return a.name.localeCompare(b.name); });
  return { success: true, sheets: sheets };
}

// ============================================================
// ACTION: getSppgList — Daftar unik SPPG dari Kolom D
// ============================================================
function getSppgList(sheetId) {
  var sheet = getTargetSheet(sheetId);
  var data = sheet.getDataRange().getValues();

  if (data.length < 2) {
    return { success: true, sppgList: [] };
  }

  var headers = data[0];
  var sppgMap = {};
  var currentMonth = Utilities.formatDate(new Date(), "GMT+7", "yyyy-MM");

  for (var i = 1; i < data.length; i++) {
    var row = data[i];
    var namaSppg = String(row[COL.NAMA_SPPG] || '').trim();
    if (!namaSppg) continue;

    if (!sppgMap[namaSppg]) {
      sppgMap[namaSppg] = {
        nama: namaSppg,
        idSppg: String(row[COL.ID_SPPG] || ''),
        kodeSppg: String(row[COL.KODE_SPPG] || ''),
        alamat: String(row[COL.ALAMAT_SPPG] || ''),
        kabupaten: String(row[COL.KABUPATEN] || ''),
        kecamatan: String(row[COL.KECAMATAN] || ''),
        distribusiVerif: String(row[COL.MBG_DIST_VERIF] || ''),
        statusSppg: String(row[COL.STATUS_SPPG] || ''),
        totalDesa: 0,
        desaUpdatedCount: 0
      };
    }

    sppgMap[namaSppg].totalDesa += 1;
    
    // Cek apakah row desa ini sudah diupdate bulan ini (kolom AQ)
    var tsVal = row[COL.TIMESTAMP];
    if (tsVal) {
      var tsStr = tsVal instanceof Date ? Utilities.formatDate(tsVal, "GMT+7", "yyyy-MM") : String(tsVal);
      if (tsStr.indexOf(currentMonth) > -1) {
        sppgMap[namaSppg].desaUpdatedCount += 1;
      }
    }
  }

  var sppgList = Object.values(sppgMap);
  sppgList.sort(function(a, b) { return a.nama.localeCompare(b.nama); });

  return { success: true, sppgList: sppgList, currentMonth: currentMonth };
}

// ============================================================
// ACTION: getSppgData — Seluruh data desa untuk SPPG terpilih
// ============================================================
function getSppgData(sheetId, namaSppg) {
  if (!namaSppg) return { success: false, error: 'namaSppg wajib diisi' };

  var sheet = getTargetSheet(sheetId);
  var data = sheet.getDataRange().getValues();

  if (data.length < 2) return { success: false, error: 'Data sheet kosong' };

  var headers = data[0];
  var rows = [];
  var sppgInfo = null;
  var currentMonth = Utilities.formatDate(new Date(), "GMT+7", "yyyy-MM");

  for (var i = 1; i < data.length; i++) {
    var row = data[i];
    var rowNama = String(row[COL.NAMA_SPPG] || '').trim();

    if (rowNama.toLowerCase() === namaSppg.trim().toLowerCase()) {
      if (!sppgInfo) {
        sppgInfo = {
          nama: rowNama,
          idSppg: String(row[COL.ID_SPPG] || ''),
          kodeSppg: String(row[COL.KODE_SPPG] || ''),
          alamat: String(row[COL.ALAMAT_SPPG] || ''),
          kabupaten: String(row[COL.KABUPATEN] || ''),
          kecamatan: String(row[COL.KECAMATAN] || ''),
          distribusiVerif: String(row[COL.MBG_DIST_VERIF] || ''),
          statusSppg: String(row[COL.STATUS_SPPG] || '')
        };
      }

      var rowObj = {
        __rowIndex: i + 1, // 1-indexed untuk Google Sheet
        no: row[COL.NO],
        idSppg: row[COL.ID_SPPG],
        kodeSppg: row[COL.KODE_SPPG],
        namaSppg: row[COL.NAMA_SPPG],
        alamatSppg: row[COL.ALAMAT_SPPG],
        kabupaten: row[COL.KABUPATEN],
        kecamatan: row[COL.KECAMATAN],
        kodeKelurahan: row[COL.KODE_DESA],
        kelurahan: row[COL.DESA],
        
        // Acuan BGN (Readonly)
        balitaBgn: row[COL.BALITA_BGN] || 0,
        bumilBgn: row[COL.BUMIL_BGN] || 0,
        busuiBgn: row[COL.BUSUI_BGN] || 0,
        totalBgn: row[COL.TOTAL_BGN] || 0,
        jmlTpk: row[COL.JML_TPK] || 0,
        jmlTpkDist: row[COL.JML_TPK_DIST] || 0,

        // KPPG Sasaran (O, R, U, X)
        balitaKppg: row[COL.BALITA_KPPG] || '',
        bumilKppg: row[COL.BUMIL_KPPG] || '',
        busuiKppg: row[COL.BUSUI_KPPG] || '',
        totalKppg: row[COL.TOTAL_KPPG] || '',

        // General SPPG (AA & AB)
        distribusiVerif: row[COL.MBG_DIST_VERIF] || '',
        statusSppg: row[COL.STATUS_SPPG] || '',

        // Desa Fields (AC-AJ, AL, AN, AO, AP)
        freqDist: row[COL.FREQ_DIST] || '',
        freqSasaran: row[COL.FREQ_SASARAN] || '',
        freqMenuBasah: row[COL.FREQ_MENU_BASAH] || '',
        makananUpf: row[COL.MAKANAN_UPF] || '',
        insentifKader: row[COL.INSENTIF_KADER] || '',
        nominalInsentif: row[COL.NOMINAL_INSENTIF] || '',
        metodeDist: row[COL.METODE_DIST] || '',
        jmlTitikDist: row[COL.JML_TITIK_DIST] || '',
        jmlTpkVerif: row[COL.JML_TPK_VERIF] || '',
        jmlTpkDistVerif: row[COL.JML_TPK_DIST_VERIF] || '',
        jmlKaderNonTpk: row[COL.JML_KADER_NON_TPK] || '',
        keterangan: row[COL.KETERANGAN] || '',

        // Timestamp (AQ)
        timestamp: row[COL.TIMESTAMP] instanceof Date ? 
          Utilities.formatDate(row[COL.TIMESTAMP], "GMT+7", "yyyy-MM-dd HH:mm:ss") : 
          String(row[COL.TIMESTAMP] || '')
      };

      // Cek status bulan ini
      var ts = rowObj.timestamp;
      rowObj.isUpdatedThisMonth = ts && ts.indexOf(currentMonth) > -1;

      rows.push(rowObj);
    }
  }

  return {
    success: true,
    sppgInfo: sppgInfo,
    rows: rows,
    currentMonth: currentMonth
  };
}

// ============================================================
// ACTION: updateGeneralSppg — Update Kolom AA & AB untuk SPPG
// ============================================================
function updateGeneralSppg(sheetId, namaSppg, data) {
  if (!namaSppg) return { success: false, error: 'namaSppg required' };
  
  var sheet = getTargetSheet(sheetId);
  var sheetData = sheet.getDataRange().getValues();
  var updatedCount = 0;

  var distVerif = data.distribusiVerif !== undefined ? data.distribusiVerif : data['AA'];
  var statusSppg = data.statusSppg !== undefined ? data.statusSppg : data['AB'];

  for (var i = 1; i < sheetData.length; i++) {
    var rowNama = String(sheetData[i][COL.NAMA_SPPG] || '').trim();
    if (rowNama.toLowerCase() === namaSppg.trim().toLowerCase()) {
      var rowNum = i + 1; // 1-indexed

      if (distVerif !== undefined) {
        sheet.getRange(rowNum, COL.MBG_DIST_VERIF + 1).setValue(distVerif);
      }
      if (statusSppg !== undefined) {
        sheet.getRange(rowNum, COL.STATUS_SPPG + 1).setValue(statusSppg);
      }
      updatedCount++;
    }
  }

  SpreadsheetApp.flush();
  return {
    success: true,
    message: 'General SPPG berhasil diperbarui untuk ' + updatedCount + ' desa.',
    updatedCount: updatedCount
  };
}

// ============================================================
// ACTION: updateDesaRow — Update Kolom O, R, U, X, AC-AJ, AL, AN-AP & AQ
// ============================================================
function updateDesaRow(sheetId, rowIndex, data) {
  if (!rowIndex || rowIndex < 2) {
    return { success: false, error: 'rowIndex valid (>= 2) diperlukan' };
  }

  var sheet = getTargetSheet(sheetId);
  var newTimestamp = getFormattedTimestamp();

  // Pastikan kolom AQ (Timestamp) ada di header jika sheet belum memiliki kolom AQ
  var lastCol = sheet.getLastColumn();
  if (lastCol <= COL.TIMESTAMP) {
    sheet.getRange(1, COL.TIMESTAMP + 1).setValue('Timestamp');
  }

  // Update nilai-nilai yang dikirimkan
  // KPPG Sasaran (O, R, U, X)
  if (data.balitaKppg !== undefined) sheet.getRange(rowIndex, COL.BALITA_KPPG + 1).setValue(data.balitaKppg);
  if (data.bumilKppg !== undefined) sheet.getRange(rowIndex, COL.BUMIL_KPPG + 1).setValue(data.bumilKppg);
  if (data.busuiKppg !== undefined) sheet.getRange(rowIndex, COL.BUSUI_KPPG + 1).setValue(data.busuiKppg);
  if (data.totalKppg !== undefined) sheet.getRange(rowIndex, COL.TOTAL_KPPG + 1).setValue(data.totalKppg);

  // General SPPG per desa (Kolom AA & AB)
  if (data.distribusiVerif !== undefined) sheet.getRange(rowIndex, COL.MBG_DIST_VERIF + 1).setValue(data.distribusiVerif);
  if (data.statusSppg !== undefined) sheet.getRange(rowIndex, COL.STATUS_SPPG + 1).setValue(data.statusSppg);

  // Desa Fields
  if (data.freqDist !== undefined) sheet.getRange(rowIndex, COL.FREQ_DIST + 1).setValue(data.freqDist);
  if (data.freqSasaran !== undefined) sheet.getRange(rowIndex, COL.FREQ_SASARAN + 1).setValue(data.freqSasaran);
  if (data.freqMenuBasah !== undefined) sheet.getRange(rowIndex, COL.FREQ_MENU_BASAH + 1).setValue(data.freqMenuBasah);
  if (data.makananUpf !== undefined) sheet.getRange(rowIndex, COL.MAKANAN_UPF + 1).setValue(data.makananUpf);
  if (data.insentifKader !== undefined) sheet.getRange(rowIndex, COL.INSENTIF_KADER + 1).setValue(data.insentifKader);
  if (data.nominalInsentif !== undefined) sheet.getRange(rowIndex, COL.NOMINAL_INSENTIF + 1).setValue(data.nominalInsentif);
  if (data.metodeDist !== undefined) sheet.getRange(rowIndex, COL.METODE_DIST + 1).setValue(data.metodeDist);
  if (data.jmlTitikDist !== undefined) sheet.getRange(rowIndex, COL.JML_TITIK_DIST + 1).setValue(data.jmlTitikDist);
  if (data.jmlTpkVerif !== undefined) sheet.getRange(rowIndex, COL.JML_TPK_VERIF + 1).setValue(data.jmlTpkVerif);
  if (data.jmlTpkDistVerif !== undefined) sheet.getRange(rowIndex, COL.JML_TPK_DIST_VERIF + 1).setValue(data.jmlTpkDistVerif);
  if (data.jmlKaderNonTpk !== undefined) sheet.getRange(rowIndex, COL.JML_KADER_NON_TPK + 1).setValue(data.jmlKaderNonTpk);
  if (data.keterangan !== undefined) sheet.getRange(rowIndex, COL.KETERANGAN + 1).setValue(data.keterangan);

  // Set Kolom AQ (Timestamp)
  sheet.getRange(rowIndex, COL.TIMESTAMP + 1).setValue(newTimestamp);

  SpreadsheetApp.flush();

  return {
    success: true,
    message: 'Data desa berhasil diperbarui.',
    updatedTimestamp: newTimestamp
  };
}
