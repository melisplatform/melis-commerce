var commerceDashPluginOrderMessagesAllMessagesInterval      = '',
    commerceDashPluginOrderMessagesUnseenMessagesInterval   = '',
    commerceDashPluginorderMessagesInstanceCount            = 0,
    commDashPluginOrderMessagesWithUnansweredFilterInstance = 0;

$(function() {
    var $body                   = $("body"),
        placeholder             = '',
        messagecountplaceholder = '',
        intervalDelay           = 60000;

        // changing filter for charts
        $body.on("change", '.commerce-dashboard-plugin-order-messages', function () {
            commerceDashboardPluginOrderMessagesInit( $(this) );
        });

        //opening the message in orders
        // NOTE: scope to the message anchors only. In the BS5 markup the All/Unanswered
        // toggle radios also carry the class `commerce-dashboard-plugin-order-messages`
        // (via `btn-check`); matching them here would run openOrderMessages() on a radio
        // (no order id) and wrongly open the Orders tool when switching filter.
        $body.on("click", "a.commerce-dashboard-plugin-order-messages", function (e) {
            e.preventDefault();
            orderMessages.openOrderMessages(this);
        });

        window.commerceDashboardPluginOrderMessagesInit = function (target) {
            var filter = '';

                messagecountplaceholder = '.message-count';

                if (typeof target === "undefined") {
                    //first load or when using "all" filter
                    filter = 'all';
                    placeholder = '.commerce-dashboard-plugin-order-messages-list';
                }
                else {
                    //when "unanswered" filter is used
                    filter = target.val();
                    placeholder = "#" + target.closest(".melis-commerce-dashboard-plugin-order-messages-parent").find(".commerce-dashboard-plugin-order-messages-list").attr("id");
                }

                commDashPluginOrderMessagesWithUnansweredFilterInstance = $(".melis-commerce-dashboard-plugin-order-messages-parent").find('input.commerce-dashboard-plugin-order-messages[value="unseen"]:checked').length;
                commerceDashPluginorderMessagesInstanceCount = $(".melis-commerce-dashboard-plugin-order-messages-parent").find('input.commerce-dashboard-plugin-order-messages[value="all"]:checked').length;
                
                // console.log(`commerceDashboardPluginOrderMessagesInit() filter: `, filter);
                appendMessages(filter);

                if ( commDashPluginOrderMessagesWithUnansweredFilterInstance === 0 ) {
                    clearInterval(commerceDashPluginOrderMessagesUnseenMessagesInterval);
                    commerceDashPluginOrderMessagesUnseenMessagesInterval = '';
                }

                if ( commerceDashPluginorderMessagesInstanceCount === 0 ) {
                    clearInterval(commerceDashPluginOrderMessagesAllMessagesInterval);
                    commerceDashPluginOrderMessagesAllMessagesInterval = '';
                }

                if ( filter === 'all' ) {
                    if ( commerceDashPluginOrderMessagesAllMessagesInterval === '' ) {
                        commerceDashPluginOrderMessagesAllMessagesInterval = setInterval(appendMessages, intervalDelay, filter);
                    }
                }
                else {
                    if (commerceDashPluginOrderMessagesUnseenMessagesInterval === '') {
                        commerceDashPluginOrderMessagesUnseenMessagesInterval = setInterval(appendMessages, intervalDelay, filter);
                    }
                }
        }

        //initialize the order messages that are already in the dashboard
        if ( $('#'+activeTabId+'[data-meliskey="meliscore_dashboard"]').find(".commerce-dashboard-plugin-order-messages-list").length > 0 ) {
            commerceDashboardPluginOrderMessagesInit();
        }

        function appendMessages(filter) {
            $.ajax({
                type: 'POST',
                url: '/melis/dashboard-plugin/MelisCommerceDashboardPluginOrderMessages/getMessages',
                data: {filter: filter},
                dataType: 'json',
                encode: true
            }).done(function (data) {
                // console.log(`appendMessages() data:`, data);
                if ( data ) {
                    //empty divs first
                    $(".melis-commerce-dashboard-plugin-order-messages-parent").find('input.commerce-dashboard-plugin-order-messages[value=' + '"' + filter + '"' + ']:checked').each(function (index, element) {
                        orderMessages.clear(element);
                        orderMessages.setUnansweredMessages(data.unansweredMessages, element);
                    });

                    $.each(data.messages, function (index, message) {
                        orderMessages.setMessages(placeholder, message, filter);
                    });
                }
            }).fail(function (xhr, textStatus, errorThrown) {
                // Échec silencieux : pas d'alert bloquante ni de bruit console au chargement de la
                // plateforme (ticket 0010871). Le widget reste simplement vide.
            });
        }

        var orderMessages = {
            openOrderMessages: function (element) {
                var orderId         = $(element).find('.order-message-id').val(),
                    orderReference  = $(element).find('.order-message-reference').val(),
                    navTabsGroup    = "id_meliscommerce_order_list_page";

                    // REACT back-office: the dashboard plugin runs inside the iframe pool. melisHelper.tabOpen
                    // would only open the Orders tool INSIDE this plugin's own iframe tab shell (not a real
                    // top-level React tab), and the legacy tab lookups below don't resolve there → JS errors.
                    // Ask the React host to open the Orders tool for this order instead — same bridge the
                    // Clients tool uses (see melis-core App.tsx __melisOpenTool listener). In the classic
                    // standalone back-office __melisRealParent is undefined → keep the legacy flow below.
                    if (window.__melisRealParent) {
                        window.__melisRealParent.postMessage({
                            __melisOpenTool: true,
                            forwardKey: "MelisCommerce/MelisComOrderList",
                            id: orderId,
                            label: translations.tr_meliscommerce_orders_Order + ' ' + orderReference
                        }, "*");
                        return;
                    }

                    // Open parent tab (Orders list)
                    melisHelper.tabOpen(
                        translations.tr_meliscommerce_orders_Orders,
                        'fa fa fa-cart-plus fa-2x',
                        'id_meliscommerce_order_list_page',
                        'meliscommerce_order_list_page'
                    );

                    // Callback: once the order page content is loaded, switch to its Messages sub-tab.
                    // NOTE: use jQuery .trigger("click") on the matched SET — never `[0].trigger(...)`,
                    // because `[0]` is a raw DOM node (no jQuery methods) and is `undefined` when the
                    // selector matches nothing → "can't access property 'trigger', ...[0] is undefined".
                    var openMessagesTab = function () {
                        var parent      = orderId + '_id_meliscommerce_orders_content_tabs',
                            messagesTab = $('#' + parent).find("a[href='#" + orderId + "_id_meliscommerce_orders_content_tabs_content_messages']");

                        if (messagesTab.length) {
                            messagesTab.trigger("click");
                        }
                    };

                    // Wait for the Orders list tab to exist, then open/switch to the specific order.
                    var checkOrders = setInterval(function () {
                        var ordersTab = $("body #melis-id-nav-bar-tabs li a.tab-element[data-id='id_meliscommerce_order_list_page']");

                        if (!ordersTab.length) {
                            return;
                        }

                        clearInterval(checkOrders);

                        // Re-evaluate here (not before the interval): the order tab may have been
                        // opened between the click and this tick.
                        var specificOrderTab = $("body a.tab-element[data-id='" + orderId + "_id_meliscommerce_orders_page']");

                        if (specificOrderTab.length) {
                            // Order already open -> focus it and reload its Messages zone
                            specificOrderTab.trigger("click");
                            melisHelper.zoneReload(
                                orderId + '_id_meliscommerce_orders_content_tabs_content_messages_details',
                                'meliscommerce_orders_content_tabs_content_messages_details',
                                {orderId: orderId},
                                openMessagesTab
                            );
                        }
                        else {
                            // Order not open yet -> open its tab, then open the Messages sub-tab
                            melisHelper.tabOpen(
                                translations.tr_meliscommerce_orders_Order + ' ' + orderReference,
                                'fa fa fa-cart-plus fa-2x',
                                orderId + '_id_meliscommerce_orders_page',
                                'meliscommerce_orders_page',
                                {orderId: orderId},
                                navTabsGroup,
                                openMessagesTab
                            );
                        }
                    }, 500);
            },
            clear: function (element) {
                $(element).closest('.melis-commerce-dashboard-plugin-order-messages-parent').find('.commerce-dashboard-plugin-order-messages-list').empty();
                $(element).closest('.melis-commerce-dashboard-plugin-order-messages-parent').find('.message-count').empty();
            },
            setUnansweredMessages: function (unansweredMessages, element) {
                var message     = '',
                    newMessage  = '';

                    if ( unansweredMessages > 1 ) {
                        message = translations.tr_melis_commerce_dashboard_plugin_order_messages_unanswered_messages;
                        newMessage = message.replace("%d", unansweredMessages);
                    }
                    else {
                        message = translations.tr_melis_commerce_dashboard_plugin_order_messages_unanswered_messages;
                        newMessage = message.replace("messages", "message").replace("%d", unansweredMessages);
                    }

                    $(element).closest('.melis-commerce-dashboard-plugin-order-messages-parent').find(messagecountplaceholder).append(newMessage);
            },
            setMessages: function (placeholder, message, filter) {
                var text            = message.omsg_message.length > 70 ? message.omsg_message.substring(0, 70) + '...' : message.omsg_message,
                    message_created = moment(message.omsg_date_creation, "YYYY-MM-DD HH:mm:ss"),
                    flag = 'answered';

                    if  (message.noReply ) {
                        flag = 'unanswered';
                    }

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

                    var month = months[parseInt(message_created.format("M")) - 1];

                    var dateHtml = '<span class="label label-inverse text-nowrap ms-2">' +
                        message_created.format("HH:mm:ss") + ' ' + month.replace('%day', message_created.format("DD")) +
                        '</span>';

                    // BS5-safe angle-double-right separator (the old markup emitted a malformed <i> tag)
                    var doubleArrow = ' <i class="fa fa-angle-double-right"></i> ';

                    var nameHtml = '<div class="commerce-dashboard-plugin-order-messages-line">' +
                        '<span class="fw-semibold">' +
                        message.clientFirstName + ' ' + message.clientLastName +
                        '</span>' + doubleArrow +
                        '<small class="text-muted">' +
                        translations.tr_melis_commerce_dashboard_plugin_order_messages_message_order_amount + message.totalOrderAmount +
                        '</small>' + doubleArrow +
                        '<small class="text-muted">' +
                        translations.tr_melis_commerce_dashboard_plugin_order_messages_message_placed_on + message.orderDate +
                        '</small>' +
                        '</div>';

                    // NOTE (BS5 migration): the old row used the Bootstrap 4 media object
                    // (.media / .media-body) which was removed in Bootstrap 5, so the layout
                    // collapsed (date badge overlapping the preview text). Rebuilt with flex utils:
                    // meta + preview on the left (truncated), date badge kept on the right.
                    var messageHtml = '<a href="#" class="list-group-item commerce-dashboard-plugin-order-messages ' + flag + '">' +
                        '  <input class="order-message-id" type="text" value="' + message.omsg_order_id + '" hidden="hidden">' +
                        '  <input class="order-message-reference" type="text" value="' + message.reference + '" hidden="hidden">' +
                        '  <div class="d-flex justify-content-between align-items-start">' +
                        '    <div class="flex-grow-1 pe-2" style="min-width:0;">' +
                        nameHtml +
                        '      <p class="list-group-item-text text-truncate mb-0">' + text + '</p>' +
                        '    </div>' +
                        dateHtml +
                        '  </div>' +
                        '</a>';

                    $(".melis-commerce-dashboard-plugin-order-messages-parent").find('input.commerce-dashboard-plugin-order-messages[value=' + '"' + filter + '"' + ']:checked').each(function (index, element) {
                        $(element).closest('.melis-commerce-dashboard-plugin-order-messages-parent').find('.commerce-dashboard-plugin-order-messages-list').append(messageHtml);
                    });
            }
        };
});

//delete callback if there is only one plugin and it is deleted the interval will be cleared
function commerceDasboardPluginOrderMessagesDelete(element) {
    if ( element.find(".melis-commerce-dashboard-plugin-order-messages-parent").length === 1 ) {
        if ( element.find(".melis-commerce-dashboard-plugin-order-messages-parent input.commerce-dashboard-plugin-order-messages[value='all']:checked").length > 0 ) {
            commerceDashPluginorderMessagesInstanceCount--;
            if ( commerceDashPluginorderMessagesInstanceCount === 0 ) {
                clearInterval(commerceDashPluginOrderMessagesAllMessagesInterval);
                commerceDashPluginOrderMessagesAllMessagesInterval = '';
            }
        }
        else {
            commDashPluginOrderMessagesWithUnansweredFilterInstance--;
            if ( commDashPluginOrderMessagesWithUnansweredFilterInstance === 0 ) {
                clearInterval(commerceDashPluginOrderMessagesUnseenMessagesInterval);
                commerceDashPluginOrderMessagesUnseenMessagesInterval = '';
            }
        }
    }
}