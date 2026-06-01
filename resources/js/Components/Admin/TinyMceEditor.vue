<script setup>
import Editor from '@tinymce/tinymce-vue';
import { computed } from 'vue';

const props = defineProps({
    modelValue: { type: String, default: '' },
    minHeight: { type: Number, default: 320 },
});

const emit = defineEmits(['update:modelValue']);

/** Same-origin TinyMCE (see `npm run tinymce:publish` / postinstall) — avoids Tiny Cloud referer / API key checks. */
const tinymceScriptSrc = '/vendor/tinymce/tinymce.min.js';

const content = computed({
    get: () => props.modelValue ?? '',
    set: (value) => emit('update:modelValue', value),
});

const init = computed(() => ({
    toolbar_mode: 'sliding',
    plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
    toolbar:
        'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
    min_height: props.minHeight,
    menubar: false,
    branding: false,
    promotion: false,
    resize: true,
    skin: 'oxide',
    content_style: 'body { font-family: system-ui, sans-serif; font-size: 14px; }',
}));
</script>

<template>
    <div class="overflow-hidden rounded-md border border-slate-300 bg-white">
        <Editor license-key="gpl" :tinymce-script-src="tinymceScriptSrc" v-model="content" :init="init" />
    </div>
</template>
