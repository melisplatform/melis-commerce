$(document).ready(function() {

    //when changing for report type.
    $("body").on("change", '.com-orders-dash-chart-line', function() {
        commerceDashboardOrdersLineGraphInit($(this));
    });

    //set charts options
    charts.commerceDashboardOrdersLineGraph =
        {
            // data
            data:
                {
                    d1: []
                },

            // will hold the chart object
            plot: null,

            // chart options
            options:
                {
                    grid:
                        {
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
                        mode: 'time',
                        /*timeformat: '%b %d',
                        tickSize: [1, 'day'],*/
                        tickColor: '#eee',
                    },
                    yaxis: {
                        show : true,
                        tickColor: '#eee',
                        min: 0,
                        tickDecimals: 0,
                    },
                    legend: { position: "nw", noColumns: 2, backgroundColor: null, backgroundOpacity: 0 },
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

            // initialize
            init: function()
            {
                if (this.plot == null){
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
            var tmpData = data.values;
            var tmpdataLength  = tmpData.length;
            var finalData = [];
            var curTime = null;
            var tick = [];
            var counter = tmpdataLength;

            for(var i = 0; i < tmpdataLength ; i++)
            {
                var newDate = new Date(tmpData[i][0]);

                var h = newDate.getUTCHours();
                var d = newDate.getUTCDate();
                var m = newDate.getUTCMonth() ;
                var y = newDate.getUTCFullYear();

                var newMonth = new Date(y, m, 1.5 );
                var newYear = new Date(y,0, 2);
                var newDaily = new Date(y,m,d,h);

                if (chartFor == 'hourly'){
                curTime = newDaily.getTime();
                }
                else if(chartFor == 'daily'){
                    curTime = newDate.getTime();
                }
                else if (chartFor == 'weekly'){
                    curTime = counter;
                }
                else if (chartFor == 'monthly'){
                    curTime = newMonth.getTime();
                }

                //pushing the data to the finalData which will be used in the charts
                finalData.push([ curTime , tmpData[i][1]]);

                //getting the week number of the year
                // var date = new Date(Date.UTC(newDate.getUTCFullYear(), newDate.getMonth(), newDate.getDate()));
                // var dayNum = date.getUTCDay() || 7;
                // date.setUTCDate(date.getUTCDate() + 4 - dayNum);
                // var yearStart = new Date(Date.UTC(date.getUTCFullYear(),0,1));
                // var week =  Math.ceil((((date - yearStart) / 86400000) + 1)/7);

                var week = moment(tmpData[i][0], 'YYYY-MM-DD').format('W');

                var weekday = moment().day("Monday").week(week);

                //when chart is for weekly we need to use ticks to display strings in x axis.
                tick.push([counter, weekday.format("MMMM")+" "+weekday.format("DD")]);
                counter--;
            }

            //check if data is a valid date. because charts for weekly uses a different chart options to display strings rather than time in x axis.
            if(new Date(finalData[0][0]).getTime() > 10) {
                charts.commerceDashboardOrdersLineGraph.options.xaxis.mode = 'time';
                charts.commerceDashboardOrdersLineGraph.options.xaxis.ticks = null;
                charts.commerceDashboardOrdersLineGraph.options.tooltipOpts.content = "%y %s - %x";
            }
            else {
                charts.commerceDashboardOrdersLineGraph.options.xaxis.mode = null;
                charts.commerceDashboardOrdersLineGraph.options.xaxis.ticks = tick;
                charts.commerceDashboardOrdersLineGraph.options.tooltipOpts.content = "%y %s";
            }

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

    setTimeout(function(){ commerceDashboardOrdersLineGraphInit(); }, 3000);
});


