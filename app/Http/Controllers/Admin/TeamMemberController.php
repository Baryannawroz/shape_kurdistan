<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TeamMemberFormRequest;
use App\Models\TeamMember;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class TeamMemberController extends Controller
{
    public function __construct(
        private ImageUploadService $images
    ) {}

    public function index(): Response
    {
        $members = TeamMember::query()
            ->with('translations')
            ->orderBy('order')
            ->get()
            ->map(fn (TeamMember $m) => [
                'id' => $m->id,
                'order' => $m->order,
                'is_active' => $m->is_active,
                'name' => $m->getTranslation(config('app.fallback_locale'))?->name,
            ]);

        return Inertia::render('Admin/Team/Index', [
            'members' => $members,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Team/Edit', [
            'member' => null,
            'locales' => array_keys(config('app.locales')),
        ]);
    }

    public function store(TeamMemberFormRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request): void {
            $data = [
                'role_key' => $request->input('role_key'),
                'linkedin' => $request->input('linkedin'),
                'is_active' => $request->boolean('is_active', true),
                'order' => (int) $request->input('order', 0),
            ];

            if ($request->hasFile('photo')) {
                $data['photo'] = $this->images->storeWithThumbnail($request->file('photo'), 'team')['path'];
            }

            $member = TeamMember::query()->create($data);

            foreach ($request->validated()['translations'] as $row) {
                $member->translations()->create([
                    'locale' => $row['locale'],
                    'name' => $row['name'],
                    'position' => $row['position'] ?? null,
                    'bio' => $row['bio'] ?? null,
                ]);
            }
        });

        return redirect()->route('admin.cms.team-members.index')->with('success', __('Saved.'));
    }

    public function edit(TeamMember $teamMember): Response
    {
        $teamMember->load('translations');

        return Inertia::render('Admin/Team/Edit', [
            'member' => [
                'id' => $teamMember->id,
                'order' => $teamMember->order,
                'is_active' => $teamMember->is_active,
                'role_key' => $teamMember->role_key,
                'linkedin' => $teamMember->linkedin,
                'photo' => $teamMember->photo,
                'translations' => $teamMember->translations->keyBy('locale'),
            ],
            'locales' => array_keys(config('app.locales')),
        ]);
    }

    public function update(TeamMemberFormRequest $request, TeamMember $teamMember): RedirectResponse
    {
        DB::transaction(function () use ($request, $teamMember): void {
            $data = [
                'role_key' => $request->input('role_key'),
                'linkedin' => $request->input('linkedin'),
                'is_active' => $request->boolean('is_active', true),
                'order' => (int) $request->input('order', 0),
            ];

            if ($request->hasFile('photo')) {
                $data['photo'] = $this->images->storeWithThumbnail($request->file('photo'), 'team')['path'];
            }

            $teamMember->update($data);

            foreach ($request->validated()['translations'] as $row) {
                $teamMember->translations()->updateOrCreate(
                    ['locale' => $row['locale']],
                    [
                        'name' => $row['name'],
                        'position' => $row['position'] ?? null,
                        'bio' => $row['bio'] ?? null,
                    ]
                );
            }
        });

        return redirect()->back()->with('success', __('Saved.'));
    }

    public function destroy(TeamMember $teamMember): RedirectResponse
    {
        $teamMember->delete();

        return redirect()->route('admin.cms.team-members.index')->with('success', __('Deleted.'));
    }

    public function reorder(Request $request): RedirectResponse
    {
        $order = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:team_members,id'],
        ]);

        foreach ($order['ids'] as $index => $id) {
            TeamMember::query()->whereKey($id)->update(['order' => $index]);
        }

        return redirect()->back()->with('success', __('Order updated.'));
    }
}
