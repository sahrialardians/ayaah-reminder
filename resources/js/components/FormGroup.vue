<script setup lang="ts">
interface Props {
    label?: string;
    error?: string;
    help?: string;
}

defineProps<Props>();
</script>

<template>
    <div class="flex flex-col gap-1.5">
        <label v-if="label" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
            {{ label }}
        </label>
        <slot />
        <p v-if="error" class="text-sm font-medium text-destructive">
            {{ error }}
        </p>
        <p v-if="help && !error" class="text-sm text-muted-foreground">
            {{ help }}
        </p>
    </div>
</template>
