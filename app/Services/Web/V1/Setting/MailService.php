<?php

namespace App\Services\Web\V1\Setting;

use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class MailService
{
    /**
     * update Mail Config
     * @param mixed $data
     * @return void
     */
    public function updateMailConfig($data): void
    {
        try {
            $envContent = File::get(base_path('.env'));
            $lineBreak = "\n";

            $envContent = preg_replace([
                '/MAIL_MAILER=(.*)\s/',
                '/MAIL_HOST=(.*)\s/',
                '/MAIL_PORT=(.*)\s/',
                '/MAIL_USERNAME=(.*)\s/',
                '/MAIL_PASSWORD=(.*)\s/',
                '/MAIL_ENCRYPTION=(.*)\s/',
                '/MAIL_FROM_ADDRESS=(.*)\s/',
            ], [
                'MAIL_MAILER=' . $data['mail_mailer'] . $lineBreak,
                'MAIL_HOST=' . $data['mail_host'] . $lineBreak,
                'MAIL_PORT=' . $data['mail_port'] . $lineBreak,
                'MAIL_USERNAME=' . $data['mail_username'] . $lineBreak,
                'MAIL_PASSWORD=' . $data['mail_password'] . $lineBreak,
                'MAIL_ENCRYPTION=' . $data['mail_encryption'] . $lineBreak,
                'MAIL_FROM_ADDRESS=' . '"' . $data['mail_address'] . '"' . $lineBreak,
            ], $envContent);

            if ($envContent !== null) {
                File::put(base_path('.env'), $envContent);
            }
        } catch (Exception $e) {
            Log::error('App\Services\Web\V1\Setting\MailService:updateMailConfig', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}
