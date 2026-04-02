<script setup lang="ts">
import { useForm, usePage } from '@inertiajs/vue3';
import { computed, watch, ref } from 'vue';
import FormGroup from '@/components/FormGroup.vue';
import SurahSelect from '@/components/SurahSelect.vue';
import { Button } from '@/components/ui/button';
import {
    Sheet,
    SheetContent,
    SheetHeader,
    SheetTitle,
    SheetTrigger,
} from '@/components/ui/sheet';

interface Surah {
    number: number;
    name: string;
    englishName: string;
    numberOfAyahs: number;
}

const page = usePage();
const surahs = computed(() => (page.props.surahs as Surah[]) || []);

// To pre-fill the form, we'd need the latestRead. 
// For now we default to empty, or we can fetch it from the page if available.
const latestRead = computed(() => page.props.latestRead as any);

const form = useForm({
    surah_number: latestRead.value?.surah_number ?? '',
    ayah_number: latestRead.value?.ayah_number ?? '',
});

const selectedSurah = computed(() => {
    return surahs.value.find((s) => s.number === Number(form.surah_number));
});

watch(() => form.surah_number, (newSurah) => {
    if (selectedSurah.value) {
        if (!form.ayah_number || form.ayah_number > selectedSurah.value.numberOfAyahs) {
            form.ayah_number = 1;
        }
    }
});

const isOpen = ref(false);

const submit = () => {
    form.post('/ayah', {
        preserveScroll: true,
        onSuccess: () => {
            isOpen.value = false; // Close drawer on success
            form.reset();
        }
    });
};
</script>

<template>
    <Sheet v-model:open="isOpen">
        <SheetTrigger asChild>
            <slot />
        </SheetTrigger>
        <SheetContent side="bottom" class="h-[85vh] rounded-t-[2rem] px-4 py-8 sm:max-w-md mx-auto sm:h-auto sm:bottom-auto sm:top-1/2 sm:-translate-y-1/2 sm:rounded-[2rem] flex flex-col gap-0">
            <SheetHeader class="text-left mb-6 px-2">
                <SheetTitle class="text-2xl font-bold">Record Reading</SheetTitle>
                <p class="text-sm text-muted-foreground">Select your progress</p>
            </SheetHeader>

            <form @submit.prevent="submit" class="flex-1 overflow-y-auto custom-scrollbar px-2 space-y-6">
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
                            <div class="grid grid-cols-5 gap-2 max-h-64 overflow-y-auto p-1 custom-scrollbar">
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

                <div class="pt-6 pb-safe">
                    <Button
                        type="submit"
                        :disabled="form.processing || !form.surah_number"
                        size="lg"
                        class="w-full h-14 rounded-full font-bold shadow-lg hover:shadow-primary/20 transition-all text-lg"
                    >
                        {{ form.processing ? 'Saving...' : 'Save Progress' }}
                    </Button>
                </div>
            </form>
        </SheetContent>
    </Sheet>
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
.pb-safe {
    padding-bottom: env(safe-area-inset-bottom);
}
</style>
