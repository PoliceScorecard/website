@if (isset($scorecard['police_violence']['police_shootings_2016']) && isset($scorecard['police_violence']['police_shootings_2017']) && isset($scorecard['police_violence']['police_shootings_2018']))
<div class="stat-wrapper">
    <h3>Police Shootings</h3>
    @if(intval($scorecard['police_violence']['percentile_police_shootings_per_arrest']) > 0)
        <p>More Police Shootings per Arrest than {{ num(100 - $scorecard['police_violence']['percentile_police_shootings_per_arrest'], 0, '%') }} of {{ $type === 'state' ? 'States' : 'Depts'}}</p>
        <p>
            {{ num($scorecard['report']['police_shootings_incidents'], 0) }} Shootings
            <span class="divider">&nbsp;|&nbsp;</span>
            {{ $scorecard['police_violence']['police_shootings_per_arrest'] }} every 100k arrests
        </p>

        <p><canvas id="bar-chart-history"></canvas></p>
    @else
    <p>No police shootings reported.</p>
    @endif
</div>
@endif
