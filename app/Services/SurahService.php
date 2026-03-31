<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;

class SurahService
{
    private const API_BASE_URL = 'https://api.alquran.cloud/v1';

    /**
     * Get the list of all Surahs.
     */
    public function getSurahs(): Collection
    {
        $data = Cache::get('surahs_list');

        if (!$data) {
            $response = Http::get(self::API_BASE_URL . '/surah');

            if ($response->successful()) {
                $data = $response->json('data') ?? [];
                if (!empty($data)) {
                    Cache::put('surahs_list', $data, 86400);
                }
            }
        }

        return collect($data ?? []);
    }

    /**
     * Get details for a specific Surah (including ayah count).
     */
    public function getSurah(int $number): ?array
    {
        return Cache::remember("surah_{$number}", 86400, function () use ($number) {
            $response = Http::get(self::API_BASE_URL . "/surah/{$number}");

            if ($response->failed()) {
                return null;
            }

            return $response->json('data');
        });
    }
}
