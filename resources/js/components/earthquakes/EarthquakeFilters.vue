<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';

import type { EarthquakeFilters, EarthquakePeriod } from '@/types/earthquake';

interface Props {
    filters: EarthquakeFilters;
}

const props = defineProps<Props>();

const period = ref<EarthquakePeriod>(props.filters.period);
const minimumMagnitude = ref(props.filters.minimumMagnitude);

const applyFilters = (): void => {
    router.get(
        '/earthquakes',
        {
            period: period.value,
            minimum_magnitude: minimumMagnitude.value,
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        },
    );
};
</script>

<template>
    <form
        class="flex flex-col gap-4 rounded-lg border bg-white p-5 sm:flex-row sm:items-end"
        @submit.prevent="applyFilters"
    >
        <div class="flex-1">
            <label
                for="period"
                class="mb-2 block text-sm font-medium text-gray-700"
            >
                Time range
            </label>

            <select
                id="period"
                v-model="period"
                class="w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-gray-900"
            >
                <option value="hour">Last hour</option>
                <option value="day">Last 24 hours</option>
                <option value="week">Last 7 days</option>
            </select>
        </div>

        <div class="flex-1">
            <label
                for="minimum-magnitude"
                class="mb-2 block text-sm font-medium text-gray-700"
            >
                Minimum magnitude
            </label>

            <select
                id="minimum-magnitude"
                v-model.number="minimumMagnitude"
                class="w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-gray-900"
            >
                <option :value="0">All</option>
                <option :value="1">1.0+</option>
                <option :value="2">2.0+</option>
                <option :value="3">3.0+</option>
                <option :value="4">4.0+</option>
                <option :value="5">5.0+</option>
            </select>
        </div>

        <button
            type="submit"
            class="rounded-md bg-gray-900 px-5 py-2 text-sm font-medium text-white"
        >
            Apply filters
        </button>
    </form>
</template>
