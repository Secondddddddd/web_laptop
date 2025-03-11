<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class address extends Component
{
    /**
     * Danh sách địa chỉ.
     *
     * @var \Illuminate\Support\Collection
     */
    public $addresses;

    /**
     * Danh sách tỉnh/thành phố.
     *
     * @var \Illuminate\Support\Collection
     */
    public $provinces;

    /**
     * Đường dẫn hành động khi submit form.
     *
     * @var string
     */
    public $action;

    /**
     * Create a new component instance.
     *
     * @param \Illuminate\Support\Collection $addresses
     * @param \Illuminate\Support\Collection $provinces
     * @param string $action
     */
    public function __construct($addresses, $provinces, $action = '#')
    {
        $this->addresses = $addresses;
        $this->provinces = $provinces;
        $this->action = $action;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.address');
    }
}
