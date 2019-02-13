$(document).ready(function () {
    //instance counter
    var instanceCount = 0;

    //when a filter is selected
    $("body").on("change", '.com-orders-dash-chart-line', function () {
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
    $("body").on("click", ".melis-commerce-dashboard-plugin-orders-number-item", function () {
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
        var placeholder = placeholder || null
        var chartFor = "";

        if (target === null) {
            var chartsArray = $body.find(".commerce-dashboard-orders-chart-linegraph-placeholder");
            var emptyChartCount = 0;

            //count the number of empty charts
            $body.find(".commerce-dashboard-orders-chart-linegraph-placeholder").each(function (index, value) {
                if ($(this).text() == "") {
                    emptyChartCount++;
                }
            });

            if (emptyChartCount == $body.find(".commerce-dashboard-orders-chart-linegraph-placeholder").length) {
                //when count of empty charts is equal to the count of charts then it mean the tab is closed and opened again.
                var pluginConfig = $(chartsArray[instanceCount]).closest('.grid-stack-item').find('.grid-stack-item-content .widget .widget-parent .widget-body .dashboard-plugin-json-config').text();
                pluginConfig = JSON.parse(pluginConfig);
                instanceCount++;

                if (instanceCount == chartsArray.length) {
                    instanceCount = 0;
                }

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

            for (var i = 0; i < data.values.length; i++) {
                if (chartFor == 'hourly') {
                    // displays the hour only
                    var dataString = moment(data.values[i][0], 'YYYY-MM-DD HH').format('HH');
                } else if (chartFor == 'daily') {
                    var date = moment(data.values[i][0], 'YYYY-MM-DD');
                    // displays month name in 3 letters and the day is in another line
                    var dataString = date.format("MMM") + '\n' + date.format("DD");
                } else if (chartFor == 'weekly') {
                    var week = moment(data.values[i][0], 'YYYY-MM-DD').format('W');
                    var weekday = moment().day("Monday").week(week);
                    // displays month name in 3 letters
                    var dataString = weekday.format("MMM") + "\n" + weekday.format("DD");
                } else if (chartFor == 'monthly') {
                    // displays month name in 3 letters
                    var dataString = moment(data.values[i][0], 'YYYY-MM-DD').format("MMM");
                }

                //pushing the data to the finalData which will be used in the charts
                finalData.push([counter, data.values[i][1]]);

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

        }).error(function (xhr, textStatus, errorThrown) {
            console.log("ERROR !! Status = " + textStatus + "\n Error = " + errorThrown + "\n xhr = " + xhr.statusText);
        });
    }

    //initialize all the charts on the dashboard on first load of dashboard.
    $body.find(".commerce-dashboard-orders-chart-linegraph-placeholder").each(function (index, value) {
        var pluginConfig = $(value).closest('.grid-stack-item').find('.grid-stack-item-content .widget .widget-parent .widget-body .dashboard-plugin-json-config').text();
        var filter = JSON.parse(pluginConfig).activeFilter;
        var placeholder = JSON.parse(pluginConfig).plugin_id;

        commerceDashboardOrdersLineGraphInit(filter, placeholder);
    });
});
