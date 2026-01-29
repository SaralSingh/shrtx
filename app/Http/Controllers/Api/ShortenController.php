<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ShortUrlService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class ShortenController extends Controller
{
    public function store(Request $request, ShortUrlService $service)
    {
        try {
            $shortUrl = $service->create($request->input('url'));

            return response()->json([
                'short_url' => url($shortUrl->short_code),
                'code' => $shortUrl->short_code,
                'original_url' => $shortUrl->original_url,
            ], Response::HTTP_CREATED);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Invalid URL',
                'errors' => $e->errors(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
