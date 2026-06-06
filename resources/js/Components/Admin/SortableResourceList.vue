<script setup>
import { router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import draggable from 'vuedraggable';
import { r } from '@/lib/admin-route.js';

const props = defineProps({
    items: { type: Array, required: true },
    reorderRoute: { type: String, required: true },
});

const list = ref([...props.items]);

watch(
    () => props.items,
    (next) => {
        list.value = [...next];
    },
    { deep: true }
);

function onDragEnd() {
    router.post(
        r(props.reorderRoute),
        { ids: list.value.map((row) => row.id) },
        { preserveScroll: true, preserveState: true }
    );
}
</script>

<template>
    <draggable
        v-model="list"
        item-key="id"
        tag="ul"
        handle=".drag-handle"
        class="divide-y rounded-lg bg-white shadow"
        @end="onDragEnd"
    >
        <template #item="{ element }">
            <li class="flex items-stretch gap-2 px-2 py-2 sm:px-4 sm:py-3">
                <button
                    type="button"
                    class="drag-handle flex w-8 shrink-0 cursor-grab items-center justify-center rounded border border-dashed border-slate-300 text-slate-400 hover:bg-slate-50 active:cursor-grabbing"
                    aria-label="Reorder"
                >
                    ::
                </button>
                <div class="min-w-0 flex-1">
                    <slot name="row" :item="element" />
                </div>
            </li>
        </template>
    </draggable>
</template>
