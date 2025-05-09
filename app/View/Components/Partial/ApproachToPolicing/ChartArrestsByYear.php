<?php

namespace App\View\Components\Partial\ApproachToPolicing;

use Illuminate\View\Component;

class ChartArrestsByYear extends Component
{
    public $location;
    public $scorecard;
    public $type;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($location = null, $scorecard = [], $type = null)
    {
        $this->location = $location;
        $this->scorecard = $scorecard;
        $this->type = $type;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.partial.approach-to-policing.chart-arrests-by-year');
    }
}
