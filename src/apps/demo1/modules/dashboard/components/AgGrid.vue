<template>
  <div style="height: 100%">
    <ag-grid-vue
        style="width: 100%; height: 100%;"
        class="ag-theme-balham"
        id="myGrid"
        :gridOptions="gridOptions"
        :columnDefs="columnDefs"
        :defaultColDef="defaultColDef"
        :localeText="localeText"
        :components="components"
        :rowData="rowData"
        :rowBuffer="rowBuffer"
        :rowSelection="rowSelection"
        :paginationPageSize="paginationPageSize"
        :cacheOverflowSize="cacheOverflowSize"
        :maxConcurrentDatasourceRequests="maxConcurrentDatasourceRequests"
        :infiniteInitialRowCount="infiniteInitialRowCount"
        :cacheBlockSize="cacheBlockSize"
        :maxBlocksInCache="maxBlocksInCache"
        :animateRows="true"
        :sideBar="true"
        @grid-ready="onGridReady"
        @cell-clicked="onCellClicked"
        @sort-changed="onSortChanged"
        @filter-modified="onFilterChanged">
    </ag-grid-vue>
  </div>
</template>

<script>
import 'ag-grid-community/dist/styles/ag-grid.css';
import 'ag-grid-community/dist/styles/ag-theme-balham.css';

import { AgGridVue } from "ag-grid-vue";
import { AG_GRID_LOCALE_FR } from './locale.fr';
import axios from "axios";
import 'ag-grid-enterprise';

export default {
  name: 'AgGrid',
  data() {
    return {
      gridOptions: null,
      gridApi: null,
      columnApi: null,
      columnDefs: null,
      defaultColDef: null,
      components: null,
      rowBuffer: null,
      rowSelection: null,
      localeText: null,
      rowData: null,
      rowModelType: null,
      paginationPageSize: null,
      cacheOverflowSize: null,
      maxConcurrentDatasourceRequests: null,
      infiniteInitialRowCount: null,
      cacheBlockSize: null,
      maxBlocksInCache: null,
    };
  },
  components: {
    'ag-grid-vue': AgGridVue,
  },
  beforeMount() {
    this.localeText = AG_GRID_LOCALE_FR;
    this.multiSortKey = 'shift';
    this.rowModelType = 'serverSide';
    this.gridOptions = {};

    this.rowBuffer = 0;
    this.rowSelection = 'multiple';
    this.rowModelType = 'infinite';
    this.paginationPageSize = 100;
    this.cacheOverflowSize = 2;
    this.maxConcurrentDatasourceRequests = 1;
    this.infiniteInitialRowCount = 1000;
    this.maxBlocksInCache = 10;

    this.columnDefs = [
      { field: 'athlete', minWidth: 160, resizable: true },
      { field: 'age', resizable: true, filter: 'agNumberColumnFilter', enablePivot: true},
      { field: 'country', minWidth: 140, resizable: true, enablePivot: true },
      { field: 'year', resizable: true, filter: 'agMultiColumnFilter' },
      { field: 'date', minWidth: 140, resizable: true },
      { field: 'sport', minWidth: 160, resizable: true, enablePivot: true },
      { field: 'gold', resizable: true, enableValue: true },
      { field: 'silver', resizable: true, enableValue: true },
      { field: 'bronze', resizable: true, enableValue: true },
      { field: 'total', resizable: true, enableValue: true },
    ];

    this.defaultColDef = {
      flex: 1,
      minWidth: 100,
      editable: true,
      filter: true,
      filterParams: {
        buttons: ['apply', 'reset'],
        // debounceMs: 200,
        closeOnApply: true,
      },
      sortable: true
    };

    this.components = {
      loadingRenderer: (params) => {
        if (params.value !== undefined) {
          return params.value;
        } else {
          return '<img src="https://raw.githubusercontent.com/ag-grid/ag-grid/master/grid-packages/ag-grid-docs/src/images/loading.gif">';
        }
      },
    };


  },
  mounted() {
    this.gridApi = this.gridOptions.api;
    this.gridColumnApi = this.gridOptions.columnApi;
  },
  methods: {
    onGridReady(params) {

      const updateData = (data) => {
        this.rowData = data;
        this.params = params;
      };

      axios
          .get('/phalcon_multisite/dashboard/index/tableData')
          .then(function (response) {
            updateData(response.data, params);
          })

      // const httpRequest = new XMLHttpRequest();
      // httpRequest.open(
      //     'GET',
      //     '/phalcon_multisite/dashboard/index/tableData'
      // );
      // httpRequest.send();
      // httpRequest.onreadystatechange = () => {
      //   if (httpRequest.readyState === 4 && httpRequest.status === 200) {
      //     updateData(JSON.parse(httpRequest.responseText));
      //   }
      // };
    },

    onCellClicked(event) {
      console.log(event.node.data.age);
    },

    onFilterChanged(event) {

      console.log(event.api.getFilterModel())

      var countryFilterModel = this.gridApi.getFilterModel()['age'];
      var selected = countryFilterModel && countryFilterModel.values;

      console.log(countryFilterModel);
      console.log(selected);
    },

    onSortChanged(event) {
      console.log(event.columnApi.getColumnState())
    },

  }
};
</script>

<style scoped>

</style>