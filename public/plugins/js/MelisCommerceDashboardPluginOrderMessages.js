$(document).ready(function() {

    //when filtering the messages
    $("body").on("change", '.commerce-dashboard-plugin-order-messages', function() {
        commerceDashboardPluginOrderMessagesInit($(this));
    });

    $("body").on("click", ".commerce-dashboard-plugin-order-messages", function(){
        var orderId = $(this).find('.order-message-id').val();
        var orderReference = $(this).find('.order-message-reference').val();

        // Open parent tab
        melisHelper.tabOpen(
            translations.tr_meliscommerce_orders_Orders,
            'fa fa fa-cart-plus fa-2x',
            'id_meliscommerce_order_list_page',
            'meliscommerce_order_list_page'
        );

        var alreadyOpen = $("body #melis-id-nav-bar-tabs li a.tab-element[data-id='id_meliscommerce_order_list_page']");
        // check if it exists
        var checkOrders = setInterval(function() {
            if (alreadyOpen.length) {
                var navTabsGroup = "id_meliscommerce_order_list_page";

                melisHelper.tabOpen(
                    translations.tr_meliscommerce_orders_Order+' '+orderReference,
                    'fa fa fa-cart-plus fa-2x',
                    orderId+'_id_meliscommerce_orders_page',
                    'meliscommerce_orders_page',
                    { orderId : orderId},
                    navTabsGroup,
                    function(){
                    //JS CALLBACK FOR THE ORDER MESSAGES
                    //TO OPEN THE MESSAGE TAB OF A SPECIFIC ORDER
                    var parent = orderId + '_id_meliscommerce_orders_content_tabs';

                    $('#' + parent).find('.widget-head').find('ul').find('li').each(function(){
                        if ($(this).find('a').attr('href') == "#" + orderId + "_id_meliscommerce_orders_content_tabs_content_messages") {
                            $(this).addClass("active");
                        } else {
                            if($(this).hasClass("active")) {
                                $(this).removeClass("active");
                            }
                        }
                    });

                    //get parent for the content
                    var parent = orderId + '_id_meliscommerce_orders_content_tabs_content';

                    $('#'+parent).find('.tab-pane').each( function(){
                       if ($(this).attr('id') == orderId + "_id_meliscommerce_orders_content_tabs_content_messages") {
                           $(this).addClass("active");
                       } else {
                           if ($(this).hasClass("active")) {
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

        if (typeof target === "undefined") {
            //first load
            filter = 'all';
            if (melisDashBoardDragnDrop.getCurrentPlugin() == null) {
                placeholder = '.commerce-dashboard-plugin-order-messages-list';
                messagecountplaceholder = '.message-count';
            } else {
                placeholder = "#"+melisDashBoardDragnDrop.getCurrentPlugin().find(".commerce-dashboard-plugin-order-messages-list").attr("id");
                messagecountplaceholder = '#'+melisDashBoardDragnDrop.getCurrentPlugin().find(".message-count").attr("id");
            }
        } else {
            //get clicked button value
            filter = target.val();
            placeholder = "#"+target.closest(".melis-commerce-dashboard-plugin-order-messages-parent").find(".commerce-dashboard-plugin-order-messages-list").attr("id");
            messagecountplaceholder = "#"+target.closest('.commerce-dashboard-plugin-messages-head').find('.message-count').attr('id');
        }

        $.ajax({
            type        : 'POST',
            url         : '/melis/dashboard-plugin/MelisCommerceDashboardPluginOrderMessages/getMessages',
            data		: {filter : filter},
            dataType 	: 'json',
            encode		: true
        }).success(function(data) {
            //empty divs first
            $(placeholder).empty();
            $(messagecountplaceholder).empty();

            if (data.unansweredMessages > 1) {
                var message = translations.tr_melis_commerce_dashboard_plugin_order_messages_unanswered_messages;
                //replace x with the count of unanswered messages
                var newMessage = message.replace("%d", data.unansweredMessages);
                $(messagecountplaceholder).append(newMessage);
            } else {
                var message = translations.tr_melis_commerce_dashboard_plugin_order_messages_unanswered_messages;
                //replace x with the count of unanswered messages
                var newMessage = message.replace("messages", "message").replace("%d", data.unansweredMessages);
                $(messagecountplaceholder).append(newMessage);
            }

            $.each(data.messages, function(index, message){
                if (message.omsg_message.length > 70) {
                    var text = message.omsg_message.substring(0, 70) + '...';
                } else {
                    var text = message.omsg_message;
                }

                var message_created = moment(message.omsg_date_creation, "YYYY-MM-DD HH:mm:ss");

                if (message.noReply) {
                    var dateHtml = '<span class="label label-inverse pull-right" style="background-color: #981a1f;">' +
                        message_created.format("HH:mm:ss DD MMM") +
                        '</span>';

                    var doubleArrow = '<i class="fa fa-angle-double-right" style="color: #981a1f;"<i/>';

                    var nameHtml =  '<span class="strong" style="color: #981a1f;">' +
                        message.clientFirstName + ' ' + message.clientLastName +
                        '</span> ' + doubleArrow +
                        ' <small class="strong" style="color:#981a1f;">' +
                        translations.tr_melis_commerce_dashboard_plugin_order_messages_message_order_amount + message.totalOrderAmount +
                        '</small> ' + doubleArrow +
                        ' <small class="strong" style="color:#981a1f;">' +
                        translations.tr_melis_commerce_dashboard_plugin_order_messages_message_placed_on + message.orderDate +
                        '</small>';

                } else {
                    var dateHtml = '<span class="label label-inverse label-stroke pull-right">' +
                        message_created.format("HH:mm:ss DD MMM") +
                        '</span>';

                    var doubleArrow = '<i class="fa fa-angle-double-right"<i/>';

                    var nameHtml =  '<span>' +
                        message.clientFirstName + ' ' + message.clientLastName +
                        '</span> ' + doubleArrow +
                        ' <small>' +
                        translations.tr_melis_commerce_dashboard_plugin_order_messages_message_order_amount + message.totalOrderAmount +
                        '</small> ' + doubleArrow +
                        ' <small>' +
                        translations.tr_melis_commerce_dashboard_plugin_order_messages_message_placed_on + message.orderDate +
                        '</small>';
                }

                var html =  '<a href="#" class="list-group-item commerce-dashboard-plugin-order-messages" ' +
                    'style="border-radius: 0px;border-top: 0px;border-right: 0px;border-left:0px;margin-bottom: 0px;">' +
                    '    <input class="order-message-id" type="text" value="' + message.omsg_order_id + '" hidden="hidden">' +
                    '    <input class="order-message-reference" type="text" value="' + message.reference + '" hidden="hidden">' +
                    '    <span class="media">' +
                    '        <span class="media-body media-body-inline">' +
                    dateHtml +
                    nameHtml +
                    '            <p class="list-group-item-text" style="font-size:12px;"> ' + text + ' ' +
                    '            </p>' +
                    '        </span>' +
                    '    </span>' +
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