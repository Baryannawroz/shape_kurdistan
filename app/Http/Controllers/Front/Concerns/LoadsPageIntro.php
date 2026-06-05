<?php

namespace App\Http\Controllers\Front\Concerns;

use App\Models\Page;
use App\Support\AdminEditLinks;
use App\Support\RichContent;

trait LoadsPageIntro
{
    /**
     * @param  array{eyebrow?: string, title: string, lead?: string}  $defaults
     * @return array{pageContent: array<string, mixed>|null, localeMeta: array<int, array{code: string, name: string}>, intro: array{eyebrow: string, title: string, lead: string, leadHtml: string|null}}
     */
    protected function loadPageIntro(string $slug, array $defaults): array
    {
        $page = Page::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->with('translations')
            ->first();

        $translation = $page?->getTranslation();
        $user = request()->user();
        $canManage = AdminEditLinks::canManage($user);

        $defaultLead = $defaults['lead'] ?? '';
        $leadHtml = $translation?->content
            ? RichContent::expand($translation->content)
            : ($defaultLead !== '' ? '<p>'.e($defaultLead).'</p>' : null);

        $lead = $defaultLead;
        if ($translation?->content) {
            $lead = trim(preg_replace('/\s+/', ' ', strip_tags($translation->content)) ?? '') ?: $lead;
        }

        return [
            'pageContent' => $canManage ? AdminEditLinks::pagePayload($page) : null,
            'localeMeta' => $canManage ? AdminEditLinks::localeMeta() : [],
            'intro' => [
                'eyebrow' => $defaults['eyebrow'] ?? '',
                'title' => $translation?->title ?? $defaults['title'],
                'lead' => $lead,
                'leadHtml' => $leadHtml,
            ],
        ];
    }
}
