@if (isset($scorecard['police_funding']['total_officers_2013']) && isset($scorecard['police_funding']['total_officers_2014']) && isset($scorecard['police_funding']['total_officers_2015']))
<div class="stat-wrapper">
    <h3>Number of officers per 1k population</h3>

    <p>
        @php
        $total_officers = num($scorecard['police_funding']['total_officers_2019']);

        if (isset($scorecard['police_funding']['total_officers_2020'])) {
            $total_officers = num($scorecard['police_funding']['total_officers_2020']);
        }
        if (isset($scorecard['police_funding']['total_officers_2021'])) {
            $total_officers = num($scorecard['police_funding']['total_officers_2021']);
        }
        if (isset($scorecard['police_funding']['total_officers_2022'])) {
            $total_officers = num($scorecard['police_funding']['total_officers_2022']);
        }
        if (isset($scorecard['police_funding']['total_officers_2023'])) {
            $total_officers = num($scorecard['police_funding']['total_officers_2023']);
        }
        @endphp

        {{ $total_officers }} Officers
        <span class="divider">&nbsp;|&nbsp;</span>
        {{ num($scorecard['police_funding']['officers_per_10k_population'], 2) }} per 10k Residents
    </p>
    <p>
        More Officers per Population than {{ num($scorecard['police_funding']['percentile_officers_per_population'], 0, '%', true) }} of {{ $type === 'state' ? 'States' : 'Depts'}}
    </p>
    <p>
        <canvas id="bar-chart-officers-per-population"></canvas>
    </p>
    <p class="source-link-wrapper">
        Source:
        <a href="https://crime-data-explorer.fr.cloud.gov/pages/le/pe" class="source-link" rel="noopener" target="_blank" {!! trackData('External Nav', 'Officers per Population', 'Federal LEOKA Database') !!}>
            Federal LEOKA Database
        </a>
    </p>
</div>
@endif
