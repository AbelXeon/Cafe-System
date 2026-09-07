<?php

namespace App\Http\Controllers;

use App\Events\ChatMessageSent;
use App\Models\ChatMessage;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * List conversations for the logged-in CUSTOMER — their orders
     * that have (or had) a driver assigned.
     */
    public function customerConversations()
    {
        $userId = Auth::id();

        $orders = Order::with('deliveryUser')
            ->where('user_id', $userId)
            ->whereNotNull('delivery_user_id')
            ->orderByDesc('updated_at')
            ->get()
            ->map(fn ($o) => $this->formatConversation($o, 'customer'));

        return response()->json(['conversations' => $orders->values()]);
    }

    /**
     * List conversations for the logged-in DRIVER — orders assigned to them.
     */
    public function driverConversations()
    {
        $userId = Auth::id();

        $orders = Order::with('user')
            ->where('delivery_user_id', $userId)
            ->orderByDesc('updated_at')
            ->get()
            ->map(fn ($o) => $this->formatConversation($o, 'driver'));

        return response()->json(['conversations' => $orders->values()]);
    }

    protected function formatConversation(Order $order, string $viewerRole)
    {
        $otherParty = $viewerRole === 'customer'
            ? ($order->deliveryUser?->fullname ?? $order->deliveryUser?->name ?? 'Driver')
            : ($order->user?->fullname ?? $order->user?->name ?? 'Customer');

        $lastMessage = ChatMessage::where('order_id', $order->id)->latest()->first();

        return [
            'order_id'     => $order->id,
            'other_party'  => $otherParty,
            'status'       => $order->status,
            'can_chat'     => $order->status === 'out_for_delivery',
            'last_message' => $lastMessage?->message,
            'last_at'      => $lastMessage?->created_at?->diffForHumans(),
        ];
    }

    /**
     * Fetch full message history for one order's chat.
     * Only the two participants (customer + assigned driver) may view.
     */
    public function messages(Order $order)
    {
        $this->authorizeParticipant($order);

        $messages = ChatMessage::with('sender')
            ->where('order_id', $order->id)
            ->orderBy('created_at')
            ->get()
            ->map(fn ($m) => $this->formatMessage($m));

        return response()->json([
            'messages' => $messages->values(),
            'can_send' => $order->status === 'out_for_delivery',
            'order'    => [
                'id'     => $order->id,
                'status' => $order->status,
            ],
        ]);
    }

    /**
     * Send a message. Only allowed while the order is actively
     * out for delivery — chat opens on accept, closes on delivery.
     */
    public function send(Request $request, Order $order)
    {
        $this->authorizeParticipant($order);

        if ($order->status !== 'out_for_delivery') {
            return response()->json([
                'message' => 'This chat is closed. Messaging is only available while the order is out for delivery.',
            ], 422);
        }

        $data = $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $chatMessage = ChatMessage::create([
            'order_id'  => $order->id,
            'sender_id' => Auth::id(),
            'message'   => $data['message'],
        ]);

        $chatMessage->load('sender');

        broadcast(new ChatMessageSent($chatMessage))->toOthers();

        return response()->json([
            'sent' => $this->formatMessage($chatMessage),
        ]);
    }

    protected function formatMessage(ChatMessage $m)
    {
        return [
            'id'          => $m->id,
            'sender_id'   => $m->sender_id,
            'sender_name' => $m->sender->fullname ?? $m->sender->name ?? 'User',
            'is_me'       => $m->sender_id === Auth::id(),
            'message'     => $m->message,
            'created_at'  => $m->created_at->format('H:i'),
        ];
    }

    protected function authorizeParticipant(Order $order)
    {
        $userId = Auth::id();
        abort_unless(
            $userId === $order->user_id || $userId === $order->delivery_user_id,
            403,
            'You are not part of this conversation.'
        );
    }
}