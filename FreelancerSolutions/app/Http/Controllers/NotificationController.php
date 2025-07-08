<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        $notifications = $user->notifications()->latest()->paginate(10); // Pagina as notificações

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead(string $id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        // Redireciona para o link da notificação se houver
        if (isset($notification->data['link'])) {
            return redirect($notification->data['link'])->with('success', 'Notificação marcada como lida.');
        }

        return back()->with('success', 'Notificação marcada como lida.');
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return back()->with('success', 'Todas as notificações foram marcadas como lidas.');
    }

    public function markAsReadAndRedirect($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
    
        // Redireciona para o link dentro da notificação, ou para a home se não houver link
        return redirect($notification->data['link'] ?? '/dashboard');
    }
}
