import Vue from "vue";
import Navbar from "../../components/Navbar.vue";

export default Vue.extend({
  name: "Upload",
  components: {
    Navbar,
  },
  watch: {
    '$store.state.fileTags': function(){
      this.editData.tags = this.$store.state.fileTags
    }
  },
  data: function () {
    return {
      action: "upload",
      files: [],
      editData: {
        hackBool: true,
        tagToAdd: null,
        tab: null,
        files: [],
        tags: this.$store.state.fileTags
      },
      uploadData: {
        uploadedFiles: 0
      }
    };
  },
  methods: {
    async upload(){
      //foreach file
      this.uploadData.uploadedFiles = 0;
      this.action = "upload-progress";
      for(const fileData of this.editData.files){
        let result = await Vue.prototype.post("files/uploadFile", {
            base64: fileData.base64,
            extension: fileData.extension,
            fileType: fileData.file.type,
            filename: fileData.newName,
            tags: fileData.tags,
            description: fileData.description
        });
        if(result.success){
          console.log(result.data);
        }
        this.uploadData.uploadedFiles++;
      }
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
    addTag(file){
      //Projistotu kopíruju, asi nebude potřeba idk
      file.tags.push({...this.editData.tags.find(x => x.idTag == this.editData.tagToAdd)});
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
    },
    move(dir){
        this.editData.tab += dir;
        if(dir < 0)
            this.editData.tab = this.editData.tab < 0 ? this.editData.files.length-1 : this.editData.tab;
        else
            this.editData.tab %= this.files.length;
    }
  },
  async mounted() {
  },
});