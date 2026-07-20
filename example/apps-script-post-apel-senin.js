// --- KODE UNTUK SPREADSHEET (MENCARI & MENIMPA DATA LAMA) --- //
function doPost(e) {
    var sheet = SpreadsheetApp.getActiveSpreadsheet().getActiveSheet();

    try {
        var data = JSON.parse(e.postData.contents);

        if (data.action === 'add_attendance') {
            // Ambil baris pertama (Header) untuk mengetahui posisi kolom
            var headers = sheet.getRange(1, 1, 1, sheet.getLastColumn()).getValues()[0];

            var targetColumnIndex = headers.findIndex(h => h.toString().trim().toUpperCase() === data.employee_unsur.toString().trim().toUpperCase());

            var dateObj = new Date(data.timestamp);
            var formattedDate = Utilities.formatDate(dateObj, "Asia/Jakarta", "M/d/yyyy H:mm:ss");

            // Temukan kolom Timestamp (1-based index)
            var timestampIndex = headers.findIndex(h => h.toString().trim().toLowerCase() === "timestamp");
            var timeCol = timestampIndex !== -1 ? (timestampIndex + 1) : 1;

            if (targetColumnIndex !== -1) {
                // Cari baris terakhir milik pegawai ini di kolom Tim Kerjanya
                var nameCol = targetColumnIndex + 1; // 1-based index

                // Ambil semua nama dan semua timestamp sekaligus (agar cepat)
                var nameValues = sheet.getRange(1, nameCol, sheet.getLastRow(), 1).getValues();
                var timeValues = sheet.getRange(1, timeCol, sheet.getLastRow(), 1).getValues();

                var foundRowIndex = -1;
                var latestTime = 0;

                // Loop dari atas ke bawah untuk mencari data dengan Timestamp paling baru
                for (var i = 1; i < nameValues.length; i++) { // Mulai dari 1 untuk melewati header
                    if (nameValues[i][0].toString().trim().toUpperCase() === data.employee_name.toString().trim().toUpperCase()) {
                        var rowTimeRaw = timeValues[i][0];
                        var rowTimeMs = 0;

                        // Baca format waktunya (Bisa berupa Object Date bawaan sheet atau String)
                        if (rowTimeRaw instanceof Date) {
                            rowTimeMs = rowTimeRaw.getTime();
                        } else {
                            var timeStr = rowTimeRaw.toString();
                            rowTimeMs = new Date(timeStr).getTime();
                            if (isNaN(rowTimeMs)) {
                                // Jika gagal diparse karena format dd/MM/yyyy, coba ekstrak manual
                                var parts = timeStr.split(/[\s/:]+/);
                                if (parts.length >= 3) {
                                    var altDate = new Date(parts[2], parts[1] - 1, parts[0]); // yyyy, MM, dd
                                    rowTimeMs = altDate.getTime();
                                }
                            }
                        }

                        // Jika ini adalah data yang lebih baru (atau data pertama yang ketemu)
                        if (rowTimeMs >= latestTime || latestTime === 0) {
                            latestTime = rowTimeMs;
                            foundRowIndex = i + 1; // 1-based row index
                        }
                    }
                }

                if (foundRowIndex !== -1) {
                    // JIKA KETEMU: Timpa Timestamp-nya saja di baris dengan data paling terbaru tersebut!
                    // Data Email dan lainnya di baris itu akan aman tak tersentuh.
                    sheet.getRange(foundRowIndex, timeCol).setValue(formattedDate);

                    return ContentService.createTextOutput(JSON.stringify({ "status": "success", "message": "Updated existing latest row" }))
                        .setMimeType(ContentService.MimeType.JSON);
                }
            }

            // JIKA TIDAK KETEMU (Misal pegawai baru): Buat baris baru di paling bawah
            var rowData = new Array(headers.length);
            for (var j = 0; j < rowData.length; j++) { rowData[j] = ""; }

            rowData[timeCol - 1] = formattedDate;
            var timKerjaIndex = headers.findIndex(h => h.toString().trim().toUpperCase() === "SILAHKAN PILIH TIM KERJA");
            if (timKerjaIndex !== -1) rowData[timKerjaIndex] = data.employee_unsur;
            if (targetColumnIndex !== -1) rowData[targetColumnIndex] = data.employee_name;

            sheet.appendRow(rowData);

            return ContentService.createTextOutput(JSON.stringify({ "status": "success", "message": "Added new row" }))
                .setMimeType(ContentService.MimeType.JSON);
        }
    } catch (err) {
        return ContentService.createTextOutput(JSON.stringify({ "status": "error", "message": err.message }))
            .setMimeType(ContentService.MimeType.JSON);
    }
}
