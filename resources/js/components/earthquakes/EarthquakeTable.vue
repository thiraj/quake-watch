<script setup lang="ts">
import type { Earthquake } from '@/types/earthquake';

interface Props {
    earthquakes: Earthquake[];
}

defineProps<Props>();
</script>

<template>
    <div class="overflow-hidden rounded-lg border bg-white">
        <div
            v-if="earthquakes.length === 0"
            class="p-8 text-center text-gray-500"
        >
            No earthquakes found for the selected filters.
        </div>

        <div v-else class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="border-b bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 font-medium text-black">
                            Magnitude
                        </th>

                        <th class="px-4 py-3 font-medium text-black">
                            Location
                        </th>

                        <th class="px-4 py-3 font-medium text-black">Depth</th>

                        <th class="px-4 py-3 font-medium text-black">Time</th>

                        <th class="px-4 py-3 text-black">Details</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    <tr v-for="earthquake in earthquakes" :key="earthquake.id">
                        <td class="px-4 py-4 font-semibold text-gray-600">
                            {{ earthquake.magnitude.toFixed(1) }}
                        </td>

                        <td class="px-4 py-4 text-gray-600">
                            {{ earthquake.location }}
                        </td>

                        <td class="px-4 py-4 text-gray-600">
                            {{ earthquake.depth.toFixed(1) }} km
                        </td>

                        <td class="px-4 py-4 text-gray-600">
                            {{ earthquake.occurredAt }}
                        </td>

                        <td class="px-4 py-4 text-right text-gray-600">
                            <a
                                :href="earthquake.url"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="font-medium underline"
                            >
                                View
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
