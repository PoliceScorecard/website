<?php

namespace App\View\Components\Partial\PoliceViolence;

use Illuminate\View\Component;

class ChartPoliceShootings extends Component
{
    public $location;
    public $scorecard;
    public $state;
    public $type;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($location = null, $scorecard = [], $state = null, $type = null)
    {
        $this->location = $location;
        $this->scorecard = $scorecard;
        $this->state = $state;
        $this->type = $type;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.partial.police-violence.chart-police-shootings');
    }
}
