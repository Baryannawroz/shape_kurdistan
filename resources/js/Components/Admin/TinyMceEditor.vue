<script setup>
import Editor from '@tinymce/tinymce-vue';
import { computed } from 'vue';

const props = defineProps({
    modelValue: { type: String, default: '' },
    minHeight: { type: Number, default: null },
    /** full = CMS pages; basic = bold/italic/lists for intros and text areas */
    variant: {
        type: String,
        default: 'full',
        validator: (value) => ['full', 'basic'].includes(value),
    },
});

const emit = defineEmits(['update:modelValue']);

/** Same-origin TinyMCE (see `npm run tinymce:publish` / postinstall) — avoids Tiny Cloud referer / API key checks. */
const tinymceScriptSrc = '/vendor/tinymce/tinymce.min.js';

const content = computed({
    get: () => props.modelValue ?? '',
    set: (value) => emit('update:modelValue', value),
});

const resolvedMinHeight = computed(() => props.minHeight ?? (props.variant === 'basic' ? 140 : 320));

const init = computed(() => {
    if (props.variant === 'basic') {
        return {
            toolbar_mode: 'sliding',
            plugins: 'autolink link lists',
            toolbar: 'undo redo | bold italic underline strikethrough | bullist numlist | link | removeformat',
            min_height: resolvedMinHeight.value,
            menubar: false,
            branding: false,
            promotion: false,
            resize: true,
            skin: 'oxide',
            content_style: 'body { font-family: system-ui, sans-serif; font-size: 14px; }',
        };
    }

    return {
        toolbar_mode: 'sliding',
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
        toolbar:
            'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
        min_height: resolvedMinHeight.value,
        menubar: false,
        branding: false,
        promotion: false,
        resize: true,
        skin: 'oxide',
        content_style: 'body { font-family: system-ui, sans-serif; font-size: 14px; }',
    };
});
</script>

<template>
    <div class="overflow-hidden rounded-md border border-slate-300 bg-white">
        <Editor license-key="gpl" :tinymce-script-src="tinymceScriptSrc" v-model="content" :init="init" />
    </div>
</template>
