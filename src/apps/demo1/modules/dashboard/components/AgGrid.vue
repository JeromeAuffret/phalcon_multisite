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
      {
        headerName: 'NomFlux',
        field: 'NomFlux',
        enableValue: true,
        hide: true
      },
      {
        headerName: 'TypeFlux',
        field: 'TypeFlux'
      },
      {
        headerName: 'DateLot',
        field: 'DateLot',
        rowGroup: true,
        enablePivot: true
      },
      {
        headerName: 'ClefNumLot',
        field: 'ClefNumLot'
      },
      {
        headerName: 'Statut',
        field: 'Statut',
        enablePivot: true
      },
      {
        headerName: 'NbPlisIdx',
        field: 'NbPlisIdx',
        enableValue: true,
        type: 'numericColumn',
        valueFormatter: function(params) {
          return params.value ? parseInt(params.value) : 0;
        },
        comparator: function(valueA, valueB, nodeA, nodeB, isInverted) {
          return parseInt(valueA) - parseInt(valueB);
        }
      },
      {
        headerName: 'NbPlisCons',
        field: 'NbPlisCons',
        enableValue: true,
        valueFormatter: function(params) {
          return params.value ? parseInt(params.value) : 0;
        },
      },
      {
        headerName: 'NbPlisDest',
        field: 'NbPlisDest',
        enableValue: true,
        valueFormatter: function(params) {
          return params.value ? parseInt(params.value) : 0;
        },
      },
    ];

    this.defaultColDef = {
      flex: 1,
      minWidth: 100,
      editable: true,
      resizable: true,
      filter: 'agMultiColumnFilter',
      filterParams: {
        buttons: ['reset']
      },
      sortable: true
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
    },

    onCellClicked() {
      // console.log(event.node.data.age);
    },

    onFilterChanged() {
      //
      // var countryFilterModel = this.gridApi.getFilterModel()['age'];
      // var selected = countryFilterModel && countryFilterModel.values;
      //
      // console.log(countryFilterModel);
      // console.log(selected);
    },

    onSortChanged() {
      // console.log(event.columnApi.getColumnState())
    },

  }
};


</script>

<style scoped>

</style>