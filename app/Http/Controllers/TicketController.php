<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Models\User;
use App\Notifications\TicketUpdatedNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // using relationships to just get the tickets of the user which is logged in and if it is admin can see all the tickets:
        $user = auth()->user();
        // latest is the orderby
        $tickets = $user->is_admin ? Ticket::latest()->get() : $user->tickets;
        return view('dashboard', ['tickets' => $tickets]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('ticket.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTicketRequest $request)
    {
        $ticket = Ticket::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => Auth::user()->id,
        ]);

        if ($request->file('attachment')) {
            $this->storeAttachment($request, $ticket);
        }



        return response()->redirectToRoute('dashboard');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        return view('ticket.show', compact('ticket'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        return view('ticket.edit', compact('ticket'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        // dd($request->except('attachment'));
        $ticket->update($request->except('attachment'));

        if ($request->has('status')) {
            // sending notification (we need to configurate emails as well):
            $ticket->user->notify(new TicketUpdatedNotification($ticket));

            //! to preview the email
            // todo: return (new TicketUpdatedNotification($ticket))->toMail($user);
        }

        if ($request->file('attachment')) {
            Storage::disk('public')->delete($ticket->attachment);
            $this->storeAttachment($request, $ticket);
        }

        return redirect()->route('dashboard');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return redirect()->route('dashboard');
    }

    protected function storeAttachment($request, $ticket)
    {
        $extention = $request->file('attachment')->extension();
        $contents = file_get_contents($request->file('attachment'));
        $filename = Str::random(25);
        $path = "attachments/$filename.$extention";
        Storage::disk('public')->put($path, $contents);
        $ticket->update(['attachment' => $path]);
    }
}
