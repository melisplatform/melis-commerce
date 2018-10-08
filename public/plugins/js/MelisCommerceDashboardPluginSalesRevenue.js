$(document).ready(function() {

    //when changing chart for (hourly, daily, weekly, monthly)
    $("body").on("change", '.commerce-dashboard-plugin-sales-revenue', function() {
        commerceDashboardPluginSalesRevenueChartStackedBarsInit($(this));
    });

        //chart options
        charts.commerceDashboardPluginSalesRevenueChartStackedBars =
            {
                // chart data
                data: null,

                // will hold the chart object
                plot: null,

                // chart options
                options:
                    {
                        grid:
                            {
                                color: "#dedede",
                                borderWidth: 1,
                                borderColor: "transparent",
                                clickable: true,
                                hoverable: true,
                                backgroundColor:
                                    {
                                        colors:
                                            [
                                                "#fff", "#fff"
                                            ],
                                    },
                            },
                        series:
                            {
                                stack: true,
                                grow:
                                    {
                                        active:false,
                                    },
                                bars:
                                    {
                                        show: true,
                                        barWidth: 0.5,
                                        fill: 1,
                                        align: 'center',
                                    },
                            },
                        xaxis:
                            {
                                // we are not using any plugin for the xaxis, we use ticks instead.
                            },
                        yaxis:
                            {
                                min: 0,
                                tickDecimals: 0,
                            },
                        legend:
                            {
                                position: "ne",
                                backgroundColor: null,
                                backgroundOpacity: 0,
                                noColumns: 2,
                            },
                        colors:
                            [
                                "#7acc66",
                                "#66cccc",
                            ],
                        shadowSize: 0,
                        tooltip: true,
                        tooltipOpts:
                            {
                                content: "%s : %y",
                                shifts:
                                    {
                                        x: -30,
                                        y: -50
                                    },
                                defaultTheme: false
                            }
                    },

                placeholder: ".commerce-dashboard-plugin-sales-revenue-placeholder",

                // initialize
                init: function()
                {
                    if(this.plot == null)
                    {
                        // hook the init function for plotting the chart
                        commerceDashboardPluginSalesRevenueChartStackedBarsInit();
                    }
                }
            };

        window.commerceDashboardPluginSalesRevenueChartStackedBarsInit = function(target){
            var placeholder = "";
            var chartFor = "";

            if(typeof target === "undefined")
            {
                //set default
                chartFor = 'hourly';
                if(melisDashBoardDragnDrop.getCurrentPlugin() == null)
                {
                    placeholder = charts.commerceDashboardPluginSalesRevenueChartStackedBars.placeholder;
                }
                else
                {
                    placeholder = "#"+melisDashBoardDragnDrop.getCurrentPlugin().find(".commerce-dashboard-plugin-sales-revenue-placeholder").attr("id");
                }
            }else
            {
                chartFor = target.val();
                placeholder = "#"+target.closest(".tab-pane").find(".commerce-dashboard-plugin-sales-revenue-placeholder").attr("id");
            }

            // get data
            $.ajax({
                type        : 'POST',
                url         : '/melis/dashboard-plugin/MelisCommerceDashboardPluginSalesRevenue/getDashboardSalesRevenueData',
                data        : {chartFor : chartFor},
                dataType    : 'json',
                encode      : true
            }).success(function(data){
                // for total order price.
                var data1 = [];
                // for total shipping fee.
                var data2 = [];
                var ticks = [];
                var counter = data.values.length;
                var window_width = $(window).width();

                //the first value of the data.values is the current date / time.
                for (var i = 0; i < data.values.length; i++) {
                    if (chartFor == 'hourly')
                    {
                        if ( window_width <= 767 ) { // limit the changes on format to max 600px
                        var dataString  = moment(data.values[i][0], 'YYYY-MM-DD HH').format('HH');
                        } else {
                            var dataString  = moment(data.values[i][0], 'YYYY-MM-DD HH').format('HH:mm');
                        }
                    }
                    else if(chartFor == 'daily')
                    {
                        var date = moment(data.values[i][0], 'YYYY-MM-DD');
                        if ( window_width <= 767 ) {
                            var dataString = date.format("MMM") + '\n' + date.format("DD");    
                        } else {
                            var dataString = date.format("MMMM") + ' ' + date.format("DD");
                        }
                    }
                    else if (chartFor == 'weekly')
                    {
                        var week = moment(data.values[i][0], 'YYYY-MM-DD').format('W');
                        var weekday = moment().day("Monday").week(week);
                        if ( window_width <= 767 ) {
                            var dataString = weekday.format("MMM") + "\n" + weekday.format("DD");
                        } else {
                            var dataString = weekday.format("MMMM") + " " + weekday.format("DD");
                        }
                    }
                    else if (chartFor == 'monthly')
                    {
                        if ( window_width <= 767 ) {
                            var dataString = moment(data.values[i][0], 'YYYY-MM-DD').format("MMM");
                        } else {
                            var dataString = moment(data.values[i][0], 'YYYY-MM-DD').format("MMMM");
                        }
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
                //chart datas
                charts.commerceDashboardPluginSalesRevenueChartStackedBars.data = new Array();
                charts.commerceDashboardPluginSalesRevenueChartStackedBars.data.push({
                    label: translations.tr_melis_commerce_dashboard_plugin_sales_revenue_order_price,
                    data: data1
                });
                charts.commerceDashboardPluginSalesRevenueChartStackedBars.data.push({
                    label: translations.tr_melis_commerce_dashboard_plugin_sales_revenue_shipping_price,
                    data: data2
                });

                $(placeholder).each(function(){
                    charts.commerceDashboardPluginSalesRevenueChartStackedBars.plot = $.plot(
                        $(this),
                        charts.commerceDashboardPluginSalesRevenueChartStackedBars.data,
                        charts.commerceDashboardPluginSalesRevenueChartStackedBars.options
                    );
                });
            }).error(function(xhr, textStatus, errorThrown){
                console.log("ERROR !! Status = "+ textStatus + "\n Error = "+ errorThrown + "\n xhr = "+ xhr.statusText);
            });
        }

        setTimeout(function(){ commerceDashboardPluginSalesRevenueChartStackedBarsInit(); }, 3000);
});