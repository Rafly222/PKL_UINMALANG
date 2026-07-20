<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class Recaptcha implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $secretKey = config('services.recaptcha.secret_key');
        $siteKey = config('services.recaptcha.site_key');
        
        if (empty($secretKey) || $siteKey === '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI') {
            // Bypass verification if key is not configured or using Google's test key (for local dev/testing)
            return;
        }

        if (empty($value)) {
            $fail('Verifikasi reCAPTCHA wajib diisi.');
            return;
        }

        $http = Http::asForm();
        if (config('app.env') === 'local') {
            $http = $http->withoutVerifying();
        }

        $response = $http->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $secretKey,
            'response' => $value,
        ]);

        if ($response->failed() || !$response->json('success') || $response->json('score') < 0.5) {
            $fail('Verifikasi reCAPTCHA gagal. Anda terdeteksi sebagai robot/spam.');
        }
    }
}
