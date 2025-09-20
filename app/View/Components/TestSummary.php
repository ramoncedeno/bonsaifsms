<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TestSummary extends Component
{
    public $successPercentage;
    public $successCount;
    public $failureCount;

    /**
     * Create a new component instance.
     */
    public function __construct($successPercentage, $successCount, $failureCount)
    {
        $this->successPercentage = $successPercentage;
        $this->successCount = $successCount;
        $this->failureCount = $failureCount;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.test-summary');
    }
}
