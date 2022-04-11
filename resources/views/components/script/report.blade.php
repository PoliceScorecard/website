
<script>
  var SCORECARD_STATE = '{{ $state }}';
  var SCORECARD_DATA = {!! json_encode($scorecard) !!};
  var map_data = {
    city: {!! $type === 'police-department' ? getMapData($state, 'police-department', $grades['all']) : 'null' !!} ,
    sheriff: {!! $type === 'sheriff' ? getMapData($state, 'sheriff', $grades['all']) : 'null' !!},
    selected: {!! getMapLocation($type, $scorecard, $location) !!}
  };
</script>

<script src="/maps/us-{{ strtolower($state) }}-all.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>

@if (isset($scorecard['police_accountability']['civilian_complaints_reported_2016']) || isset($scorecard['police_accountability']['civilian_complaints_reported_2017']) || isset($scorecard['police_accountability']['civilian_complaints_reported_2018']) || isset($scorecard['police_accountability']['civilian_complaints_reported_2019']) || isset($scorecard['police_accountability']['civilian_complaints_reported_2020']) || isset($scorecard['police_accountability']['civilian_complaints_reported_2021']) || isset($scorecard['police_accountability']['civilian_complaints_reported_2022']))
<script>
  window.addEventListener('load', function() {
    var renderComplaintsChart = function(complaintsCTX, complaintsData){
      return new Chart(complaintsCTX, {
        type: 'bar',
        data: complaintsData,
        options: {
          maintainAspectRatio: false,
          responsive: true,
          legend: {
            display: false,
          },
          title: {
            display: false,
          },
          tooltips: {
            mode: 'index',
            intersect: false,
            callbacks: {
              afterTitle: function() {
                  window.total = 0;
              },
              label: function(tooltipItem, data) {
                var label = (data.datasets[tooltipItem.datasetIndex].label) ? ' ' + data.datasets[tooltipItem.datasetIndex].label : '';
                var val = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];

                window.total += val;

                if (label) {
                  label += ': ';
                }

                label += PoliceScorecard.numberWithCommas(tooltipItem.yLabel);

                return label;
              },
              footer: function() {
                  return 'Total Complaints: ' + PoliceScorecard.numberWithCommas(window.total);
              }
            },
          },
          scales: {
            xAxes: [{
              stacked: true,
              gridLines: {
                color: "rgba(0, 0, 0, 0)",
              }
            }],
            yAxes: [{
              stacked: true,
              gridLines: {
                color: "rgba(0, 0, 0, 0)",
              },
              ticks: {
                beginAtZero: true,
                maxTicksLimit: 5,
                callback: function(value, index, values) {
                  return (value === 0) ? '' : PoliceScorecard.numberWithCommas(value);
                }
              }
            }]
          }
        }
      });
    };

    var civilianCTX = document.getElementById('bar-chart-civilian');
    if (civilianCTX) {
      var civilianData = {!! generateCivilianChart($scorecard, $type) !!};
      renderComplaintsChart(civilianCTX.getContext('2d'), civilianData);
    }

    var useOfForceCTX = document.getElementById('bar-chart-use-of-force');
    if (useOfForceCTX) {
      var useOfForceData = {!! generateUseOfForceChart($scorecard, $type) !!};
      renderComplaintsChart(useOfForceCTX.getContext('2d'), useOfForceData);
    }

    var discriminationCTX = document.getElementById('bar-chart-discrimination');
    if (discriminationCTX) {
      var discriminationData = {!! generateDiscriminationChart($scorecard, $type) !!};
      renderComplaintsChart(discriminationCTX.getContext('2d'), discriminationData);
    }

    var criminalCTX = document.getElementById('bar-chart-criminal');
    if (criminalCTX) {
      var criminalData = {!! generateCriminalChart($scorecard, $type) !!};
      renderComplaintsChart(criminalCTX.getContext('2d'), criminalData);
    }

    var detentionCTX = document.getElementById('bar-chart-detention');
    if (detentionCTX) {
      var detentionData = {!! generateDetentionChart($scorecard, $type) !!};
      renderComplaintsChart(detentionCTX.getContext('2d'), detentionData);
    }
  });
</script>
@endif

@if (isset($scorecard['arrests']['black_low_level_arrest_rate']) || isset($scorecard['arrests']['hispanic_low_level_arrest_rate']) || isset($scorecard['arrests']['white_low_level_arrest_rate']))
<script>
  window.addEventListener('load', function() {
    var lowLevelDisparityCTX = document.getElementById('bar-chart-low-level-disparity').getContext('2d');
    var lowLevelDisparityData = {!! generateArrestDisparityChart($scorecard, $type) !!};

    var renderlowLevelDisparityChart = function(disparityCTX, disparityData){
      return new Chart(disparityCTX, {
        type: 'bar',
        data: disparityData,
        options: {
          maintainAspectRatio: false,
          responsive: true,
          legend: {
            display: false,
          },
          title: {
            display: false,
          },
          tooltips: {
            mode: 'index',
            intersect: false
          },
          scales: {
            xAxes: [{
              stacked: true,
              gridLines: {
                color: "rgba(0, 0, 0, 0)",
              }
            }],
            yAxes: [{
              stacked: true,
              gridLines: {
                color: "rgba(0, 0, 0, 0)",
              },
              ticks: {
                beginAtZero: true,
                maxTicksLimit: 5,
                callback: function(value, index, values) {
                  return (value === 0) ? '' : PoliceScorecard.numberWithCommas(value);
                }
              }
            }]
          }
        }
      });
    };

    renderlowLevelDisparityChart(lowLevelDisparityCTX, lowLevelDisparityData);
  });
</script>
@endif

@if (isset($scorecard['arrests']))
<script>
  window.addEventListener('load', function() {
    var ctx = document.getElementById('bar-chart-arrests').getContext('2d');
    var arrestsData = {!! generateArrestChart($scorecard, $type) !!};
    window.myBarArrests = new Chart(ctx, {
      type: 'bar',
      data: arrestsData,
      options: {
        maintainAspectRatio: false,
        responsive: document.documentElement.clientWidth > 940 ? false : true,
        legend: {
          display: false,
        },
        title: {
          display: false,
        },
        tooltips: {
          mode: 'index',
          intersect: false,
          callbacks: {
            afterTitle: function() {
                window.total = 0;
            },
            label: function(tooltipItem, data) {
              var label = (data.datasets[tooltipItem.datasetIndex].label) ? ' ' + data.datasets[tooltipItem.datasetIndex].label : '';
              var val = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];

              window.total += val;

              if (label) {
                label += ': ';
              }

              label += PoliceScorecard.numberWithCommas(tooltipItem.yLabel);

              return label;
            },
            footer: function() {
                return 'Total Arrests: ' + PoliceScorecard.numberWithCommas(window.total);
            }
          },
        },
        scales: {
          xAxes: [{
            stacked: true,
            gridLines: {
              color: "rgba(0, 0, 0, 0)",
            }
          }],
          yAxes: [{
            stacked: true,
            gridLines: {
              color: "rgba(0, 0, 0, 0)",
            },
            ticks: {
              beginAtZero: true,
              maxTicksLimit: 2,
              callback: function(value, index, values) {
                return (value === 0) ? '' : PoliceScorecard.numberWithCommas(value);
              }
            }
          }]
        }
      }
    });
  });
</script>
@endif

@if(isset($scorecard['police_violence']))
<script>
window.addEventListener('load', function() {
    var $historyChart = document.getElementById('bar-chart-history');
    if ($historyChart) {
        var ctx = $historyChart.getContext('2d');
        var historyChartData = {!! generateHistoryChart($scorecard) !!};
        window.myBarHistory = new Chart(ctx, {
            type: 'bar',
            data: historyChartData,
            options: {
                animation: {
                    duration: 0,
                },
                maintainAspectRatio: false,
                responsive: document.documentElement.clientWidth > 940 ? false : true,
                legend: {
                    display: false,
                },
                title: {
                    display: false,
                },
                tooltips: {
                    mode: 'index',
                    intersect: false
                },
                scales: {
                    xAxes: [{
                        stacked: true,
                        gridLines: {
                            color: "rgba(0, 0, 0, 0)",
                        }
                    }],
                    yAxes: [{
                        stacked: true,
                        gridLines: {
                            color: "rgba(0, 0, 0, 0)",
                        },
                        ticks: {
                            beginAtZero: true,
                            maxTicksLimit: 2,
                            callback: function(value, index, values) {
                                return (value === 0) ? '' : Math.round(value);
                            }
                        }
                    }]
                }
            }
        });
    }

    var $violenceChart = document.getElementById('bar-violence-history');
    if ($violenceChart) {
        var violenceChartData = {!! json_encode(generateViolenceChart($scorecard)) !!};

        if (violenceChartData.series && violenceChartData.series.length > 0) {
            Highcharts.chart($violenceChart, {
                chart: {
                    type: 'column'
                },
                colors: [
                    '#b02424',
                    '#f19975',
                    '#9a9b9f',
                    '#a7cc84',
                    '#d4d9e4'
                ],
                title: {
                    enabled: false,
                    text: ''
                },
                xAxis: {
                    categories: violenceChartData.categories
                },
                yAxis: {
                    min: 0,
                    title: {
                        enabled: false,
                        text: ''
                    },
                    stackLabels: {
                        enabled: true,
                        style: {
                            fontWeight: 'bold',
                            color: ( // theme
                                Highcharts.defaultOptions.title.style &&
                                Highcharts.defaultOptions.title.style.color
                            ) || 'gray'
                        }
                    }
                },
                legend: {
                    align: 'center',
                    verticalAlign: 'top',
                    layout: 'horizontal',
                    width: '100%',
                    margin: 30,
                    padding: 0
                },
                tooltip: {
                    className: 'police-funding',
                    followPointer: false,
                    shared: false,
                    borderWidth: 0,
                    backgroundColor: 'rgba(0, 0, 0, 0.9)',
                    borderRadius: 8,
                    padding: 16,
                    shadow: false,
                    style: {
                        color: '#FFF',
                        padding: 8
                    },
                    headerFormat: '<b>YEAR:</b> {point.x}<br/>',
                    pointFormat: '<b>{series.name}</b>: {point.y}<br/><b>Total</b>: {point.stackTotal}'
                },
                plotOptions: {
                    column: {
                        stacking: 'normal',
                        dataLabels: {
                            enabled: true
                        }
                    },
                    series: {
                        pointWidth: 20
                    }
                },
                series: violenceChartData.series
            });
        }
    }
});

</script>
@endif

@if(isset($scorecard['police_funding']))
<script>
  window.addEventListener('load', function() {
    var ctxFundsTaken = document.getElementById('bar-chart-funds-taken').getContext('2d');
    var chartFundsTakenData = {!! generateBarChartFundsTaken($scorecard) !!};
    window.chartFundsTaken = new Chart(ctxFundsTaken, {
      type: 'bar',
      data: chartFundsTakenData,
      options: {
        animation: {
          duration: 0,
        },
        maintainAspectRatio: false,
        responsive: document.documentElement.clientWidth > 940 ? false : true,
        legend: {
          display: false,
        },
        title: {
          display: false,
        },
        tooltips: {
          mode: 'index',
          intersect: false,
          callbacks: {
            label: function(tooltipItem, data) {
              var label = (data.datasets[tooltipItem.datasetIndex].label) ? ' ' + data.datasets[tooltipItem.datasetIndex].label : '';

              if (label) {
                label += ': ';
              }

              label += PoliceScorecard.nFormatter(tooltipItem.yLabel);

              return label;
            }
          },
        },
        scales: {
          xAxes: [{
            stacked: true,
            gridLines: {
              color: "rgba(0, 0, 0, 0)",
            }
          }],
          yAxes: [{
            reversedStacks: true,
            stacked: true,
            gridLines: {
              color: "rgba(0, 0, 0, 0)",
            },
            ticks: {
              beginAtZero: true,
              maxTicksLimit: 2,
              callback: function(value, index, values) {
                return (value === 0) ? '' : PoliceScorecard.nFormatter(value);
              }
            }
          }]
        }
      }
    });
  });

</script>

<script>
  window.addEventListener('load', function() {
    var ctxOfficers = document.getElementById('bar-chart-officers-per-population').getContext('2d');
    var chartOfficersData = {!! generateBarChartOfficers($scorecard) !!};
    window.chartFundsTaken = new Chart(ctxOfficers, {
      type: 'bar',
      data: chartOfficersData,
      options: {
        animation: {
          duration: 0,
        },
        maintainAspectRatio: false,
        responsive: document.documentElement.clientWidth > 940 ? false : true,
        legend: {
          display: false,
        },
        title: {
          display: false,
        },
        tooltips: {
          mode: 'index',
          intersect: false,
          callbacks: {
            label: function(tooltipItem, data) {
              var label = (data.datasets[tooltipItem.datasetIndex].label) ? ' ' + data.datasets[tooltipItem.datasetIndex].label : '';

              if (label) {
                label += ': ';
              }

              label += PoliceScorecard.numberWithCommas(tooltipItem.yLabel);

              return label;
            }
          },
        },
        scales: {
          xAxes: [{
            stacked: true,
            gridLines: {
              color: "rgba(0, 0, 0, 0)",
            }
          }],
          yAxes: [{
            stacked: true,
            gridLines: {
              color: "rgba(0, 0, 0, 0)",
            },
            ticks: {
              beginAtZero: true,
              maxTicksLimit: 2,
              callback: function(value, index, values) {
                return (value === 0) ? '' : PoliceScorecard.numberWithCommas(value);
              }
            }
          }]
        }
      }
    });
  });

</script>
@endif

<script>
  var policeFundingChart = {!! getPoliceFundingChart($scorecard['police_funding']) !!};
  window.addEventListener('load', function() {
    if (policeFundingChart && typeof policeFundingChart.labels !== 'undefined' && policeFundingChart.labels.length > 0) {
      Highcharts.chart(document.getElementById('chart-police-funding'), {
        chart: {
          type: 'line',
          height: 300
        },
        legend: {
          align: 'center',
          verticalAlign: 'top',
          layout: 'horizontal',
          width: '100%',
          margin: 30,
          padding: 0
        },
        title: {
          enabled: false,
          text: ''
        },
        yAxis: {
          labels: {
            enabled: true,
            formatter: function () {
              return (this.value === 0) ? '' : PoliceScorecard.nFormatter(this.value);
            }
          },
          title: {
            text: ''
          }
        },
        colors: [
          '#dc4646',
          '#7c8894',
          '#a7cc84',
          '#c5882a'
        ],
        tooltip: {
          className: 'police-funding',
          followPointer: false,
          shared: true,
          borderWidth: 0,
          backgroundColor: 'rgba(0, 0, 0, 0.9)',
          borderRadius: 8,
          padding: 16,
          shadow: false,
          style: {
            color: '#FFF',
            padding: 8
          },
          pointFormatter: function() {
            var shape = '●';
            if (this.series.name === 'Health') {
                shape = '■';
            } else if (this.series.name === 'Housing') {
                shape = '▴';
            } else if (this.series.name === 'Corrections') {
                shape = '⬥';
            }

            return '<span style="color:' + this.color + '; font-size: 16px; vertical-align: middle;">' + shape + '</span> ' + this.series.name + ': <b>$' + this.y.toLocaleString() + '</b><br/>';
          }
        },
        xAxis: {
          categories: policeFundingChart.labels,
          title: {
            enabled: false
          }
        },
        plotOptions: {
          series: {
            fillOpacity: 0.1
          }
        },
        series: [
          {
            name: 'Police',
            lineColor: '#dc4646',
            marker: {
              symbol: 'circle'
            },
            data: policeFundingChart.police
          },
          {
            name: 'Health',
            lineColor: '#7c8894',
            marker: {
              symbol: 'square'
            },
            data: policeFundingChart.health
          },
          {
            name: 'Housing',
            lineColor: '#a7cc84',
            marker: {
              symbol: 'triangle'
            },
            data: policeFundingChart.housing
          },
          {
            name: 'Corrections',
            lineColor: '#c5882a',
            marker: {
              symbol: 'diamond'
            },
            data: policeFundingChart.corrections
          }
        ]
      });
    }
  });
</script>

<script>
  window.addEventListener('load', function() {
    var $deadlyForceChart = document.getElementById('deadly-force-chart');
    var $chartMiniPeopleKilled = document.getElementById('chart-mini-people-killed');
    var $chartMiniComplaintsReported = document.getElementById('chart-mini-complaints-reported');

    PoliceScorecard.loadMap('{{ $state }}');

    if ($deadlyForceChart) {
      var chart = new Chart($deadlyForceChart.getContext('2d'), {
        type: 'doughnut',
        options: {
          cutoutPercentage: 75,
          animation: {
            animateRotate: true,
            animateScale: false
          },
          tooltips: {
            callbacks: {
              label: function(tooltip, data) {
                return ' ' + data['labels'][tooltip.index] + ': ' + data['datasets'][tooltip.datasetIndex]['data'][tooltip.index] + '%';
              }
            }
          },
          legend: {
            display: true,
            labels: {
              boxWidth: 20
            }
          }
        },
        data: {
          labels: [
            'Unarmed',
            'Other',
            'Gun',
            'Vehicle'
          ],
          datasets: [{
            borderWidth: 0,
            data: [
              {{ $scorecard['report']['percent_used_against_people_who_were_unarmed'] }},
              {{ ($scorecard['report']['percent_used_against_people_who_were_not_armed_with_gun'] - $scorecard['report']['percent_used_against_people_who_were_unarmed']) }},
              {{ (100 - floatval($scorecard['report']['percent_used_against_people_who_were_not_armed_with_gun'])) }},
              {{ $scorecard['police_violence']['vehicle_people_killed'] }}
            ],
            backgroundColor: [
              '#dc4646',
              '#5a6f83',
              '#f3f4f6',
              '#aab8c5'
            ],
            hoverBackgroundColor: [
              '#dc4646',
              '#5a6f83',
              '#f3f4f6',
              '#aab8c5'
            ]
          }]
        }
      });
    }

    if ($chartMiniPeopleKilled) {
      Highcharts.chart($chartMiniPeopleKilled, {
        chart: {
          type: 'column',
          backgroundColor: 'transparent',
          width: 100,
          height: 100,
          margin: 0,
          maintainAspectRatio: false,
          clip: false
        },
        responsive: document.documentElement.clientWidth > 940 ? false : true,
        legend: {
          enabled: false
        },
        title: {
          enabled: false,
          text: ''
        },
        tooltip: {
          enabled: false
        },
        xAxis: {
          gridLineWidth: 0,
          lineWidth: 0,
          tickWidth: 0,
          labels: {
            enabled: false
          },
          title: {
            text: ''
          }
        },
        yAxis: {
          gridLineWidth: 0,
          lineWidth: 0,
          tickWidth: 0,
          labels: {
            enabled: false
          },
          title: {
            text: ''
          }
        },
        plotOptions: {
          series: {
            borderWidth: 0,
            groupPadding: 0,
            pointPadding: 0.1,
            animation: false,
            enableMouseTracking: false,
            color: '#FFFFFF',
            dataLabels: {
              rotation: -90,
              color: '#d8d8d8',
              enabled: true,
              format: '{point.name}',
              align: 'left',
              y: -8,
              shadow: false,
              crop: false,
              style: {
                fontSize: document.documentElement.clientWidth > 940 ? '12px' : '10px',
                fontFamily: 'Verdana, sans-serif',
                textTransform: 'uppercase',
                opacity: '1 !important'
              }
            }
          }
        },
        series: [{
          data: [
            ['Black', {{ (!isset($scorecard['agency']['black_population']) || $scorecard['agency']['black_population'] === 0) ? 0.1 : round(0.1 + ($scorecard['police_violence']['black_people_killed'] / $scorecard['agency']['black_population']) * 100, 2) }}],
            ['Latinx', {{ (!isset($scorecard['agency']['hispanic_population']) || $scorecard['agency']['hispanic_population'] === 0) ? 0.1 : round(0.1 + ($scorecard['police_violence']['hispanic_people_killed'] / $scorecard['agency']['hispanic_population']) * 100, 2) }}],
            ['White', {{ (!isset($scorecard['agency']['white_population']) || $scorecard['agency']['white_population'] === 0) ? 0.1 : round(0.1 + ($scorecard['police_violence']['white_people_killed'] / $scorecard['agency']['white_population']) * 100, 2) }}]
          ]
        }]
      });
    }

    if ($chartMiniComplaintsReported) {
      new Chart($chartMiniComplaintsReported.getContext('2d'), {
        type: 'doughnut',
        chart: {
          backgroundColor: 'transparent',
          width: 125,
          height: 125
        },
        responsive: document.documentElement.clientWidth > 940 ? false : true,
        legend: {
          enabled: false
        },
        title: {
          enabled: false,
          text: ''
        },
        tooltip: {
          enabled: false
        },
        options: {
          cutoutPercentage: 75,
          animation: {
            animateRotate: true,
            animateScale: false
          },
          tooltips: {
            callbacks: {
              label: function(tooltip, data) {
                var labels = ['Sustained', 'Reports'];
                return ' ' + (data['datasets'][tooltip.datasetIndex]['data'][tooltip.index]).toLocaleString() + ' ' + labels[tooltip.index];
              }
            }
          },
          legend: {
            display: false
          }
        },
        data: {
          datasets: [{
            borderWidth: 0,
            data: [
              CHART_MINI_SUSTAINED,
              (CHART_MINI_REPORTED === 0) ? 1 : CHART_MINI_REPORTED
            ],
            backgroundColor: [
              '#d8d8d8',
              '#58595b'
            ]
          }]
        }
      });

      var label = ((CHART_MINI_SUSTAINED === 0 && CHART_MINI_REPORTED === 0) || CHART_MINI_REPORTED === 0) ? '0' : Math.round((CHART_MINI_SUSTAINED / CHART_MINI_REPORTED) * 100);
      document.getElementById('chart-mini-complaints-reported-label').innerHTML = label + '%';
    }

    setTimeout(function() {
        Array.prototype.forEach.call(document.getElementsByClassName('highcharts-credits'), function(el) {
            el.innerHTML = '';
        });
    }, 10)

    setTimeout(PoliceScorecard.animate, 250);
  });
</script>
