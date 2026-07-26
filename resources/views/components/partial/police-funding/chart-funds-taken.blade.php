@if (isset($scorecard['police_funding']['fines_forfeitures_2010']) && isset($scorecard['police_funding']['fines_forfeitures_2011']) && isset($scorecard['police_funding']['fines_forfeitures_2012']))
<div class="stat-wrapper">
    <h3>Funds taken from communities in fines and forfeitures</h3>

    @php
        $funds_taken = 0;
        $funds_taken_end_year = '2020';

        for ($year = 2010; $year <= 2025; $year++) {
            $key = "fines_forfeitures_{$year}";

            if (isset($scorecard['police_funding'][$key])) {
                $funds_taken += $scorecard['police_funding'][$key];

                if ($year > 2020) {
                    $funds_taken_end_year = (string) $year;
                }
            }
        }
    @endphp

    <p>
        Total: {{ nFormatter($funds_taken, 2) }}
        from 2010-{{ substr($funds_taken_end_year, -2) }}
    </p>
    <p>More Fines/Forfeitures than {{ $scorecard['police_funding']['percentile_fines_forfeitures_per_resident'] }}% of {{ $type === 'state' ? 'States' : 'Depts'}}</p>

    <p>
        <canvas id="bar-chart-funds-taken"></canvas>
    </p>

    @if (!empty($scorecard['police_funding']['budget_source_link']) && !empty($scorecard['police_funding']['budget_source_name']))
        <p class="source-link-wrapper">
            Source:
            <a href="{{ $scorecard['police_funding']['budget_source_link'] }}" class="source-link" rel="noopener" target="_blank" {!! trackData('External Nav', 'Funds Taken', $scorecard['police_funding']['budget_source_name']) !!}>
                {{ $scorecard['police_funding']['budget_source_name'] }}
            </a>
        </p>
    @endif
</div>
@endif
