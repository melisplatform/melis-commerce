/* Category Sticky Top Start */
if (melisCore.screenSize >= 768) {
	$(window).on("scroll click resize", function(e) {
		$("#id_meliscommerce_categories_category_header").css("width", "100%");

		var stickyCatNav = $(
				"#" + activeTabId + " #id_meliscommerce_categories_category"
			),
			$tabSeo = $(
				"#id_meliscommerce_categories_category_tabs .widget-head ul li.active a.search.active"
			);

		if (stickyCatNav.length) {
			var position = stickyCatNav.position();

			if (position.top < $(window).scrollTop() - 10 && $tabSeo.length !== 1) {
				$("#id_meliscommerce_categories_category").addClass("fix-cat");
				//$("#categoryInfoPanel").css("padding-top","66px");
				$("#saveCategory").css("margin-top", "10px");
				$("#id_meliscommerce_categories_category_header").width(
					$("#id_meliscommerce_categories_list").width()
				);
			} else {
				$("#id_meliscommerce_categories_category").removeClass("fix-cat");
				$("#categoryInfoPanel").css("padding-top", "0");
				$("#saveCategory").css("margin-top", "0");
			}
		}
	});
}
/* Category Sticky Top End */

var categoryOpeningItemFlag = true;

$(function() {
	var $body = $("body");

	$body.on("click", ".addCategory", function(e) {
		$("#categoryTreeViewPanel").collapse("hide");

		var zoneId = "id_meliscommerce_categories_category",
			melisKey = "meliscommerce_categories_category",
			catTree = $("#categoryTreeView").jstree(true),
			catSelected = catTree.get_selected(),
			catFatherId = "";

		if (catSelected.length >= 1) {
			/**
			 * using parseInt this will get only the
			 * number value in a string value
			 */
			catFatherId = parseInt(catSelected[0]);
		}

		$("#" + zoneId).removeClass("hidden");

		melisHelper.zoneReload(zoneId, melisKey, {
			catId: 0,
			catFatherId: catFatherId,
		});
		melisCommerce.setUniqueId(0);
	});

	$body.on("click", ".addCatalog", function(e) {
		$("#categoryTreeViewPanel").collapse("hide");

		var zoneId = "id_meliscommerce_categories_category",
			melisKey = "meliscommerce_categories_category";

		$("#" + zoneId).removeClass("hidden");

		melisHelper.zoneReload(zoneId, melisKey, { catId: 0, catFatherId: -1 });
		melisCommerce.setUniqueId(0);
	});

	$body.on("click", "#saveCategory", function() {
		var $this = $(this),
			catId = $this.data("catid"),
			dataString = new Array();

		$this.prop("disabled", true);

		// Serialize Forms of Category Panel
		dataString = $("#id_meliscommerce_categories_category form")
			.not(".category_" + catId + "_seo_form, .cat_trans_form")
			.serializeArray();

		// Category Id
		dataString.push({
			name: "cat_id",
			value: catId,
		});

		dataString = melisCommerceSeo.serializeSeo("category", catId, dataString);

		// Category Parent Id
		var catFatherId = $this.data("catfatherid");

		dataString.push({
			name: "cat_father_cat_id",
			value: catFatherId,
		});

		// Category Status
		var catStatus = 0;
		if ($('input[name="cat_status"]').is(":checked")) {
			catStatus = 1;
		}

		dataString.push({
			name: "cat_status",
			value: catStatus,
		});

		// Category Transalations
		$("form.cat_trans_form").each(function() {
			var $this = $(this);

			langLocale = $this.data("locale");
			langId = $this.data("langid");

			// convert the serialized form values into an array
			catDataString = $this.serializeArray();

			$.each(catDataString, function() {
				dataString.push({
					name: "cat_trans[" + langId + "][" + this.name + "]",
					value: this.value,
				});
			});
		});

		// Product discount
		$("form.categoriesPriceDiscountForm").each(function() {
			var $this = $(this);
			var countryId = $this.data("countryid");
			var groupId = $this.data("groupid");
			var priceDiscountDataString = $this.serializeArray();

			$.each(priceDiscountDataString, function() {
				if (this.value !== "") {
					dataString.push({
						name:
							"price_discount[" +
							countryId +
							"][" +
							groupId +
							"][" +
							this.name +
							"]",
						value: this.value,
					});
				}
			});
		});

		// serialize the new array and send it to server
		dataString = $.param(dataString);

		$.ajax({
			type: "POST",
			url: "/melis/MelisCommerce/MelisComCategory/saveCategory",
			data: dataString,
			dataType: "json",
			encode: true,
			cache: false,
		})
			.done(function(data) {
				$("#saveCategory").prop("disabled", false);

				if (data.success) {
					$("#categoryTreeViewPanel").collapse("show");

					$("body").animate(
						{
							scrollTop: 0,
						},
						1000
					);

					melisCore.flashMessenger();
					melisHelper.melisOkNotification(data.textTitle, data.textMessage);

					var catTree = $("#categoryTreeView").jstree(true),
						// Get Current Url of the category Tree view
						realUrl = catTree.settings.core.data.url,
						// selected Category Id/Node
						selectedNode = "";

					if (catId == 0) {
						// New Category Created
						var nodeData = catTree.get_node(catFatherId),
							nodeParents = new Array();

						nodeParentsStr = "";

						nodeParents.push(catFatherId);

						if (typeof nodeData === "object") {
							if (nodeData.parents.length > 1) {
								for (i = 0; i < nodeData.parents.length - 1; i++) {
									nodeParents.push(nodeData.parents[i]);
								}
							}
						}

						nodeParentsStr = "&openStateParent=" + nodeParents.join();

						selectedNode = data.cat_id;
					} else {
						// Category exist
						var nodeData = catTree.get_node(catId),
							nodeParents = new Array();

						nodeParentsStr = "";

						if (nodeData !== false) {
							if (nodeData.parents.length > 1) {
								for (i = 0; i < nodeData.parents.length - 1; i++) {
									nodeParents.push(nodeData.parents[i]);
								}
								nodeParentsStr = "&openStateParent=" + nodeParents.join();
							}

							catTree.get_node(catId).state.selected = true;
						}

						selectedNode = catId;
					}

					// Set JsTree Url with Selected Node and Open State Nodes
					catTree.settings.core.data.url =
						realUrl + "&selected=" + selectedNode + nodeParentsStr;
					// Deselect selected node
					catTree.deselect_all();
					// Remove Node Highllight
					$("#categoryTreeView ul li div").removeClass(
						"jstree-wholerow-clicked"
					);
					//refresh Category view
					catTree.refresh();
					// Rollback the real/default url
					catTree.settings.core.data.url = realUrl;

					var zoneId = "id_meliscommerce_categories_category",
						melisKey = "meliscommerce_categories_category";

					melisHelper.zoneReload(zoneId, melisKey, { catId: selectedNode });
					melisCommerce.setUniqueId(selectedNode);

					// Highlighting the node
					$("#categoryTreeView #" + selectedNode + " div")
						.first()
						.addClass("jstree-wholerow-clicked");
					
					$("#refreshCategoryTreeView").trigger("click");
				} else {
					melisHelper.melisKoNotification(
						data.textTitle,
						data.textMessage,
						data.errors
					);
					melisCoreTool.highlightErrors(
						data.success,
						data.errors,
						"id_meliscommerce_categories_category_form_transalations"
					);
				}
				melisCore.flashMessenger();
			})
			.fail(function() {
				$("#saveCategory").prop("disabled", false);
				alert(translations.tr_meliscore_error_message);
			});
	});

	// Category Tree Languages Dropdown
	$body.on(
		"click",
		".category-tree-view-lang.commerce-category-tree-view-lang li a",
		function() {
			var $this = $(this),
				langText = $this.text(),
				langLocale = $this.data("locale");

			categoryOpeningItemFlag = false;

			$(".cat-tree-view-languages.commerce-category-tree-view-languages span.filter-key").text(langText);
			$("#categoryTreeView").data("langlocale", langLocale);
			$("#categoryTreeView").jstree(true).settings.core.data.data = [
				{ name: "langlocale", value: langLocale },
			];
			$("#categoryTreeView")
				.jstree(true)
				.refresh();
		}
	);

	// Search Input
	$body.on("keyup", "#categoryTreeViewSearchInput", function(e) {
		var $this = $(this),
			searchString = $this.val().trim(),
			searchResult = $("#categoryTreeView").jstree("search", searchString);

		categoryOpeningItemFlag = false;

		setTimeout(function() {
			if (
				$(searchResult).find(".jstree-search").length == 0 &&
				searchString != ""
			) {
				$("#searchNoResult").removeClass("hidden");
				$("#searchNoResult")
					.find("strong")
					.text(searchString);
			} else {
				$("#searchNoResult").addClass("hidden");
			}
		}, 1500);
	});

	$body.on("keyup keypress", "#categoryTreeViewSearchForm", function(e) {
		var key = e.key || e.which;
		if (key === 'Enter') {
			e.preventDefault();
			return false;
		}
	});

	// Clear Input Search
	$body.on("click", "#clearSearchInputBtn", function(e) {
		var catTree = $("#categoryTreeView").jstree(true);

		categoryOpeningItemFlag = false;

		$("#categoryTreeViewSearchInput").val("");
		$("#categoryTreeView").jstree("search", "");
	});

	// Toggle Buttons for Category Tree View
	$body.on("click", "#expandCategoryTreeViewBtn", function(e) {
		categoryOpeningItemFlag = false;
		$("#categoryTreeView").jstree("open_all");
	});

	$body.on("click", "#collapseCategoryTreeViewBtn", function(e) {
		categoryOpeningItemFlag = false;
		$("#categoryTreeView").jstree("close_all");
	});

	// Refresh Category Tree View
	$body.on("click", "#refreshCategoryTreeView", function(e) {
		categoryOpeningItemFlag = false;
		var catTree = $("#categoryTreeView").jstree(true);

		catTree.deselect_all();
		catTree.refresh();
		$("#categoryTreeViewSearchInput").val("");
		$("#categoryTreeView").jstree("search", "");
	});

	// Category Information Form Countries Custom Checkboxes
	$body.on("click", ".ecom-coutries-checkbox", function(evt) {
		var $this = $(this);

		if ($this.find(".fa").hasClass("fa-check-square-o")) {
			// unchecking category Checkbox
			$this.find(".fa").removeClass("fa-check-square-o");
			$this.find(".fa").addClass("fa-square-o");
			$this.find('input[type="checkbox"]').prop("checked", false);

			// If the uncheck is check all checkbox
			if ($this.find(".check-all").hasClass("fa-square-o")) {
				$(".ecom-coutries-checkbox .fa")
					.not(".check-all")
					.addClass("fa-square-o");
				$(".ecom-coutries-checkbox .fa")
					.not(".check-all")
					.removeClass("fa-check-square-o");
				$(".ecom-coutries-checkbox .fa")
					.not(".check-all")
					.next('input[type="checkbox"]')
					.prop("checked", false);
			}
		} else {
			// Checking Category Checkboxes
			$this.find(".fa").removeClass("fa-square-o");
			$this.find(".fa").addClass("fa-check-square-o");
			$this.find('input[type="checkbox"]').prop("checked", true);
		}

		// check all countries
		if (
			$(".ecom-coutries-checkbox .fa").not(".check-all").length ==
				$(".ecom-coutries-checkbox .fa.fa-check-square-o")
					.not(".check-all")
					.next('input[type="checkbox"]:checked').length ||
			$this.find(".check-all").hasClass("fa-check-square-o")
		) {
			// Keeping the check mark but removing the checkbox unchecked
			$(".ecom-coutries-checkbox .fa")
				.not(".check-all")
				.removeClass("fa-square-o");
			$(".ecom-coutries-checkbox .fa")
				.not(".check-all")
				.addClass("fa-check-square-o");
			$(".ecom-coutries-checkbox .fa")
				.not(".check-all")
				.next('input[type="checkbox"]')
				.prop("checked", false);

			// Check mark on checkbox all ang its input checkbox
			$(".ecom-coutries-checkbox .fa.check-all").removeClass("fa-square-o");
			$(".ecom-coutries-checkbox .fa.check-all").addClass("fa-check-square-o");
			$(".ecom-coutries-checkbox .fa.check-all")
				.next('input[type="checkbox"]')
				.prop("checked", true);
		} else {
			// puting back checkbox with check mark to input checkbox checked
			$(".ecom-coutries-checkbox .fa.fa-check-square-o")
				.not(".check-all")
				.next('input[type="checkbox"]')
				.prop("checked", true);

			// Unchecking "check all" checkbox
			$(".ecom-coutries-checkbox .fa.check-all").addClass("fa-square-o");
			$(".ecom-coutries-checkbox .fa.check-all").removeClass(
				"fa-check-square-o"
			);
			$(".ecom-coutries-checkbox .fa.check-all")
				.next('input[type="checkbox"]')
				.prop("checked", false);
		}

		evt.stopPropagation();
		evt.preventDefault();
	});

	// Category Information Form Status, Switch Plugin
	$body.on("switch-change", "#cat_status", function(event, state) {
		var $this = $(this);

		if (state.value == true) {
			$this.find('input[type="checkbox"]').prop("checked", true);
		} else {
			$this.find('input[type="checkbox"]').prop("checked", false);
		}
	});

	$body.on("click", ".categoryProductsRefresh", function(event, state) {
		var catId = $("#categoryProductListTbl").data("catid");

		melisHelper.zoneReload(
			"id_meliscommerce_categories_category_tab_products",
			"meliscommerce_categories_category_tab_products",
			{ catId: catId, activateTab: true }
		);
	});

	// Category Products Remove Button
	$body.on("click", ".categoryProductsRemove", function() {
		var $this = $(this),
			pcatId = $this.parents("tr").attr("id"),
			catId = $("#categoryProductListTbl").data("catid"),
			parentId = $("#saveCategory").data("catfatherid"),
			deleteTitle =
				translations.tr_meliscommerce_categories_category_product_remove,
			deleteMessage =
				translations.tr_meliscommerce_categories_category_product_remove_confirm_msg;

		if (parentId == "-1") {
			deleteTitle =
				translations.tr_meliscommerce_categories_catalog_product_remove;
			deleteMessage =
				translations.tr_meliscommerce_categories_catalog_product_remove_confirm_msg;
		}

		// deletion confirmation
		melisCoreTool.confirm(
			translations.tr_meliscommerce_categories_common_label_yes,
			translations.tr_meliscommerce_categories_common_label_no,
			deleteTitle,
			deleteMessage,
			function() {
				var dataString = new Array();

				dataString.push({
					name: "pcat_id",
					value: pcatId,
				});

				dataString.push({
					name: "parent_id",
					value: parentId,
				});

				dataString.push({
					name: "cat_id",
					value: catId,
				});

				dataString = $.param(dataString);

				$.ajax({
					type: "POST",
					url: "/melis/MelisCommerce/MelisComCategory/removeCategoryProduct",
					data: dataString,
					dataType: "json",
					encode: true,
				})
					.done(function(data) {
						if (data.success) {
							melisHelper.zoneReload(
								"id_meliscommerce_categories_category_tab_products",
								"meliscommerce_categories_category_tab_products",
								{ catId: catId, activateTab: true }
							);
						} else {
							alert(translations.tr_meliscore_error_message);
						}
						melisCore.flashMessenger();
						melisHelper.melisOkNotification(data.textTitle, data.textMessage);
					})
					.fail(function() {
						alert(translations.tr_meliscore_error_message);
					});
			}
		);
	});

	// Category Products View Button
	$body.on("click", ".categoryProductsView", function() {
		var $this = $(this),
			productId = $this.closest("tr").data("productid"),
			//productName 	= $this.parents("tr").find(".toolTipHoverEvent").text(),
			productName = $this.closest("tr").data("productname"),
			navTabsGroup = "id_meliscommerce_product_list_container";

		// Open parent tab
		melisHelper.tabOpen(
			translations.tr_meliscommerce_products_Products,
			"fa icon-shippment fa-2x",
			"id_meliscommerce_product_list_container",
			"meliscommerce_product_list_container"
		);

		var alreadyOpen = $(
			"body #melis-id-nav-bar-tabs li a.tab-element[data-id='" +
				navTabsGroup +
				"']"
		);

		// check if it exists
		var checkProducts = setInterval(function() {
			if (alreadyOpen.length) {
				melisCommerce.openProductPage(productId, productName, navTabsGroup);
				melisCommerce.setUniqueId(productId);
				clearInterval(checkProducts);
			}
		}, 500);
		// melisCommerce.openProductPage(productId, productName, navTabsGroup);
	});

	// Category Tree Double Click Item Action
	$body.on("dblclick", "#categoryTreeViewPanel .jstree-node", function(evt) {
		evt.stopPropagation();
		evt.preventDefault();

		$("#categoryTreeViewPanel").collapse("hide");

		var $this = $(this),
			catId = parseInt($this.attr("id"), 10),
			zoneId = "id_meliscommerce_categories_category",
			melisKey = "meliscommerce_categories_category";

		$("#" + zoneId).removeClass("hidden");

		melisHelper.zoneReload(zoneId, melisKey, { catId: catId });
		// $(".categoryProductsRefresh").trigger("click");

		// Highlighting the node
		$("#categoryTreeView #" + catId + " div")
			.first()
			.addClass("jstree-wholerow-clicked");
		melisCommerce.setUniqueId(catId);
	});

	/* $body.on("click", "#categoryTreeView .jstree-node", function() {
			var $addCategory = $("#"+activeTabId+" .addCategory");

				$addCategory.attr("disabled", false);
				$addCategory.attr("title", null);
		}); */

	$body.on("click", "#categoryTreeView .jstree-clicked", function() {
		var $addCategory = $("#" + activeTabId + " .addCategory");

		$addCategory.prop("disabled", false);
		$addCategory.prop("title", null);
	});

	// Open Single Node in JSTree
	$body.on("click", ".cat-div .jstree-node .jstree-icon", function() {
		categoryOpeningItemFlag = true;
	});

	$body.on(
		"mouseenter mouseleave",
		"#categoryProductListTbl .toolTipHoverEvent",
		function(e) {
			var $this = $(this),
				productId = $this.data("productid");
			loaderText =
				'<div class="qtipLoader"><hr/><span class="text-center col-lg-12">Loading...</span><br/></div>';

			$(".thClassColId").prop("style", null);

			$.each(
				$("table#catProductTable" + productId + " thead").nextAll(),
				function(i, v) {
					$(v).remove();
				}
			);

			$(loaderText).insertAfter("table#catProductTable" + productId + " thead");

			$.ajax({
				type: "POST",
				url: "/melis/MelisCommerce/MelisComProductList/getToolTip",
				data: { productId: productId },
				dataType: "json",
				encode: true,
			})
				.done(function(data) {
					$("div.qtipLoader").remove();

					if (data.content.length === 0) {
						$(
							'<div class="qtipLoader"><hr/><span class="text-center col-lg-12">' +
								translations.tr_meliscommerce_product_tooltip_no_variants +
								"</span><br/></div>"
						).insertAfter("table.qtipTable thead");
					} else {
						// make sure tbody is clear
						$.each(
							$("table#catProductTable" + productId + " thead").nextAll(),
							function(i, v) {
								$(v).remove();
							}
						);

						$.each(data.content.reverse(), function(i, v) {
							$(v).insertAfter("table#catProductTable" + productId + " thead");
						});
					}
				})
				.fail(function(jqXHR, textStatus, errorThrown) {
					//console.log("jqXHR: " + jqXHR + " textStatus: " + textStatus + " errorThrown: " + errorThrown);
					alert(translations.tr_meliscore_error_message);
				});
		}
	);

	$body.on(
		"click",
		"#categoryInfoPanel .widget-head .nav-tabs li a.shopping_cart",
		function() {
			if (melisCore.screenSize <= 767) {
				$("#id_meliscommerce_categories_category_tab_products")
					.find(".categoryProductsRefresh")
					.trigger("click");
			}
		}
	);
});

window.enableDisableAddCategoryBtn = function(action) {
	var addCategory = $(".addCategory");

	if (action == "enable") {
		addCategory.prop("disabled", false);
		addCategory.prop("title", null);
	} else if (action == "disable") {
		addCategory.prop("disabled", true);
		addCategory.prop(
			"title",
			translations.tr_meliscommerce_categories_category_no_selected_catalog_category
		);
	}
};

window.initCategoryTreeView = function() {
	var $this = $(this),
		$body = $("body"),
		$category = $("#categoryTreeView");

	$body.on("click", "#categoryTreeView", function(evt) {
		$("#categoryTreeView ul li div").removeClass("jstree-wholerow-clicked");

		evt.stopPropagation();
		evt.preventDefault();
	});

	$category
		.on("changed.jstree", function(e, data) {
			enableDisableAddCategoryBtn("enable");
		})
		.on("refresh.jstree", function(e, data) {
			enableDisableAddCategoryBtn("disable");
		})
		.on("loading.jstree", function(e, data) {
			melisCommerce.pendingZoneStart(
				"meliscommerce_categories_list_search_input"
			);
		})
		.on("loaded.jstree", function(e, data) {
			melisCommerce.pendingZoneDone(
				"meliscommerce_categories_list_search_input"
			);

			var temp = $("ul.jstree-container-ul > li > a");

			temp.each(function() {
				var father = $(this),
					fatherIcon = father.data("fathericon"),
					temp = father.find("i");

				father.html(
					temp.get(0).outerHTML +
						"<strong>" +
						fatherIcon +
						" " +
						father.text() +
						"</strong>"
				);
			});
		})
		.on("refresh.jstree", function(e, data) {
			var temp = $("ul.jstree-container-ul > li > a");

			temp.each(function() {
				var father = $(this),
					fatherIcon = father.data("fathericon"),
					temp = father.find("i");

				father.html(
					temp.get(0).outerHTML +
						"<strong>" +
						fatherIcon +
						" " +
						father.text() +
						"</strong>"
				);
			});
		})
		.on("ready.jstree", function(e, data) {
			/*console.log(data);*/
		})
		.on("load_node.jstree", function(e, data) {
			/*console.log(data);*/
		})
		.on("open_node.jstree", function(e, data) {
			if (categoryOpeningItemFlag == true) {
				if ($(".cat-div").length) {
					// if Node open sub nodes and not visible to the parent container, this will scroll down to show the sub nodes
					if (
						$(".cat-div #" + data.node.id).offset().top +
							$(".cat-div #" + data.node.id).height() >
						$(".cat-div").offset().top + $(".cat-div").height()
					) {
						// exucute scroll after the opening animation of the node
						$timeOut = setTimeout(function() {
							var catContainer = $(".cat-div").scrollTop(),
								catItemHeight = $(".cat-div #" + data.node.id).innerHeight();

							$(".cat-div").animate(
								{
									scrollTop: catContainer + catItemHeight,
								},
								"slow"
							);
						}, 1000);
					}
				}
			}
		})
		.on("after_open.jstree", function(e, data) {
			$.each(data.node.children_d, function(k, v) {
				var textlang = $("#" + v + "_anchor").data("textlang"),
					products = $("#" + v + "_anchor").data("numprods"),
					spanHtml =
						'<span title="' +
						translations.tr_meliscommerce_categories_list_tree_view_product_num +
						'">(' +
						products +
						")</span>",
					seoId = $("#" + v + "_anchor").data("seopage");

				if (seoId) {
					spanHtml =
						spanHtml + ' - <span class="fa fa-file-o"></span> ' + seoId;
				}

				if (textlang) {
					spanHtml = " " + textlang + spanHtml;
				}

				if (!$("#" + v + "_anchor").hasClass("updatedText")) {
					$("#" + v + "_anchor").append(spanHtml);
					$("#" + v + "_anchor").addClass("updatedText");
				}
			});
		})
		.on("move_node.jstree", function(e, data) {
			// Category Id
			var categoryId = data.node.id,
				// New category Parent ID
				// if value is '#', the Category is on the root of the list
				newParentId = data.parent == "#" ? "-1" : data.parent,
				// Old category Parent ID
				// if value is '#', the Category is on the root of the list
				oldParent = data.old_parent == "#" ? "-1" : data.old_parent,
				// New Category Position
				// Position is the index on the data
				// Adding One(1) to make to avaoid Zero(0) index of position
				categoryNewPosition = data.position + 1,
				dataString = new Array();

			// get data from input
			dataString.push({
				name: "cat_id",
				value: parseInt(categoryId, 10),
			});
			// get date data from param
			dataString.push({
				name: "cat_father_cat_id",
				value: parseInt(newParentId, 10),
			});
			// get date data from param
			dataString.push({
				name: "cat_order",
				value: categoryNewPosition,
			});
			// get date data from param
			dataString.push({
				name: "old_parent",
				value: parseInt(oldParent, 10),
			});

			dataString = $.param(dataString);

			$.ajax({
				type: "POST",
				url: "/melis/MelisCommerce/MelisComCategoryList/saveCategoryTreeView",
				data: dataString,
				dataType: "json",
				encode: true,
			})
				.done(function(data) {
					if (data.success) {
						$currentCategoryId = $("body #saveCategory").data("catid");

						if ($currentCategoryId == parseInt(categoryId, 10)) {
							$("body #saveCategory").data(
								"catfatherid",
								parseInt(newParentId, 10)
							);
						}

						var temp = $("ul.jstree-container-ul > li > a");

						temp.each(function() {
							var father = $(this),
								fatherIcon = father.data("fathericon"),
								temp = father.find("i");

							father.html(
								temp.get(0).outerHTML +
									"<strong>" +
									fatherIcon +
									" " +
									father.text() +
									"</strong>"
							);
							
						});
					} else {
						alert(translations.tr_meliscore_error_message);
					}
				})
				.fail(function() {
					alert(translations.tr_meliscore_error_message);
				});
		})
		.jstree({
			contextmenu: {
				items: function(node) {
					return {
						Add: {
							label: translations.tr_meliscommerce_categories_common_btn_add,
							icon: "fa fa-plus",
							action: function(obj) {
								var parentId = parseInt(node.id),
									position = node.children.length + 1;

								$("#categoryTreeViewPanel").collapse("hide");

								var zoneId = "id_meliscommerce_categories_category",
									melisKey = "meliscommerce_categories_category";

								melisHelper.zoneReload(zoneId, melisKey, {
									catId: 0,
									catFatherId: parentId,
									catOrder: position,
								});
							},
						},
						Update: {
							label: translations.tr_meliscommerce_categories_common_btn_update,
							icon: "fa fa-edit",
							action: function(obj) {
								var catId = parseInt(node.id, 10),
									zoneId = "id_meliscommerce_categories_category",
									melisKey = "meliscommerce_categories_category";

								melisHelper.zoneReload(zoneId, melisKey, { catId: catId });

								$("#categoryTreeViewPanel").collapse("hide");
							},
						},
						Delete: {
							label: translations.tr_meliscommerce_categories_common_btn_delete,
							icon: "fa fa-trash-o",
							action: function(obj) {
								var dataString = new Array(),
									// New category Parent ID
									// if value is '#', the Category is on the root of the list
									parentId =
										node.parent == "#" ? "-1" : parseInt(node.parent, 10);

								dataString.push({
									name: "cat_father_cat_id",
									value: parentId,
								});

								var cattId = parseInt(node.id);

								dataString.push({
									name: "cat_id",
									value: cattId,
								});

								dataString = $.param(dataString);

								var deleteTitle =
										translations.tr_meliscommerce_categories_category_delete,
									deleteMessage =
										translations.tr_meliscommerce_categories_category_delete_confirm_msg;

								if (parentId == "-1") {
									deleteTitle =
										translations.tr_meliscommerce_categories_catalog_delete;
									deleteMessage =
										translations.tr_meliscommerce_categories_catalog_delete_confirm_msg;
								}

								// deletion confirmation
								melisCoreTool.confirm(
									translations.tr_meliscommerce_categories_common_label_yes,
									translations.tr_meliscommerce_categories_common_label_no,
									deleteTitle,
									deleteMessage,
									function() {
										$.ajax({
											type: "POST",
											url:
												"/melis/MelisCommerce/MelisComCategory/deleteCategory",
											data: dataString,
											dataType: "json",
											encode: true,
										})
											.done(function(data) {
												if (data.success) {
													var catTree = $("#categoryTreeView").jstree(true);
													catTree.delete_node(cattId + "_categoryId_anchor");

													if ($("#saveCategory").data("catid") == cattId) {
														var zoneId = "id_meliscommerce_categories_category";
														var melisKey = "meliscommerce_categories_category";

														melisHelper.zoneReload(zoneId, melisKey);
													}

													melisCore.flashMessenger();
													melisHelper.melisOkNotification(
														data.textTitle,
														data.textMessage
													);
												} else {
													melisHelper.melisKoNotification(
														data.textTitle,
														data.textMessage,
														data.errors
													);
												}
											})
											.fail(function() {
												alert(translations.tr_meliscore_error_message);
											});
									}
								);
							},
						},
					};
				},
			},
			core: {
				multiple: false,
				check_callback: true,
				animation: 500,
				themes: {
					name: "default",
					responsive: false,
				},
				dblclick_toggle: false,
				data: {
					cache: false,
					url:
						"/melis/MelisCommerce/MelisComCategoryList/getCategoryTreeView?langlocale=" +
						$("#categoryTreeView").data("langlocale"),
				},
			},
			types: {
				"#": {
					valid_children: ["catalog"],
				},
				catalog: {
					valid_children: ["category"],
				},
				category: {
					valid_children: ["category"],
				},
			},
			plugins: [
				"contextmenu", // plugin makes it possible to right click nodes and shows a list of configurable actions in a menu.
				"changed", // Plugins for Change and Click Event
				"dnd", // Plugins for Drag and Drop
				"search", // Plugins for Search of the Node(s) of the Tree View
				"types", // Plugins for Customizing the Nodes
			],
		});

	$body.on("click", ".categoryProductsExport", function() {
		if (!melisCoreTool.isTableEmpty("categoryProductListTbl")) {
			melisCoreTool.exportData(
				"/melis/MelisCommerce/MelisComCategory/productsExportToCsv?catId=" +
					$(this).data("catid")
			);
		}
	});
};

// Category Information Status Switch Initialization
window.initCategoryStatus = function() {
	$("#cat_status").bootstrapSwitch();
};

window.initCategoryProducts = function(data, tblSettings) {
	// get Category Id from table data
	var catId = $("#" + tblSettings.sTableId).data("catid");

	// Add DataTable Data catId and assign value of CategoryId
	data.catId = catId;

	var catLangLocale = $("#categoryTreeView").data("langlocale");

	// Add DataTable Data catLangLocale and assign value of catLangLocale
	data.catLangLocale = catLangLocale;

	$("#categoryProductListTbl").on("row-reorder.dt", function(e, diff, edit) {
		var result =
			"Reorder started on row: " + edit.triggerRow.data()[1] + "<br>";

		for (var i = 0, ien = diff.length; i < ien; i++) {
			var rowData = $categoryProductListTbl.row(diff[i].node).data();
			result +=
				rowData[1] +
				" updated to be in position " +
				diff[i].newData +
				" (was " +
				diff[i].oldData +
				")<br>";
		}

		if (!$.isEmptyObject(diff)) {
			var dataString = new Array(),
				prdNodes = new Array();

			$.each(diff, function() {
				prdNodes.push(this.node.id + "-" + this.newPosition);
			});

			dataString.push({
				name: "catPrdOrderData",
				value: prdNodes.join(),
			});

			dataString = $.param(dataString);

			$.ajax({
				type: "POST",
				url: "/melis/MelisCommerce/MelisComCategory/reOrderCategoryProducts",
				data: dataString,
				dataType: "json",
				encode: true,
			})
				.done(function(data) {
					if (!data.success) {
						alert(translations.tr_meliscore_error_message);
					}
				})
				.fail(function() {
					alert(translations.tr_meliscore_error_message);
				});
		}
	});
};

window.initCategoryProductsImgs = function() {
	// Lightbox Plugin Initialization
	lightbox.option({
		resizeDuration: 200,
		wrapAround: true,
	});
};
