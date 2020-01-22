var commerceDashboardPluginSalesRevenue = (function($, window) {
    var $body           = $("body"),
        instanceCount   = 0;

        function setUpChart() {
            charts.commerceDashboardPluginSalesRevenueChartStackedBars = {
                //chart data
                data: null,
                //will hold the chart object
                plot: null,
                options: {
                    grid: {
                        color: "#dedede",
                        borderWidth: 1,
                        borderColor: "transparent",
                        clickable: true,
                        hoverable: true,
                        backgroundColor: {
                            colors: [
                                "#fff", "#fff"
                            ]
                        }
                    },
                    series: {
                        stack: true,
                        grow: {
                            active: false
                        },
                        bars: {
                            show: true,
                            barWidth: 0.5,
                            fill: 1,
                            align: 'center'
                        }
                    },
                    xaxis: {
                        // we are not using any plugin for the xaxis, we use ticks instead.
                        mode: "time"
                    },
                    yaxis: {
                        min: 0,
                        tickDecimals: 2
                    },
                    legend: {
                        position: "ne",
                        backgroundColor: null,
                        backgroundOpacity: 0,
                        noColumns: 2
                    },
                    colors: [
                        "#7acc66",
                        "#66cccc"
                    ],
                    shadowSize: 0,
                    tooltip: true,
                    tooltipOpts: {
                        content: "%s : %y",
                        shifts: {
                            x: -30,
                            y: -50
                        },
                        defaultTheme: false
                    }
                },
                placeholder: ".commerce-dashboard-plugin-sales-revenue-placeholder",
                // initialize
                init: function() {
                    if (this.plot == null) {
                        // hook the init function for plotting the chart
                        commerceDashboardPluginSalesRevenueChartStackedBarsInit();
                    }
                }
            };
        }

        function commerceDashboardPluginSalesRevenueChartStackedBarsInit( target, placeholder ) {
            var target = target || null;
            var placeholder = placeholder || null;
            var chartFor = "";

            // run set up for initializing all the charts on first load of dashboard
            setUpChart();

            if (target == null) {
                var chartsArray = $body.find(".commerce-dashboard-plugin-sales-revenue-placeholder");
                var emptyChartCount = 0;

                //count the number of empty charts
                $body.find(".commerce-dashboard-plugin-sales-revenue-placeholder").each(function (index, value) {
                    if ($(this).text() === "") {
                        emptyChartCount++;
                    }
                });

                if (emptyChartCount === $body.find(".commerce-dashboard-plugin-sales-revenue-placeholder").length) {
                    //when count of empty charts is equal to the count of charts then it means the tab is closed and opened again.
                    var pluginConfig = $(chartsArray[instanceCount]).closest('.grid-stack-item').find('.grid-stack-item-content .widget .widget-parent .widget-body .dashboard-plugin-json-config').text();
                    pluginConfig = JSON.parse(pluginConfig);
                    instanceCount++;

                    if (instanceCount === chartsArray.length) {
                        instanceCount = 0;
                    }

                    chartFor = pluginConfig.datas.activeFilter;
                    placeholder = "#commerce-dashboard-plugin-sales-revenue-placeholder-" + pluginConfig.plugin_id;
                } else {
                    //when a new plugin is dragged to the grid stack
                    var lastItem = $body.find(".commerce-dashboard-plugin-sales-revenue-placeholder").length - 1;
                    var pluginConfig = $(chartsArray[lastItem]).closest('.grid-stack-item').find('.grid-stack-item-content .widget .widget-parent .widget-body .dashboard-plugin-json-config').text();
                    pluginConfig = JSON.parse(pluginConfig);

                    chartFor = pluginConfig.datas.activeFilter;
                    placeholder = "#commerce-dashboard-plugin-sales-revenue-placeholder-" + pluginConfig.plugin_id;
                }
            } else if (typeof target === "string") {
                //when initializing the charts on first load of dashboard
                chartFor = target;
                placeholder = "#commerce-dashboard-plugin-sales-revenue-placeholder-" + placeholder;
            } else {
                //when a filter is selected
                chartFor = target.val();
                placeholder = "#" + target.closest(".tab-pane").find(".commerce-dashboard-plugin-sales-revenue-placeholder").attr("id");
            }

            $.ajax({
                type: 'POST',
                url: '/melis/dashboard-plugin/MelisCommerceDashboardPluginSalesRevenue/getDashboardSalesRevenueData',
                data: {chartFor: chartFor},
                dataType: 'json',
                encode: true
            }).done(function (data) {
                // for total order price.
                var data1 = [];
                // for total shipping fee.
                var data2 = [];
                var ticks = [];
                var counter = data.values.length;
                var window_width = $(window).width();
                var dataString = '';
                
                var months = [
                    translations.tr_meliscommerce_dashboardplugin_jan,
                    translations.tr_meliscommerce_dashboardplugin_feb,
                    translations.tr_meliscommerce_dashboardplugin_mar,
                    translations.tr_meliscommerce_dashboardplugin_apr,
                    translations.tr_meliscommerce_dashboardplugin_may,
                    translations.tr_meliscommerce_dashboardplugin_jun,
                    translations.tr_meliscommerce_dashboardplugin_jul,
                    translations.tr_meliscommerce_dashboardplugin_aug,
                    translations.tr_meliscommerce_dashboardplugin_sep,
                    translations.tr_meliscommerce_dashboardplugin_oct,
                    translations.tr_meliscommerce_dashboardplugin_nov,
                    translations.tr_meliscommerce_dashboardplugin_dec
                ];

                //the first value of the data.values is the current date / time.
                for (var i = 0; i < data.values.length; i++) {
                    if (chartFor === 'hourly') {
                        var locale = melisLangId;
                        var hour = '';
                        var time = '';

                        if (locale === 'fr_FR') {
                            hour = moment(data.values[i][0], 'YYYY-MM-DD HH').format('HH');
                            time = hour + 'h';
                        } else {
                            hour = moment(data.values[i][0], 'YYYY-MM-DD HH').format('h A');
                            time = hour;
                        }

                        dataString = time;
                    } else if (chartFor === 'daily') {
                        var date = moment(data.values[i][0], 'YYYY-MM-DD');
                        var day = date.format("DD");
                        var month = months[parseInt(date.format("M")) - 1];

                        dataString = month.replace('%day', day);
                    } else if (chartFor === 'weekly') {
                        var week = moment(data.values[i][0], 'YYYY-MM-DD').format('W');
                        var weekday = moment().day("Monday").week(week);
                        var month = months[parseInt(weekday.format("M")) - 1];
                        var day = weekday.format("DD");

                        dataString = month.replace('%day', day);
                    } else if (chartFor === 'monthly') {
                        var monthOfYear = moment(data.values[i][0], 'YYYY-MM-DD').format("M");
                        var month = months[parseInt(parseInt(monthOfYear)) - 1];

                        dataString = month.replace('%day', '');
                    }

                    /*
                    *  first parameter is for the xaxis. second param is the total order price/ shipping fee.
                    *  we use counter so that the date / time would be ordered from left to right. The time / date
                    *  on the right most is the current date / time.
                    */
                    data1.push([counter, data.values[i][1]]);
                    data2.push([counter, data.values[i][2]]);
                    /*
                    *   first parameter is for the data identifier.
                    *   Example: data[0,1000] ticks[0,'June 11 2018']
                    *   y axis = 1000 and x axis = June 11 2018
                    *   the counter is the identifier for the x and y axis.
                    */
                    ticks.push([counter, dataString]);
                    counter--;
                }
                //insert the ticks to the charts object
                charts.commerceDashboardPluginSalesRevenueChartStackedBars.options.xaxis.ticks = ticks;

                //chart data
                charts.commerceDashboardPluginSalesRevenueChartStackedBars.data = [];
                charts.commerceDashboardPluginSalesRevenueChartStackedBars.data.push({
                    label: translations.tr_melis_commerce_dashboard_plugin_sales_revenue_order_price,
                    data: data1
                });
                charts.commerceDashboardPluginSalesRevenueChartStackedBars.data.push({
                    label: translations.tr_melis_commerce_dashboard_plugin_sales_revenue_shipping_price,
                    data: data2
                });
                //plot the chart
                charts.commerceDashboardPluginSalesRevenueChartStackedBars.plot = $.plot(
                    $(placeholder),
                    charts.commerceDashboardPluginSalesRevenueChartStackedBars.data,
                    charts.commerceDashboardPluginSalesRevenueChartStackedBars.options
                );
            }).fail(function (xhr, textStatus, errorThrown) {
                console.log("ERROR !! Status = " + textStatus + "\n Error = " + errorThrown + "\n xhr = " + xhr.statusText);
            });
        }

        function loadChart() {
            commerceDashboardPluginSalesRevenueChartStackedBarsInit();
        }

        return {
            loadChart                                               : loadChart,
            setUpChart                                              : setUpChart,
            commerceDashboardPluginSalesRevenueChartStackedBarsInit : commerceDashboardPluginSalesRevenueChartStackedBarsInit
        };
})($, window);

$(function() {
    /**
     * chart class 
     * [.flotchart-holder] 
     * [.commerce-dashboard-plugin-sales-revenue-placeholder]
     */
    var $body           = $("body"),
        $activeTab      = $("#"+activeTabId);

        //when a filter is selected
        $body.on("change", '.commerce-dashboard-plugin-sales-revenue', function() {
            var $this = $(this);

                commerceDashboardPluginSalesRevenue.commerceDashboardPluginSalesRevenueChartStackedBarsInit( $this );

                //get the hidden plugin config
                var $hiddenJsonConfig   = $this.closest('.grid-stack-item').find('.grid-stack-item-content .widget .widget-parent .widget-body .dashboard-plugin-json-config'),
                    pluginConfig        = JSON.parse($hiddenJsonConfig.text());

                    //update the filter and save the plugin
                    pluginConfig.activeFilter = $this.val();
                    $($hiddenJsonConfig).text( JSON.stringify(pluginConfig) );
                    melisDashBoardDragnDrop.saveCurrentDashboard( $this );
        });

        //initialize all the charts on the dashboard on first load of dashboard.
        $body.find(".commerce-dashboard-plugin-sales-revenue-placeholder").each(function(index, value) {
            var pluginConfig = $(value).closest('.grid-stack-item').find('.grid-stack-item-content .widget .widget-parent .widget-body .dashboard-plugin-json-config').text();
            var filter = JSON.parse(pluginConfig).activeFilter;
            var placeholder = JSON.parse(pluginConfig).plugin_id;

                commerceDashboardPluginSalesRevenue.commerceDashboardPluginSalesRevenueChartStackedBarsInit(filter, placeholder);
        });

        if ( typeof melisUserTabs === "undefined" ) {
            if ( $activeTab.data("meliskey") === "meliscore_dashboard" && $activeTab.find(".commerce-dashboard-plugin-sales-revenue-placeholder").length > 0 ) {
                commerceDashboardPluginSalesRevenue.loadChart();
            }
        }
});