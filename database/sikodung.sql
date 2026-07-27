-- ============================================================
-- IMPORT DATA dugong_bintan ke tabel laporan sikodung
-- Jalankan di phpMyAdmin → database sikodung → tab SQL
-- ============================================================

-- Step 1: Update lokasi options di tabel laporan yang sudah ada
-- (opsional, data lama tetap ada)

-- Step 2: Insert 58 data dari dugong_bintan ke tabel laporan
-- Mapping:
--   condition 'Live'           → kondisi_dugong 'hidup'
--   condition 'Stranded Dead'  → kondisi_dugong 'mati_terdampar'
--   condition 'By Catch Dead'  → kondisi_dugong 'mati_tertangkap'
--   XCoord  → longitude
--   YCoord  → latitude
--   Tahun   → tanggal (1 Januari tahun tersebut)
--   Sumber  → catatan (untuk dokumentasi sumber data)

INSERT INTO `laporan`
  (`kode`, `user_id`, `jenis`, `kondisi_dugong`, `tanggal`, `waktu`,
   `lokasi`, `jumlah_dugong`, `latitude`, `longitude`,
   `deskripsi`, `nama_pelapor`, `kontak`, `foto`, `status`, `catatan`,
   `created_at`, `updated_at`)
VALUES

-- Busung
('DB-2024-001', NULL, 'dugong', 'mati_terdampar',  '2024-01-01', NULL, 'Busung',           1,  0.971092, 104.234869, 'Data pengamatan dugong dari sumber penelitian dan kuesioner.',  'Kuesioner', NULL, NULL, 'terverifikasi', 'Sumber: Kuesioner',        NOW(), NOW()),
('DB-2020-002', NULL, 'dugong', 'hidup',            '2020-01-01', NULL, 'Busung',           1,  0.989714, 104.278563, 'Data pengamatan dugong dari sumber penelitian dan kuesioner.',  'Kuesioner', NULL, NULL, 'terverifikasi', 'Sumber: Kuesioner',        NOW(), NOW()),
('DB-2017-003', NULL, 'dugong', 'hidup',            '2017-01-01', NULL, 'Busung',           1,  0.993495, 104.277721, 'Data pengamatan dugong dari sumber penelitian dan kuesioner.',  'Kuesioner', NULL, NULL, 'terverifikasi', 'Sumber: Kuesioner',        NOW(), NOW()),
('DB-2010-004', NULL, 'dugong', 'hidup',            '2010-01-01', NULL, 'Busung',           1,  0.981287, 104.265619, 'Data pengamatan dugong dari sumber penelitian dan kuesioner.',  'BPSPL',     NULL, NULL, 'terverifikasi', 'Sumber: BPSPL',            NOW(), NOW()),
('DB-2018-005', NULL, 'dugong', 'hidup',            '2018-01-01', NULL, 'Busung',           1,  0.96123,  104.270146, 'Data pengamatan dugong dari sumber penelitian dan kuesioner.',  'BPSPL',     NULL, NULL, 'terverifikasi', 'Sumber: BPSPL',            NOW(), NOW()),
('DB-2017-006', NULL, 'dugong', 'hidup',            '2017-01-01', NULL, 'Busung',           1,  0.994612, 104.220682, 'Data pengamatan dugong dari sumber penelitian dan kuesioner.',  'BPSPL',     NULL, NULL, 'terverifikasi', 'Sumber: BPSPL',            NOW(), NOW()),
('DB-2023-007', NULL, 'dugong', 'hidup',            '2023-01-01', NULL, 'Busung',           1,  1.004254, 104.242719, 'Data pengamatan dugong dari sumber penelitian dan kuesioner.',  'Kuesioner', NULL, NULL, 'terverifikasi', 'Sumber: Kuesioner',        NOW(), NOW()),
('DB-2022-008', NULL, 'dugong', 'hidup',            '2022-01-01', NULL, 'Busung',           1,  0.948953, 104.25052,  'Data pengamatan dugong dari sumber penelitian dan kuesioner.',  'Kuesioner', NULL, NULL, 'terverifikasi', 'Sumber: Kuesioner',        NOW(), NOW()),
('DB-2024-009', NULL, 'dugong', 'mati_terdampar',   '2024-01-01', NULL, 'Busung',           1,  0.999095, 104.28918,  'Data pengamatan dugong dari sumber penelitian dan kuesioner.',  'BPSPL',     NULL, NULL, 'terverifikasi', 'Sumber: BPSPL',            NOW(), NOW()),

-- Pengudang
('DB-2024-010', NULL, 'dugong', 'hidup',            '2024-01-01', NULL, 'Pengudang',        1,  1.228335, 104.5269,   'Data pengamatan dugong dari sumber penelitian dan kuesioner.',  'Kuesioner', NULL, NULL, 'terverifikasi', 'Sumber: Kuesioner',        NOW(), NOW()),
('DB-2012-011', NULL, 'dugong', 'mati_terdampar',   '2012-01-01', NULL, 'Pengudang',        1,  1.193414, 104.501257, 'Data pengamatan dugong dari sumber penelitian dan kuesioner.',  'Kuesioner', NULL, NULL, 'terverifikasi', 'Sumber: Kuesioner',        NOW(), NOW()),
('DB-2019-012', NULL, 'dugong', 'mati_terdampar',   '2019-01-01', NULL, 'Pengudang',        1,  1.195128, 104.51424,  'Data pengamatan dugong dari sumber penelitian dan kuesioner.',  'Kuesioner', NULL, NULL, 'terverifikasi', 'Sumber: Kuesioner',        NOW(), NOW()),
('DB-2015-013', NULL, 'dugong', 'hidup',            '2015-01-01', NULL, 'Pengudang',        1,  1.205445, 104.523216, 'Data pengamatan dugong dari sumber penelitian dan kuesioner.',  'Zaki M et al 2022', NULL, NULL, 'terverifikasi', 'Sumber: Zaki_M_et_al_2022', NOW(), NOW()),
('DB-2021-014', NULL, 'dugong', 'hidup',            '2021-01-01', NULL, 'Pengudang',        1,  1.208608, 104.530299, 'Data pengamatan dugong dari sumber penelitian dan kuesioner.',  'Kuesioner', NULL, NULL, 'terverifikasi', 'Sumber: Kuesioner',        NOW(), NOW()),
('DB-2008-015', NULL, 'dugong', 'hidup',            '2008-01-01', NULL, 'Pengudang',        1,  1.188706, 104.501454, 'Data pengamatan dugong dari sumber penelitian dan kuesioner.',  'Zaki M et al 2022', NULL, NULL, 'terverifikasi', 'Sumber: Zaki_M_et_al_2022', NOW(), NOW()),

-- Pangkil Sidi
('DB-2020-016', NULL, 'dugong', 'hidup',            '2020-01-01', NULL, 'Pangkil Sidi',     1,  0.76072,  104.563686, 'Data pengamatan dugong dari sumber penelitian dan kuesioner.',  'BPSPL',     NULL, NULL, 'terverifikasi', 'Sumber: BPSPL',            NOW(), NOW()),
('DB-2019-017', NULL, 'dugong', 'hidup',            '2019-01-01', NULL, 'Pangkil Sidi',     1,  0.77289,  104.551491, 'Data pengamatan dugong dari sumber penelitian dan kuesioner.',  'Zaki M et al 2022', NULL, NULL, 'terverifikasi', 'Sumber: Zaki_M_et_al_2022', NOW(), NOW()),
('DB-2019-018', NULL, 'dugong', 'mati_terdampar',   '2019-01-01', NULL, 'Pangkil Sidi',     1,  0.762909, 104.574998, 'Data pengamatan dugong dari sumber penelitian dan kuesioner.',  'Zaki M et al 2022', NULL, NULL, 'terverifikasi', 'Sumber: Zaki_M_et_al_2022', NOW(), NOW()),
('DB-2018-019', NULL, 'dugong', 'mati_tertangkap',  '2018-01-01', NULL, 'Pangkil Sidi',     1,  0.746627, 104.534946, 'Data pengamatan dugong dari sumber penelitian dan kuesioner.',  'Kuesioner', NULL, NULL, 'terverifikasi', 'Sumber: Kuesioner',        NOW(), NOW()),
('DB-2018-020', NULL, 'dugong', 'mati_terdampar',   '2018-01-01', NULL, 'Pangkil Sidi',     1,  0.736989, 104.556624, 'Data pengamatan dugong dari sumber penelitian dan kuesioner.',  'BPSPL',     NULL, NULL, 'terverifikasi', 'Sumber: BPSPL',            NOW(), NOW()),
('DB-2022-021', NULL, 'dugong', 'hidup',            '2022-01-01', NULL, 'Pangkil Sidi',     1,  0.783065, 104.515465, 'Data pengamatan dugong dari sumber penelitian dan kuesioner.',  'BPSPL',     NULL, NULL, 'terverifikasi', 'Sumber: BPSPL',            NOW(), NOW()),
('DB-2015-022', NULL, 'dugong', 'hidup',            '2015-01-01', NULL, 'Pangkil Sidi',     1,  0.770223, 104.562659, 'Data pengamatan dugong dari sumber penelitian dan kuesioner.',  'Kuesioner', NULL, NULL, 'terverifikasi', 'Sumber: Kuesioner',        NOW(), NOW()),
('DB-2019-023', NULL, 'dugong', 'mati_tertangkap',  '2019-01-01', NULL, 'Pangkil Sidi',     1,  0.783065, 104.515465, 'Data pengamatan dugong dari sumber penelitian dan kuesioner.',  'Kuesioner', NULL, NULL, 'terverifikasi', 'Sumber: Kuesioner',        NOW(), NOW()),

-- Pangkil
('DB-2024-024', NULL, 'dugong', 'hidup',            '2024-01-01', NULL, 'Pangkil',          1,  0.856508, 104.322095, 'Data pengamatan dugong dari sumber penelitian dan kuesioner.',  'Kuesioner', NULL, NULL, 'terverifikasi', 'Sumber: Kuesioner',        NOW(), NOW()),
('DB-2010-025', NULL, 'dugong', 'hidup',            '2010-01-01', NULL, 'Pangkil',          1,  0.846922, 104.353361, 'Data pengamatan dugong dari sumber penelitian dan kuesioner.',  'Kuesioner', NULL, NULL, 'terverifikasi', 'Sumber: Kuesioner',        NOW(), NOW()),
('DB-2013-026', NULL, 'dugong', 'hidup',            '2013-01-01', NULL, 'Pangkil',          1,  0.787137, 104.36407,  'Data pengamatan dugong dari sumber penelitian dan kuesioner.',  'Kuesioner', NULL, NULL, 'terverifikasi', 'Sumber: Kuesioner',        NOW(), NOW()),
('DB-2024-027', NULL, 'dugong', 'hidup',            '2024-01-01', NULL, 'Pangkil',          1,  0.887949, 104.348784, 'Data pengamatan dugong dari sumber penelitian dan kuesioner.',  'Zaki M et al 2022', NULL, NULL, 'terverifikasi', 'Sumber: Zaki_M_et_al_2022', NOW(), NOW()),
('DB-2011-028', NULL, 'dugong', 'hidup',            '2011-01-01', NULL, 'Pangkil',          1,  0.786782, 104.356678, 'Data pengamatan dugong dari sumber penelitian dan kuesioner.',  'BPSPL',     NULL, NULL, 'terverifikasi', 'Sumber: BPSPL',            NOW(), NOW()),
('DB-2019-029', NULL, 'dugong', 'mati_tertangkap',  '2019-01-01', NULL, 'Pangkil',          1,  0.847568, 104.326231, 'Data pengamatan dugong dari sumber penelitian dan kuesioner.',  'Kuesioner', NULL, NULL, 'terverifikasi', 'Sumber: Kuesioner',        NOW(), NOW()),

-- Penaga
('DB-2017-030', NULL, 'dugong', 'mati_terdampar',   '2017-01-01', NULL, 'Penaga',           1,  1.014194, 104.400493, 'Data pengamatan dugong dari sumber penelitian dan kuesioner.',  'Kuesioner', NULL, NULL, 'terverifikasi', 'Sumber: Kuesioner',        NOW(), NOW()),
('DB-2017-031', NULL, 'dugong', 'mati_tertangkap',  '2017-01-01', NULL, 'Penaga',           1,  1.053805, 104.41473,  'Data pengamatan dugong dari sumber penelitian dan kuesioner.',  'BPSPL',     NULL, NULL, 'terverifikasi', 'Sumber: BPSPL',            NOW(), NOW()),
('DB-2011-032', NULL, 'dugong', 'hidup',            '2011-01-01', NULL, 'Penaga',           1,  1.027288, 104.432817, 'Data pengamatan dugong dari sumber penelitian dan kuesioner.',  'Kuesioner', NULL, NULL, 'terverifikasi', 'Sumber: Kuesioner',        NOW(), NOW()),
('DB-2016-033', NULL, 'dugong', 'hidup',            '2016-01-01', NULL, 'Penaga',           1,  1.034517, 104.437337, 'Data pengamatan dugong dari sumber penelitian dan kuesioner.',  'BPSPL',     NULL, NULL, 'terverifikasi', 'Sumber: BPSPL',            NOW(), NOW()),
('DB-2020-034', NULL, 'dugong', 'hidup',            '2020-01-01', NULL, 'Penaga',           1,  1.029887, 104.430384, 'Data pengamatan dugong dari sumber penelitian dan kuesioner.',  'Kuesioner', NULL, NULL, 'terverifikasi', 'Sumber: Kuesioner',        NOW(), NOW()),
('DB-2020-035', NULL, 'dugong', 'mati_tertangkap',  '2020-01-01', NULL, 'Penaga',           1,  1.050385, 104.407548, 'Data pengamatan dugong dari sumber penelitian dan kuesioner.',  'Zaki M et al 2022', NULL, NULL, 'terverifikasi', 'Sumber: Zaki_M_et_al_2022', NOW(), NOW()),
('DB-2011-036', NULL, 'dugong', 'mati_tertangkap',  '2011-01-01', NULL, 'Penaga',           1,  1.0055,   104.417316, 'Data pengamatan dugong dari sumber penelitian dan kuesioner.',  'BPSPL',     NULL, NULL, 'terverifikasi', 'Sumber: BPSPL',            NOW(), NOW()),

-- Pantai Dugong
('DB-2010-037', NULL, 'dugong', 'hidup',            '2010-01-01', NULL, 'Pantai Dugong',    1,  1.140203, 104.606993, 'Data pengamatan dugong dari sumber penelitian dan kuesioner.',  'BPSPL',     NULL, NULL, 'terverifikasi', 'Sumber: BPSPL',            NOW(), NOW()),
('DB-2010-038', NULL, 'dugong', 'hidup',            '2010-01-01', NULL, 'Pantai Dugong',    1,  1.135357, 104.605135, 'Data pengamatan dugong dari sumber penelitian dan kuesioner.',  'BPSPL',     NULL, NULL, 'terverifikasi', 'Sumber: BPSPL',            NOW(), NOW()),
('DB-2017-039', NULL, 'dugong', 'hidup',            '2017-01-01', NULL, 'Pantai Dugong',    1,  1.140708, 104.623689, 'Data pengamatan dugong dari sumber penelitian dan kuesioner.',  'Kuesioner', NULL, NULL, 'terverifikasi', 'Sumber: Kuesioner',        NOW(), NOW()),
('DB-2017-040', NULL, 'dugong', 'mati_terdampar',   '2017-01-01', NULL, 'Pantai Dugong',    1,  1.162671, 104.61671,  'Data pengamatan dugong dari sumber penelitian dan kuesioner.',  'Kuesioner', NULL, NULL, 'terverifikasi', 'Sumber: Kuesioner',        NOW(), NOW()),

-- Berakit
('DB-2023-041', NULL, 'dugong', 'hidup',            '2023-01-01', NULL, 'Berakit',          1,  1.207059, 104.543368, 'Data pengamatan dugong dari sumber penelitian dan kuesioner.',  'Kuesioner', NULL, NULL, 'terverifikasi', 'Sumber: Kuesioner',        NOW(), NOW()),
('DB-2009-042', NULL, 'dugong', 'mati_terdampar',   '2009-01-01', NULL, 'Berakit',          1,  1.209224, 104.544734, 'Data pengamatan dugong dari sumber penelitian dan kuesioner.',  'Kuesioner', NULL, NULL, 'terverifikasi', 'Sumber: Kuesioner',        NOW(), NOW()),
('DB-2016-043', NULL, 'dugong', 'mati_tertangkap',  '2016-01-01', NULL, 'Berakit',          1,  1.225728, 104.544362, 'Data pengamatan dugong dari sumber penelitian dan kuesioner.',  'BPSPL',     NULL, NULL, 'terverifikasi', 'Sumber: BPSPL',            NOW(), NOW()),
('DB-2014-044', NULL, 'dugong', 'mati_tertangkap',  '2014-01-01', NULL, 'Berakit',          1,  1.205631, 104.509542, 'Data pengamatan dugong dari sumber penelitian dan kuesioner.',  'Zaki M et al 2022', NULL, NULL, 'terverifikasi', 'Sumber: Zaki_M_et_al_2022', NOW(), NOW()),
('DB-2016-045', NULL, 'dugong', 'hidup',            '2016-01-01', NULL, 'Berakit',          1,  1.23037,  104.525475, 'Data pengamatan dugong dari sumber penelitian dan kuesioner.',  'Kuesioner', NULL, NULL, 'terverifikasi', 'Sumber: Kuesioner',        NOW(), NOW()),
('DB-2019-046', NULL, 'dugong', 'mati_tertangkap',  '2019-01-01', NULL, 'Berakit',          1,  1.242206, 104.528229, 'Data pengamatan dugong dari sumber penelitian dan kuesioner.',  'BPSPL',     NULL, NULL, 'terverifikasi', 'Sumber: BPSPL',            NOW(), NOW()),

-- Pantai Kelam Pagi
('DB-2009-047', NULL, 'dugong', 'hidup',            '2009-01-01', NULL, 'Pantai Kelam Pagi',1,  0.806847, 104.501084, 'Data pengamatan dugong dari sumber penelitian dan kuesioner.',  'BPSPL',     NULL, NULL, 'terverifikasi', 'Sumber: BPSPL',            NOW(), NOW()),
('DB-2009-048', NULL, 'dugong', 'mati_tertangkap',  '2009-01-01', NULL, 'Pantai Kelam Pagi',1,  0.824786, 104.48042,  'Data pengamatan dugong dari sumber penelitian dan kuesioner.',  'Kuesioner', NULL, NULL, 'terverifikasi', 'Sumber: Kuesioner',        NOW(), NOW()),
('DB-2016-049', NULL, 'dugong', 'mati_tertangkap',  '2016-01-01', NULL, 'Pantai Kelam Pagi',1,  0.848529, 104.465695, 'Data pengamatan dugong dari sumber penelitian dan kuesioner.',  'Kuesioner', NULL, NULL, 'terverifikasi', 'Sumber: Kuesioner',        NOW(), NOW()),
('DB-2017-050', NULL, 'dugong', 'mati_terdampar',   '2017-01-01', NULL, 'Pantai Kelam Pagi',1,  0.828452, 104.438956, 'Data pengamatan dugong dari sumber penelitian dan kuesioner.',  'Kuesioner', NULL, NULL, 'terverifikasi', 'Sumber: Kuesioner',        NOW(), NOW()),
('DB-2019-051', NULL, 'dugong', 'mati_tertangkap',  '2019-01-01', NULL, 'Pantai Kelam Pagi',1,  0.828144, 104.496353, 'Data pengamatan dugong dari sumber penelitian dan kuesioner.',  'Zaki M et al 2022', NULL, NULL, 'terverifikasi', 'Sumber: Zaki_M_et_al_2022', NOW(), NOW()),
('DB-2023-052', NULL, 'dugong', 'hidup',            '2023-01-01', NULL, 'Pantai Kelam Pagi',1,  0.825179, 104.492484, 'Data pengamatan dugong dari sumber penelitian dan kuesioner.',  'BPSPL',     NULL, NULL, 'terverifikasi', 'Sumber: BPSPL',            NOW(), NOW()),

-- Mangkik Kecil
('DB-2013-053', NULL, 'dugong', 'hidup',            '2013-01-01', NULL, 'Mangkik Kecil',    1,  0.8989,   104.714687, 'Data pengamatan dugong dari sumber penelitian dan kuesioner.',  'Zaki M et al 2022', NULL, NULL, 'terverifikasi', 'Sumber: Zaki_M_et_al_2022', NOW(), NOW()),
('DB-2008-054', NULL, 'dugong', 'hidup',            '2008-01-01', NULL, 'Mangkik Kecil',    1,  0.913005, 104.679716, 'Data pengamatan dugong dari sumber penelitian dan kuesioner.',  'Zaki M et al 2022', NULL, NULL, 'terverifikasi', 'Sumber: Zaki_M_et_al_2022', NOW(), NOW()),
('DB-2015-055', NULL, 'dugong', 'mati_terdampar',   '2015-01-01', NULL, 'Mangkik Kecil',    1,  0.928606, 104.694827, 'Data pengamatan dugong dari sumber penelitian dan kuesioner.',  'BPSPL',     NULL, NULL, 'terverifikasi', 'Sumber: BPSPL',            NOW(), NOW()),
('DB-2012-056', NULL, 'dugong', 'mati_terdampar',   '2012-01-01', NULL, 'Mangkik Kecil',    1,  0.941696, 104.715984, 'Data pengamatan dugong dari sumber penelitian dan kuesioner.',  'Kuesioner', NULL, NULL, 'terverifikasi', 'Sumber: Kuesioner',        NOW(), NOW()),
('DB-2017-057', NULL, 'dugong', 'mati_terdampar',   '2017-01-01', NULL, 'Mangkik Kecil',    1,  0.914097, 104.771429, 'Data pengamatan dugong dari sumber penelitian dan kuesioner.',  'Kuesioner', NULL, NULL, 'terverifikasi', 'Sumber: Kuesioner',        NOW(), NOW()),
('DB-2023-058', NULL, 'dugong', 'hidup',            '2023-01-01', NULL, 'Mangkik Kecil',    1,  0.937666, 104.708563, 'Data pengamatan dugong dari sumber penelitian dan kuesioner.',  'BPSPL',     NULL, NULL, 'terverifikasi', 'Sumber: BPSPL',            NOW(), NOW()),
('DB-2024-059', NULL, 'dugong', 'mati_terdampar',   '2024-01-01', NULL, 'Mangkik Kecil',    1,  0.913031, 104.733024, 'Data pengamatan dugong dari sumber penelitian dan kuesioner.',  'BPSPL',     NULL, NULL, 'terverifikasi', 'Sumber: BPSPL',            NOW(), NOW());

-- Step 3: Verifikasi
SELECT
  COUNT(*) AS total_laporan,
  SUM(CASE WHEN kode LIKE 'DB-%' THEN 1 ELSE 0 END) AS dari_dugong_bintan,
  SUM(CASE WHEN kode NOT LIKE 'DB-%' THEN 1 ELSE 0 END) AS laporan_masyarakat
FROM laporan;

-- Breakdown kondisi
SELECT kondisi_dugong, COUNT(*) AS jumlah
FROM laporan
WHERE kode LIKE 'DB-%'
GROUP BY kondisi_dugong;
