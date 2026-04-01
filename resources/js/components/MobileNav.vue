<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { Home, History, Plus, Bell, User } from 'lucide-vue-next';
import { dashboard, history } from '@/routes';
import { edit as editReminders } from '@/routes/reminders';
import { edit as editProfile } from '@/routes/profile';
import { computed } from 'vue';

const page = usePage();

const currentUrl = computed(() => page.url);

const items = [
    { title: 'Home', href: dashboard(), icon: Home },
    { title: 'History', href: history(), icon: History },
    { title: 'Add', href: dashboard(), icon: Plus },
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
                <Link
                    :href="item.href"
                    :class="[
                        'flex flex-col items-center justify-center gap-1 w-full h-full transition-colors',
                        isActive(item.href) ? 'text-primary' : 'text-muted-foreground'
                    ]"
                >
                    <component :is="item.icon" class="w-5 h-5" :class="{ 'stroke-[2.5]': isActive(item.href) }" />
                    <span class="text-[10px] font-bold uppercase tracking-tight">{{ item.title }}</span>
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
