<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
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
                title: 'Reminder settings',
                href: edit(),
            },
        ],
    },
});

// Timezones list (simplified for example)
const timezones = [
    'UTC', 'Asia/Jakarta', 'Asia/Riyadh', 'Europe/London', 'America/New_York'
];
</script>

<template>
    <Head title="Reminder settings" />

    <h1 class="sr-only">Reminder settings</h1>

    <div class="flex flex-col space-y-6">
        <Heading
            variant="small"
            title="Notification Preferences"
            description="Manage how and when you receive reminders to read your Ayah."
        />

        <Form
            v-bind="ReminderController.update.form()"
            class="space-y-6"
            v-slot="{ errors, processing, recentlySuccessful, setFieldValue, values }"
        >
            <div class="flex flex-row items-start space-x-3 space-y-0 rounded-md border p-4">
                <Checkbox
                    id="is_enabled"
                    name="is_enabled"
                    :checked="values.is_enabled ?? settings.is_enabled"
                    @update:checked="(checked) => setFieldValue('is_enabled', checked)"
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
                    name="reminder_time"
                    :default-value="settings.reminder_time.substring(0, 5)"
                    required
                />
                <InputError class="mt-2" :message="errors.reminder_time" />
            </div>

            <div class="grid gap-2">
                <Label for="timezone">Timezone</Label>
                <select
                    id="timezone"
                    name="timezone"
                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                    required
                >
                    <option v-for="tz in timezones" :key="tz" :value="tz" :selected="tz === settings.timezone">
                        {{ tz }}
                    </option>
                </select>
                <InputError class="mt-2" :message="errors.timezone" />
            </div>

            <div class="flex items-center gap-4">
                <Button :disabled="processing">Save</Button>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p
                        v-show="recentlySuccessful"
                        class="text-sm text-neutral-600"
                    >
                        Saved.
                    </p>
                </Transition>
            </div>
        </Form>
    </div>
</template>
