<script setup lang="ts">
interface Props {
    label: string;
    value: string | number;
}

defineProps<Props>();
</script>

<template>
    <div class="rounded-lg border bg-white p-5 shadow-sm">
        <p class="text-sm text-gray-500">
            {{ label }}
        </p>

        <p class="mt-2 text-2xl font-semibold text-gray-900">
            {{ value }}
        </p>
    </div>
</template>
