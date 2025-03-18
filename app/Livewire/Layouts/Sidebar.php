<?php

namespace App\Livewire\Layouts;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;

class Sidebar extends Component
{
    public function logout()
    {
        Auth::guard('web')->logout();

        return redirect('/');
    }

    #[Computed]
    public function userCount()
    {
        return User::where('is_admin', false)->count();
    }

    public function render()
    {
        return view('livewire.layouts.sidebar');
    }
}
