<?php

namespace App\View\Components\Admin\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Notifications extends Component
{
    public $notifications;

    /**
     * Create a new component instance.
     */
    public function __construct($notifications = [])
    {
        $this->notifications = $notifications;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('admin.components.notifications');
    }
}
