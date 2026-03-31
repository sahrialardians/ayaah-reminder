<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { history as historyRoute } from '@/routes';
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

interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

interface PaginatedHistory {
    data: AyahRead[];
    links: PaginationLink[];
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
</script>

<template>
    <Head title="Reading History" />

    <div class="flex flex-1 flex-col gap-6 p-4 md:p-8">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold tracking-tight">Full Reading History</h1>
            <p class="text-sm text-muted-foreground">Total {{ history.total }} entries recorded</p>
        </div>

        <Card title="All Entries" description="Your complete journey with the Qur'an">
            <div class="overflow-x-auto -mx-6 sm:mx-0">
                <table class="w-full text-left text-sm">
                    <thead class="bg-muted/50 border-y sm:border-none">
                        <tr>
                            <th class="p-4 font-bold text-xs uppercase tracking-widest text-muted-foreground text-center w-12">#</th>
                            <th class="p-4 font-bold text-xs uppercase tracking-widest text-muted-foreground">Surah</th>
                            <th class="p-4 font-bold text-xs uppercase tracking-widest text-muted-foreground text-center">Ayah</th>
                            <th class="p-4 font-bold text-xs uppercase tracking-widest text-muted-foreground hidden sm:table-cell">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        <tr v-for="(entry, index) in history.data" :key="entry.id" class="group hover:bg-muted/30 transition-colors">
                            <td class="p-4 text-center text-[10px] font-mono opacity-50">
                                {{ (history.current_page - 1) * 20 + index + 1 }}
                            </td>
                            <td class="p-4">
                                <div class="flex flex-col">
                                    <span class="font-bold text-primary group-hover:underline decoration-primary/30 underline-offset-4">
                                        {{ getSurahName(entry.surah_number) }}
                                    </span>
                                    <span class="text-[10px] text-muted-foreground sm:hidden">
                                        {{ new Date(entry.read_at).toLocaleDateString() }}
                                    </span>
                                </div>
                            </td>
                            <td class="p-4 text-center w-20">
                                <span class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-muted font-bold text-xs group-hover:bg-primary/10 group-hover:text-primary transition-colors">
                                    {{ entry.ayah_number }}
                                </span>
                            </td>
                            <td class="p-4 text-muted-foreground hidden sm:table-cell">
                                {{ new Date(entry.read_at).toLocaleDateString() }}
                            </td>
                        </tr>
                        <tr v-if="history.data.length === 0">
                            <td colspan="4" class="p-16 text-center text-muted-foreground italic">
                                No history found. Start your journey from the Dashboard!
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Simple Pagination -->
            <div v-if="history.last_page > 1" class="flex items-center justify-center gap-2 mt-8 pt-6 border-t">
                <template v-for="link in history.links" :key="link.label">
                    <Link
                        v-if="link.url"
                        :href="link.url"
                        v-html="link.label"
                        :class="[
                            'px-4 py-2 text-sm rounded-lg border transition-all',
                            link.active 
                                ? 'bg-primary text-primary-foreground border-primary font-bold' 
                                : 'bg-background hover:bg-muted text-muted-foreground'
                        ]"
                    />
                    <span
                        v-else
                        v-html="link.label"
                        class="px-4 py-2 text-sm rounded-lg border bg-muted/50 text-muted-foreground opacity-50"
                    />
                </template>
            </div>
        </Card>
    </div>
</template>
