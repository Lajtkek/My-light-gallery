import Vue from "vue";
import { randomHash } from "../../assets/js/common"
import FilePreview from "../../components/FilePreview.vue";
import Navbar from "../../components/Navbar.vue";
import TagEditor from "../../components/TagEditor.vue";
import TagSelect from "../../components/TagSelect.vue";

export default Vue.extend({
  name: "Upload",
  components: {
    Navbar, 
    TagEditor,
    FilePreview,
    TagSelect
  },
  computed: {
    fileState() {
      return `${this.editData.files.length}|${this.uploadData.files.length}`;
    },
  },
  watch: {
    '$store.state.fileTags': function(){
      this.editData.tags = this.$store.state.fileTags
    },
    'fileState': function(){
      if(this.editData.files.length == 0 && this.uploadData.files.length == 0)
        this.reload();
    }
  },
  data: function () {
    return {
      action: "upload",
      files: [],
      editData: {
        tagsToAdd: [],
        tab: 0,
        files: [],
        tags: this.$store.state.fileTags,
        renameFiles: false,
      },
      uploadData: {
        files: [],
        filesUploaded: 0
      }
    };
  },
  methods: {
    async uploadFirst(){
      let fileData = this.editData.files.shift();
      this.$nextTick(() => {
        this.editData.tab = 0;
      })
      this.uploadData.files.push(fileData);

      let result = await Vue.prototype.post("files/uploadFile", {
          name: fileData.name,
          base64: fileData.base64,
          extension: fileData.extension,
          mimeType: fileData.file.type,
          filename: fileData.newName,
          tags: this.$store.state.fileTags.filter(x => fileData.tags.includes(x.idTag)),
          description: fileData.description
      });

      this.uploadData.files = this.uploadData.files.filter(x => x != fileData);

      if(result.error){
        this.editData.files.push(fileData);
      }

      this.uploadData.filesUploaded++;
    },
    async deleteFirst(){
      this.editData.files.shift();
      this.$nextTick(() => {
        this.editData.tab = 0;
      })
    },
    reload(){
      window.location.reload();
    },
    createTag(){
      this.$store.commit("setEditedTag", {
        name: "",
        code: "",
        color: `#${Math.floor(Math.random()*16777215).toString(16)}`,
        isPublic: true,
        tags: []
      });
    },
    async edit() {
      for (const file of this.files) {
        let splitName = file.name.split(".");
        let extension = splitName[splitName.length - 1];
        
        if (!file.type.includes("video") && !file.type.includes("image")) {
          console.warn(`${file.name} is not image or video. Support for other filetypes will be soon™`);
          continue;
        }

        let base64 = await this.getBase64(file);
        this.editData.files.push({
          name: file.name,
          extension,
          mimeType: file.type,
          newName: this.editData.renameFiles ? randomHash(32) : file.name.replace(`.${extension}`, ""),
          description: "",
          tags: this.editData.tagsToAdd ?? [],
          file,
          base64,
        });
      }
      this.action = "edit";
      //Todo find if better fix
      this.$nextTick(() => {
        this.editData.files = [...this.editData.files];
      })
    },
    //TODO: try to do it in css? propably not it will fuck up the compoennt
    trimString(string, length) {
      return string.length > length
        ? string.substring(0, length) + "..."
        : string;
    },
    getBase64(file) {
      return new Promise((resolve, reject) => {
        var reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = function () {
          resolve(reader.result);
        };
        reader.onerror = function (error) {
          reject("Error: " + error);
        };
      });
    },
  },
  async mounted() {
    this.$store.commit('getFileTags');
    this.$store.onTagIU = (tag) => {
      this.addTag(this.editData.files[0], tag);
    }
  },
});