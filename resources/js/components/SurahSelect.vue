<script setup lang="ts">
import { ref, computed, watch, onMounted, onUnmounted } from 'vue';
import { cn } from '@/lib/utils';
import { Input } from '@/components/ui/input';

interface Surah {
    number: number;
    name: string;
    englishName: string;
    numberOfAyahs: number;
}

const props = defineProps<{
    surahs: Surah[];
    modelValue: string | number;
}>();

const emit = defineEmits(['update:modelValue']);

const isOpen = ref(false);
const searchQuery = ref('');
const displayLimit = ref(10);
const dropdownRef = ref<HTMLElement | null>(null);
const listRef = ref<HTMLElement | null>(null);

const filteredSurahs = computed(() => {
    if (!searchQuery.value) return props.surahs;
    const query = searchQuery.value.toLowerCase();
    return props.surahs.filter(s => 
        s.englishName.toLowerCase().includes(query) || 
        s.name.toLowerCase().includes(query) ||
        s.number.toString().includes(query)
    );
});

const visibleSurahs = computed(() => {
    return filteredSurahs.value.slice(0, displayLimit.value);
});

const selectedSurah = computed(() => {
    return props.surahs.find(s => s.number === Number(props.modelValue));
});

const toggleDropdown = () => {
    isOpen.value = !isOpen.value;
    if (isOpen.value) {
        displayLimit.value = 10;
        searchQuery.value = '';
    }
};

const selectSurah = (surah: Surah) => {
    emit('update:modelValue', surah.number);
    isOpen.value = false;
};

const handleScroll = (e: Event) => {
    const target = e.target as HTMLElement;
    if (target.scrollTop + target.clientHeight >= target.scrollHeight - 20) {
        if (displayLimit.value < filteredSurahs.value.length) {
            displayLimit.value += 10;
        }
    }
};

const handleClickOutside = (event: MouseEvent) => {
    if (dropdownRef.value && !dropdownRef.value.contains(event.target as Node)) {
        isOpen.value = false;
    }
};

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
});

watch(searchQuery, () => {
    displayLimit.value = 10;
    if (listRef.value) {
        listRef.value.scrollTop = 0;
    }
});
</script>

<template>
    <div class="relative w-full" ref="dropdownRef">
        <!-- Trigger -->
        <button
            type="button"
            @click="toggleDropdown"
            class="flex h-12 w-full items-center justify-between rounded-xl border border-input bg-background px-4 py-2 text-base shadow-sm transition-all focus:ring-2 focus:ring-primary focus:border-primary outline-none text-left"
        >
            <span v-if="selectedSurah" class="truncate font-medium">
                {{ selectedSurah.number }}. {{ selectedSurah.englishName }} ({{ selectedSurah.name }})
            </span>
            <span v-else class="text-muted-foreground italic">Choose a Surah...</span>
            <svg
                xmlns="http://www.w3.org/2000/svg"
                width="16"
                height="16"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                :class="['transition-transform duration-200', isOpen ? 'rotate-180' : '']"
            >
                <path d="m6 9 6 6 6-6" />
            </svg>
        </button>

        <!-- Dropdown -->
        <div
            v-if="isOpen"
            class="absolute z-50 mt-2 w-full rounded-xl border bg-popover text-popover-foreground shadow-xl animate-in fade-in zoom-in-95 duration-100"
        >
            <div class="p-2">
                <Input
                    v-model="searchQuery"
                    placeholder="Search surah name or number..."
                    class="h-10 mb-2 border-none bg-muted/50 focus-visible:ring-1"
                    @click.stop
                    autofocus
                />
                
                <div
                    ref="listRef"
                    @scroll="handleScroll"
                    class="max-h-64 overflow-y-auto custom-scrollbar flex flex-col gap-1 pr-1"
                >
                    <button
                        v-for="surah in visibleSurahs"
                        :key="surah.number"
                        type="button"
                        @click="selectSurah(surah)"
                        :class="[
                            'flex items-center justify-between px-3 py-3 rounded-lg text-sm transition-colors text-left',
                            modelValue === surah.number 
                                ? 'bg-primary text-primary-foreground font-bold' 
                                : 'hover:bg-muted'
                        ]"
                    >
                        <div class="flex items-center gap-3">
                            <span :class="['w-6 text-[10px] font-bold opacity-50', modelValue === surah.number ? 'opacity-100' : '']">{{ surah.number }}</span>
                            <span>{{ surah.englishName }}</span>
                        </div>
                        <span class="text-xs opacity-70">{{ surah.name }}</span>
                    </button>
                    
                    <div v-if="filteredSurahs.length === 0" class="p-8 text-center text-xs text-muted-foreground italic">
                        No Surah found matching "{{ searchQuery }}"
                    </div>
                    
                    <div v-if="displayLimit < filteredSurahs.length" class="p-2 text-center">
                        <div class="inline-block h-4 w-4 animate-spin rounded-full border-2 border-solid border-primary border-r-transparent align-middle"></div>
                    </div>
                </div>
            </div>
        </div>
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
