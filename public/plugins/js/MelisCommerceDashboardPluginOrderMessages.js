$(document).ready(function() {

    //when filtering the messages
    $("body").on("change", '.commerce-dashboard-plugin-order-messages', function() {
        commerceDashboardPluginOrderMessagesInit($(this));
    });

    $("body").on("click", ".commerce-dashboard-plugin-order-messages", function(){
        var orderId = $(this).find('.order-message-id').val();
        var orderReference = $(this).find('.order-message-reference').val();

        // Open parent tab
        melisHelper.tabOpen(translations.tr_meliscommerce_orders_Orders, 'fa fa fa-cart-plus fa-2x', 'id_meliscommerce_order_list_page', 'meliscommerce_order_list_page');
        var alreadyOpen = $("body #melis-id-nav-bar-tabs li a.tab-element[data-id='id_meliscommerce_order_list_page']");
        // check if it exists
        var checkOrders = setInterval(function() {
            if(alreadyOpen.length){
                var navTabsGroup = "id_meliscommerce_order_list_page";

                melisHelper.tabOpen(translations.tr_meliscommerce_orders_Order+' '+orderReference, 'fa fa fa-cart-plus fa-2x', orderId+'_id_meliscommerce_orders_page', 'meliscommerce_orders_page', { orderId : orderId}, navTabsGroup, function(){
                    //JS CALLBACK FOR THE ORDER MESSAGES
                    //TO OPEN THE MESSAGE TAB OF A SPECIFIC ORDER

                    //get parent for the head
                    var parent = orderId + '_id_meliscommerce_orders_content_tabs';
                    //loop through every li
                    $('#' + parent).find('.widget-head').find('ul').find('li').each(function(){
                        //check the li's href attribute
                        if($(this).find('a').attr('href') == "#" + orderId + "_id_meliscommerce_orders_content_tabs_content_messages")
                        {
                            //after finding the right li we give it a class active
                            $(this).addClass("active");
                        }
                        else
                        {
                            //if it is not the right li and it has a class active
                            //we remove it's active class
                            if($(this).hasClass("active"))
                            {
                                $(this).removeClass("active");
                            }
                        }
                    });

                    //get parent for the content
                    var parent = orderId + '_id_meliscommerce_orders_content_tabs_content';
                    //loopp through every div
                    $('#'+parent).find('.tab-pane').each( function(){
                        //check for div's id attribute
                       if($(this).attr('id') == orderId + "_id_meliscommerce_orders_content_tabs_content_messages")
                       {
                           //after finding the right div we give it a class active
                           //to show the messages content
                           $(this).addClass("active");
                       }
                       else
                       {
                           //if it is not the right div and it has a class active
                           //we remove it's active class
                           if($(this).hasClass("active")) {
                               $(this).removeClass("active");
                           }
                       }
                    });

                });
                clearInterval(checkOrders);
            }
        }, 500);
    });


    window.commerceDashboardPluginOrderMessagesInit = function(target){
        var filter = null;
        var placeholder = "";
        var messagecountplaceholder = "";
        //check for target
        if(typeof target === "undefined")
        {
            //if undefined which is the first load
            //by default we should use all to list all messages
            filter = 'all';
            if(melisDashBoardDragnDrop.getCurrentPlugin() == null){
                placeholder = '.commerce-dashboard-plugin-order-messages-list';
                messagecountplaceholder = '.message-count';
            }else{
                placeholder = "#"+melisDashBoardDragnDrop.getCurrentPlugin().find(".commerce-dashboard-plugin-order-messages-list").attr("id");
                messagecountplaceholder = '#'+melisDashBoardDragnDrop.getCurrentPlugin().find(".message-count").attr("id");
            }
        }
        else
        {
            //get clicked button value
            filter = target.val();
            placeholder = "#"+target.closest(".melis-commerce-dashboard-plugin-order-messages-parent").find(".commerce-dashboard-plugin-order-messages-list").attr("id");
            messagecountplaceholder = "#"+target.closest('.commerce-dashboard-plugin-messages-head').find('.message-count').attr('id');
        }

        //ajax
        $.ajax({
            type        : 'POST',
            url         : '/melis/dashboard-plugin/MelisCommerceDashboardPluginOrderMessages/getMessages',
            data		: {filter : filter},
            dataType 	: 'json',
            encode		: true
        }).success(function(data) {
            //the div where the messages will be appended
            console.log(messagecountplaceholder);
            $(placeholder).empty();
            $(messagecountplaceholder).empty();

            //append the unanswered messages
            $(messagecountplaceholder).append('You have <strong class="text-primary">' + data.unansweredMessages + ' messages unanswered</strong>');

            //loop through messages
            $.each(data.messages, function(index, message){
                //if message text is more than 70
                if(message.omsg_message.length > 70) {
                    //we cut the string that will be shown in the dashboard plugin
                    var text = message.omsg_message.substring(0, 70) + '...';
                }else{
                    //if not we retain the original message
                    var text = message.omsg_message;
                }
                //initialize date
                var message_created = moment(message.omsg_date_creation, "YYYY-MM-DD HH:mm:ss");
                //check if the message has already been replied because unanswered messages has different UI
                if(message.noReply){
                    var dateHtml =
                        '<span class="label label-inverse pull-right" style="background-color: #981a1f;">' + message_created.format("HH:mm:ss DD MMM") + '</span>\n';
                    var nameHtml =
                        '<h4 class=" strong" style="color: #981a1f;">' + message.clientFirstName + ' ' + message.clientLastName + '<i class="icon-flag text-primary icon-2x"></i></h4>\n';
                }
                else {
                    var dateHtml = '<span class="label label-inverse label-stroke pull-right">' + message_created.format("HH:mm:ss DD MMM") + '</span>\n';
                    var nameHtml = '<h4 class="">' + message.clientFirstName + ' ' + message.clientLastName + '<i class="icon-flag text-primary icon-2x"></i></h4>\n';
                }
                var html =
                    '<a href="#" class="list-group-item commerce-dashboard-plugin-order-messages" style="border-radius: 0px;border-top: 0px;border-right: 0px;border-left:0px;margin-bottom: 0px;">\n' +
                    '    <input class="order-message-id" type="text" value="' + message.omsg_order_id + '" hidden="hidden">\n' +
                    '    <input class="order-message-reference" type="text" value="' + message.reference + '" hidden="hidden">\n' +
                    '    <span class="media">\n' +
                    '        <span class="media-body media-body-inline">\n' +
                    dateHtml +
                    nameHtml +
                    '            <p class="list-group-item-text" style="font-size:12px;"> ' + text + ' \n' +
                    '            </p>\n' +
                    '        </span>\n' +
                    '    </span>\n' +
                    '</a>';
                //append the message
                $(placeholder).append(html);
            });
        }).error(function(xhr, textStatus, errorThrown) {
            console.log("ERROR !! Status = "+ textStatus + "\n Error = "+ errorThrown + "\n xhr = "+ xhr.statusText);
        });
    }

    $('.commerce-dashboard-plugin-order-messages-list').each(function(){
        commerceDashboardPluginOrderMessagesInit();
    });

});