<script setup>
import { onUnmounted, watch } from 'vue';

const props = defineProps({
    open: { type: Boolean, default: false },
    src: { type: String, default: '' },
    alt: { type: String, default: '' },
});

const emit = defineEmits(['close']);

function onKeydown(e) {
    if (e.key === 'Escape') {
        emit('close');
    }
}

watch(
    () => props.open,
    (isOpen) => {
        if (isOpen) {
            document.addEventListener('keydown', onKeydown);
            document.body.style.overflow = 'hidden';
        } else {
            document.removeEventListener('keydown', onKeydown);
            document.body.style.overflow = '';
        }
    }
);

onUnmounted(() => {
    document.removeEventListener('keydown', onKeydown);
    document.body.style.overflow = '';
});
</script>

<template>
    <Teleport to="body">
        <div
            v-show="open && src"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 p-4"
            role="dialog"
            aria-modal="true"
            @click.self="emit('close')"
        >
            <button type="button" class="absolute end-4 top-4 rounded-full bg-white/10 px-3 py-1 text-sm text-white hover:bg-white/20" @click="emit('close')">
                Close
            </button>
            <img :src="src" :alt="alt" class="max-h-[90vh] max-w-full rounded-lg object-contain shadow-2xl" />
        </div>
    </Teleport>
</template>
