<div class="stat-wrapper no-border-mobile">
    <h3>Disparities in Arrests for Low Level Offenses by Race/Ethnicity</h3>

    <p class="multiline">
        @if(isset($scorecard['arrests']['black_white_low_level_arrest_disparity']))
            Black people were {{ $scorecard['arrests']['black_white_low_level_arrest_disparity'] }}x more likely
        @endif

        @if(isset($scorecard['arrests']['black_white_low_level_arrest_disparity']) && isset($scorecard['arrests']['hispanic_white_low_level_arrest_disparity']))
            and
        @endif

        @if(isset($scorecard['arrests']['hispanic_white_low_level_arrest_disparity']))
            Latinx people were {{ $scorecard['arrests']['hispanic_white_low_level_arrest_disparity'] }}x more likely
        @endif

        to be arrested for low level, non-violent offenses than a white person.
    </p>

    <div class="keys">
        <span class="key key-red tooltip" data-tooltip="Black"></span> Black
        <span class="key key-orange tooltip" data-tooltip="Hispanic"></span> Latinx
        <span class="key key-white tooltip" data-tooltip="White"></span> White
    </div>
    <br><br>
    TODO: Make Chart with These Values<br><br>
    black_low_level_arrest_rate: {{ $scorecard['arrests']['black_low_level_arrest_rate'] }}<br>
    hispanic_low_level_arrest_rate: {{ $scorecard['arrests']['hispanic_low_level_arrest_rate'] }}<br>
    white_low_level_arrest_rate: {{ $scorecard['arrests']['white_low_level_arrest_rate'] }}<br>
</div>
