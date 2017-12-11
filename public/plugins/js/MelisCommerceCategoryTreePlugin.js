var catTreeConfig = (function(){

    var categoryTree = "";
    var root_node_id = "";
    var selectorId = "";
    var allowClickableNode = true;

    /**
     * we need to detect the click on li manually because
     * if the node is disable the select_node and
     * deselect_node event are not working
     **/
    $('body').on('click', '#catTreeFilterPluginConfig ul li', function (e) {
        if(allowClickableNode) {
            var id = $(this).find('a').attr('id');
            var target_id = $(e.target).attr('id');
            if (id === target_id) {
                var node_id = '#' + $(this).attr('id');
                if (root_node_id != node_id) {
                    processNodeDisableState('enable', node_id, categoryTree, function () {
                        //process the previous selected node
                        categoryTree.jstree("deselect_node", root_node_id);
                        checkNodeIfUnderParent(root_node_id, node_id);

                        //enable our new root node
                        categoryTree.jstree("select_node", node_id);
                        categoryTree.jstree("open_node", node_id);
                        categoryTree.jstree("check_node", node_id);
                        processNodeDisableState('enable', node_id, categoryTree);

                        //assign new root node
                        root_node_id = node_id;
                    });
                } else {
                    //this is the process where we deselect the selected(highlighted) category
                    var node = categoryTree.jstree().get_node(node_id);
                    if (node.state.selected) {
                        categoryTree.jstree("deselect_node", node_id);
                        processNodeDisableState('disable', node_id, categoryTree);
                        $(selectorId + "_form input[name='m_box_filter_root_category_id']").val('');
                    } else {
                        processNodeDisableState('enable', node_id, categoryTree, function () {
                            categoryTree.jstree("select_node", node_id);
                            categoryTree.jstree("check_node", node_id);
                        });
                    }
                }
            }
        }
    });

    /**
     * Function to set category tree config
     *
     * @param treeInstance
     * @param selected_category_id - array of selected category (checked category in the tree)
     * @param selected_root_id - selected root category (highlighted category in the tree)
     * @param clickableNode - set children in the tree if clickable
     *                      (although all the tree node are clickable by default using select_node and deselect_node event
     *                      of the jsTree, if the node are disable, this event are not fired, so we need to trigger the clicked
     *                      on node manually.)
     * @param disableNodeOnLoad - set if we disable all not selected(not highlighted) node in the tree
     */
    function setCategoryTreeConfig(treeInstance, selected_category_id, selected_root_id, clickableNode, disableNodeOnLoad){

        clickableNode = (clickableNode == undefined) ? true : clickableNode;
        disableNodeOnLoad = (disableNodeOnLoad == undefined) ? true : disableNodeOnLoad;

        allowClickableNode = clickableNode;

        categoryTree = treeInstance;
        root_node_id = "#"+selected_root_id+"_categoryId";
        selectorId = categoryTree['selector'];

        var pre_id_text = "_categoryId";
        if(!jQuery.isEmptyObject(selected_category_id)) {
            //open node
            $.each(selected_category_id, function (i, id) {
                var node_id = "#" + id + pre_id_text;
                categoryTree.jstree("open_node", node_id);
                categoryTree.jstree("check_node", node_id);
            });
        }
        //highlight the root node
        if(selected_root_id != 0){
            var root_node = "#" + selected_root_id + pre_id_text;
            categoryTree.jstree("select_node", root_node);
        }

        if(disableNodeOnLoad) {
            //process the disable and enable of the node
            getAllNode("disable", categoryTree);
        }
    }

    /**
     * Function to process the tree state
     * on tree load
     * @param type
     * @param categoryTree
     */
    function getAllNode(type, categoryTree){
        var nodes = categoryTree.jstree('get_json');
        nodes.forEach( function(node_id) {
            processNodeDisableState(type, node_id, categoryTree);
        });
    }

    /**
     * Function to change the disable state of the tree
     *
     * @param type
     * @param node_id
     * @param categoryTree
     * @param fn
     */
    function processNodeDisableState(type, node_id, categoryTree, fn) {
        fn = (fn === undefined) ? null : fn;

        var node = categoryTree.jstree().get_node( node_id );
        if(node) {
            //node is not selected
            if (!node.state.selected) {
                if (type == "enable") {
                    categoryTree.jstree('enable_node', node);
                } else {
                    categoryTree.jstree('disable_node', node);
                    categoryTree.jstree('uncheck_node', node);
                }
                //enable/disable the children also
                node.children.forEach(function (child_id) {
                    processNodeDisableState(type, child_id, categoryTree);
                });
            }
        }
        if(fn !== null)
            fn();
    }

    /**
     * Function to check if newly selected root node is
     * under the previous root node, if it under the
     * previous root node, don't close the tree
     * It will also check if whether select the parent of
     * all node, so that we can enable all its children
     * @param currentNode
     * @param selectedNode
     */
    function checkNodeIfUnderParent(currentNode, selectedNode){
        var cleanStr = selectedNode.replace('#', '');
        var node = categoryTree.jstree().get_node( currentNode );
        //check if childNode is under the parentNode
        if(jQuery.inArray(cleanStr, node.children) === -1 && jQuery.inArray(cleanStr, node.children_d) === -1){
            categoryTree.jstree("close_node", currentNode);
        }
        /*
            check if we select the parent of all node
            so that we can enable all children
        */
        var nodeParent = categoryTree.jstree().get_node( selectedNode );
        var cleanParentText = nodeParent.parent.replace('#', '');
        if(cleanParentText == ""){
            processNodeDisableState('enable', currentNode, categoryTree);
        }else{
            processNodeDisableState('disable', currentNode, categoryTree);
        }

        //check if we select the parent of our previous node
        if(jQuery.inArray(cleanStr, node.parents) !== -1){
            processNodeDisableState('enable', currentNode, categoryTree);
        }
    }

    return{
        setCategoryTreeConfig	:	setCategoryTreeConfig
    };

})();