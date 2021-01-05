import Vue from 'vue';
import AgGrid from "@/apps/demo1/modules/dashboard/components/ag-grid/AgGrid";
import FileTreeView from "@/apps/demo1/modules/dashboard/components/tree-view/FileTreeView";

new Vue({
    el: '#dashboard_index',
    components: {
        'ag-grid': AgGrid,
        'file-tree-view': FileTreeView
    }
})
