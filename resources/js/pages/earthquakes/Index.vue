<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';

import EarthquakeFiltersForm from '@/components/earthquakes/EarthquakeFilters.vue';
import EarthquakeTable from '@/components/earthquakes/EarthquakeTable.vue';
import SummaryCard from '@/components/earthquakes/SummaryCard.vue';

import type {
    Earthquake,
    EarthquakeFilters,
    EarthquakeSummary,
} from '@/types/earthquake';

interface Props {
    earthquakes: Earthquake[];
    summary: EarthquakeSummary;
    filters: EarthquakeFilters;
}

const props = defineProps<Props>();

const totalDisplayValue = computed(() =>
    props.summary.total >= 100 ? '100+' : props.summary.total,
);
</script>

<template>
    <Head title="QuakeWatch" />

    <main class="min-h-screen bg-gray-50">
        <div class="mx-auto max-w-7xl px-6 py-10">
            <header>
                <h1 class="text-3xl font-bold tracking-tight text-gray-900">
                    QuakeWatch
                </h1>

                <p class="mt-2 text-gray-600">
                    Explore recent earthquake activity around the world.
                </p>
            </header>

            <section class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <SummaryCard
                    label="Total earthquakes"
                    :value="totalDisplayValue"
                />

                <SummaryCard
                    label="Strongest magnitude"
                    :value="summary.strongestMagnitude ?? '—'"
                />

                <SummaryCard
                    label="Average magnitude"
                    :value="summary.averageMagnitude ?? '—'"
                />
            </section>

            <section class="mt-8">
                <EarthquakeFiltersForm :filters="filters" />
            </section>

            <section class="mt-8">
                <div class="mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">
                        Recent earthquakes
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Showing activity for {{ filters.period }} with magnitude
                        {{ filters.minimumMagnitude }}+
                    </p>
                </div>

                <EarthquakeTable :earthquakes="earthquakes" />
            </section>
        </div>
    </main>
</template>
