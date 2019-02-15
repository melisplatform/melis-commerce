var commerceDashPluginOrderMessagesAllMessagesInterval = '';
var commerceDashPluginOrderMessagesUnseenMessagesInterval = '';
var commerceDashPluginorderMessagesInstanceCount = 0;
var commDashPluginOrderMessagesWithUnansweredFilterInstance = 0;

$(document).ready(function () {
    var placeholder = '';
    var messagecountplaceholder = '';
    var intervalDelay = 60000;

    // changing filter for charts
    $("body").on("change", '.commerce-dashboard-plugin-order-messages', function () {
        commerceDashboardPluginOrderMessagesInit($(this));
    });

    //opening the message in orders
    $("body").on("click", ".commerce-dashboard-plugin-order-messages", function () {
        orderMessages.openOrderMessages(this);
    });

    window.commerceDashboardPluginOrderMessagesInit = function (target) {
        var filter = '';
        messagecountplaceholder = '.message-count';
        if (typeof target === "undefined") {
            //first load or when using "all" filter
            filter = 'all';
            placeholder = '.commerce-dashboard-plugin-order-messages-list';
        } else {
            //when "unanswered" filter is used
            filter = target.val();
            placeholder = "#" + target.closest(".melis-commerce-dashboard-plugin-order-messages-parent").find(".commerce-dashboard-plugin-order-messages-list").attr("id");
        }

        commDashPluginOrderMessagesWithUnansweredFilterInstance = $(".melis-commerce-dashboard-plugin-order-messages-parent").find('label.active input[value="unseen"]').length;
        commerceDashPluginorderMessagesInstanceCount = $(".melis-commerce-dashboard-plugin-order-messages-parent").find('label.active input[value="all"]').length;
        appendMessages(filter);

        if (commDashPluginOrderMessagesWithUnansweredFilterInstance === 0) {
            clearInterval(commerceDashPluginOrderMessagesUnseenMessagesInterval);
            commerceDashPluginOrderMessagesUnseenMessagesInterval = '';
        }

        if (commerceDashPluginorderMessagesInstanceCount === 0) {
            clearInterval(commerceDashPluginOrderMessagesAllMessagesInterval);
            commerceDashPluginOrderMessagesAllMessagesInterval = '';
        }

        if (filter === 'all') {
            if (commerceDashPluginOrderMessagesAllMessagesInterval === '') {
                commerceDashPluginOrderMessagesAllMessagesInterval = setInterval(appendMessages, intervalDelay, filter);
            }
        } else {
            if (commerceDashPluginOrderMessagesUnseenMessagesInterval === '') {
                commerceDashPluginOrderMessagesUnseenMessagesInterval = setInterval(appendMessages, intervalDelay, filter);
            }
        }
    }

    //initialize the order messages that are already in the dashboard
    if ($('.commerce-dashboard-plugin-order-messages-list').length > 0) {
        commerceDashboardPluginOrderMessagesInit();
    }

    function appendMessages(filter) {
        $.ajax({
            type: 'POST',
            url: '/melis/dashboard-plugin/MelisCommerceDashboardPluginOrderMessages/getMessages',
            data: {filter: filter},
            dataType: 'json',
            encode: true
        }).success(function (data) {
            //empty divs first
            $(".melis-commerce-dashboard-plugin-order-messages-parent").find('label.active input[value=' + '"' + filter + '"' + ']').each(function (index, element) {
                orderMessages.clear(element);
                orderMessages.setUnansweredMessages(data.unansweredMessages, element);
            });

            $.each(data.messages, function (index, message) {
                orderMessages.setMessages(placeholder, message, filter);
            });
        }).error(function (xhr, textStatus, errorThrown) {
            console.log("ERROR !! Status = " + textStatus + "\n Error = " + errorThrown + "\n xhr = " + xhr.statusText);
        });
    }

    var orderMessages = {
        openOrderMessages: function (element) {
            var orderId = $(element).find('.order-message-id').val();
            var orderReference = $(element).find('.order-message-reference').val();

            // Open parent tab
            melisHelper.tabOpen(
                translations.tr_meliscommerce_orders_Orders,
                'fa fa fa-cart-plus fa-2x',
                'id_meliscommerce_order_list_page',
                'meliscommerce_order_list_page'
            );

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
                        navTabsGroup,
                        function () {
                            //JS CALLBACK FOR THE ORDER MESSAGES
                            //TO OPEN THE MESSAGE TAB OF A SPECIFIC ORDER
                            var parent = orderId + '_id_meliscommerce_orders_content_tabs';

                            $('#' + parent).find('.widget-head').find('ul').find('li').each(function () {
                                if ($(this).find('a').attr('href') === "#" + orderId + "_id_meliscommerce_orders_content_tabs_content_messages") {
                                    $(this).addClass("active");
                                } else {
                                    if ($(this).hasClass("active")) {
                                        $(this).removeClass("active");
                                    }
                                }
                            });

                            var parent = orderId + '_id_meliscommerce_orders_content_tabs_content';

                            $('#' + parent).find('.tab-pane').each(function () {
                                if ($(this).attr('id') === orderId + "_id_meliscommerce_orders_content_tabs_content_messages") {
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
        },
        clear: function (element) {
            $(element).closest('.melis-commerce-dashboard-plugin-order-messages-parent').find('.commerce-dashboard-plugin-order-messages-list').empty();
            $(element).closest('.melis-commerce-dashboard-plugin-order-messages-parent').find('.message-count').empty();
        },
        setUnansweredMessages: function (unansweredMessages, element) {
            var message = '';
            var newMessage = '';

            if (unansweredMessages > 1) {
                message = translations.tr_melis_commerce_dashboard_plugin_order_messages_unanswered_messages;
                newMessage = message.replace("%d", unansweredMessages);
            } else {
                message = translations.tr_melis_commerce_dashboard_plugin_order_messages_unanswered_messages;
                newMessage = message.replace("messages", "message").replace("%d", unansweredMessages);
            }

            $(element).closest('.melis-commerce-dashboard-plugin-order-messages-parent').find(messagecountplaceholder).append(newMessage);
        },
        setMessages: function (placeholder, message, filter) {
            var colorRed = '';
            var bgColorRed = '';
            var classStrong = '';
            var text = message.omsg_message.length > 70 ? message.omsg_message.substring(0, 70) + '...' : message.omsg_message;
            var message_created = moment(message.omsg_date_creation, "YYYY-MM-DD HH:mm:ss");

            if (message.noReply) {
                colorRed = 'style="color: #981a1f;"';
                classStrong = 'strong';
                bgColorRed = 'style="background-color: #981a1f;"';
            }

            var dateHtml = '<span class="label label-inverse pull-right" ' + bgColorRed + '>' +
                message_created.format("HH:mm:ss DD MMM") +
                '</span>';

            var doubleArrow = '<i class="fa fa-angle-double-right" ' + colorRed + '<i/>';

            var nameHtml = '<span class="' + classStrong + '" ' + colorRed + '>' +
                message.clientFirstName + ' ' + message.clientLastName +
                '</span> ' + doubleArrow +
                ' <small class="' + classStrong + '" ' + colorRed + '>' +
                translations.tr_melis_commerce_dashboard_plugin_order_messages_message_order_amount + message.totalOrderAmount +
                '</small> ' + doubleArrow +
                ' <small class="' + classStrong + '" ' + colorRed + '>' +
                translations.tr_melis_commerce_dashboard_plugin_order_messages_message_placed_on + message.orderDate +
                '</small>';

            var messageHtml = '<a href="#" class="list-group-item commerce-dashboard-plugin-order-messages" ' +
                'style="border-radius: 0px;border-top: 0px;border-right: 0px;border-left:0px;margin-bottom: 0px;">' +
                '  <input class="order-message-id" type="text" value="' + message.omsg_order_id + '" hidden="hidden">' +
                '  <input class="order-message-reference" type="text" value="' + message.reference + '" hidden="hidden">' +
                '  <span class="media">' +
                '    <span class="media-body media-body-inline">' +
                dateHtml +
                nameHtml +
                '    <p class="list-group-item-text" style="font-size:12px;"> ' +
                text + ' ' +
                '    </p>' +
                '    </span>' +
                '  </span>' +
                '</a>';

            $(".melis-commerce-dashboard-plugin-order-messages-parent").find('label.active input[value=' + '"' + filter + '"' + ']').each(function (index, element) {
                $(element).closest('.melis-commerce-dashboard-plugin-order-messages-parent').find('.commerce-dashboard-plugin-order-messages-list').append(messageHtml);
            });
        }
    };
});

//delete callback if there is only one plugin and it is deleted the interval will be cleared
function commerceDasboardPluginOrderMessagesDelete(element) {
    if (element.find(".melis-commerce-dashboard-plugin-order-messages-parent").length === 1) {
        if (element.find(".melis-commerce-dashboard-plugin-order-messages-parent label.active input[value='all']").length > 0) {
            commerceDashPluginorderMessagesInstanceCount--;
            if (commerceDashPluginorderMessagesInstanceCount === 0) {
                clearInterval(commerceDashPluginOrderMessagesAllMessagesInterval);
                commerceDashPluginOrderMessagesAllMessagesInterval = '';
            }
        } else {
            commDashPluginOrderMessagesWithUnansweredFilterInstance--;
            if (commDashPluginOrderMessagesWithUnansweredFilterInstance === 0) {
                clearInterval(commerceDashPluginOrderMessagesUnseenMessagesInterval);
                commerceDashPluginOrderMessagesUnseenMessagesInterval = '';
            }
        }
    }
}
