import Vue from 'vue';
import AgGrid from "@/apps/demo1/modules/dashboard/components/AgGrid";

new Vue({
    el: '#dashboard_index',
    components: {
        'ag-grid': AgGrid
    },
})
