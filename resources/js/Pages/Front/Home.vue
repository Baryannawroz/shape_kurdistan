<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import {
    ArrowRightIcon,
    CheckIcon,
    ShieldCheckIcon,
} from '@heroicons/vue/24/outline';
import AppLayout from '@/Layouts/AppLayout.vue';
import SectionHeader from '@/Components/Front/SectionHeader.vue';
import MarqueeStrip from '@/Components/Front/MarqueeStrip.vue';
import FaqAccordion from '@/Components/Front/FaqAccordion.vue';
import CloverIcon from '@/Components/Front/CloverIcon.vue';
import CloverDecor from '@/Components/Front/CloverDecor.vue';
import CloverAsset from '@/Components/Front/CloverAsset.vue';
import { r } from '@/lib/route.js';
import { stripHtml } from '@/lib/stripHtml.js';

const props = defineProps({
    jsonLd: { type: String, required: true },
    hero: Object,
    stats: Object,
    services: Array,
    projects: Array,
    team: Array,
    testimonials: Array,
    showBlogTeaser: Boolean,
});

const page = usePage();
const locale = computed(() => String(page.props.locale ?? 'en'));
const siteTitle = computed(() => page.props.siteName);

const trustLabel = computed(() => {
    const clients = props.stats?.clients ?? 0;
    if (clients > 0) {
        return `${clients.toLocaleString()}+ trusted clients`;
    }
    return 'Trusted by growing businesses';
});

const featuredServices = computed(() => (props.services ?? []).slice(0, 3));
const benefitServices = computed(() => (props.services ?? []).slice(0, 6));
const showcaseItems = computed(() => {
    const fromServices = (props.services ?? []).slice(0, 3);
    if (fromServices.length >= 3) {
        return fromServices;
    }
    return (props.projects ?? []).slice(0, 3);
});

const marqueeTags = [
    'Strategy & Discovery',
    'Design Systems',
    'Web Development',
    'Performance',
    'Analytics',
    'Dedicated Support',
    'Flexible Engagements',
    'Secure Delivery',
];

const processSteps = [
    {
        step: '01',
        icon: 'lightbulb',
        title: 'Discovery & Strategy',
        points: ['Understand goals, users, and constraints.', 'Define scope, milestones, and success metrics.', 'Align stakeholders before build begins.'],
    },
    {
        step: '02',
        icon: 'cursor',
        title: 'Design & Prototype',
        points: ['Translate strategy into UX and visual design.', 'Iterate quickly with clickable prototypes.', 'Validate decisions before development.'],
    },
    {
        step: '03',
        icon: 'rocket',
        title: 'Build & Launch',
        points: ['Ship with clean, maintainable code.', 'Test across devices and performance budgets.', 'Launch with confidence and clear handoff.'],
    },
    {
        step: '04',
        icon: 'trending',
        title: 'Grow & Support',
        points: ['Monitor, refine, and optimize post-launch.', 'Transparent communication at every stage.', 'Flexible support as your needs evolve.'],
    },
];

const pricingPlans = [
    {
        name: 'Starter',
        icon: 'sparkles',
        price: 'Custom',
        description: 'Ideal for focused projects that need a clear scope and fast delivery.',
        features: ['Discovery workshop', 'Core design & build', 'Email support', 'Launch checklist', '30-day post-launch support'],
        popular: false,
    },
    {
        name: 'Pro',
        icon: 'bolt',
        price: 'Custom',
        description: 'For growing teams that need deeper collaboration and ongoing iteration.',
        features: ['Everything in Starter', 'Extended design system', 'Priority support', 'Analytics setup', 'Monthly review sessions'],
        popular: true,
    },
    {
        name: 'Enterprise',
        icon: 'shield',
        price: 'Custom',
        description: 'Tailored engagements for larger organizations with complex requirements.',
        features: ['Dedicated lead', 'Multi-team coordination', 'Custom integrations', 'SLA-backed support', 'Security & compliance review'],
        popular: false,
    },
];

const faqItems = [
    {
        question: 'How do we get started on a project?',
        answer: 'Reach out through the contact form with a short brief. We will schedule a call to understand your goals, timeline, and budget, then propose a tailored plan.',
    },
    {
        question: 'Can you work with our existing team or codebase?',
        answer: 'Yes. We regularly collaborate with in-house teams and integrate with existing design systems, repositories, and workflows.',
    },
    {
        question: 'How long does a typical engagement take?',
        answer: 'Timelines depend on scope. A focused landing page may take a few weeks; a full product build can span several months with phased delivery.',
    },
    {
        question: 'What kind of support do you offer after launch?',
        answer: 'We offer flexible retainers and ad-hoc support for updates, performance tuning, and new features — scoped to what you need.',
    },
    {
        question: 'Is our data and IP secure?',
        answer: 'We follow secure development practices, use private repositories, and can sign NDAs. You retain ownership of all deliverables.',
    },
];

const takeaways = [
    'Clear strategy before a single line of code',
    'Design and engineering under one roof',
    'Flexible plans that scale with your business',
    'Transparent process from kickoff to launch',
    'Long-term partnership, not one-off delivery',
];

const clientCompanies = computed(() =>
    (props.testimonials ?? [])
        .map((t) => t.company)
        .filter(Boolean)
        .slice(0, 8),
);
</script>

<template>
    <AppLayout :title="siteTitle">
        <Head>
            <component :is="'script'" type="application/ld+json" v-text="props.jsonLd" />
        </Head>

        <!-- Hero -->
        <section class="relative overflow-hidden px-4 pb-16 pt-10 md:pb-24 md:pt-16 lg:px-8">
            <CloverDecor variant="hero" />
            <div class="relative mx-auto max-w-6xl">
                <div class="mx-auto max-w-3xl text-center">
                    <p class="inline-flex items-center gap-2 rounded-full border border-clover-border bg-white px-4 py-1.5 text-xs font-semibold text-clover-muted shadow-sm">
                        <CloverIcon name="sparkles" size="sm" variant="soft" />
                        {{ trustLabel }}
                    </p>
                    <h1 class="mt-6 text-balance text-4xl font-semibold tracking-tight text-clover-ink md:text-5xl lg:text-[3.5rem] lg:leading-[1.08]">
                        {{ hero.headline }}
                    </h1>
                    <p class="mx-auto mt-5 max-w-2xl text-lg leading-relaxed text-clover-muted">
                        {{ hero.subheadline }}
                    </p>
                    <div class="mt-9 flex flex-wrap justify-center gap-3">
                        <Link :href="r('site.contact', { locale })" class="clover-btn-primary">
                            {{ hero.primaryCta || 'Get Started' }}
                        </Link>
                        <Link :href="r('site.services', { locale })" class="clover-btn-secondary">
                            {{ hero.secondaryCta || 'More Info' }}
                        </Link>
                    </div>
                    <p class="mt-4 text-xs text-clover-muted">*Flexible engagements — no long-term lock-in.*</p>
                </div>

                <div class="clover-hero-mock relative mx-auto mt-14 max-w-4xl p-3 md:p-4">
                    <div class="overflow-hidden rounded-[1.35rem] bg-clover-bg">
                        <img
                            v-if="hero.image"
                            :src="hero.image"
                            alt=""
                            class="aspect-[16/10] w-full object-cover"
                        />
                        <CloverAsset
                            v-else
                            src="hero-widget.svg"
                            alt=""
                            aspect="aspect-[16/10]"
                            contain
                        />
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats strip -->
        <section v-if="stats" class="border-y border-clover-border bg-white py-10">
            <div class="mx-auto grid max-w-4xl grid-cols-2 gap-8 px-4 md:grid-cols-4 lg:px-8">
                <div v-for="(label, key) in { projects: 'Projects', clients: 'Clients', years: 'Years', awards: 'Awards' }" :key="key" class="text-center">
                    <p class="text-3xl font-bold tabular-nums tracking-tight text-clover-ink md:text-4xl">
                        {{ stats[key] }}+
                    </p>
                    <p class="mt-1 text-xs font-semibold uppercase tracking-wider text-clover-muted">{{ label }}</p>
                </div>
            </div>
        </section>

        <!-- Why choose us -->
        <section class="relative px-4 py-20 lg:px-8 lg:py-28">
            <CloverDecor variant="section" />
            <div class="relative mx-auto max-w-6xl">
                <SectionHeader
                    eyebrow="Our advantages"
                    title="Why choose us?"
                    lead="Focused engagements from discovery through launch — clear milestones, shared ownership, and measurable outcomes."
                />
                <div v-if="featuredServices.length" class="mt-14 grid gap-6 md:grid-cols-3">
                    <article
                        v-for="(s, index) in featuredServices"
                        :key="s.id"
                        class="clover-card-hover p-7 animate-fade-up"
                        :style="{ animationDelay: `${index * 80}ms` }"
                    >
                        <CloverIcon :index="index" size="md" class="mb-5" />
                        <h3 class="text-lg font-semibold text-clover-ink">{{ s.title }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-clover-muted line-clamp-4">{{ stripHtml(s.description) }}</p>
                    </article>
                </div>
                <MarqueeStrip :items="marqueeTags" class="mt-12" />
            </div>
        </section>

        <!-- Benefits -->
        <section class="relative border-y border-clover-border bg-white px-4 py-20 lg:px-8 lg:py-28">
            <CloverDecor variant="dots" />
            <div class="relative mx-auto max-w-6xl">
                <SectionHeader
                    eyebrow="Benefits"
                    title="Why we shine"
                    lead="Everything you need to ship with confidence — strategy, design, engineering, and support in one partnership."
                />
                <div v-if="benefitServices.length" class="mt-14 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    <article v-for="(s, index) in benefitServices" :key="s.id" class="clover-card p-6">
                        <CloverIcon :index="index + 3" size="sm" class="mb-4" />
                        <h3 class="font-semibold text-clover-ink">{{ s.title }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-clover-muted line-clamp-3">{{ stripHtml(s.description) }}</p>
                    </article>
                </div>
            </div>
        </section>

        <!-- Process -->
        <section class="px-4 py-20 lg:px-8 lg:py-28">
            <div class="mx-auto max-w-6xl">
                <SectionHeader
                    eyebrow="Process"
                    title="Our approach"
                    lead="A streamlined process with clear steps and full transparency from kickoff to launch and beyond."
                />
                <div class="mt-14 grid gap-6 md:grid-cols-2">
                    <article v-for="step in processSteps" :key="step.step" class="clover-card p-7">
                        <div class="flex items-start justify-between gap-4">
                            <CloverIcon :name="step.icon" size="md" variant="ring" />
                            <p class="text-sm font-bold text-primary">{{ step.step }}</p>
                        </div>
                        <h3 class="mt-2 text-lg font-semibold text-clover-ink">{{ step.title }}</h3>
                        <ul class="mt-4 space-y-2">
                            <li v-for="(point, i) in step.points" :key="i" class="flex gap-2 text-sm text-clover-muted">
                                <CheckIcon class="mt-0.5 h-4 w-4 shrink-0 text-primary" aria-hidden="true" />
                                {{ point }}
                            </li>
                        </ul>
                    </article>
                </div>
            </div>
        </section>

        <!-- Product / service showcase -->
        <section v-if="showcaseItems.length" class="border-t border-clover-border bg-white px-4 py-20 lg:px-8 lg:py-28">
            <div class="mx-auto max-w-6xl space-y-24">
                <article
                    v-for="(item, index) in showcaseItems"
                    :key="item.id"
                    class="grid items-center gap-10 lg:grid-cols-2 lg:gap-16"
                    :class="index % 2 === 1 ? 'lg:[direction:rtl]' : ''"
                >
                    <div :class="index % 2 === 1 ? 'lg:[direction:ltr]' : ''">
                        <p class="clover-eyebrow">Product</p>
                        <h3 class="mt-3 text-2xl font-semibold tracking-tight text-clover-ink md:text-3xl">{{ item.title }}</h3>
                        <p class="mt-4 text-base leading-relaxed text-clover-muted">
                            {{ item.description ? stripHtml(item.description) : 'Explore how we deliver results for teams like yours.' }}
                        </p>
                        <Link
                            :href="item.category ? r('site.portfolio.show', { locale, slug: item.slug }) : r('site.services.show', { locale, slug: item.slug })"
                            class="clover-btn-green mt-6"
                        >
                            Get Started
                            <ArrowRightIcon class="h-4 w-4" aria-hidden="true" />
                        </Link>
                    </div>
                    <div
                        class="clover-hero-mock overflow-hidden p-2"
                        :class="index % 2 === 1 ? 'lg:[direction:ltr]' : ''"
                    >
                        <img
                            v-if="item.image"
                            :src="item.image"
                            alt=""
                            class="aspect-[4/3] w-full rounded-[1rem] object-cover"
                        />
                        <div v-else class="flex aspect-[4/3] items-center justify-center rounded-[1rem] bg-gradient-to-br from-primary/10 to-clover-bg">
                            <CloverIcon :index="index + 6" size="xl" variant="soft" />
                        </div>
                    </div>
                </article>
            </div>
        </section>

        <!-- Portfolio preview -->
        <section v-if="projects?.length" class="px-4 py-20 lg:px-8 lg:py-28">
            <div class="mx-auto max-w-6xl">
                <SectionHeader
                    eyebrow="Portfolio"
                    title="Featured work"
                    lead="Case studies and launches we are proud to have shaped end-to-end."
                />
                <div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <Link
                        v-for="p in projects.slice(0, 6)"
                        :key="p.id"
                        :href="r('site.portfolio.show', { locale, slug: p.slug })"
                        class="clover-card-hover group overflow-hidden"
                    >
                        <div class="aspect-video overflow-hidden bg-clover-bg">
                            <img v-if="p.image" :src="p.image" class="h-full w-full object-cover transition duration-500 group-hover:scale-105" alt="" />
                        </div>
                        <div class="p-5">
                            <p class="text-xs font-semibold uppercase tracking-wider text-primary">{{ p.category }}</p>
                            <p class="mt-1 font-semibold text-clover-ink">{{ p.title }}</p>
                        </div>
                    </Link>
                </div>
                <div class="mt-10 text-center">
                    <Link :href="r('site.portfolio', { locale })" class="clover-btn-secondary">
                        View all work
                        <ArrowRightIcon class="h-4 w-4" aria-hidden="true" />
                    </Link>
                </div>
            </div>
        </section>

        <!-- Pricing -->
        <section class="relative border-y border-clover-border bg-white px-4 py-20 lg:px-8 lg:py-28">
            <CloverDecor variant="dots" />
            <div class="relative mx-auto max-w-6xl">
                <SectionHeader
                    eyebrow="Pricing"
                    title="Choose the perfect plan"
                    lead="Flexible pricing aligned with where your business stands today and where it is headed."
                />
                <div class="mt-14 grid gap-6 lg:grid-cols-3">
                    <article
                        v-for="plan in pricingPlans"
                        :key="plan.name"
                        :class="plan.popular ? 'clover-pricing-popular p-7 lg:-mt-2 lg:mb-2' : 'clover-card p-7'"
                    >
                        <CloverIcon :name="plan.icon" size="md" variant="solid" class="mb-4" />
                        <p v-if="plan.popular" class="mb-3 text-[10px] font-bold uppercase tracking-widest text-primary">Most popular</p>
                        <p class="text-sm font-semibold text-clover-muted">{{ plan.name }} Plan</p>
                        <p class="mt-2 text-3xl font-bold text-clover-ink">{{ plan.price }}</p>
                        <p class="mt-3 text-sm leading-relaxed text-clover-muted">{{ plan.description }}</p>
                        <ul class="mt-6 space-y-3">
                            <li v-for="(feature, i) in plan.features" :key="i" class="flex gap-2 text-sm text-clover-muted">
                                <CheckIcon class="mt-0.5 h-4 w-4 shrink-0 text-primary" aria-hidden="true" />
                                {{ feature }}
                            </li>
                        </ul>
                        <Link :href="r('site.contact', { locale })" class="clover-btn-primary mt-8 w-full">
                            Get Started
                        </Link>
                        <p class="mt-3 text-center text-xs text-clover-muted">*No commitment — let's talk first*</p>
                    </article>
                </div>
                <div class="mt-10 flex flex-wrap items-center justify-center gap-6 text-sm text-clover-muted">
                    <span class="inline-flex items-center gap-2"><ShieldCheckIcon class="h-5 w-5 text-primary" /> Transparent scope</span>
                    <span class="inline-flex items-center gap-2"><ShieldCheckIcon class="h-5 w-5 text-primary" /> Clear milestones</span>
                    <span class="inline-flex items-center gap-2"><ShieldCheckIcon class="h-5 w-5 text-primary" /> Fast kickoff</span>
                </div>
            </div>
        </section>

        <!-- FAQ -->
        <section class="px-4 py-20 lg:px-8 lg:py-28">
            <div class="mx-auto max-w-3xl">
                <SectionHeader
                    eyebrow="FAQ"
                    title="Got a quick question?"
                    lead="We are here to help you make the right decision. Explore common questions below."
                />
                <FaqAccordion :items="faqItems" class="mt-12" />
            </div>
        </section>

        <!-- Reviews -->
        <section v-if="testimonials?.length" class="border-y border-clover-border bg-white px-4 py-20 lg:px-8 lg:py-28">
            <div class="mx-auto max-w-6xl">
                <SectionHeader
                    eyebrow="Reviews"
                    title="Our valued clients"
                    lead="Voices from teams we have shipped with."
                />
                <div class="mt-12 flex gap-4 overflow-x-auto pb-4 [-ms-overflow-style:none] [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
                    <figure v-for="t in testimonials" :key="t.id" class="clover-review-card">
                        <blockquote class="text-sm leading-relaxed text-clover-muted">
                            <div class="prose prose-sm max-w-none" v-html="t.content" />
                        </blockquote>
                        <figcaption class="mt-5 flex items-center gap-3 border-t border-clover-border pt-4">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-primary/10 text-sm font-bold text-primary">
                                <img v-if="t.avatar" :src="t.avatar" class="h-full w-full rounded-full object-cover" alt="" />
                                <span v-else>{{ String(t.author_name || '?').charAt(0) }}</span>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-clover-ink">{{ t.author_name }}</p>
                                <p v-if="t.company" class="text-xs text-clover-muted">{{ t.company }}</p>
                            </div>
                        </figcaption>
                    </figure>
                </div>
            </div>
        </section>

        <!-- Client logos -->
        <section v-if="clientCompanies.length" class="px-4 py-12 lg:px-8">
            <div class="mx-auto max-w-6xl text-center">
                <p class="clover-eyebrow">Clients we work with</p>
                <div class="mt-6 flex flex-wrap items-center justify-center gap-3">
                    <span
                        v-for="(company, i) in clientCompanies"
                        :key="i"
                        class="rounded-full border border-clover-border bg-white px-5 py-2 text-sm font-medium text-clover-muted"
                    >
                        {{ company }}
                    </span>
                </div>
            </div>
        </section>

        <!-- Team -->
        <section v-if="team?.length" class="border-t border-clover-border px-4 py-20 lg:px-8 lg:py-28">
            <div class="mx-auto max-w-6xl">
                <SectionHeader eyebrow="Team" title="The people behind the work" />
                <div class="mt-14 grid gap-6 sm:grid-cols-2 md:grid-cols-4">
                    <div v-for="m in team" :key="m.id" class="clover-card p-6 text-center">
                        <div class="mx-auto h-24 w-24 overflow-hidden rounded-full bg-clover-bg ring-2 ring-white">
                            <img v-if="m.photo" :src="m.photo" class="h-full w-full object-cover" alt="" />
                        </div>
                        <p class="mt-4 font-semibold text-clover-ink">{{ m.name }}</p>
                        <p class="text-sm text-clover-muted">{{ m.position }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Key takeaways + CTA -->
        <section class="px-4 py-20 lg:px-8 lg:py-28">
            <div class="mx-auto max-w-6xl">
                <div class="grid items-center gap-12 lg:grid-cols-2">
                    <div>
                        <SectionHeader
                            :centered="false"
                            eyebrow="Key takeaways"
                            title="Build with clarity and confidence"
                            lead="Partner with a team that combines strategy, design, and engineering — tailored to your goals."
                        />
                        <Link :href="r('site.contact', { locale })" class="clover-btn-green mt-8">
                            Get Started
                        </Link>
                    </div>
                    <ul class="space-y-3">
                        <li v-for="(item, i) in takeaways" :key="i" class="flex gap-3 rounded-clover border border-clover-border bg-white px-5 py-4 text-sm text-clover-ink">
                            <CheckIcon class="mt-0.5 h-5 w-5 shrink-0 text-primary" aria-hidden="true" />
                            {{ item }}
                        </li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- Final CTA -->
        <section class="mx-4 mb-16 lg:mx-8">
            <div class="relative mx-auto max-w-6xl overflow-hidden rounded-[1.75rem] bg-clover-dark px-6 py-16 text-center text-white md:px-12">
                <CloverDecor variant="cta" />
                <p class="relative mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-sm font-bold">
                    {{ String(siteTitle).charAt(0) }}
                </p>
                <h2 class="relative mx-auto mt-6 max-w-2xl text-3xl font-semibold tracking-tight md:text-4xl">
                    Start building something great today
                </h2>
                <p class="relative mx-auto mt-4 max-w-xl text-base text-white/70">
                    Tell us about your project — we will respond quickly with next steps and a tailored proposal.
                </p>
                <Link :href="r('site.contact', { locale })" class="relative clover-btn-green mt-8 bg-primary hover:bg-primary-dark">
                    Get Started
                </Link>
            </div>
        </section>

        <section v-if="page.props.flash?.success" class="bg-primary-soft py-4 text-center text-sm font-medium text-primary-dark">
            {{ page.props.flash.success }}
        </section>
    </AppLayout>
</template>
