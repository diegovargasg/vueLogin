<template>
  <b-container>
    <h2 class="mb-5">Files</h2>
    <b-table
      striped
      hover
      :total-rows="pagination.rows"
      :per-page="pagination.perPage"
      :items="files"
      id="files"
      class="mb-5"
    ></b-table>
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
      file: {
        id: "",
        name: "",
        type: "",
        size: ""
      },
      file_id: "",
      pagination: {},
      edit: false
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
    }
  }
};
</script>