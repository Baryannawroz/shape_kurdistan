<script setup>
defineProps({
    items: { type: Array, required: true },
});
</script>

<template>
    <div class="relative overflow-hidden py-2">
        <div class="pointer-events-none absolute inset-y-0 start-0 z-10 w-16 bg-gradient-to-r from-clover-bg to-transparent" aria-hidden="true" />
        <div class="pointer-events-none absolute inset-y-0 end-0 z-10 w-16 bg-gradient-to-l from-clover-bg to-transparent" aria-hidden="true" />
        <div class="clover-marquee-track">
            <span v-for="(item, i) in items" :key="`a-${i}`" class="clover-marquee-item">{{ item }}</span>
            <span v-for="(item, i) in items" :key="`b-${i}`" class="clover-marquee-item" aria-hidden="true">{{ item }}</span>
        </div>
    </div>
</template>
