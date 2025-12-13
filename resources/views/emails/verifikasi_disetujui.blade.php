<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Akun Disetujui</title>
    <style>
        /* RESET STYLES */
        body {
            margin: 0;
            padding: 0;
            background-color: #f3f4f6;
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

        /* CONTAINER */
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
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        /* HEADER BLUE (BRANDING) */
        .header {
            background: linear-gradient(135deg, #0C4481 0%, #004aad 100%);
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        /* CONTENT */
        .content {
            padding: 40px 30px;
            text-align: center;
        }
        
        /* SUCCESS ICON */
        .success-icon {
            font-size: 50px;
            color: #10b981; /* Hijau Sukses */
            background-color: #ecfdf5;
            width: 80px;
            height: 80px;
            line-height: 80px;
            border-radius: 50%;
            margin: 0 auto 20px auto;
            border: 2px solid #a7f3d0;
        }

        .greeting {
            font-size: 22px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 10px;
        }
        .sub-greeting {
            font-size: 16px;
            color: #6b7280;
            margin-bottom: 24px;
        }

        /* CARD STATUS */
        .status-card {
            background-color: #f0f9ff;
            border-left: 4px solid #0C4481;
            padding: 20px;
            text-align: left;
            margin-bottom: 24px;
            border-radius: 4px;
        }
        .status-title {
            color: #0C4481;
            font-weight: bold;
            font-size: 14px;
            text-transform: uppercase;
            margin-bottom: 5px;
            display: block;
        }
        .status-value {
            font-size: 18px;
            font-weight: 700;
            color: #111827;
        }

        .message {
            font-size: 15px;
            line-height: 1.6;
            color: #4b5563;
            margin-bottom: 30px;
        }

        /* BUTTON */
        .btn {
            background-color: #0C4481;
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
                    <h1>QuickFix Notifikasi</h1>
                </td>
            </tr>

            <tr>
                <td class="content">
                    <div class="success-icon">&#10004;</div>

                    <div class="greeting">Selamat, {{ $namaUser }}!</div>
                    <div class="sub-greeting">Akun Anda siap digunakan.</div>

                    <div class="status-card">
                        <span class="status-title">Status Verifikasi</span>
                        <span class="status-value">✅ DISETUJUI & AKTIF</span>
                    </div>

                    <p class="message">
                        Kabar gembira! Data diri dan dokumen Anda telah diperiksa dan <strong>disetujui</strong> oleh Admin. 
                        Sekarang Anda sudah resmi menjadi mitra teknisi QuickFix dan dapat mulai menerima pesanan dari pelanggan.
                    </p>

                    <p class="message">
                        Pastikan Anda selalu mengaktifkan aplikasi agar pesanan masuk dapat segera diambil. Selamat bekerja dan sukses selalu!
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