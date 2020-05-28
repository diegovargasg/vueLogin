<template>
  <b-container>
    <h3 class="mb-2 mt-5">Add a new file</h3>
    <b-form-group>
      <b-form-file
        id="upload-file"
        class="mb-2"
        accept=".jpg, .png, .gif, txt"
        placeholder="Choose a file or drop it here..."
        drop-placeholder="Drop file here..."
        :state="fileToUploadState"
        v-model="fileToUpload"
        @change="selectFile"
      ></b-form-file>
      <b-form-invalid-feedback
        id="upload-file"
        class="mb-2"
      >The file must be maximum 8mb and format PDF, JPG or PNG</b-form-invalid-feedback>
      <b-button @click="uploadFile()" variant="secondary" size="sm" :disabled="isUploading">
        <template v-if="isUploading">
          <b-spinner small></b-spinner>Loading...
        </template>
        <template v-else>Upload</template>
      </b-button>
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
          <b-button v-b-modal.edit-modal variant="primary" size="sm" class="mr-2">Edit</b-button>
          <b-button
            @click="downloadFile(selectedFile)"
            variant="success"
            size="sm"
            class="mr-2"
          >Download</b-button>
          <b-button @click="deleteFile(selectedFile)" variant="danger" size="sm">Delete</b-button>
        </template>
      </template>
      <template v-slot:table-caption>Select a file for more options</template>
    </b-table>
    <b-modal
      id="edit-modal"
      ref="modal"
      title="Rename"
      @show="showModal"
      @hidden="resetModal"
      @ok="handleOk"
    >
      <form ref="form" @submit.stop.prevent="handleSubmit">
        <b-form-group
          :state="editNameState"
          label-for="name-input"
          invalid-feedback="Name is required"
        >
          <b-form-input
            id="name-input"
            v-model="editSelectedFileName"
            :state="editNameState"
            required
          ></b-form-input>
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
import { mapGetters } from "vuex";

export default {
  computed: {
    ...mapGetters({
      user: "auth/user"
    })
  },
  data() {
    return {
      files: [],
      fields: ["name", "size", "type", { key: "action", class: "text-center" }],
      file_id: "",
      isUploading: false,
      fileToUpload: null,
      fileToUploadState: null,
      pagination: {},
      selectedFile: {},
      editNameState: null,
      editSelectedFileName: ""
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
        const response = await axios.delete(`file/${fileId}`);
        if (response) {
          this.fetchFiles(1);
        }
      } catch (error) {
        alert(`the file could not be deleted: ${error}`);
      }
    },
    async editFile() {
      try {
        const fileId = this.selectedFile.id;
        const fileNewName = this.editSelectedFileName;
        const filePrevName = this.selectedFile.name;

        if (fileNewName === filePrevName) {
          return false;
        }

        let response = await axios.put(`file/${fileId}`, {
          file_id: fileId,
          name: fileNewName
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
    showModal() {
      this.editNameState = null;
      this.editSelectedFileName = this.selectedFile.name;
    },
    resetModal() {
      this.editNameState = null;
      this.editSelectedFileName = "";
    },
    restartPagination() {
      this.fetchFiles(1);
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
        this.restartPagination();
      }

      // Hide the modal manually
      this.$nextTick(() => {
        this.$bvModal.hide("edit-modal");
      });
    },
    selectFile() {
      this.fileToUpload = event.target.files[0];
      this.fileToUploadState = null;

      // is the file is more than 8mb
      if (this.fileToUpload.size > 8000000) {
        this.fileToUploadState = false;
        this.fileToUpload = null;
      }
    },
    async uploadFile() {
      this.isUploading = true;
      if (this.fileToUpload === null) {
        this.fileToUploadState = false;
        this.isUploading = false;
        return;
      }
      const data = new FormData();
      data.append("name", this.fileToUpload.name);
      data.append("type", this.fileToUpload.type);
      data.append("size", this.fileToUpload.size);
      data.append("archive", this.fileToUpload);
      data.append("user_id", this.user.id);

      try {
        const response = await axios.post("/file", data);

        if (response) {
          this.fileToUpload = null;
          this.restartPagination();
          this.isUploading = false;
        }
      } catch (error) {
        this.isUploading = false;
        alert(error);
      }
    },
    downloadFile() {
      const fileId = this.selectedFile.id;
      window.open(`${axios.defaults.baseURL}/file/${fileId}`, "_blank");
    }
  }
};
</script>