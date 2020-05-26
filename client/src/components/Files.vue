<template>
  <b-container>
    <h2 class="mb-5">Files</h2>
    <b-table
      :total-rows="pagination.rows"
      :per-page="pagination.perPage"
      :items="files"
      :fields="fields"
      select-mode="single"
      id="files"
      class="mb-5"
      selectable
      bordered
      hover
      @row-selected="onRowSelected"
    >
      <template v-slot:cell(action)="{ rowSelected }">
        <template v-if="rowSelected">
          <b-button @click="deleteFile(selected)" variant="danger">Delete</b-button>
        </template>
      </template>
    </b-table>
    <b-pagination
      v-model="pagination.currentPage"
      :total-rows="pagination.rows"
      :per-page="pagination.perPage"
      aria-controls="files"
      size="sm"
      align="center"
      @input="fetchFiles"
      :current-page="pagination.currentPage"
    ></b-pagination>
  </b-container>
</template>

<script>
import axios from "axios";
export default {
  data() {
    return {
      files: [],
      fields: ["name", "size", "type", "action"],
      file: {
        id: "",
        name: "",
        type: "",
        size: ""
      },
      file_id: "",
      pagination: {},
      edit: false,
      selected: {}
    };
  },
  created() {
    this.fetchFiles();
  },
  methods: {
    async fetchFiles(pageNum) {
      pageNum = pageNum ? pageNum : 1;
      let pageUrl = `files?page=${pageNum}`;
      let response = await axios.get(pageUrl);
      this.files = response.data.data;
      this.makePagination(response.data.meta, response.data.links);
    },
    makePagination(meta, links) {
      let pagination = {
        currentPage: meta.current_page,
        lastPage: meta.last_page,
        total: meta.total,
        perPage: meta.per_page,
        rows: meta.total,
        nextPage: links.next,
        prevPage: links.prev
      };
      this.pagination = pagination;
    },
    onRowSelected(item) {
      this.selected = { ...item[0] };
    },
    async deleteFile(file) {
      const fileId = file.id;
      let response = await axios.delete(`file/${fileId}`);
      this.fetchFiles(1);
      console.log(response);
    }
  }
};
</script>