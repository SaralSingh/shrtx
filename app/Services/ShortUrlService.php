<?php

namespace App\Services;

use App\Models\ShortUrl;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ShortUrlService
{
    public function create(string $originalUrl): ShortUrl
    {
        // 1. Business-level validation
        $this->validateUrl($originalUrl);

        // 2. Create DB record (ID needed)
        $shortUrl = ShortUrl::create([
            'original_url' => $originalUrl,
        ]);

        // 3. Generate deterministic short code
        $shortCode = Base62Encoder::encode($shortUrl->id);

        // 4. Update record with short code
        $shortUrl->update([
            'short_code' => $shortCode,
        ]);

        return $shortUrl;
    }

    private function validateUrl(string $url): void
    {
        $validator = Validator::make(
            ['url' => $url],
            [
                'url' => ['required', 'url'],
            ]
        );

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        // Enforce protocol rule (security)
        if (!preg_match('#^https?://#i', $url)) {
            throw ValidationException::withMessages([
                'url' => 'Only http and https URLs are allowed.',
            ]);
        }
    }
}
