<div class="stat-wrapper">
    <h3>Complaints of Police Discrimination</h3>

@if (num($scorecard['police_accountability']['discrimination_complaints_reported']) === '0')
    <p>0 Complaints Reported</p>
    <div class="progress-bar-wrapper">
        <div class="progress-bar bright-green" style="width: 0"></div>
    </div>
    <p class="note">&nbsp;</p>
@else
    <p>
        {{ num($scorecard['police_accountability']['discrimination_complaints_reported']) }} Reported
        <span class="divider">&nbsp;|&nbsp;</span>
        {{ num($scorecard['report']['percent_discrimination_complaints_sustained'], 0, '%') }} Ruled in Favor of Civilians
    </p>
    @if (!isset($scorecard['report']['percent_discrimination_complaints_sustained']) && !isset($scorecard['police_accountability']['discrimination_complaints_reported']) )
    <div class="progress-bar-wrapper">
        <div class="progress-bar no-data" style="width: 0"></div>
    </div>
    <x-partial.no-data-found />
    @else
    <div class="progress-bar-wrapper">
        <div class="progress-bar animate-bar {{ progressBar(100 - $scorecard['report']['percent_discrimination_complaints_sustained'], 'reverse') }}" data-percent="{{ output($scorecard['report']['percent_discrimination_complaints_sustained'], 0, '%') }}"></div>
    </div>
    <p class="note" style="margin-bottom: 0;">&nbsp;</p>
    <div class="keys">
        <strong style="font-weight: 600;">YEARLY:</strong>
        <span class="key key-red tooltip" data-tooltip="Reported"></span> Reported
        <span class="key key-green tooltip" data-tooltip="Sustained"></span> Sustained
    </div>

    <p style="margin-top: 18px; margin-bottom: 6px;">
        <canvas id="bar-chart-discriminiation"></canvas>
    </p>
    @endif
@endif
</div>
