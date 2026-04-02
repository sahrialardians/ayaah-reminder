<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { Home, History, Plus, Bell, User } from 'lucide-vue-next';
import { dashboard, history } from '@/routes';
import { edit as editReminders } from '@/routes/reminders';
import { edit as editProfile } from '@/routes/profile';
import { computed } from 'vue';
import RecordReadingDrawer from '@/components/RecordReadingDrawer.vue';

const page = usePage();

const currentUrl = computed(() => page.url);

const items = [
    { title: 'Home', href: dashboard(), icon: Home },
    { title: 'History', href: history(), icon: History },
    { title: 'Add', href: '#', icon: Plus, isAction: true },
    { title: 'Reminder', href: editReminders(), icon: Bell },
    { title: 'Profile', href: editProfile(), icon: User },
];

const isActive = (href: string) => {
    return currentUrl.value === href || currentUrl.value.startsWith(href + '?');
};
</script>

<template>
    <nav class="fixed bottom-0 left-0 right-0 z-50 bg-background/80 backdrop-blur-lg border-t pb-safe">
        <div class="flex items-center justify-around h-16 px-2 max-w-md mx-auto">
            <template v-for="item in items" :key="item.title">
                <!-- Action Button opens Drawer -->
                <RecordReadingDrawer v-if="item.isAction">
                    <button
                        type="button"
                        class="flex flex-col items-center justify-center gap-1 w-full h-full transition-colors text-muted-foreground outline-none"
                    >
                        <div class="flex items-center justify-center w-11 h-11 rounded-full bg-primary text-primary-foreground shadow-md hover:bg-primary/90 transition-all">
                            <component :is="item.icon" class="w-5 h-5 stroke-[3]" />
                        </div>
                    </button>
                </RecordReadingDrawer>

                <!-- Normal Navigation Links -->
                <Link
                    v-else
                    :href="item.href"
                    :class="[
                        'flex flex-col items-center justify-center gap-1 w-full h-full transition-colors',
                        isActive(item.href) ? 'text-primary' : 'text-muted-foreground'
                    ]"
                >
                    <component :is="item.icon" class="w-6 h-6 transition-transform duration-200 hover:scale-110" :class="{ 'stroke-[2.5] scale-110': isActive(item.href) }" />
                </Link>
            </template>
        </div>
    </nav>
</template>

<style scoped>
.pb-safe {
    padding-bottom: env(safe-area-inset-bottom);
}
</style>
