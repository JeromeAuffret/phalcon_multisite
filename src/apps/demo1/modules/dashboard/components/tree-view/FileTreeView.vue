<template>
  <div>
    <ul id="demo">
      <tree-item class="item"></tree-item>
    </ul>
  </div>
</template>

<script>
// https://fr.vuejs.org/v2/examples/tree-view.html
// import Vue from "vue";
import TreeItem from "@/apps/demo1/modules/dashboard/components/tree-view/TreeItem";

export default {
  name: "FileTreeView",
  components: {
    'tree-item': TreeItem
  },
  data() {
    return {
      treeData: null
    }
  },
  methods: {
    // makeFolder: function(item) {
    //   Vue.set(item, "children", []);
    //   this.addItem(item);
    // },
    // addItem: function(item) {
    //   item.children.push({
    //     name: "new stuff"
    //   });
    // },
  }
}

</script>

<style scoped>

</style>