<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { dashboard } from '@/routes';
import Card from '@/components/Card.vue';

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

interface Props {
    surahs?: Surah[];
    latestRead?: AyahRead | null;
    totalAyahs?: number;
    totalSurahs?: number;
}

const props = withDefaults(defineProps<Props>(), {
    surahs: () => [],
    totalAyahs: 0,
    totalSurahs: 0,
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
        <!-- Current Status Stats (3 Columns) -->
        <Card v-if="latestRead" class="overflow-hidden">
            <div class="grid grid-cols-3 divide-x text-center">
                <div class="flex flex-col p-4 sm:p-6 bg-muted/10">
                    <span class="text-[10px] sm:text-xs text-muted-foreground uppercase tracking-widest font-bold mb-1 sm:mb-2">Last Read</span>
                    <span class="text-sm sm:text-2xl font-extrabold text-primary truncate px-1" :title="getSurahName(latestRead.surah_number) + ' : ' + latestRead.ayah_number">
                        <span class="hidden sm:inline">{{ getSurahName(latestRead.surah_number) }} : {{ latestRead.ayah_number }}</span>
                        <span class="sm:hidden">{{ latestRead.surah_number }}:{{ latestRead.ayah_number }}</span>
                    </span>
                </div>
                <div class="flex flex-col p-4 sm:p-6 bg-muted/10">
                    <span class="text-[10px] sm:text-xs text-muted-foreground uppercase tracking-widest font-bold mb-1 sm:mb-2">Total Ayahs</span>
                    <span class="text-sm sm:text-2xl font-extrabold text-primary px-1">{{ totalAyahs }}</span>
                </div>
                <div class="flex flex-col p-4 sm:p-6 bg-muted/10">
                    <span class="text-[10px] sm:text-xs text-muted-foreground uppercase tracking-widest font-bold mb-1 sm:mb-2">Total Surahs</span>
                    <span class="text-sm sm:text-2xl font-extrabold text-primary">{{ totalSurahs }}</span>
                </div>
            </div>
        </Card>
        <Card v-else>
            <div class="py-8 text-center text-muted-foreground text-sm">
                No reading history yet. Start your journey today by tapping the + icon!
            </div>
        </Card>
    </div>
</template>
