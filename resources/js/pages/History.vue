<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { history as historyRoute } from '@/routes';
import { InfiniteScroll } from '@inertiajs/vue3';

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

interface PaginatedHistory {
    data: AyahRead[];
    current_page: number;
    last_page: number;
    total: number;
}

interface Props {
    history: PaginatedHistory;
    surahs: Surah[];
}

const props = defineProps<Props>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'History',
                href: historyRoute(),
            },
        ],
    },
});

const getSurahName = (number: number) => {
    if (!props.surahs || props.surahs.length === 0) return `Surah ${number}`;
    const surah = props.surahs.find((s) => s.number === number);
    return surah ? surah.englishName : `Surah ${number}`;
};

const formatDate = (dateString: string) => {
    const date = new Date(dateString);
    return date.toLocaleDateString(undefined, { 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};
</script>

<template>
    <Head title="Reading History" />

    <div class="flex flex-1 flex-col gap-3 p-4 md:gap-4 md:p-8 max-w-3xl mx-auto w-full pb-24">
        <div class="flex flex-col space-y-1.5 px-2 md:px-0">
            <h1 class="text-xl font-bold leading-none tracking-tight">Reading History</h1>
            <p class="text-sm text-muted-foreground">Your complete journey with the Qur'an ({{ history.total }} entries)</p>
        </div>

        <div v-if="history.data.length === 0" class="py-16 text-center text-muted-foreground italic bg-card rounded-2xl border shadow-sm">
            No history found. Start your journey from the Dashboard!
        </div>

        <InfiniteScroll v-else data="history" class="flex flex-col gap-2.5">
            <template #default>
                <div
                    v-for="(entry, index) in history.data"
                    :key="entry.id"
                    class="flex items-center justify-between p-4 rounded-xl border bg-card text-card-foreground shadow-sm transition-all hover:shadow-md hover:border-primary/30 group"
                >
                    <div class="flex flex-col gap-1">
                        <span class="font-bold text-base sm:text-lg leading-none">{{ getSurahName(entry.surah_number) }}</span>
                        <span class="text-xs text-muted-foreground">{{ formatDate(entry.read_at) }}</span>
                    </div>
                    
                    <div class="flex items-center gap-3 sm:gap-4">
                        <div class="hidden sm:flex text-xs font-mono text-muted-foreground opacity-50 px-2">
                            #{{ history.total - index }}
                        </div>
                        <div class="flex flex-col items-end shrink-0 text-primary">
                            <span class="text-[10px] font-bold opacity-70 uppercase tracking-widest leading-none mb-0.5">Ayah</span>
                            <span class="text-lg sm:text-xl font-extrabold leading-none">{{ entry.ayah_number }}</span>
                        </div>
                    </div>
                </div>
            </template>
            <template #loading>
                <div class="py-6 flex justify-center">
                    <div class="inline-block h-6 w-6 animate-spin rounded-full border-2 border-solid border-primary border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"></div>
                </div>
            </template>
        </InfiniteScroll>
    </div>
</template>
