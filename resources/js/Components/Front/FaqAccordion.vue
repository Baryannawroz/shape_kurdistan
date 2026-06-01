<script setup>
import { ref } from 'vue';
import { ChevronDownIcon } from '@heroicons/vue/24/outline';

defineProps({
    items: { type: Array, required: true },
});

const openIndex = ref(0);

function toggle(index) {
    openIndex.value = openIndex.value === index ? -1 : index;
}
</script>

<template>
    <div class="space-y-3">
        <div v-for="(item, index) in items" :key="index" class="clover-faq-item">
            <button
                type="button"
                class="flex w-full items-center justify-between gap-4 px-5 py-4 text-start"
                :aria-expanded="openIndex === index"
                @click="toggle(index)"
            >
                <span class="text-sm font-semibold text-clover-ink md:text-base">{{ item.question }}</span>
                <ChevronDownIcon
                    class="h-5 w-5 shrink-0 text-clover-muted transition duration-200"
                    :class="openIndex === index ? 'rotate-180' : ''"
                    aria-hidden="true"
                />
            </button>
            <Transition
                enter-active-class="transition duration-200 ease-out"
                enter-from-class="opacity-0 -translate-y-1"
                enter-to-class="opacity-100 translate-y-0"
                leave-active-class="transition duration-150 ease-in"
                leave-from-class="opacity-100 translate-y-0"
                leave-to-class="opacity-0 -translate-y-1"
            >
                <div v-show="openIndex === index" class="border-t border-clover-border px-5 pb-5 pt-3">
                    <p class="text-sm leading-relaxed text-clover-muted">{{ item.answer }}</p>
                </div>
            </Transition>
        </div>
    </div>
</template>
