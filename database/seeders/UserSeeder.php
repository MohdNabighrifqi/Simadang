<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'id'         => 1,
                'name'       => 'Admin Konservasi',
                'email'      => 'admin@dugong.id',
                'password'   => '$2y$12$plbx7av5i0QHWkJkHAYhKuJYPEWx5tYatkFLW2iuHu3zxg8FE6FtW',
                'role'       => 'admin',
                'daerah'     => 'Tanjungpinang',
                'created_at' => '2026-04-24 06:06:46',
                'updated_at' => '2026-04-24 06:06:46',
            ],
            [
                'id'         => 2,
                'name'       => 'Siti Rahayu',
                'email'      => 'user@dugong.id',
                'password'   => '$2y$12$MAzvhgy8k1MKS1w7gcJxO.v8jImjyR8MCS6nFMXsnodRLgxWMSl26',
                'role'       => 'warga',
                'daerah'     => 'Batu 10',
                'created_at' => '2026-04-24 06:06:46',
                'updated_at' => '2026-04-24 06:06:46',
            ],
            [
                'id'         => 3,
                'name'       => 'Ahmad Fauzi',
                'email'      => 'ahmad@gmail.com',
                'password'   => '$2y$12$UZsFxOedZO8Iqe3nfBB4QOmP/fKVdczCCs9D3u8fG4fleExLFYUe.',
                'role'       => 'warga',
                'daerah'     => 'Tanjung Ayun',
                'created_at' => '2026-04-24 06:06:46',
                'updated_at' => '2026-04-24 06:06:46',
            ],
            [
                'id'         => 4,
                'name'       => 'Budi Santoso',
                'email'      => 'budi@gmail.com',
                'password'   => '$2y$12$yFVpzyXpYQjqJT2YDTtrouLGyWm.pCKreoxSesC/940A01bJ3bMcS',
                'role'       => 'warga',
                'daerah'     => 'Tanjungpinang Kota',
                'created_at' => '2026-04-24 06:06:47',
                'updated_at' => '2026-04-24 06:06:47',
            ],
            [
                'id'         => 5,
                'name'       => 'Rina Wati',
                'email'      => 'rina@gmail.com',
                'password'   => '$2y$12$/8EWGxMEITDNylR.U6Kqne7K.L7VKf9rP9TPEo/6KjGAos.T7edBe',
                'role'       => 'warga',
                'daerah'     => 'Bukit Bestari',
                'created_at' => '2026-04-24 06:06:47',
                'updated_at' => '2026-04-24 06:06:47',
            ],
        ]);
    }
}
