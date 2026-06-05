<script setup>
import { ref } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import TinyMceEditor from '@/Components/Admin/TinyMceEditor.vue';
import SeoFields from '@/Components/Admin/SeoFields.vue';
import { homeFieldLabels, homeSectionLabels } from '@/lib/homeSectionLabels.js';
import { r } from '@/lib/route.js';

const props = defineProps({
    homeSettings: {
        type: Object,
        required: true,
    },
    localeMeta: {
        type: Array,
        default: () => [],
    },
});

const page = usePage();
const open = ref(false);

const form = useForm({
    settings: { ...props.homeSettings },
});

function settingKey(section, field, locale) {
    return `home.${section}_${field}_${locale}`;
}

function seoTitleKey(locale) {
    return `seo.page.home.title_${locale}`;
}

function seoDescriptionKey(locale) {
    return `seo.page.home.description_${locale}`;
}

function isRichField(field) {
    return field === 'lead' || field === 'body';
}

function submit() {
    form.put(r('admin.cms.site-settings.update'), {
        preserveScroll: true,
        onSuccess: () => {
            open.value = false;
        },
    });
}
</script>

<template>
    <div v-if="page.props.canManageSite" class="relative z-10 mx-auto mb-6 max-w-3xl text-center">
        <button
            type="button"
            class="inline-flex items-center gap-2 rounded-lg border border-primary/30 bg-primary/5 px-4 py-2 text-sm font-semibold text-primary transition hover:bg-primary/10"
            @click="open = !open"
        >
            <span>{{ open ? 'Close editor' : 'Edit home page' }}</span>
        </button>

        <form
            v-if="open"
            class="mt-4 max-h-[80vh] space-y-4 overflow-y-auto rounded-clover border border-clover-border bg-white p-6 text-left shadow-sm"
            @submit.prevent="submit"
        >
            <details class="rounded-lg border border-clover-border p-4" open>
                <summary class="cursor-pointer text-sm font-semibold text-clover-ink">Hero &amp; stats</summary>
                <div class="mt-4 space-y-4">
                    <div v-for="loc in localeMeta" :key="'hero-' + loc.code" class="space-y-3 border-t border-clover-border pt-3 first:border-t-0 first:pt-0">
                        <h4 class="text-xs font-bold uppercase text-clover-muted">{{ loc.name }}</h4>
                        <div>
                            <label class="text-sm font-medium">Headline</label>
                            <input v-model="form.settings['appearance.hero_headline_' + loc.code]" type="text" class="mt-1 w-full rounded-lg border border-clover-border px-3 py-2 text-sm" />
                        </div>
                        <div>
                            <label class="text-sm font-medium">Subheadline</label>
                            <TinyMceEditor v-model="form.settings['appearance.hero_subheadline_' + loc.code]" class="mt-2" variant="basic" :min-height="120" />
                        </div>
                        <div class="grid gap-3 md:grid-cols-2">
                            <div>
                                <label class="text-sm font-medium">Primary button</label>
                                <input v-model="form.settings['appearance.hero_primary_cta_' + loc.code]" type="text" class="mt-1 w-full rounded-lg border border-clover-border px-3 py-2 text-sm" />
                            </div>
                            <div>
                                <label class="text-sm font-medium">Secondary button</label>
                                <input v-model="form.settings['appearance.hero_secondary_cta_' + loc.code]" type="text" class="mt-1 w-full rounded-lg border border-clover-border px-3 py-2 text-sm" />
                            </div>
                        </div>
                    </div>
                    <div class="grid gap-3 sm:grid-cols-2 md:grid-cols-4">
                        <div v-for="stat in ['projects', 'clients', 'years', 'awards']" :key="stat">
                            <label class="text-sm font-medium capitalize">{{ stat }}</label>
                            <input v-model="form.settings['appearance.stat_' + stat]" type="text" class="mt-1 w-full rounded-lg border border-clover-border px-3 py-2 text-sm" />
                        </div>
                    </div>
                </div>
            </details>

            <details v-for="(config, section) in homeSectionLabels" :key="section" class="rounded-lg border border-clover-border p-4">
                <summary class="cursor-pointer text-sm font-semibold text-clover-ink">{{ config.label }}</summary>
                <div class="mt-4 space-y-4">
                    <div v-for="loc in localeMeta" :key="section + loc.code" class="space-y-3 border-t border-clover-border pt-3 first:border-t-0 first:pt-0">
                        <h4 class="text-xs font-bold uppercase text-clover-muted">{{ loc.name }}</h4>
                        <div v-for="field in config.fields" :key="field">
                            <label class="text-sm font-medium">{{ homeFieldLabels[field] }}</label>
                            <input
                                v-if="! isRichField(field)"
                                v-model="form.settings[settingKey(section, field, loc.code)]"
                                type="text"
                                class="mt-1 w-full rounded-lg border border-clover-border px-3 py-2 text-sm"
                            />
                            <TinyMceEditor
                                v-else
                                v-model="form.settings[settingKey(section, field, loc.code)]"
                                class="mt-2"
                                variant="basic"
                                :min-height="field === 'body' ? 180 : 120"
                            />
                        </div>
                    </div>
                </div>
            </details>

            <details class="rounded-lg border border-amber-200 bg-amber-50/40 p-4">
                <summary class="cursor-pointer text-sm font-semibold text-amber-900">Google search (SEO)</summary>
                <div class="mt-4 space-y-4">
                    <div v-for="loc in localeMeta" :key="'seo-' + loc.code" class="border-t border-amber-200/60 pt-4 first:border-t-0 first:pt-0">
                        <h4 class="mb-3 text-xs font-bold uppercase text-amber-800">{{ loc.name }}</h4>
                        <SeoFields
                            v-model:title="form.settings[seoTitleKey(loc.code)]"
                            v-model:description="form.settings[seoDescriptionKey(loc.code)]"
                            :keywords="loc.code === localeMeta[0]?.code ? form.settings['seo.keywords'] : ''"
                            @update:keywords="(value) => { if (loc.code === localeMeta[0]?.code) form.settings['seo.keywords'] = value; }"
                        />
                    </div>
                </div>
            </details>

            <div class="flex flex-wrap gap-3 pt-2">
                <button type="submit" class="rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-white disabled:opacity-60" :disabled="form.processing">
                    Save home page
                </button>
                <button type="button" class="rounded-lg border border-clover-border px-4 py-2 text-sm font-medium text-clover-muted" @click="open = false">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</template>
