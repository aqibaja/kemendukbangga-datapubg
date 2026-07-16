/**
 * ============================================================
 * UPDATE K0 SPPG — Google Apps Script Web App
 * ============================================================
 * CARA DEPLOY:
 * 1. Buka script.google.com → New Project → beri nama "UpdateK0SPPG"
 * 2. Copy-paste seluruh kode ini
 * 3. Klik Deploy → New Deployment → Web App
 *    - Execute as: Me
 *    - Who has access: Anyone
 * 4. Copy URL deployment, paste ke variabel APPS_SCRIPT_URL di blade view
 * ============================================================
 */

/*
============================================================
HELPER: FILE UPLOAD
============================================================
*/
function uploadBase64File(base64DataUrl, filename, formName, columnName) {
  try {
    // base64DataUrl format: "data:image/jpeg;base64,/9j/4AAQSkZJ..."
    var parts = base64DataUrl.split(',');
    var metadata = parts[0];
    var base64Data = parts[1];

    var mimeType = metadata.split(';')[0].split(':')[1];
    var decoded = Utilities.base64Decode(base64Data);
    var blob = Utilities.newBlob(decoded, mimeType, filename);

    var folderId = '1MdzfmSdANnHPd3CbGvXEpzu3mgGq8rvZ'; // Folder Utama
    var mainFolder = DriveApp.getFolderById(folderId);
    var targetFolder = mainFolder;

    // Jika formName dan columnName tersedia, masuk ke subfolder
    if (formName && columnName) {
      // 1. Cari/buat folder tingkat pertama: "NamaForm (File responses)"
      var folder1Name = formName + " (File responses)";
      var folder1Iter = mainFolder.getFoldersByName(folder1Name);
      var folder1;
      if (folder1Iter.hasNext()) {
        folder1 = folder1Iter.next();
      } else {
        folder1 = mainFolder.createFolder(folder1Name);
      }

      // 2. Cari/buat folder tingkat kedua: "NamaKolom (File responses)"
      var folder2Name = columnName + " (File responses)";
      var folder2Iter = folder1.getFoldersByName(folder2Name);
      var folder2;
      if (folder2Iter.hasNext()) {
        folder2 = folder2Iter.next();
      } else {
        folder2 = folder1.createFolder(folder2Name);
      }

      targetFolder = folder2;
    }

    var file = targetFolder.createFile(blob);

    // Set permission agar siapapun yang punya link bisa melihat
    file.setSharing(DriveApp.Access.ANYONE_WITH_LINK, DriveApp.Permission.VIEW);

    return file.getUrl();
  } catch (e) {
    throw new Error("Gagal mengupload file: " + e.message);
  }
}

// ============================================================
// KONFIGURASI
// ============================================================
const FOLDER_ID = '1MdzfmSdANnHPd3CbGvXEpzu3mgGq8rvZ';
const COL_TIMESTAMP = 0;  // A — Timestamp (readonly)
const COL_NAMA_PKB = 1;  // B — NAMA PKB/PPPK WILAYAH BINAAN MBG (readonly)
const COL_PASSWORD = 2;  // C — PASSWORD (readonly, untuk validasi)
const READONLY_COLS = [COL_TIMESTAMP, COL_NAMA_PKB, COL_PASSWORD];

// ============================================================
// ENTRY POINT — Router
// ============================================================
function doGet(e) {
  const action = e.parameter.action || '';
  let result;

  try {
    switch (action) {
      case 'listSheets':
        result = listSheets();
        break;
      case 'getNames':
        result = getNames(e.parameter.sheetId);
        break;
      default:
        result = { error: 'Unknown action: ' + action };
    }
  } catch (err) {
    result = { error: err.message, stack: err.stack };
  }

  return ContentService
    .createTextOutput(JSON.stringify(result))
    .setMimeType(ContentService.MimeType.JSON);
}

function doPost(e) {
  let body;
  try {
    body = JSON.parse(e.postData.contents);
  } catch (err) {
    return jsonResponse({ error: 'Invalid JSON body' });
  }

  const action = body.action || '';
  let result;

  try {
    switch (action) {
      case 'validate':
        result = validateAndGetData(body.sheetId, body.nama, body.password);
        break;
      case 'updateRow':
        // --- PRE-PROCESS ROW UPDATES UNTUK UPLOAD FILE ---
        var formName = "";
        try {
          var ssName = SpreadsheetApp.openById(body.sheetId).getName();
          // Hapus bagian "(Responses)" dll jika ada
          formName = ssName.replace(/\s*\(Responses\).*/i, '').trim();
        } catch (e) {
          formName = "Form_Unknown";
        }

        var rowUpdates = body.data;
        for (var h in rowUpdates) {
          var val = rowUpdates[h];
          if (typeof val === 'string' && val.indexOf('data:') === 0 && val.indexOf(';base64,') > -1) {
            // Ini adalah file base64
            var mime = val.split(';')[0].split(':')[1] || '';
            var ext = mime.split('/')[1] || 'bin';
            var ts = new Date().getTime();
            var sanitizedName = String(body.nama).replace(/[^a-zA-Z0-9]/g, '_');
            var filename = "FOTO_" + sanitizedName + "_" + ts + "." + ext;

            // Upload ke Drive dan timpa nilai val dengan URL file-nya (termasuk folder management)
            body.data[h] = uploadBase64File(val, filename, formName, h);
          }
        }
        // -------------------------------------------------
        result = updateRow(body.sheetId, body.rowIndex, body.data, body.nama, body.password);
        break;
      default:
        result = { error: 'Unknown action: ' + action };
    }
  } catch (err) {
    result = { error: err.message };
  }

  return jsonResponse(result);
}

// ============================================================
// ACTION: listSheets — daftar semua spreadsheet dalam folder
// ============================================================
function listSheets() {
  const folder = DriveApp.getFolderById(FOLDER_ID);
  const files = folder.getFilesByType(MimeType.GOOGLE_SHEETS);
  const sheets = [];

  while (files.hasNext()) {
    const file = files.next();
    sheets.push({
      id: file.getId(),
      name: file.getName()
    });
  }

  // Sort by name (K0 01, K0 02, ..., K0 23)
  sheets.sort((a, b) => a.name.localeCompare(b.name));

  return { success: true, sheets };
}

// ============================================================
// ACTION: getNames — daftar unik NAMA PKB dari sheet tertentu
// ============================================================
function getNames(sheetId) {
  if (!sheetId) return { error: 'sheetId required' };

  const ss = SpreadsheetApp.openById(sheetId);
  const sheet = ss.getSheets()[0]; // "Form Responses 1"
  const data = sheet.getDataRange().getValues();

  if (data.length < 2) return { success: true, names: [] };

  // Row 0 = header, mulai dari row 1
  const nameSet = new Set();
  for (let i = 1; i < data.length; i++) {
    const nama = String(data[i][COL_NAMA_PKB] || '').trim();
    if (nama) nameSet.add(nama);
  }

  const names = Array.from(nameSet).sort();
  return { success: true, names };
}

// ============================================================
// ACTION: validate — validasi password dan ambil data rows + form structure
// ============================================================
function validateAndGetData(sheetId, nama, password) {
  if (!sheetId || !nama || !password) {
    return { error: 'sheetId, nama, dan password wajib diisi' };
  }

  const ss = SpreadsheetApp.openById(sheetId);
  const sheet = ss.getSheets()[0];
  const data = sheet.getDataRange().getValues();

  if (data.length < 2) return { error: 'Spreadsheet kosong' };

  const headers = data[0];

  // Cari semua row milik nama ini
  const userRows = [];
  for (let i = 1; i < data.length; i++) {
    const rowNama = String(data[i][COL_NAMA_PKB] || '').trim();
    if (rowNama.toLowerCase() === nama.toLowerCase()) {
      userRows.push({ rowIndex: i, row: data[i] });
    }
  }

  if (userRows.length === 0) {
    return { valid: false, error: 'Nama tidak ditemukan di spreadsheet ini.' };
  }

  // Validasi password: cek dari row pertama yang ditemukan
  const correctPassword = String(userRows[0].row[COL_PASSWORD] || '').trim();
  if (password.trim() !== correctPassword) {
    return { valid: false, error: 'Password salah. Silakan coba lagi.' };
  }

  // Format data untuk dikirim ke frontend
  const rows = userRows.map(({ rowIndex, row }) => {
    const obj = {};
    headers.forEach((header, colIdx) => {
      let val = row[colIdx];
      // Format date objects
      if (val instanceof Date) {
        val = Utilities.formatDate(val, 'Asia/Jakarta', 'M/d/yyyy HH:mm:ss');
      }
      obj[header] = val !== undefined ? String(val) : '';
    });
    obj.__rowIndex = rowIndex; // simpan index row untuk update nanti
    return obj;
  });

  // ---- Baca struktur Google Form ----
  // Strategi 1: getFormUrl() dari spreadsheet
  // Strategi 2: cari file Google Form di folder yang sama berdasarkan nama
  let formStructure = [];
  let formDebugMsg = '';

  try {
    var form = null;

    // === Strategi 1: link langsung dari spreadsheet ===
    try {
      var formUrl = ss.getFormUrl();
      if (formUrl) {
        form = FormApp.openByUrl(formUrl);
        formDebugMsg = 'strategy1_formUrl';
      }
    } catch (e1) {
      formDebugMsg = 'strategy1_failed:' + e1.message;
    }

    // === Strategi 2: cari di folder Drive berdasarkan nama ===
    // Nama spreadsheet: "K0 08 SPPG (Responses)"
    // Nama form:        "K0 08 SPPG"
    if (!form) {
      try {
        var ssName = ss.getName(); // mis. "K0 08 SPPG (Responses) - Form Responses 1"
        // Ambil bagian sebelum " (Responses)"
        var formName = ssName.replace(/\s*\(Responses\).*/i, '').trim();
        var folder = DriveApp.getFolderById(FOLDER_ID);
        var formMime = MimeType.GOOGLE_FORMS;

        // Cari exact match dulu
        var foundFiles = folder.getFilesByType(formMime);
        while (foundFiles.hasNext()) {
          var f = foundFiles.next();
          if (f.getName().trim() === formName) {
            form = FormApp.openById(f.getId());
            formDebugMsg = 'strategy2_exact:' + formName;
            break;
          }
        }

        // Kalau tidak ketemu exact, coba partial match
        if (!form) {
          var foundFiles2 = folder.getFilesByType(formMime);
          while (foundFiles2.hasNext()) {
            var f2 = foundFiles2.next();
            if (f2.getName().toLowerCase().indexOf(formName.toLowerCase()) !== -1) {
              form = FormApp.openById(f2.getId());
              formDebugMsg = 'strategy2_partial:' + f2.getName();
              break;
            }
          }
        }
      } catch (e2) {
        formDebugMsg += '|strategy2_failed:' + e2.message;
      }
    }

    // === Baca items dari form yang ditemukan ===
    if (form) {
      var items = form.getItems();
      formStructure = items.map(function (item) {
        var itemType = item.getType().toString();
        var base = {
          title: item.getTitle(),
          type: itemType,
          helpText: item.getHelpText() || '',
          choices: [],
        };
        try {
          if (itemType === 'MULTIPLE_CHOICE') base.choices = item.asMultipleChoiceItem().getChoices().map(function (c) { return c.getValue(); });
          else if (itemType === 'CHECKBOX') base.choices = item.asCheckboxItem().getChoices().map(function (c) { return c.getValue(); });
          else if (itemType === 'LIST') base.choices = item.asListItem().getChoices().map(function (c) { return c.getValue(); });
        } catch (ei) { }
        return base;
      });
      formDebugMsg += '|items:' + formStructure.length;
    } else {
      formDebugMsg += '|form_not_found';
    }

  } catch (e) {
    formDebugMsg = 'outer_error:' + e.message;
    formStructure = [];
  }

  return {
    valid: true,
    headers: headers,
    rows: rows,
    formStructure: formStructure,
    _formDebug: formDebugMsg,   // kirim ke frontend untuk debug (bisa dihapus setelah ok)
  };
}



// ============================================================
// ACTION: updateRow — update 1 row di spreadsheet
// ============================================================
function updateRow(sheetId, rowIndex, data, nama, password) {
  if (!sheetId || rowIndex === undefined || !data || !nama || !password) {
    return { success: false, error: 'Parameter tidak lengkap' };
  }

  const ss = SpreadsheetApp.openById(sheetId);
  const sheet = ss.getSheets()[0];
  const allData = sheet.getDataRange().getValues();

  if (rowIndex < 1 || rowIndex >= allData.length) {
    return { success: false, error: 'Row index tidak valid' };
  }

  // Security check: pastikan row ini benar-benar milik nama + password yang benar
  const targetRow = allData[rowIndex];
  const rowNama = String(targetRow[COL_NAMA_PKB] || '').trim();
  const rowPassword = String(targetRow[COL_PASSWORD] || '').trim();

  if (rowNama.toLowerCase() !== nama.toLowerCase()) {
    return { success: false, error: 'Akses ditolak: row bukan milik user ini.' };
  }
  if (password.trim() !== rowPassword) {
    return { success: false, error: 'Akses ditolak: password tidak cocok.' };
  }

  const headers = allData[0];

  // Update cell per cell, SKIP readonly columns
  headers.forEach((header, colIdx) => {
    if (READONLY_COLS.includes(colIdx)) return; // skip timestamp, nama, password
    if (data.hasOwnProperty(header)) {
      const newVal = data[header];
      // rowIndex di sheet adalah rowIndex+1 (1-based, row 1 = header)
      sheet.getRange(rowIndex + 1, colIdx + 1).setValue(newVal);
    }
  });

  // Update Timestamp (kolom A) dengan waktu submit terbaru (sebagai string agar format jam ikut tersimpan)
  sheet.getRange(rowIndex + 1, COL_TIMESTAMP + 1).setValue(Utilities.formatDate(new Date(), 'Asia/Jakarta', 'M/d/yyyy HH:mm:ss'));

  // Log perubahan ke sheet "Update Log" (buat jika belum ada)
  try {
    let logSheet = ss.getSheetByName('Update Log');
    if (!logSheet) {
      logSheet = ss.insertSheet('Update Log');
      logSheet.appendRow(['Timestamp', 'Nama PKB', 'Row Index', 'Perubahan']);
    }
    logSheet.appendRow([
      new Date(),
      nama,
      rowIndex + 1,
      'Data diupdate via website'
    ]);
  } catch (e) {
    // Log error tidak menghentikan proses utama
  }

  return { success: true, message: 'Data berhasil disimpan (VERSI BARU).', updatedTimestamp: Utilities.formatDate(new Date(), 'Asia/Jakarta', 'M/d/yyyy HH:mm:ss') };
}

// ============================================================
// HELPER
// ============================================================
function jsonResponse(obj) {
  return ContentService
    .createTextOutput(JSON.stringify(obj))
    .setMimeType(ContentService.MimeType.JSON);
}
