<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { dashboard } from '@/routes';
import Card from '@/components/Card.vue';
import FormGroup from '@/components/FormGroup.vue';
import SurahSelect from '@/components/SurahSelect.vue';
import { Button } from '@/components/ui/button';
import { computed, watch } from 'vue';

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
    history?: AyahRead[];
}

const props = withDefaults(defineProps<Props>(), {
    surahs: () => [],
    history: () => [],
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

const form = useForm({
    surah_number: props.latestRead?.surah_number ?? '',
    ayah_number: props.latestRead?.ayah_number ?? '',
});

const selectedSurah = computed(() => {
    return props.surahs.find((s) => s.number === Number(form.surah_number));
});

// Reset ayah number if it exceeds the new surah's limit
watch(() => form.surah_number, (newSurah) => {
    if (selectedSurah.value) {
        if (!form.ayah_number || form.ayah_number > selectedSurah.value.numberOfAyahs) {
            form.ayah_number = 1;
        }
    }
});

const getSurahName = (number: number) => {
    if (!props.surahs || props.surahs.length === 0) return `Surah ${number}`;
    const surah = props.surahs.find((s) => s.number === number);
    return surah ? surah.englishName : `Surah ${number}`;
};

const submit = () => {
    form.post('/ayah', {
        preserveScroll: true,
    });
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
        <div class="grid gap-4 md:gap-6 md:grid-cols-2 lg:grid-cols-3">
            <!-- Current Status Card -->
            <Card title="Latest Reading" description="Where you last left off" class="lg:col-span-1">
                <div v-if="latestRead" class="flex flex-col gap-1 sm:gap-2 py-2 text-left">
                    <div class="text-2xl sm:text-3xl font-bold text-primary truncate">
                        {{ getSurahName(latestRead.surah_number) }}
                    </div>
                    <div class="text-base sm:text-lg text-muted-foreground">
                        Ayah {{ latestRead.ayah_number }}
                    </div>
                    <div class="mt-2 sm:mt-4 text-[10px] sm:text-xs text-muted-foreground uppercase tracking-wider">
                        Last Read {{ new Date(latestRead.read_at).toLocaleDateString() }}
                    </div>
                </div>
                <div v-else class="py-8 text-center text-muted-foreground text-sm border-2 border-dashed rounded-xl">
                    No reading history yet.<br>Start your journey today!
                </div>
            </Card>

            <!-- Save Ayah Card -->
            <Card title="Record Reading" description="Select your progress" class="lg:col-span-2">
                <form @submit.prevent="submit" class="space-y-6">
                    <div class="grid gap-6">
                        <FormGroup label="1. Select Surah" :error="form.errors.surah_number">
                            <SurahSelect
                                v-model="form.surah_number"
                                :surahs="surahs"
                            />
                        </FormGroup>

                        <div v-if="selectedSurah" class="space-y-3 animate-in fade-in slide-in-from-top-2">
                            <div class="flex items-center justify-between">
                                <label class="text-sm font-medium leading-none italic">2. Ayah Number</label>
                                <span class="text-xs text-primary font-bold">Selected: {{ form.ayah_number }}</span>
                            </div>
                            
                            <div class="p-4 rounded-xl border bg-muted/30">
                                <div class="grid grid-cols-5 sm:grid-cols-8 md:grid-cols-10 lg:grid-cols-12 gap-2 max-h-64 overflow-y-auto p-1 custom-scrollbar">
                                    <button
                                        v-for="n in selectedSurah.numberOfAyahs"
                                        :key="n"
                                        type="button"
                                        @click="form.ayah_number = n"
                                        :class="[
                                            'h-10 w-full text-xs rounded-lg border transition-all flex items-center justify-center font-bold',
                                            form.ayah_number == n 
                                                ? 'bg-primary text-primary-foreground border-primary shadow-md scale-105 z-10' 
                                                : 'bg-background hover:border-primary hover:text-primary'
                                        ]"
                                    >
                                        {{ n }}
                                    </button>
                                </div>
                                <p class="mt-3 text-[10px] text-muted-foreground text-center uppercase tracking-widest font-semibold">
                                    {{ selectedSurah.englishName }} contains {{ selectedSurah.numberOfAyahs }} ayahs
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col-reverse sm:flex-row sm:items-center gap-4 pt-2">
                        <Button
                            type="submit"
                            :disabled="form.processing || !form.surah_number"
                            size="lg"
                            class="w-full sm:w-auto h-12 px-10 rounded-full font-bold shadow-lg hover:shadow-primary/20 transition-all"
                        >
                            {{ form.processing ? 'Saving...' : 'Save Progress' }}
                        </Button>

                        <transition
                            enter-active-class="transition ease-in-out"
                            enter-from-class="opacity-0"
                            leave-active-class="transition ease-in-out"
                            leave-to-class="opacity-0"
                        >
                            <p v-if="form.recentlySuccessful" class="text-sm font-bold text-green-600 dark:text-green-400 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                Saved successfully!
                            </p>
                        </transition>
                    </div>
                </form>
            </Card>
        </div>

        <!-- History Card -->
        <Card title="Recent History" description="Your last 10 reading entries">
            <div class="overflow-x-auto -mx-6 sm:mx-0">
                <table class="w-full text-left text-sm">
                    <thead class="bg-muted/50 border-y sm:border-none">
                        <tr>
                            <th class="p-4 font-bold text-xs uppercase tracking-widest text-muted-foreground">Surah</th>
                            <th class="p-4 font-bold text-xs uppercase tracking-widest text-muted-foreground text-center">Ayah</th>
                            <th class="p-4 font-bold text-xs uppercase tracking-widest text-muted-foreground hidden sm:table-cell">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        <tr v-for="entry in history" :key="entry.id" class="group hover:bg-muted/30 transition-colors">
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
                        <tr v-if="history.length === 0">
                            <td colspan="3" class="p-16 text-center text-muted-foreground italic">
                                Your history will appear here once you start reading.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </Card>
    </div>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: hsl(var(--muted-foreground) / 0.2);
    border-radius: 10px;
}
</style>
