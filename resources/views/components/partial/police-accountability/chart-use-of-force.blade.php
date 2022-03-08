<div class="stat-wrapper">
    <h3>Use of Force Complaints</h3>

    @if (output($scorecard['police_accountability']['use_of_force_complaints_reported']) === '0')
    <p>0 Complaints Reported</p>
    <div class="progress-bar-wrapper">
        <div class="progress-bar bright-green" style="width: 0"></div>
    </div>
    <p class="note">&nbsp;</p>
    @elseif (!isset($scorecard['report']['percent_use_of_force_complaints_sustained']))
    <p>
        {{ num($scorecard['police_accountability']['use_of_force_complaints_reported']) }} Reported

        @if(isset($scorecard['report']['percent_use_of_force_complaints_sustained']))
        <span class="divider">&nbsp;|&nbsp;</span>
        {{ num($scorecard['report']['percent_use_of_force_complaints_sustained'], 0, '%') }} Ruled in Favor of Civilians
        @endif
    </p>
    <div class="progress-bar-wrapper">
        <div class="progress-bar no-data" style="width: 0"></div>
    </div>
    <x-partial.no-data-found />
    @else
    <p>
        {{ num($scorecard['police_accountability']['use_of_force_complaints_reported']) }} Reported

        @if(isset($scorecard['report']['percent_use_of_force_complaints_sustained']))
        <span class="divider">&nbsp;|&nbsp;</span>
        {{ num($scorecard['report']['percent_use_of_force_complaints_sustained'], 0, '%') }} Ruled in Favor of Civilians
        @endif
    </p>
    <div class="progress-bar-wrapper">
        <div class="progress-bar animate-bar {{ progressBar(100 - intval($scorecard['report']['percent_use_of_force_complaints_sustained']), 'reverse') }}" data-percent="{{ output(intval($scorecard['report']['percent_use_of_force_complaints_sustained']), 0, '%') }}"></div>
    </div>
    <p class="note" style="margin-bottom: 0;">&nbsp;</p>
    <div class="keys">
        <span class="key key-red tooltip" data-tooltip="Complaints Reported"></span> Complaints Reported
        <span class="key key-grey tooltip" data-tooltip="Ruled in Favor of Civilians"></span> Ruled in Favor of Civilians
    </div>

    <p style="margin-top: 18px; margin-bottom: 6px;">
        <canvas id="bar-chart-use-of-force"></canvas>
    </p>
    @endif
</div>
