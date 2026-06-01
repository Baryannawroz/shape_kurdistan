<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ContactMessageController extends Controller
{
    public function index(): Response
    {
        $messages = ContactMessage::query()
            ->latest()
            ->paginate(20)
            ->through(fn (ContactMessage $m) => [
                'id' => $m->id,
                'name' => $m->name,
                'email' => $m->email,
                'subject' => $m->subject,
                'is_read' => $m->is_read,
                'created_at' => $m->created_at?->toIso8601String(),
            ]);

        return Inertia::render('Admin/Messages/Index', [
            'messages' => $messages,
        ]);
    }

    public function show(ContactMessage $message): Response
    {
        if (! $message->is_read) {
            $message->update(['is_read' => true]);
        }

        return Inertia::render('Admin/Messages/Show', [
            'message' => [
                'id' => $message->id,
                'name' => $message->name,
                'email' => $message->email,
                'phone' => $message->phone,
                'subject' => $message->subject,
                'body' => $message->message,
                'created_at' => $message->created_at?->toIso8601String(),
            ],
        ]);
    }

    public function destroy(ContactMessage $message): RedirectResponse
    {
        $message->delete();

        return redirect()->route('admin.cms.messages.index')->with('success', __('Deleted.'));
    }
}
