import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

export function useTranslation() {
    const page = usePage();

    const locale = computed(() => page.props.locale);
    const direction = computed(() => page.props.direction);
    const isRtl = computed(() => direction.value === 'rtl');

    return { locale, direction, isRtl };
}
