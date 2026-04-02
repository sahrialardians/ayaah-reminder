<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { MoreVertical, Info, Heart } from 'lucide-vue-next';
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { dashboard } from '@/routes';
import type { BreadcrumbItem } from '@/types';

type Props = {
    breadcrumbs?: BreadcrumbItem[];
};

const props = withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});

const pageTitle = computed(() => {
    if (props.breadcrumbs && props.breadcrumbs.length > 0) {
        const title = props.breadcrumbs[props.breadcrumbs.length - 1].title;
        return title === 'Dashboard' ? 'Ayaah Reminder' : title;
    }
    return 'Ayaah Reminder';
});
</script>

<template>
    <div class="sticky top-0 z-50 bg-background/80 backdrop-blur-lg border-b border-sidebar-border/80">
        <div>
            <div class="mx-auto flex h-14 items-center justify-between px-4 max-w-md">
                <!-- Logo/Title -->
                <Link :href="dashboard()" class="flex items-center gap-x-2">
                    <span class="text-lg font-bold tracking-tight">{{ pageTitle }}</span>
                </Link>

                <!-- More Menu -->
                <div class="flex items-center">
                    <DropdownMenu>
                        <DropdownMenuTrigger :as-child="true">
                            <Button
                                variant="ghost"
                                size="icon"
                                class="relative size-10 w-auto rounded-full p-1 focus-within:ring-2 focus-within:ring-primary"
                            >
                                <MoreVertical class="h-5 w-5 text-muted-foreground" />
                            </Button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent align="end" class="w-48">
                            <DropdownMenuItem as-child>
                                <a href="#" class="cursor-pointer flex items-center w-full">
                                    <Info class="mr-2 h-4 w-4" />
                                    <span>About</span>
                                </a>
                            </DropdownMenuItem>
                            <DropdownMenuItem as-child>
                                <a href="#" class="cursor-pointer flex items-center w-full text-primary">
                                    <Heart class="mr-2 h-4 w-4" />
                                    <span>Donate</span>
                                </a>
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                </div>
            </div>
        </div>

        <div
            v-if="props.breadcrumbs.length > 1"
            class="flex w-full border-t border-sidebar-border/70"
        >
            <div
                class="mx-auto flex h-10 w-full items-center justify-start px-4 text-xs text-neutral-500 max-w-md"
            >
                <Breadcrumbs :breadcrumbs="breadcrumbs" />
            </div>
        </div>
    </div>
</template>
