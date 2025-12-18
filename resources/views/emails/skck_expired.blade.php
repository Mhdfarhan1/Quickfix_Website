<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peringatan Masa Berlaku SKCK</title>
    <style>
        /* RESET STYLES */
        body {
            margin: 0;
            padding: 0;
            background-color: #f3f4f6; /* Abu-abu muda lembut */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #374151;
        }
        table {
            border-spacing: 0;
            width: 100%;
        }
        td {
            padding: 0;
        }
        img {
            border: 0;
        }

        /* CONTAINER STYLES */
        .email-wrapper {
            width: 100%;
            background-color: #f3f4f6;
            padding: 40px 0;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        /* HEADER BLUE */
        .header {
            background: linear-gradient(135deg, #0C4481 0%, #004aad 100%); /* Gradasi Biru QuickFix */
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 24px;
            letter-spacing: 1px;
            font-weight: 700;
        }

        /* CONTENT */
        .content {
            padding: 40px 30px;
            text-align: center;
        }
        .alert-icon {
            font-size: 48px;
            margin-bottom: 20px;
            color: #ef4444; /* Merah untuk warning icon */
        }
        .greeting {
            font-size: 20px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 16px;
        }
        .message {
            font-size: 16px;
            line-height: 1.6;
            color: #4b5563;
            margin-bottom: 24px;
        }
        
        /* BOX INFORMASI TANGGAL */
        .info-box {
            background-color: #fee2e2; /* Merah sangat muda */
            border: 1px solid #fecaca;
            border-radius: 8px;
            padding: 16px;
            margin: 0 auto 24px auto;
            display: inline-block;
            width: 80%;
        }
        .info-label {
            display: block;
            font-size: 12px;
            text-transform: uppercase;
            color: #991b1b;
            font-weight: bold;
            margin-bottom: 4px;
        }
        .info-date {
            font-size: 18px;
            font-weight: 700;
            color: #dc2626; /* Merah untuk tanggal */
        }

        /* BUTTON */
        .btn-container {
            margin: 30px 0;
        }
        .btn {
            background-color: #0C4481; /* Biru Utama */
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 16px;
            box-shadow: 0 4px 6px rgba(12, 68, 129, 0.3);
            transition: background-color 0.3s;
            display: inline-block;
        }
        .btn:hover {
            background-color: #0a3666;
        }

        /* FOOTER */
        .footer {
            background-color: #f9fafb;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
        }
        .footer a {
            color: #0C4481;
            text-decoration: none;
        }
    </style>
</head>
<body>

    <div class="email-wrapper">
        <table class="email-container">
            <tr>
                <td class="header">
                    {{-- Ganti dengan Logo Image jika ada --}}
                    <h1>QuickFix Notifikasi</h1>
                </td>
            </tr>

            <tr>
                <td class="content">
                    <div class="alert-icon">&#9888;</div> 

                    <div class="greeting">Halo, {{ $namaUser }}!</div>

                    <p class="message">
                        Kami mendeteksi bahwa dokumen verifikasi <strong>SKCK</strong> (Surat Keterangan Catatan Kepolisian) Anda telah kadaluarsa.
                    </p>

                    <div class="info-box">
                        <span class="info-label">Tanggal Kadaluarsa</span>
                        <span class="info-date">{{ $expiredDate }}</span>
                    </div>

                    <p class="message">
                        Untuk menjaga status <strong>Aktif</strong> pada akun teknisi Anda dan tetap bisa menerima pesanan, mohon segera perbarui dokumen Anda.
                    </p>


                    <p style="font-size: 14px; color: #6b7280; margin-top: 20px;">
                        Jika Anda sudah memperbarui dokumen, abaikan pesan ini.
                    </p>
                </td>
            </tr>

            <tr>
                <td class="footer">
                    <p>&copy; {{ date('Y') }} QuickFix Indonesia. All rights reserved.</p>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>