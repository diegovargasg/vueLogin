<template>
  <b-container>
    <h3 class="mb-2 mt-5">Add a new file</h3>
    <b-form-group>
      <b-form-file
        id="file-default"
        accept=".jpg, .png, .gif, txt"
        placeholder="Choose a file or drop it here..."
        drop-placeholder="Drop file here..."
      ></b-form-file>
    </b-form-group>

    <h3 class="mb-2 mt-5">Files</h3>
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
          <b-button v-b-modal.edit-modal variant="primary" size="sm">Edit</b-button>
          <b-button @click="deleteFile(selectedFile)" variant="danger" size="sm">Delete</b-button>
        </template>
      </template>
      <template v-slot:table-caption>Select a file for more options</template>
    </b-table>

    <b-modal
      id="edit-modal"
      ref="modal"
      title="Rename"
      @show="resetModal"
      @hidden="resetModal"
      @ok="handleOk"
    >
      <form ref="form" @submit.stop.prevent="handleSubmit">
        <b-form-group
          :state="editNameState"
          label-for="name-input"
          invalid-feedback="Name is required"
        >
          <b-form-input id="name-input" v-model="selectedFile.name" :state="editNameState" required></b-form-input>
        </b-form-group>
      </form>
    </b-modal>

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
      fields: ["name", "size", "type", { key: "action", class: "text-center" }],
      file: {
        id: "",
        name: "",
        type: "",
        size: ""
      },
      file_id: "",
      pagination: {},
      selectedFile: {},
      editNameState: null
    };
  },
  created() {
    this.fetchFiles();
  },
  methods: {
    async fetchFiles(pageNum = 1) {
      try {
        const pageUrl = `files?page=${pageNum}`;
        let response = await axios.get(pageUrl);
        this.files = response.data.data;
        this.makePagination(response.data.meta, response.data.links);
      } catch (error) {
        alert(`Files could not be retrieved: ${error}`);
      }
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
      this.selectedFile = { ...item[0] };
    },
    async deleteFile(file) {
      const fileId = file.id;
      try {
        await axios.delete(`file/${fileId}`);
        this.fetchFiles(1);
      } catch (error) {
        alert(`the file could not be deleted: ${error}`);
      }
    },
    async editFile() {
      try {
        const fileId = this.selectedFile.id;
        const fileName = this.selectedFile.name;

        let response = await axios.put(`file/${fileId}`, {
          file_id: fileId,
          name: fileName
        });

        return response;
      } catch (error) {
        alert(`The file could not be updated: ${error}`);
        return false;
      }
    },
    checkFormValidity() {
      const valid = this.$refs.form.checkValidity();
      this.editNameState = valid;
      return valid;
    },
    resetModal() {
      this.editNameState = null;
    },
    handleOk(bvModalEvt) {
      // Prevent modal from closing
      bvModalEvt.preventDefault();
      // Trigger submit handler
      this.handleSubmit();
    },
    async handleSubmit() {
      // Exit when the form isn't valid
      if (!this.checkFormValidity()) {
        return;
      }

      // sends the put request to API
      const response = await this.editFile();
      if (response) {
        this.fetchFiles(1);
      }

      // Hide the modal manually
      this.$nextTick(() => {
        this.$bvModal.hide("edit-modal");
      });
    }
  }
};
</script>