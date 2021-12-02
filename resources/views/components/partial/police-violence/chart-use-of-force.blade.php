@if (isset($scorecard['police_violence']['police_shootings_2016']) && isset($scorecard['police_violence']['police_shootings_2017']) && isset($scorecard['police_violence']['police_shootings_2018']))
<div class="stat-wrapper">
    <h3>Police Use of Force By Year</h3>
    @if(intval($scorecard['police_violence']['percentile_police_shootings_per_arrest']) > 0)
        <p>More Police Shootings per Arrest than {{ num(100 - $scorecard['police_violence']['percentile_police_shootings_per_arrest'], 0, '%') }} of Depts</p>

        <p><canvas id="bar-chart-history"></canvas></p>
        <p>&nbsp;</p>
        <div id="bar-violence-history"></div>
    @else
    <p>No police shootings reported.</p>
    @endif
</div>
@endif
