<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Service;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Support\SiteSettings;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(): Response
    {
        $services = Service::query()
            ->where('is_active', true)
            ->with('translations')
            ->orderBy('order')
            ->limit(6)
            ->get()
            ->map(fn (Service $s) => $this->mapService($s));

        $projects = Project::query()
            ->where('is_active', true)
            ->with('translations')
            ->orderBy('order')
            ->limit(12)
            ->get()
            ->map(fn (Project $p) => $this->mapProject($p));

        $team = TeamMember::query()
            ->where('is_active', true)
            ->with('translations')
            ->orderBy('order')
            ->limit(8)
            ->get()
            ->map(fn (TeamMember $m) => $this->mapTeamMember($m));

        $testimonials = Testimonial::query()
            ->where('is_active', true)
            ->with('translations')
            ->orderBy('order')
            ->limit(10)
            ->get()
            ->map(fn (Testimonial $t) => $this->mapTestimonial($t));

        $siteName = (string) (SiteSettings::get('general.site_name_'.app()->getLocale()) ?? config('app.name'));

        $jsonLd = json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => $siteName,
            'url' => url('/'.app()->getLocale()),
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);

        return Inertia::render('Front/Home', [
            'jsonLd' => $jsonLd,
            'hero' => [
                'headline' => SiteSettings::get('appearance.hero_headline_'.app()->getLocale()),
                'subheadline' => SiteSettings::get('appearance.hero_subheadline_'.app()->getLocale()),
                'primaryCta' => SiteSettings::get('appearance.hero_primary_cta_'.app()->getLocale()),
                'secondaryCta' => SiteSettings::get('appearance.hero_secondary_cta_'.app()->getLocale()),
                'image' => SiteSettings::get('appearance.hero_image') ? Storage::url(SiteSettings::get('appearance.hero_image')) : null,
            ],
            'stats' => [
                'projects' => (int) (SiteSettings::get('appearance.stat_projects') ?? 0),
                'clients' => (int) (SiteSettings::get('appearance.stat_clients') ?? 0),
                'years' => (int) (SiteSettings::get('appearance.stat_years') ?? 0),
                'awards' => (int) (SiteSettings::get('appearance.stat_awards') ?? 0),
            ],
            'services' => $services,
            'projects' => $projects,
            'team' => $team,
            'testimonials' => $testimonials,
            'showBlogTeaser' => filter_var(SiteSettings::get('appearance.show_blog_teaser') ?? false, FILTER_VALIDATE_BOOLEAN),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function mapService(Service $service): array
    {
        $t = $service->getTranslation();

        return [
            'id' => $service->id,
            'title' => $t?->title,
            'description' => $t?->description,
            'slug' => $t?->slug,
            'icon' => $service->icon ? Storage::url($service->icon) : null,
            'image' => $service->image ? Storage::url($service->image) : null,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function mapProject(Project $project): array
    {
        $t = $project->getTranslation();

        return [
            'id' => $project->id,
            'title' => $t?->title,
            'slug' => $t?->slug,
            'category' => $project->category,
            'client' => $project->client,
            'year' => $project->year,
            'image' => $project->image ? Storage::url($project->image) : null,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function mapTeamMember(TeamMember $member): array
    {
        $t = $member->getTranslation();

        return [
            'id' => $member->id,
            'name' => $t?->name,
            'position' => $t?->position,
            'bio' => $t?->bio,
            'photo' => $member->photo ? Storage::url($member->photo) : null,
            'linkedin' => $member->linkedin,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function mapTestimonial(Testimonial $testimonial): array
    {
        $t = $testimonial->getTranslation();

        return [
            'id' => $testimonial->id,
            'author_name' => $t?->author_name,
            'company' => $t?->company,
            'content' => $t?->content,
            'rating' => $testimonial->rating,
            'avatar' => $testimonial->avatar ? Storage::url($testimonial->avatar) : null,
        ];
    }
}
