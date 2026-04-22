Membuat PRD (Product Requirements Document) untuk sistem kasir retail model minimarket seperti Alfamidi atau Indomaret memiliki tantangan tersendiri, karena sistem ini **harus sangat cepat** dan mencakup banyak variasi transaksi (bukan sekadar jual barang, tapi juga layanan pembayaran).

Berikut adalah draf PRD yang disesuaikan dengan kebutuhan bisnis *high-volume retail*.

---

# Product Requirements Document (PRD): Sistem POS Retail Minimarket (Model Alfamidi)

## 1. Latar Belakang & Tujuan
**Tujuan:** Membangun sistem POS yang mampu menangani transaksi retail dengan kecepatan tinggi, mengelola promosi kompleks, serta melayani jasa pembayaran tagihan (PPOB) untuk memaksimalkan *revenue* per pelanggan.

**Metrik Keberhasilan:**
* **Transaction Speed:** Waktu rata-rata per transaksi < 60 detik.
* **Accuracy:** Selisih kas (kassier shortage) mendekati 0%.
* **Loyalty Conversion:** Persentase pelanggan yang menggunakan nomor member saat belanja.

---

## 2. Fitur Utama (Functional Requirements)

### A. Modul Penjualan Produk (Retail)
* **Scan & Search:** Integrasi *Barcode Scanner*. Dukungan *auto-complete* pencarian produk jika barcode rusak/tidak terbaca.
* **Manajemen Promosi Otomatis:**
    * *Multi-buy:* Beli 2 gratis 1, beli 2 diskon 20%.
    * *PWP (Purchase with Purchase):* Jika belanja > Rp 50.000, pelanggan berhak membeli barang tertentu dengan harga murah.
    * *Bundling:* Paket hemat (misal: Roti + Susu).
* **Fitur Retur/Void:** Kemampuan membatalkan item di tengah transaksi atau retur barang setelah struk tercetak (harus dengan *approval* manager/supervisor).

### B. Modul Layanan PPOB (Payment Point Online Bank)
*Ini adalah fitur krusial untuk minimarket Indonesia.*
* **Tagihan Rutin:** Integrasi untuk pembayaran PLN, BPJS, PDAM, dan cicilan motor (Finance).
* **Top-up Digital:** Isi ulang saldo e-wallet (GoPay, OVO, ShopeePay) dan pulsa/paket data.
* **Tiket & Voucher:** Penjualan tiket konser, travel, atau voucher game.
* **Validasi Real-time:** Sistem harus melakukan pengecekan ke *server* penyedia jasa untuk memverifikasi nomor tagihan sebelum pembayaran dilakukan.

### C. Modul Membership & Loyalitas
* **Input Member:** Input nomor HP sebagai ID member.
* **Poin & Stamp:** Otomatis menghitung perolehan poin setiap transaksi.
* **Redeem:** Menukar poin dengan potongan harga atau produk gratis saat di kasir.

### D. Modul Pembayaran (Payment Gateway)
* **Split Payment:** Mendukung kombinasi pembayaran (misal: Rp20.000 tunai, sisanya QRIS).
* **Multi-Channel QRIS:** Mendukung pembayaran QRIS statis maupun dinamis (dari layar kasir).
* **Integrasi EDC:** Sistem harus mengirim nominal otomatis ke mesin EDC agar kasir tidak salah ketik (mengurangi *human error*).

---

## 3. Alur Pengguna (User Flow) Transaksi Campuran

1.  **Start:** Kasir menyapa dan menanyakan kartu/nomor member.
2.  **Input:** Kasir melakukan *scan* produk fisik.
3.  **Cross-sell:** Sistem menampilkan pop-up "Tawarkan PWP" jika total belanja memenuhi syarat.
4.  **Extra Service:** Pelanggan ingin bayar tagihan listrik. Kasir menekan tombol "Layanan Lain", input nomor meter, validasi, dan tambahkan ke keranjang.
5.  **Summary:** Sistem menampilkan total belanja + tagihan.
6.  **Loyalty:** Pelanggan menukarkan poin untuk diskon.
7.  **Payment:** Kasir klik "Bayar". Sistem menampilkan pilihan metode (Tunai/QRIS/EDC).
8.  **Completion:** Struk tercetak. Jika pelanggan meminta, kirim *e-receipt* via WhatsApp/Email.

---

## 4. Kebutuhan Non-Fungsional (Critical Requirements)

* **Mode Offline (Hard Requirement):** Jika internet mati, sistem **harus tetap bisa** memproses transaksi retail tunai. Data disinkronisasi ke server pusat saat internet kembali aktif.
* **Log Audit:** Setiap tindakan sensitif (Void, Diskon manual, Retur) harus tercatat (siapa, kapan, alasan apa).
* **Latency:** Pencarian data produk harus < 200ms.
* **Hardware Interop:** Mendukung Printer Thermal, Scanner 2D (untuk QR), EDC via Serial/USB, dan Laci Kasir (Cash Drawer).

---

## 5. Pertimbangan Khusus (Constraints)

* **Keamanan Kas:** Laci kasir hanya boleh terbuka jika transaksi selesai atau ada otorisasi khusus (tidak bisa dibuka paksa via software tanpa alasan).
* **Shift Kerja:** Sistem harus memiliki fitur "Buka Kasir" (input modal awal) dan "Tutup Kasir" (rekap tunai, EDC, dan QRIS) yang wajib dilakukan di akhir shift.
* **Manajemen Stok:** Stok barang berkurang *real-time* agar sistem stok pusat (warehouse) bisa membuat *Purchase Order* otomatis jika barang hampir habis (*Auto-replenishment*).

---
