<?php

namespace App\Livewire\Layouts;

use App\Models\User;
use Livewire\Component;
use App\Models\Division;
use Livewire\Attributes\On;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Auth;

class Sidebar extends Component
{
    public function logout()
    {
        Auth::guard('web')->logout();

        return redirect('/');
    }

    #[On('user-updated')]
    #[Computed]
    public function userCount()
    {
        return User::where('is_admin', false)->count();
    }

    #[On('division-updated')]
    #[Computed]
    public function divisionCount()
    {
        return Division::count();
    }

    public function render()
    {
        return view('livewire.layouts.sidebar');
    }
}
