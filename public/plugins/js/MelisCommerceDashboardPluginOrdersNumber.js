$(function () {
    //instance counter
<<<<<<< HEAD
    var instanceCount   = 0,
        $body           = $("body");

        //when a filter is selected
        $body.on("change", '.com-orders-dash-chart-line', function() {
            var $this = $(this);

                commerceDashboardOrdersLineGraphInit( $this );

                //get the hidden plugin config
                var $hiddenJsonConfig   = $this.closest('.grid-stack-item').find('.grid-stack-item-content .widget .widget-parent .widget-body .dashboard-plugin-json-config'),
                    pluginConfig        = JSON.parse($hiddenJsonConfig.text());

                //update the filter and save the plugin
                pluginConfig.activeFilter = $this.val();
                $($hiddenJsonConfig).text(JSON.stringify(pluginConfig));
                melisDashBoardDragnDrop.saveCurrentDashboard( $this );
        });

        //opening order tab when an order is clicked
        $body.on("click", ".melis-commerce-dashboard-plugin-orders-number-item", function() {
            var $this           = $(this),
                orderId         = $this.find('.melis-commerce-dashboard-plugin-orders-number-item-id').text(),
                orderReference  = $this.find('.melis-commerce-dashboard-plugin-orders-number-item-ref').text();

                // Open parent tab
                melisHelper.tabOpen(translations.tr_meliscommerce_orders_Orders, 'fa fa fa-cart-plus fa-2x', 'id_meliscommerce_order_list_page', 'meliscommerce_order_list_page');

                var alreadyOpen = $("body #melis-id-nav-bar-tabs li a.tab-element[data-id='id_meliscommerce_order_list_page']");

                    // check if it exists
                    var checkOrders = setInterval(function () {
                        if ( alreadyOpen.length ) {
                            var navTabsGroup = "id_meliscommerce_order_list_page";

                                melisHelper.tabOpen(
                                    translations.tr_meliscommerce_orders_Order + ' ' + orderReference,
                                    'fa fa fa-cart-plus fa-2x',
                                    orderId + '_id_meliscommerce_orders_page',
                                    'meliscommerce_orders_page',
                                    {orderId: orderId},
                                    navTabsGroup);
                                clearInterval(checkOrders);
                        }
                    }, 500);
        });

        charts.commerceDashboardOrdersLineGraph = {
            //chart data
            data: {
                d1: []
=======
    var instanceCount = 0;
    var $body = $("body");

    //when a filter is selected
    $body.on("change", '.com-orders-dash-chart-line', function () {
        commerceDashboardOrdersLineGraphInit($(this));

        //get the hidden plugin config
        var $hiddenJsonConfig = $(this).closest('.grid-stack-item').find('.grid-stack-item-content .widget .widget-parent .widget-body .dashboard-plugin-json-config');
        var pluginConfig = JSON.parse($hiddenJsonConfig.text());

        //update the filter and save the plugin
        pluginConfig.activeFilter = $(this).val();
        $($hiddenJsonConfig).text(JSON.stringify(pluginConfig));
        melisDashBoardDragnDrop.saveCurrentDashboard($(this));
    });

    //opening order tab when an order is clicked
    $body.on("click", ".melis-commerce-dashboard-plugin-orders-number-item", function () {
        var orderId = $(this).find('.melis-commerce-dashboard-plugin-orders-number-item-id').text();
        var orderReference = $(this).find('.melis-commerce-dashboard-plugin-orders-number-item-ref').text();

        // Open parent tab
        melisHelper.tabOpen(translations.tr_meliscommerce_orders_Orders, 'fa fa fa-cart-plus fa-2x', 'id_meliscommerce_order_list_page', 'meliscommerce_order_list_page');
        var alreadyOpen = $("body #melis-id-nav-bar-tabs li a.tab-element[data-id='id_meliscommerce_order_list_page']");
        // check if it exists
        var checkOrders = setInterval(function () {
            if (alreadyOpen.length) {
                var navTabsGroup = "id_meliscommerce_order_list_page";

                melisHelper.tabOpen(
                    translations.tr_meliscommerce_orders_Order + ' ' + orderReference,
                    'fa fa fa-cart-plus fa-2x',
                    orderId + '_id_meliscommerce_orders_page',
                    'meliscommerce_orders_page',
                    {orderId: orderId},
                    navTabsGroup);
                clearInterval(checkOrders);
            }
        }, 500);
    });

    charts.commerceDashboardOrdersLineGraph = {
        //chart data
        data: {
            d1: []
        },
        //will hold the chart object
        plot: null,
        options: {
            grid: {
                color: "#dedede",
                borderWidth: 1,
                borderColor: "#eee",
                clickable: true,
                hoverable: true,
                labelMargin: 20
>>>>>>> develop
            },
            //will hold the chart object
            plot: null,
            options: {
                grid: {
                    color: "#dedede",
                    borderWidth: 1,
                    borderColor: "#eee",
                    clickable: true,
                    hoverable: true,
                    labelMargin: 20,
                },
                series: {
                    lines: {
                        show: true,
                        fill: false,
                        lineWidth: 2,
                        steps: false
                    },
                    points: {
                        show: true,
                        radius: 5,
                        lineWidth: 3,
                        fill: true,
                        fillColor: "#000"
                    }
                },
                xaxis: {
                    // we are not using any plugin for the xaxis, we use ticks instead.
                    tickColor: '#eee',
                },
                yaxis: {
                    show: true,
                    tickColor: '#eee',
                    min: 0,
                    tickDecimals: 0,
                },
                legend: {
                    position: "nw",
                    noColumns: 2,
                    backgroundColor: null,
                    backgroundOpacity: 0
                },
                shadowSize: 0,
                tooltip: true,
                tooltipOpts: {
                    content: "%y %s - %x",
                    shifts: {
                        x: -30,
                        y: -50
                    },
                    defaultTheme: false
                }
            },
<<<<<<< HEAD
            placeholder: "#"+activeTabId+" .commerce-dashboard-orders-chart-linegraph-placeholder",
            init: function () {
                if ( this.plot == null ) {
                    // hook the init function for plotting the chart
                    commerceDashboardOrdersLineGraphInit();
=======
            xaxis: {
                // we are not using any plugin for the xaxis, we use ticks instead.
                tickColor: '#eee'
            },
            yaxis: {
                show: true,
                tickColor: '#eee',
                min: 0,
                tickDecimals: 0
            },
            legend: {
                position: "nw",
                noColumns: 2,
                backgroundColor: null,
                backgroundOpacity: 0
            },
            shadowSize: 0,
            tooltip: true,
            tooltipOpts: {
                content: "%y %s - %x",
                shifts: {
                    x: -30,
                    y: -50
                },
                defaultTheme: false
            }
        },
        placeholder: ".commerce-dashboard-orders-chart-linegraph-placeholder",
        init: function () {
            if (this.plot == null) {
                // hook the init function for plotting the chart
                commerceDashboardOrdersLineGraphInit();
            }
        }
    };

    // INIT PLOTTING FUNCTION [also used as callback in the app.interface for when we refresh the chart]
    window.commerceDashboardOrdersLineGraphInit = function (target, placeholder) {
        var target = target || null;
        var placeholder = placeholder || null;
        var chartFor = "";

        if (target === null) {
            var chartsArray = $body.find(".commerce-dashboard-orders-chart-linegraph-placeholder");
            var emptyChartCount = 0;

            //count the number of empty charts
            $body.find(".commerce-dashboard-orders-chart-linegraph-placeholder").each(function (index, value) {
                if ($(this).text() === "") {
                    emptyChartCount++;
>>>>>>> develop
                }
            }
        };

        // INIT PLOTTING FUNCTION [also used as callback in the app.interface for when we refresh the chart]
        window.commerceDashboardOrdersLineGraphInit = function (target, placeholder) {
            var target      = target || null,
                placeholder = placeholder || null,
                chartFor    = "";

            if ( target === null ) {
                var chartsArray     = $body.find(".commerce-dashboard-orders-chart-linegraph-placeholder"),
                    emptyChartCount = 0;

                    //count the number of empty charts
                    $body.find(".commerce-dashboard-orders-chart-linegraph-placeholder").each(function (index, value) {
                        if ( $(this).text() === "" ) {
                            emptyChartCount++;
                        }
                    });

<<<<<<< HEAD
                    if ( emptyChartCount === $body.find(".commerce-dashboard-orders-chart-linegraph-placeholder").length ) {
                        //when count of empty charts is equal to the count of charts then it mean the tab is closed and opened again.
                        var pluginConfig = $(chartsArray[instanceCount]).closest('.grid-stack-item').find('.grid-stack-item-content .widget .widget-parent .widget-body .dashboard-plugin-json-config').text();
=======
            if (emptyChartCount === $body.find(".commerce-dashboard-orders-chart-linegraph-placeholder").length) {
                //when count of empty charts is equal to the count of charts then it mean the tab is closed and opened again.
                var pluginConfig = $(chartsArray[instanceCount]).closest('.grid-stack-item').find('.grid-stack-item-content .widget .widget-parent .widget-body .dashboard-plugin-json-config').text();
                pluginConfig = JSON.parse(pluginConfig);
                instanceCount++;
>>>>>>> develop

                            instanceCount++;

                            if ( instanceCount === chartsArray.length ) {
                                instanceCount = 0;
                            }

                        chartFor = JSON.parse(pluginConfig).activeFilter;
                        placeholder = "#commerce-dashboard-orders-chart-linegraph-placeholder-" + JSON.parse(pluginConfig).plugin_id;
                }
                else {
                    //when a new plugin is dragged to the grid stack
                    var lastItem = body.find(".commerce-dashboard-orders-chart-linegraph-placeholder").length - 1,
                        pluginConfig = $(chartsArray[lastItem]).closest('.grid-stack-item').find('.grid-stack-item-content .widget .widget-parent .widget-body .dashboard-plugin-json-config').text();

<<<<<<< HEAD
                    chartFor = JSON.parse(pluginConfig).activeFilter;
                    placeholder = "#commerce-dashboard-orders-chart-linegraph-placeholder-" + JSON.parse(pluginConfig).plugin_id;
                }
            } else if ( typeof target == "string" ) {
                //when initializing the charts on the first load of dashboard
                chartFor = target;
                placeholder = "#commerce-dashboard-orders-chart-linegraph-placeholder-" + placeholder;
            } else {
                //when filter is selected
                chartFor = target.val();
                placeholder = "#" + target.closest(".tab-pane").find(".commerce-dashboard-orders-chart-linegraph-placeholder").attr("id");
            }

            // get the orders data
            $.ajax({
                type    : 'POST',
                url     : '/melis/dashboard-plugin/MelisCommerceDashboardPluginOrdersNumber/getDashboardOrdersData',
                data    : {chartFor: chartFor},
                dataType: 'json',
                encode  : true
            }).done(function (data) {
                var finalData       = [],
                    tick            = [],
                    counter         = data.values.length,
                    window_width    = $(window).width(),
                    dataString      = '';

                let months = [
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
                    translations.tr_meliscommerce_dashboardplugin_dec,
                ];

                //the first value of the data.values is the current date / time.
                for ( var i = 0; i < data.values.length; i++ ) {
                    if ( chartFor === 'hourly' ) {
                        let locale  = melisLangId,
                            hour    = '',
                            time    = '';

                            if ( locale === 'fr_FR' ) {
                                hour = moment(data.values[i][0], 'YYYY-MM-DD HH').format('HH');
                                time = hour + 'h';
                            } else {
                                hour = moment(data.values[i][0], 'YYYY-MM-DD HH').format('h A');
                                time = hour;
                            }

                        dataString = time;
                    } else if (chartFor === 'daily') {
                        var date = moment(data.values[i][0], 'YYYY-MM-DD');
                        let day = date.format("DD");
                        let month = months[parseInt(date.format("M")) - 1];

                        dataString = month.replace('%day', day);
                    } else if (chartFor === 'weekly') {
                        var week = moment(data.values[i][0], 'YYYY-MM-DD').format('W');
                        var weekday = moment().day("Monday").week(week);
                        let month = months[parseInt(weekday.format("M")) - 1];
                        let day = weekday.format("DD");

                        dataString = month.replace('%day', day);
                    } else if (chartFor === 'monthly') {
                        let monthOfYear = moment(data.values[i][0], 'YYYY-MM-DD').format("M");
                        let month = months[parseInt(parseInt(monthOfYear)) - 1];

                        dataString = month.replace('%day', '');
                    }

                    //pushing the data to the finalData which will be used in the charts
                    finalData.push([counter, data.values[i][1]]);
=======
                chartFor = pluginConfig.datas.activeFilter;
                placeholder = "#commerce-dashboard-orders-chart-linegraph-placeholder-" + pluginConfig.plugin_id;
            } else {
                //when a new plugin is dragged to the grid stack
                var lastItem = body.find(".commerce-dashboard-orders-chart-linegraph-placeholder").length - 1;
                var pluginConfig = $(chartsArray[lastItem]).closest('.grid-stack-item').find('.grid-stack-item-content .widget .widget-parent .widget-body .dashboard-plugin-json-config').text();
                pluginConfig = JSON.parse(pluginConfig);

                chartFor = pluginConfig.datas.activeFilter;
                placeholder = "#commerce-dashboard-orders-chart-linegraph-placeholder-" + pluginConfig.plugin_id;
            }
        } else if (typeof target == "string") {
            //when initializing the charts on the first load of dashboard
            chartFor = target;
            placeholder = "#commerce-dashboard-orders-chart-linegraph-placeholder-" + placeholder;
        } else {
            //when filter is selected
            chartFor = target.val();
            placeholder = "#" + target.closest(".tab-pane").find(".commerce-dashboard-orders-chart-linegraph-placeholder").attr("id");
        }

        // get the orders data
        $.ajax({
            type: 'POST',
            url: '/melis/dashboard-plugin/MelisCommerceDashboardPluginOrdersNumber/getDashboardOrdersData',
            data: {chartFor: chartFor},
            dataType: 'json',
            encode: true
        }).success(function (data) {
            var finalData = [];
            var tick = [];
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

            // the first value of the data.values is the current date / time.
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
>>>>>>> develop

                    //when chart is for weekly we need to use ticks to display strings in x axis.
                    tick.push([counter, dataString]);
                    counter--;
                }

                charts.commerceDashboardOrdersLineGraph.options.xaxis.ticks = tick;
                charts.commerceDashboardOrdersLineGraph.options.tooltipOpts.content = "%y %s";

                charts.commerceDashboardOrdersLineGraph.plot = $.plot(
                    $(placeholder),
                    [{
                        label: translations.tr_melis_commerce_dashboard_plugin_orders_number,
                        data: finalData,
                        color: "#3997d4",
                        lines: {fill: 0.2},
                        points: {fillColor: "#fff"}
                    }],
                    charts.commerceDashboardOrdersLineGraph.options
                );
            }).fail(function (xhr, textStatus, errorThrown) {
                console.log("ERROR !! Status = " + textStatus + "\n Error = " + errorThrown + "\n xhr = " + xhr.statusText);
                alert( translations.tr_meliscore_error_message );
            });
        };

        //initialize all the charts on the dashboard on first load of dashboard.
        $body.find(".commerce-dashboard-orders-chart-linegraph-placeholder").each(function (index, value) {
            var pluginConfig    = $(value).closest('.grid-stack-item').find('.grid-stack-item-content .widget .widget-parent .widget-body .dashboard-plugin-json-config').text(),
                filter          = JSON.parse(pluginConfig).activeFilter,
                placeholder     = JSON.parse(pluginConfig).plugin_id;

                commerceDashboardOrdersLineGraphInit(filter, placeholder);
        });
});
