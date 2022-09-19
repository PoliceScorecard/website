@extends('layouts.app')

@section('title', $title)
@section('description', $description)

@section('content')
    <x-partial.menu />

    <x-partial.hero-report :type="$type" :state="$state" :location="$location" :stateData="$stateData" />

    <x-partial.location-selection :type="$type" :scorecard="$scorecard" :state="$state" />

    <x-partial.at-a-glance :type="$type" :scorecard="$scorecard" />

    <x-partial.killings-by-police-report :state="$state" :type="$type" :scorecard="$scorecard" />

    <!-- Police Funding -->
    <div class="section bb pad funding">
        <!-- Section Title -->
        <x-partial.police-funding.title :type="$type" :location="$location" :scorecard="$scorecard" />

        <div class="content">
            <div class="left">
                <!-- Police Funding -->
                <x-partial.police-funding.chart-police-funding :type="$type" :location="$location" :scorecard="$scorecard" />

                <!-- Funds Spent -->
                <x-partial.police-funding.chart-funds-spent :type="$type" :location="$location" :scorecard="$scorecard" />
            </div>

            <div class="right">
                <!-- Funds Taken from Communities -->
                <x-partial.police-funding.chart-funds-taken  :type="$type":location="$location" :scorecard="$scorecard" />

                <!-- Officers Per Population -->
                <x-partial.police-funding.chart-officers-per-population :type="$type" :location="$location" :scorecard="$scorecard"  />
            </div>
        </div>
    </div>

    <!-- Police Violence -->
    <div class="section bb pad score-details">
        <!-- Section Title -->
        <x-partial.police-violence.violence-title :type="$type" :location="$location" :scorecard="$scorecard"  />

        <div class="content">
            <div class="left">
                <!-- Police Use of Force by Year -->
                <x-partial.police-violence.chart-use-of-force :type="$type" :location="$location" :scorecard="$scorecard"  />

                <!-- Less-Lethal Force -->
                <x-partial.police-violence.chart-less-lethal-force :type="$type" :location="$location" :scorecard="$scorecard"  />

                <!-- Deadly Force -->
                <x-partial.police-violence.chart-deadly-force :type="$type" :location="$location" :scorecard="$scorecard"  />

                <!-- Police Shootings Where Police Did Not Attempt Non-Lethal Force Before Shooting -->
                <x-partial.police-violence.chart-police-shootings :type="$type" :location="$location" :scorecard="$scorecard" :state="$state" />

                <!-- Where Police say they saw a gun but no gun was found -->
                <x-partial.police-violence.chart-saw-gun :type="$type" :location="$location" :scorecard="$scorecard" :state="$state"  />
            </div>
            <div class="right">
                <!-- People Killed by Police, 2013-2019 -->
                <x-partial.police-violence.chart-people-killed :type="$type" :location="$location" :scorecard="$scorecard"  />

                <!-- Police Violence by race -->
                <x-partial.police-violence.chart-violence-by-race :type="$type" :location="$location" :scorecard="$scorecard"  />
            </div>
        </div>
    </div>

    <!-- Police Accountability -->
    <div class="section bb pad accountability">
        <!-- Section Title -->
        <x-partial.police-accountability.accountability-title :type="$type" :location="$location" :scorecard="$scorecard"  />

        <div class="content">
            <div class="left">
                <!-- Total civilian complaints -->
                <x-partial.police-accountability.chart-civilian-complaints :type="$type" :location="$location" :scorecard="$scorecard"  />

                <!-- Use of Force Complaints -->
                <x-partial.police-accountability.chart-use-of-force :type="$type" :location="$location" :scorecard="$scorecard"  />

                <!-- Complaints of Misconduct in Jail -->
                <x-partial.police-accountability.chart-misconduct :type="$type" :location="$location" :scorecard="$scorecard"  />
            </div>

            <div class="right">
                <!-- Complaints of Police Discrimination -->
                <x-partial.police-accountability.chart-police-discrimination :type="$type" :location="$location" :scorecard="$scorecard"  />

                <!-- Alleged Crimes Committed by Police -->
                <x-partial.police-accountability.chart-alleged-crimes :type="$type" :location="$location" :scorecard="$scorecard"  />
            </div>
        </div>
    </div>

    <!-- Approach to Law Enforcement -->
    <div class="section pad approach">
        <!-- Section Title -->
        <x-partial.approach-to-policing.approach-title :type="$type" :location="$location" :scorecard="$scorecard" />

        <div class="content">
            <div class="left">
                <!-- Arrests By Year -->
                <x-partial.approach-to-policing.chart-arrests-by-year :type="$type" :location="$location" :scorecard="$scorecard" />

                <!-- Arrests for Low Level Offenses -->
                <x-partial.approach-to-policing.chart-arrests-low-level :type="$type" :location="$location" :scorecard="$scorecard" />

                <!-- Disparities in Arrests for Low Level Offenses by Race/Ethnicity -->
                <x-partial.approach-to-policing.chart-disparities-low-level :type="$type" :location="$location" :scorecard="$scorecard" />

                <!-- Percent of total arrests by type -->
                <x-partial.approach-to-policing.chart-arrests-by-type :type="$type" :location="$location" :scorecard="$scorecard" />
            </div>

            <div class="right">
                <!-- Homicides Unsolved -->
                <x-partial.approach-to-policing.chart-homicides-unsolved :type="$type" :location="$location" :scorecard="$scorecard" />

                <!-- Percent of Homicides Unsolved by Race -->
                <x-partial.approach-to-policing.chart-homicides-unsolved-by-race :type="$type" :location="$location" :scorecard="$scorecard" />
            </div>
        </div>
    </div>

    @if(($type === 'sheriff' || $type === 'state') && $scorecard['jail']['avg_daily_jail_population'])
    <!-- Jail -->
    <div class="section pad jail">
        <div class="content">
            <div class="left">
                <!-- Deaths in Jail -->
                <x-partial.jail.chart-deaths-in-jail :type="$type" :location="$location" :scorecard="$scorecard"  />
            </div>

            <div class="right">
                <!-- People Transferred to ICE -->
                <x-partial.jail.chart-transferred-to-ice :type="$type" :location="$location" :scorecard="$scorecard"  />
            </div>
        </div>
    </div>
    @endif

    <x-partial.grades :state="$state" :type="$type" :grades="$grades" />

    <x-partial.about :type="$type" :scorecard="$scorecard" />

    <x-partial.whats-next />

    <x-partial.footer :state="$state" :states="$states" />
@endsection

@section('modal')
    <x-partial.modal :location="$location" :state="$state" :type="$type" :states="$states" :stateData="$stateData" />
@endsection

@section('scripts')
    <x-script.report :grades="$grades" :location="$location" :state="$state" :states="$states" :type="$type" :scorecard="$scorecard" />
@endsection
