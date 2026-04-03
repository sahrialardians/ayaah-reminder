<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { dashboard } from '@/routes';
import Card from '@/components/Card.vue';
import { computed } from 'vue';

interface Surah {
    number: number;
    name: string;
    englishName: string;
    numberOfAyahs: number;
}

interface AyahRead {
    id: string;
    surah_number: number;
    ayah_number: number;
    read_at: string;
}

interface HeatmapDay {
    date: string;
    count: number;
}

interface Quote {
    text: string;
    source: string;
}

interface Props {
    surahs?: Surah[];
    latestRead?: AyahRead | null;
    totalAyahs?: number;
    totalSurahs?: number;
    streak?: number;
    heatmap?: HeatmapDay[];
    dailyQuote?: Quote;
}

const props = withDefaults(defineProps<Props>(), {
    surahs: () => [],
    totalAyahs: 0,
    totalSurahs: 0,
    streak: 0,
    heatmap: () => [],
    dailyQuote: () => ({ text: '', source: '' })
});

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Dashboard',
                href: dashboard(),
            },
        ],
    },
});

const getSurahName = (number: number) => {
    if (!props.surahs || props.surahs.length === 0) return `Surah ${number}`;
    const surah = props.surahs.find((s) => s.number === number);
    return surah ? surah.englishName : `Surah ${number}`;
};

const khatmProgress = computed(() => {
    const totalAyahsInQuran = 6236;
    const progress = (props.totalAyahs / totalAyahsInQuran) * 100;
    return Math.min(progress, 100).toFixed(2);
});

const maxHeatmapCount = computed(() => {
    if (!props.heatmap || props.heatmap.length === 0) return 1;
    return Math.max(...props.heatmap.map(day => day.count));
});

const getHeatmapColor = (count: number) => {
    if (count === 0) return 'bg-muted/30';
    
    // Calculate intensity 1-4 based on max count
    const intensity = Math.ceil((count / maxHeatmapCount.value) * 4);
    
    if (intensity === 1) return 'bg-primary/25';
    if (intensity === 2) return 'bg-primary/50';
    if (intensity === 3) return 'bg-primary/75';
    return 'bg-primary';
};

const formatAyahRange = (start: number, end: number) => {
    if (!start || !end) return '';
    return start === end ? end.toString() : `${start}-${end}`;
};
</script>

<template>
    <Head title="Dashboard" />

    <div v-if="!surahs || surahs.length === 0" class="flex items-center justify-center p-12 min-h-[400px]">
        <div class="text-center">
            <div class="inline-block h-8 w-8 animate-spin rounded-full border-4 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]" role="status">
                <span class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Loading...</span>
            </div>
            <p class="mt-4 text-lg text-muted-foreground font-medium">Fetching Surahs...</p>
        </div>
    </div>

    <div v-else class="flex flex-1 flex-col gap-4 p-4 md:gap-6 md:p-8">
        
        <!-- Daily Quote -->
        <Card class="bg-primary text-primary-foreground border-none overflow-hidden relative">
            <div class="absolute right-0 top-0 opacity-10">
                <svg xmlns="http://www.w3.org/2000/svg" width="120" height="120" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1 0-5H20" /></svg>
            </div>
            <div class="p-6 relative z-10 flex flex-col gap-3">
                <span class="text-xs uppercase tracking-widest font-bold opacity-70">Daily Inspiration</span>
                <p class="text-lg font-medium leading-relaxed italic">"{{ dailyQuote.text }}"</p>
                <span class="text-sm opacity-80 mt-2">— {{ dailyQuote.source }}</span>
            </div>
        </Card>

        <!-- Current Status Stats (3 Columns) -->
        <Card v-if="latestRead" class="overflow-hidden">
            <div class="grid grid-cols-3 divide-x text-center">
                <div class="flex flex-col p-4 sm:p-6 bg-muted/10">
                    <span class="text-xs text-muted-foreground uppercase tracking-widest font-bold mb-2">Last Read</span>
                    <span class="text-xl font-extrabold text-primary truncate px-1" :title="getSurahName(latestRead.surah_number) + ' : ' + formatAyahRange(latestRead.start_ayah, latestRead.end_ayah)">
                        <span class="hidden sm:inline">{{ getSurahName(latestRead.surah_number) }} : {{ formatAyahRange(latestRead.start_ayah, latestRead.end_ayah) }}</span>
                        <span class="sm:hidden">{{ latestRead.surah_number }}:{{ formatAyahRange(latestRead.start_ayah, latestRead.end_ayah) }}</span>
                    </span>
                </div>
                <div class="flex flex-col p-4 sm:p-6 bg-muted/10">
                    <span class="text-xs text-muted-foreground uppercase tracking-widest font-bold mb-2">Total Ayahs</span>
                    <span class="text-xl font-extrabold text-primary px-1">{{ totalAyahs }}</span>
                </div>
                <div class="flex flex-col p-4 sm:p-6 bg-muted/10">
                    <span class="text-xs text-muted-foreground uppercase tracking-widest font-bold mb-2">Total Surahs</span>
                    <span class="text-xl font-extrabold text-primary">{{ totalSurahs }}</span>
                </div>
            </div>
        </Card>
        <Card v-else>
            <div class="py-8 text-center text-muted-foreground text-sm">
                No reading history yet. Start your journey today by tapping the + icon!
            </div>
        </Card>

        <div class="grid gap-4 md:gap-6 md:grid-cols-2">
            <!-- Khatm Progress -->
            <Card title="Khatm Progress" description="Your journey to complete the Qur'an" class="flex flex-col">
                <div class="p-4 pt-0 flex-1 flex flex-col justify-center gap-4">
                    <div class="flex items-end justify-between">
                        <span class="text-3xl font-extrabold text-primary">{{ khatmProgress }}%</span>
                        <span class="text-sm text-muted-foreground mb-1">{{ totalAyahs }} / 6236 Ayahs</span>
                    </div>
                    <div class="h-4 w-full bg-muted rounded-full overflow-hidden">
                        <div class="h-full bg-primary transition-all duration-1000 ease-out" :style="`width: ${khatmProgress}%`"></div>
                    </div>
                    <p class="text-xs text-muted-foreground text-center mt-2 italic">
                        "The most beloved of deeds to Allah are those that are most consistent, even if it is small."
                    </p>
                </div>
            </Card>

            <!-- Activity & Streak -->
            <Card title="Activity" description="Your reading consistency" class="flex flex-col">
                <div class="p-4 pt-0 flex flex-col gap-6">
                    <div class="flex items-center gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-primary/10 text-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                        </div>
                        <div>
                            <p class="text-xs text-muted-foreground font-bold uppercase tracking-widest">Current Streak</p>
                            <p class="text-2xl font-extrabold text-primary">{{ streak }} <span class="text-base font-medium text-foreground">Days</span></p>
                        </div>
                    </div>

                    <div>
                        <p class="text-xs text-muted-foreground font-bold uppercase tracking-widest mb-3">Last 30 Days</p>
                        <div class="flex flex-wrap gap-1.5 sm:gap-2">
                            <!-- Tooltip wrapper could be added here, but simple colored divs work well for mobile -->
                            <div 
                                v-for="day in heatmap" 
                                :key="day.date"
                                :class="['w-4 h-4 sm:w-5 sm:h-5 rounded-sm transition-colors', getHeatmapColor(day.count)]"
                                :title="`${day.count} reads on ${day.date}`"
                            ></div>
                        </div>
                    </div>
                </div>
            </Card>
        </div>
    </div>
</template>
