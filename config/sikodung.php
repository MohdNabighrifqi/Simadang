<?php
// config/sikodung.php
return [
    // Lokasi sesuai tabel lokasi di database (id 1-15)
    'lokasi' => [
        'Pantai Trikora',
        'Perairan Bintan',
        'Teluk Sebong',
        'Pulau Penyengat',
        'Perairan Dompak',
        'Pangkil Sidi',
        'Pangkil',
        'Penaga',
        'Pengudang',
        'Busung',
        'Berakit',
        'Pantai Dugong',
        'Pantai Kelam Pagi',
        'Mangkik Kecil',
        'Lainnya',
    ],

    // Zona peta — koordinat per lokasi untuk Leaflet
    'zona_peta' => [
        ['nama'=>'Pangkil Sidi',     'lat'=>0.760720,'lng'=>104.563686,'penampakan'=>8, 'status'=>'Terancam','update'=>'2022','deskripsi'=>'Perairan Pangkil Sidi, beberapa kasus mati tertangkap.'],
        ['nama'=>'Pangkil',          'lat'=>0.856508,'lng'=>104.322095,'penampakan'=>6, 'status'=>'Sedang',  'update'=>'2024','deskripsi'=>'Perairan Pangkil di barat Bintan.'],
        ['nama'=>'Penaga',           'lat'=>1.014194,'lng'=>104.400493,'penampakan'=>7, 'status'=>'Terancam','update'=>'2020','deskripsi'=>'Perairan Penaga, terdapat kasus by catch dead.'],
        ['nama'=>'Pengudang',        'lat'=>1.228335,'lng'=>104.526900,'penampakan'=>5, 'status'=>'Sedang',  'update'=>'2024','deskripsi'=>'Pantai Pengudang di utara Bintan.'],
        ['nama'=>'Busung',           'lat'=>0.971092,'lng'=>104.234869,'penampakan'=>9, 'status'=>'Sedang',  'update'=>'2024','deskripsi'=>'Perairan Busung di barat Bintan.'],
        ['nama'=>'Berakit',          'lat'=>1.222700,'lng'=>104.531800,'penampakan'=>6, 'status'=>'Sedang',  'update'=>'2023','deskripsi'=>'Perairan Berakit di utara Bintan.'],
        ['nama'=>'Pantai Dugong',    'lat'=>1.144900,'lng'=>104.613100,'penampakan'=>4, 'status'=>'Baik',    'update'=>'2017','deskripsi'=>'Pantai Dugong di timur laut Bintan.'],
        ['nama'=>'Pantai Kelam Pagi','lat'=>0.826900,'lng'=>104.485900,'penampakan'=>6, 'status'=>'Terancam','update'=>'2023','deskripsi'=>'Pantai Kelam Pagi, beberapa kasus by catch dead.'],
        ['nama'=>'Mangkik Kecil',    'lat'=>0.921900,'lng'=>104.711600,'penampakan'=>7, 'status'=>'Sedang',  'update'=>'2024','deskripsi'=>'Perairan Mangkik Kecil di timur Bintan.'],
    ],

    'per_page' => env('SIKODUNG_PER_PAGE', 15),
];
