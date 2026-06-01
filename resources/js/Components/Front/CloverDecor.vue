<script setup>
defineProps({
    variant: {
        type: String,
        default: 'hero',
        validator: (v) => ['hero', 'section', 'cta', 'dots'].includes(v),
    },
});
</script>

<template>
    <!-- Hero: soft blobs + faint grid (Clover-style depth) -->
    <div
        v-if="variant === 'hero'"
        class="pointer-events-none absolute inset-0 overflow-hidden"
        aria-hidden="true"
    >
        <div class="absolute -end-24 -top-24 h-72 w-72 rounded-full bg-primary/15 blur-3xl" />
        <div class="absolute -start-16 top-1/3 h-56 w-56 rounded-full bg-emerald-300/20 blur-3xl" />
        <div class="absolute bottom-0 start-1/2 h-64 w-64 -translate-x-1/2 translate-y-1/2 rounded-full bg-lime-200/30 blur-3xl" />
        <svg class="absolute inset-0 h-full w-full opacity-[0.35]" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="clover-grid" width="32" height="32" patternUnits="userSpaceOnUse">
                    <circle cx="1" cy="1" r="0.75" fill="currentColor" class="text-neutral-300" />
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#clover-grid)" />
        </svg>
        <svg class="absolute end-[8%] top-[18%] h-24 w-24 text-primary/20" viewBox="0 0 100 100" fill="none">
            <circle cx="50" cy="50" r="48" stroke="currentColor" stroke-width="1.5" stroke-dasharray="6 8" />
            <circle cx="50" cy="50" r="28" stroke="currentColor" stroke-width="1" stroke-dasharray="4 6" />
        </svg>
        <svg class="absolute start-[6%] bottom-[22%] h-16 w-16 text-emerald-400/25" viewBox="0 0 64 64" fill="currentColor">
            <path d="M32 4L38 26H62L42 40L50 62L32 48L14 62L22 40L2 26H26L32 4Z" />
        </svg>
    </div>

    <!-- Section accent: corner blob -->
    <div
        v-else-if="variant === 'section'"
        class="pointer-events-none absolute inset-0 overflow-hidden"
        aria-hidden="true"
    >
        <div class="absolute -end-20 -top-20 h-52 w-52 rounded-full bg-primary/8 blur-2xl" />
        <div class="absolute -start-12 bottom-0 h-40 w-40 rounded-full bg-lime-100/60 blur-2xl" />
    </div>

    <!-- CTA band shapes -->
    <div
        v-else-if="variant === 'cta'"
        class="pointer-events-none absolute inset-0 overflow-hidden"
        aria-hidden="true"
    >
        <div class="absolute -end-16 -top-16 h-48 w-48 rounded-full bg-primary/20 blur-2xl" />
        <div class="absolute -start-10 bottom-0 h-40 w-40 rounded-full bg-white/5 blur-xl" />
        <svg class="absolute end-[12%] top-[20%] h-20 w-20 text-white/10" viewBox="0 0 100 100" fill="none">
            <rect x="10" y="10" width="80" height="80" rx="20" stroke="currentColor" stroke-width="2" />
            <path d="M30 50H70M50 30V70" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
        </svg>
    </div>

    <!-- Dot field for benefits/pricing -->
    <div
        v-else-if="variant === 'dots'"
        class="pointer-events-none absolute inset-0 overflow-hidden opacity-40"
        aria-hidden="true"
    >
        <svg class="h-full w-full" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="clover-dots" width="24" height="24" patternUnits="userSpaceOnUse">
                    <circle cx="2" cy="2" r="1" fill="currentColor" class="text-neutral-300" />
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#clover-dots)" />
        </svg>
    </div>
</template>
