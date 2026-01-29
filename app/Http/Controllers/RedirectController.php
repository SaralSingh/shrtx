<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;
use Illuminate\Http\Request;

class RedirectController extends Controller
{
    public function handle(string $shortCode)
    {
        $shortUrl = ShortUrl::where('short_code', $shortCode)->first();

        if (!$shortUrl) {
            abort(404);
        }

        // Optional: handle expiry
        if ($shortUrl->expires_at && $shortUrl->expires_at->isPast()) {
            abort(404);
        }

        // Increment clicks (simple version)
        $shortUrl->increment('clicks');

        return redirect()->away($shortUrl->original_url);
    }
}
