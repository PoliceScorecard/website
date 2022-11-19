<div class="stat-wrapper">
    <h3>Total civilian complaints</h3>

    @if (output($scorecard['police_accountability']['civilian_complaints_reported']) === '0')
    <p>0 Complaints Reported</p>
    @else
    <p>
        {{ num($scorecard['police_accountability']['civilian_complaints_reported']) }} from {{ $scorecard['police_accountability']['years_of_complaints_data'] }}
        <span class="divider">&nbsp;|&nbsp;</span>
        {{ num($scorecard['report']['complaints_sustained'], 0, '%') }} Ruled in Favor of Civilians
    </p>
    @endif

    @if (!isset($scorecard['report']['complaints_sustained']))
    <div class="progress-bar-wrapper">
        <div class="progress-bar no-data" style="width: 0"></div>
    </div>
    <x-partial.no-data-found />
    @else
    <div class="progress-bar-wrapper">
        <div class="progress-bar animate-bar {{ progressBar(100 - intval($scorecard['report']['complaints_sustained']), 'reverse') }}" data-percent="{{ output(intval($scorecard['report']['complaints_sustained']), 0, '%') }}"></div>
    </div>
    <p class="note" style="margin-bottom: 0;">&nbsp;</p>
    <div class="keys">
        <span class="key key-red tooltip" data-tooltip="Complaints Not Sustained"></span> Complaints Not Sustained
        <span class="key key-grey tooltip" data-tooltip="Complaints Sustained"></span> Complaints Sustained
    </div>

    <p style="margin-top: 18px; margin-bottom: 6px;">
        <canvas id="bar-chart-civilian"></canvas>
    </p>
    @endif
</div>
