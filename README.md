# Videy Player — versi PHP (shared hosting)

## Cara pasang
1. Upload semua file ke `public_html` (termasuk folder `data/`).
2. Set permission folder `data/` ke 755. Kalau simpan judul gagal, coba 775.
3. Buka `https://domainanda.com/?mov=KODE` untuk memutar video.
4. Buka `https://domainanda.com/admin.php` untuk kelola judul.

## URL
- Pemutar: `/?mov=KODE`
- Paksa sumber: `/?mov=KODE&src=videy` (atau `slicedrive`, `videyc`, `aceimg`)
- API: `/api.php` (GET / POST / DELETE)
- Admin: `/admin.php`

## Catatan penting
- `admin.php` TIDAK berpassword. Lindungi dengan `.htpasswd` atau Cloudflare Access.
- Di Nginx, `.htaccess` diabaikan — blokir `/data/` lewat konfigurasi server.
- Butuh PHP 7.4+ (tidak perlu database).
