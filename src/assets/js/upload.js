import Vue from "vue";
import Navbar from "../../components/Navbar.vue";
import TagEditor from "../../components/TagEditor.vue";

export default Vue.extend({
  name: "Upload",
  components: {
    Navbar, TagEditor
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
        hackBool: true,
        tagToAdd: null,
        tab: 0,
        files: [],
        tags: this.$store.state.fileTags
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
          fileType: fileData.file.type,
          filename: fileData.newName,
          tags: fileData.tags,
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
        color: "#FF9393",
        isPublic: true,
      });
    },
    async edit() {
      for (const file of this.files) {
        let splitName = file.name.split(".");
        let extension = splitName[splitName.length - 1];

        if (extension != "gif" && extension != "jpg" && extension != "png") {
          console.warn(
            `${file.name} is not image. Support for other filetypes will be soon™`
          );
          continue;
        }

        let base64 = await this.getBase64(file);
        this.editData.files.push({
          name: file.name,
          extension,
          newName: file.name.replace(`.${extension}`, ""),
          description: "",
          tags: [],
          file,
          base64,
        });
      }
      this.$store.commit('getFileTags');
      this.action = "edit";
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
    addTag(file, tag = null){
      console.log(tag)
      //Projistotu kopíruju, asi nebude potřeba idk
      file.tags.push({...(tag ?? this.editData.tags.find(x => x.idTag == this.editData.tagToAdd))});
      this.editData.tagToAdd = null;
      // TODO: fix? mby not nessesary https://github.com/vuetifyjs/vuetify/issues/10765
      this.editData.hackBool = false;
      this.$nextTick(() => {
        this.editData.hackBool = true;
      })
    },
    removeTag(file,tag){
      file.tags = file.tags.filter(x => x.idTag != tag.idTag);
    },
    selectableTags(file){
      return this.editData.tags.filter(tag => !file.tags.some(x => x.idTag == tag.idTag)).map(x => ({ text: x.name,  value: x.idTag}))
    }
  },
  async mounted() {
    this.$store.onTagIU = (tag) => {
      this.addTag(this.editData.files[0], tag);
    }
  },
});