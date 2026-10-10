# Traceability Matrix

| ID | Kebutuhan | Use Case | Implementasi | Test |
|---|---|---|---|---|
| CAM-01 | Ajukan kausa | UC-INS-01 | route/action/model | feature |
| CAM-02 | Minta perbaikan | UC-ADM-02 | verification/history | feature |
| DON-01 | Donasi | UC-DON-03 | donation/adapter | integration |
| REP-01 | Transparansi | UC-INS-04 | fund report | feature |
| ADM-01 | Verifikasi Legalitas OPD & Yayasan | UC-ADM-03 | AdminDashboardController@legalitas | feature |
| ADM-02 | Verifikasi LPJ Dana | UC-ADM-04 | AdminDashboardController@laporan | feature |
| ADM-03 | Monitoring Kausa Aktif | UC-ADM-05 | AdminDashboardController@kausaAktif | feature |
| ADM-04 | Rekap Mutasi Donasi Kas Masuk | UC-ADM-06 | AdminDashboardController@donasi | feature |
| PUB-01 | Portal Transparansi Publik Real-time | UC-PUB-02 | TransparansiController@index | integration |
| NOT-01 | Pengiriman notifikasi in-app pada perubahan status kausa & LPJ | UC-NOT-01 | NotifikasiService::kirim | NotifikasiTest |
| NOT-02 | Tampilan daftar notifikasi dan pembedaan status baca | UC-NOT-02 | NotifikasiController@index | NotifikasiTest |
| NOT-03 | Penandaan notifikasi telah dibaca (single & mark all) | UC-NOT-03 | NotifikasiController@markRead & markAllRead | NotifikasiTest |
| NOT-04 | Badge counter notifikasi belum dibaca di navbar/layout | UC-NOT-04 | layouts/dashboard-instansi, layouts/navigation | NotifikasiTest |
| DON-04 | Listener Webhook Payment Gateway (Midtrans) & idempotensi | UC-DON-04 | MidtransWebhookController@handle | WebhookMidtransTest |
| PAY-01 | Opsi donasi transfer manual ke rekening Kasda Tulungagung | UC-DON-05 | kausa.show, donasi.bayar | PembayaranManualTest |
| PAY-02 | Unggah bukti transfer manual (jpg/png/pdf max 2MB) | UC-DON-06 | DonasiController@uploadBukti | PembayaranManualTest |
| PAY-03 | Antrean kurasi bukti transfer manual di dashboard admin | UC-ADM-07 | AdminDashboardController@donasi | PembayaranManualTest |
| PAY-04 | Persetujuan admin mutasi manual & penambahan saldo kausa | UC-ADM-08 | AdminDashboardController@approveManual | PembayaranManualTest |
| PAY-05 | Penolakan bukti manual dengan catatan alasan wajib | UC-ADM-09 | AdminDashboardController@rejectManual | PembayaranManualTest |
| DON-05 | Tab Transparansi menampilkan data riil LogTransparansi | UC-PUB-03 | kausa.show | KausaShowDetailTest |
| DON-06 | Masking donatur anonim sebagai 'Hamba Allah' | UC-PUB-04 | kausa.show | KausaShowDetailTest |
| AJU-03 | Unggah multi-foto galeri kegiatan lapangan kausa | UC-INS-02 | KausaController@store, SimpanKausaRequest | KausaShowDetailTest |
