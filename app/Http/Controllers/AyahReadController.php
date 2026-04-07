<?php

namespace App\Http\Controllers;

use App\Services\SurahService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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

        // Calculate Streak
        $datesRead = $user->ayahReads()
            ->selectRaw('DATE(read_at) as date')
            ->distinct()
            ->orderBy('date', 'desc')
            ->pluck('date')
            ->toArray();

        $streak = 0;
        $today = now()->format('Y-m-d');
        $yesterday = now()->subDay()->format('Y-m-d');

        $currentDate = $today;
        if (in_array($today, $datesRead) || in_array($yesterday, $datesRead)) {
            $currentDate = in_array($today, $datesRead) ? $today : $yesterday;
            foreach ($datesRead as $date) {
                if ($date === $currentDate) {
                    $streak++;
                    $currentDate = Carbon::parse($currentDate)->subDay()->format('Y-m-d');
                } else {
                    break;
                }
            }
        }

        // Calculate Heatmap (last 30 days)
        $thirtyDaysAgo = now()->subDays(29)->startOfDay();
        $activity = $user->ayahReads()
            ->where('read_at', '>=', $thirtyDaysAgo)
            ->selectRaw('DATE(read_at) as date, SUM(end_ayah - start_ayah + 1) as count')
            ->groupBy('date')
            ->pluck('count', 'date')
            ->toArray();

        $heatmap = [];
        for ($i = 29; $i >= 0; $i--) {
            $dateString = now()->subDays($i)->format('Y-m-d');
            $heatmap[] = [
                'date' => $dateString,
                'count' => (int) ($activity[$dateString] ?? 0),
            ];
        }

        // Daily Quote
        $quotes = [
            ['text' => "Read the Qur'an, for it will come as an intercessor for its reciters on the Day of Resurrection.", 'source' => 'Sahih Muslim'],
            ['text' => "The best among you are those who learn the Qur'an and teach it.", 'source' => 'Sahih Al-Bukhari'],
            ['text' => 'A book We have sent down to you so that you may bring people out of darkness and into light...', 'source' => 'Surah Ibrahim 14:1'],
            ['text' => "And We have certainly made the Qur'an easy for remembrance, so is there any who will remember?", 'source' => 'Surah Al-Qamar 54:17'],
            ['text' => 'Unquestionably, by the remembrance of Allah hearts are assured.', 'source' => "Surah Ar-Ra'd 13:28"],
        ];
        $quoteIndex = now()->dayOfYear % count($quotes);
        $dailyQuote = $quotes[$quoteIndex];

        return Inertia::render('Dashboard', [
            'surahs' => $this->surahService->getSurahs(),
            'totalAyahs' => (int) $user->ayahReads()->sum(\Illuminate\Support\Facades\DB::raw('end_ayah - start_ayah + 1')),
            'totalSurahs' => $user->ayahReads()->distinct('surah_number')->count('surah_number'),
            'streak' => $streak,
            'heatmap' => $heatmap,
            'dailyQuote' => $dailyQuote,
        ]);
    }

    /**
     * Display the full reading history with pagination.
     */
    public function history(): Response
    {
        $user = auth()->user();

        return Inertia::render('History', [
            'history' => Inertia::scroll(fn () => $user->ayahReads()
                ->latest()
                ->paginate(15)),
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
            'start_ayah' => ['required', 'integer', 'min:1'],
            'end_ayah' => ['required', 'integer', 'min:1', 'gte:start_ayah'],
            'read_at' => ['nullable', 'date'],
        ]);

        $surah = $this->surahService->getSurah($validated['surah_number']);

        if (! $surah || $validated['end_ayah'] > $surah['numberOfAyahs']) {
            return back()->withErrors([
                'end_ayah' => "This Surah only has {$surah['numberOfAyahs']} ayahs.",
            ]);
        }

        auth()->user()->ayahReads()->create([
            'surah_number' => $validated['surah_number'],
            'start_ayah' => $validated['start_ayah'],
            'end_ayah' => $validated['end_ayah'],
            'read_at' => $validated['read_at'] ?? now(),
        ]);

        return back()->with('status', 'Ayah saved successfully!');
    }
}
