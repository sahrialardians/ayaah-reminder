<script setup lang="ts">
import { useForm, Head } from '@inertiajs/vue3';
import ReminderController from '@/actions/App/Http/Controllers/Settings/ReminderController';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Checkbox } from '@/components/ui/checkbox';
import { edit } from '@/routes/reminders';

interface Settings {
    is_enabled: boolean;
    reminder_time: string;
    timezone: string;
}

const props = defineProps<{
    settings: Settings;
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Reminder',
                href: edit(),
            },
        ],
    },
});

const form = useForm({
    is_enabled: props.settings.is_enabled,
    reminder_time: props.settings.reminder_time.substring(0, 5),
    timezone: props.settings.timezone,
});

const submit = () => {
    form.patch(ReminderController.update().url, {
        preserveScroll: true,
    });
};

// Timezones list (common timezones)
const timezones = [
    'UTC', 
    'Asia/Jakarta', 
    'Asia/Kuala_Lumpur',
    'Asia/Singapore',
    'Asia/Riyadh', 
    'Asia/Dubai',
    'Europe/London', 
    'Europe/Paris',
    'America/New_York',
    'America/Los_Angeles',
    'Australia/Sydney'
];
</script>

<template>
    <Head title="Reminder" />

    <h1 class="sr-only">Reminder</h1>

    <div class="flex flex-col space-y-6">
        <Heading
            variant="small"
            title="Notification Preferences"
            description="Manage how and when you receive reminders to read your Ayah."
        />

        <form @submit.prevent="submit" class="space-y-6">
            <div class="flex flex-row items-start space-x-3 space-y-0 rounded-md border p-4">
                <Checkbox
                    id="is_enabled"
                    :checked="form.is_enabled"
                    @update:checked="(checked) => form.is_enabled = checked"
                />
                <div class="space-y-1 leading-none">
                    <Label for="is_enabled">Enable Daily Reminders</Label>
                    <p class="text-sm text-muted-foreground">
                        Receive a gentle notification to continue your Qur'an journey.
                    </p>
                </div>
            </div>

            <div class="grid gap-2">
                <Label for="reminder_time">Reminder Time</Label>
                <Input
                    id="reminder_time"
                    type="time"
                    class="mt-1 block w-full"
                    v-model="form.reminder_time"
                    required
                />
                <InputError class="mt-2" :message="form.errors.reminder_time" />
            </div>

            <div class="grid gap-2">
                <Label for="timezone">Timezone</Label>
                <select
                    id="timezone"
                    v-model="form.timezone"
                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                    required
                >
                    <option v-for="tz in timezones" :key="tz" :value="tz">
                        {{ tz }}
                    </option>
                </select>
                <InputError class="mt-2" :message="form.errors.timezone" />
            </div>

            <div class="flex items-center gap-4">
                <Button :disabled="form.processing">Save</Button>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p
                        v-show="form.recentlySuccessful"
                        class="text-sm text-neutral-600"
                    >
                        Saved.
                    </p>
                </Transition>
            </div>
        </form>
    </div>
</template>
