$(document).ready(function() {
    //when changing for report type.
    $("body").on("change", '.com-orders-dash-chart-line', function() {
        commerceDashboardOrdersLineGraphInit($(this));
    });

    //opening order tab when an order is clicked
    $("body").on("click", ".melis-commerce-dashboard-plugin-orders-number-item", function(){
        var orderId = $(this).find('.melis-commerce-dashboard-plugin-orders-number-item-id').text();
        var orderReference = $(this).find('.melis-commerce-dashboard-plugin-orders-number-item-ref').text();

        // Open parent tab
        melisHelper.tabOpen(translations.tr_meliscommerce_orders_Orders, 'fa fa fa-cart-plus fa-2x', 'id_meliscommerce_order_list_page', 'meliscommerce_order_list_page');
        var alreadyOpen = $("body #melis-id-nav-bar-tabs li a.tab-element[data-id='id_meliscommerce_order_list_page']");
        // check if it exists
        var checkOrders = setInterval(function() {
            if(alreadyOpen.length){
                var navTabsGroup = "id_meliscommerce_order_list_page";

                melisHelper.tabOpen(
                    translations.tr_meliscommerce_orders_Order+' '+orderReference,
                    'fa fa fa-cart-plus fa-2x',
                    orderId+'_id_meliscommerce_orders_page',
                    'meliscommerce_orders_page',
                    { orderId : orderId},
                    navTabsGroup);
                clearInterval(checkOrders);
            }
        }, 500);
    });

    charts.commerceDashboardOrdersLineGraph = {
        data: {
            d1: []
        },
        plot: null, // will hold the chart object
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
                    show:true,
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
                show : true,
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
        init: function() {
            if (this.plot == null) {
                // hook the init function for plotting the chart
                commerceDashboardOrdersLineGraphInit();
            }
        }
    };

    // INIT PLOTTING FUNCTION [also used as callback in the app.interface for when we refresh the chart]
    window.commerceDashboardOrdersLineGraphInit = function(target){
        var placeholder = "";
        var chartFor = "";
        if(typeof target === "undefined"){
            chartFor = 'hourly';
            if(melisDashBoardDragnDrop.getCurrentPlugin() == null){
                placeholder = charts.commerceDashboardOrdersLineGraph.placeholder;
            }else{
                placeholder = "#"+melisDashBoardDragnDrop.getCurrentPlugin().find(".commerce-dashboard-orders-chart-linegraph-placeholder").attr("id");
            }

        }else{
            chartFor = target.val();
            placeholder = "#"+target.closest(".tab-pane").find(".commerce-dashboard-orders-chart-linegraph-placeholder").attr("id");
        }

        // get the orders data
        $.ajax({
            type        : 'POST',
            url         : '/melis/dashboard-plugin/MelisCommerceDashboardPluginOrdersNumber/getDashboardOrdersData',
            data		: {chartFor : chartFor},
            dataType 	: 'json',
            encode		: true
        }).success(function(data){
            var finalData = [];
            var tick = [];
            var counter = data.values.length;
            var window_width = $(window).width();

            for(var i = 0; i < data.values.length ; i++)
            {
                if (chartFor == 'hourly')
                {
                    // displays the hour only
                    var dataString  = moment(data.values[i][0], 'YYYY-MM-DD HH').format('HH');
                }
                else if(chartFor == 'daily')
                {
                    var date = moment(data.values[i][0], 'YYYY-MM-DD');
                    // displays month name in 3 letters and the day is in another line
                    var dataString = date.format("MMM") + '\n' + date.format("DD");    
                }
                else if (chartFor == 'weekly')
                {
                    var week = moment(data.values[i][0], 'YYYY-MM-DD').format('W');
                    var weekday = moment().day("Monday").week(week);
                    // displays month name in 3 letters
                    var dataString = weekday.format("MMM") + "\n" + weekday.format("DD");
                }
                else if (chartFor == 'monthly')
                {
                    // displays month name in 3 letters
                    var dataString = moment(data.values[i][0], 'YYYY-MM-DD').format("MMM");
                }

                //pushing the data to the finalData which will be used in the charts
                finalData.push([ counter ,  data.values[i][1]]);

                //when chart is for weekly we need to use ticks to display strings in x axis.
                tick.push([counter, dataString]);
                counter--;
            }

            charts.commerceDashboardOrdersLineGraph.options.xaxis.ticks = tick;
            charts.commerceDashboardOrdersLineGraph.options.tooltipOpts.content = "%y %s";

            $(placeholder).each(function(){
                charts.commerceDashboardOrdersLineGraph.plot = $.plot(
                    $(this),
                    [{
                        label: translations.tr_melis_commerce_dashboard_plugin_orders_number,
                        data: finalData,
                        color: "#3997d4",
                        lines: { fill: 0.2 },
                        points: { fillColor: "#fff"}
                    }],
                    charts.commerceDashboardOrdersLineGraph.options
                );
            });

        }).error(function(xhr, textStatus, errorThrown){
            console.log("ERROR !! Status = "+ textStatus + "\n Error = "+ errorThrown + "\n xhr = "+ xhr.statusText);
        });
    }

    if ( $('.commerce-dashboard-orders-chart-linegraph-placeholder').length > 0) {
        commerceDashboardOrdersLineGraphInit();
    }
});


