<script setup>
import { computed } from 'vue';
import { resolveCloverIcon } from '@/lib/cloverIcons.js';

const props = defineProps({
    name: { type: String, default: '' },
    index: { type: Number, default: 0 },
    size: {
        type: String,
        default: 'md',
        validator: (v) => ['sm', 'md', 'lg', 'xl'].includes(v),
    },
    variant: {
        type: String,
        default: 'soft',
        validator: (v) => ['soft', 'solid', 'ghost', 'ring'].includes(v),
    },
});

const iconComponent = computed(() => resolveCloverIcon(props.name || null, props.index));

const boxClass = computed(() => {
    const sizes = {
        sm: 'h-9 w-9 rounded-lg',
        md: 'h-11 w-11 rounded-xl',
        lg: 'h-14 w-14 rounded-2xl',
        xl: 'h-16 w-16 rounded-2xl',
    };

    const icons = {
        sm: 'h-4 w-4',
        md: 'h-5 w-5',
        lg: 'h-7 w-7',
        xl: 'h-8 w-8',
    };

    const variants = {
        soft: 'bg-primary/10 text-primary ring-1 ring-primary/15',
        solid: 'bg-clover-dark text-white shadow-md',
        ghost: 'bg-white text-primary ring-1 ring-clover-border shadow-sm',
        ring: 'bg-white text-primary ring-2 ring-primary/25 shadow-sm',
    };

    return {
        box: `${sizes[props.size]} ${variants[props.variant]} inline-flex shrink-0 items-center justify-center`,
        icon: icons[props.size],
    };
});
</script>

<template>
    <span :class="boxClass.box" aria-hidden="true">
        <component :is="iconComponent" :class="boxClass.icon" />
    </span>
</template>
