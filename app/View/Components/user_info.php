<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class user_info extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public $name,
        public $email,
        public $avatar,
        public $phone,
    )
    {
        $this->phone = null;
        $this->avatar = "avatar_default.jpg";
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.user_info');
    }
}
