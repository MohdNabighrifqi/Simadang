<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Command sementara untuk debug kenapa email tidak terkirim di production.
// Hapus lagi setelah masalah mail selesai didiagnosis.
Artisan::command('mail:test {email}', function (string $email) {
    $this->info('mailer   : ' . config('mail.default'));
    $this->info('host     : ' . config('mail.mailers.smtp.host'));
    $this->info('port     : ' . config('mail.mailers.smtp.port'));
    $this->info('scheme   : ' . config('mail.mailers.smtp.scheme'));
    $this->info('username : ' . config('mail.mailers.smtp.username'));
    $this->info('from     : ' . config('mail.from.address'));

    try {
        Mail::raw('Test email dari SiKoDung production.', function ($msg) use ($email) {
            $msg->to($email)->subject('Test Mail SiKoDung');
        });
        $this->info('BERHASIL: tidak ada exception, cek inbox/spam tujuan.');
    } catch (\Throwable $e) {
        $this->error('GAGAL: ' . get_class($e));
        $this->error('Pesan : ' . $e->getMessage());
        if ($prev = $e->getPrevious()) {
            $this->error('Sebab : ' . get_class($prev) . ' - ' . $prev->getMessage());
        }
    }
})->purpose('Debug sementara: tes kirim email langsung dari production');
