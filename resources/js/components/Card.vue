<script setup lang="ts">
interface Props {
    title?: string;
    description?: string;
}

defineProps<Props>();
</script>

<template>
    <div class="rounded-xl border bg-card text-card-foreground shadow-sm">
        <div v-if="title || description" class="flex flex-col space-y-1.5 p-6">
            <h3 v-if="title" class="text-xl font-bold leading-none tracking-tight">
                {{ title }}
            </h3>
            <p v-if="description" class="text-sm text-muted-foreground">
                {{ description }}
            </p>
        </div>
        <div class="p-6 pt-0">
            <slot />
        </div>
        <div v-if="$slots.footer" class="flex items-center p-6 pt-0">
            <slot name="footer" />
        </div>
    </div>
</template>
