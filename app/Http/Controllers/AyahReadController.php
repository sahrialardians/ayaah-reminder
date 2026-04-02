<?php

namespace App\Http\Controllers;

use App\Models\AyahRead;
use App\Services\SurahService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AyahReadController extends Controller
{
    public function __construct(
        protected SurahService $surahService
    ) {}

    /**
     * Display the dashboard with the latest reading and reading history.
     */
    public function index(): Response
    {
        $user = auth()->user();

        return Inertia::render('Dashboard', [
            'surahs' => $this->surahService->getSurahs(),
            'latestRead' => $user->latestAyahRead,
            'totalAyahs' => $user->ayahReads()->count(),
            'totalSurahs' => $user->ayahReads()->distinct('surah_number')->count('surah_number'),
        ]);
    }
/**
 * Display the full reading history with pagination.
 */
public function history(): Response
{
    $user = auth()->user();

    return Inertia::render('History', [
        'history' => $user->ayahReads()
            ->latest()
            ->paginate(20),
        'surahs' => $this->surahService->getSurahs(),
    ]);
}

/**
 * Store a new ayah reading entry.
...
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'surah_number' => ['required', 'integer', 'min:1', 'max:114'],
            'ayah_number' => ['required', 'integer', 'min:1'],
            'read_at' => ['nullable', 'date'],
        ]);

        $surah = $this->surahService->getSurah($validated['surah_number']);

        if (!$surah || $validated['ayah_number'] > $surah['numberOfAyahs']) {
            return back()->withErrors([
                'ayah_number' => "This Surah only has {$surah['numberOfAyahs']} ayahs.",
            ]);
        }

        auth()->user()->ayahReads()->create([
            'surah_number' => $validated['surah_number'],
            'ayah_number' => $validated['ayah_number'],
            'read_at' => $validated['read_at'] ?? now(),
        ]);

        return back()->with('status', 'Ayah saved successfully!');
    }
}
