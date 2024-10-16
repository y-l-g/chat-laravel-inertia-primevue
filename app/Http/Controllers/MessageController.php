<?php

namespace App\Http\Controllers;

use App\Events\MessageReceived;
use App\Models\Message;
use App\Http\Requests\StoreMessageRequest;
use App\Http\Requests\UpdateMessageRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;
        $messages = Message::with('user')
            ->latest()
            ->get();
        return Inertia::render('Chat', [
            'messages' => $messages,
            'user_id' => $user_id,
            'user_name' => $user_name
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "content" => "required"
        ]);
        $message = Message::create([
            "content" => $validated['content'],
            "user_id" => auth()->user()->id
        ]);

        MessageReceived::dispatch($message, auth()->user());
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Message $message)
    {
        $message = Message::findOrFail($message->id)
            ->update($request->validated([
                "content" => "required"
            ]));
        return $message;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Message $message)
    {
        Message::findOrFail($message->id)
            ->delete();

    }
}
