import Vue from 'vue';
import { randomHash, sleep } from '../../assets/js/common';
import FilePreview from '../../components/FilePreview.vue';
import Navbar from '../../components/Navbar.vue';
import TagEditor from '../../components/TagEditor.vue';
import TagSelect from '../../components/TagSelect.vue';
import { ImageEditor } from '@toast-ui/vue-image-editor';

export default Vue.extend({
    name: 'Upload',
    components: {
        Navbar,
        TagEditor,
        FilePreview,
        TagSelect,
        'tui-image-editor': ImageEditor,
    },
    computed: {
        fileState() {
            return `${this.editData.files.length}|${this.uploadData.files.length}`;
        },
        currentFile() {
            return this.files?.[0];
        },
        currentFileData() {
            return this.editData?.files?.[0];
        },
    },
    watch: {
        '$store.state.fileTags': function () {
            this.editData.tags = this.$store.state.fileTags;
        },
        fileState: function () {
            if (this.editData.files.length == 0 && this.uploadData.files.length == 0) this.reload();
        },
		'editingImage': async function(newVal){
			if(!newVal) return 
			await sleep(100)
			this.$refs.editor.invoke('loadImageFromFile', this.files[0]).then((result) => {
				this.$refs.editor.invoke('ui.resizeEditor', {imageSize: result});
			})
            
		}
    },
    data: function () {
        return {
            action: 'upload',
            files: [],
			editingImage: false,
            editData: {
                tagsToAdd: [],
                tab: 0,
                files: [],
                tags: this.$store.state.fileTags,
                renameFiles: false,
            },
            uploadData: {
                files: [],
                filesUploaded: 0,
            },
            editorOptions: {
                useDefaultUI: true,
                options: {
					includeUI: {
                        loadImage:{
                          
                            path:'data:image/jpeg;base64,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',
                            name:'Blank'
                        },
                    },
                    // for tui-image-editor component's "options" prop
                    cssMaxWidth: 700,
                    cssMaxHeight: 500,
                },
            },
        };
    },
    methods: {
        async uploadFirst() {
            let fileData = this.editData.files.shift();
            this.$nextTick(() => {
                this.editData.tab = 0;
            });
            this.uploadData.files.push(fileData);

            let result = await Vue.prototype.post('files/uploadFile', {
                name: fileData.name,
                base64: fileData.base64,
                extension: fileData.extension,
                mimeType: fileData.file.type,
                filename: fileData.newName,
                tags: this.$store.state.fileTags.filter((x) => fileData.tags.includes(x.idTag)),
                description: fileData.description,
            });

            this.uploadData.files = this.uploadData.files.filter((x) => x != fileData);

            if (result.error) {
                if (result.error == 'TOO_LARGE') this.showErrorTooltip('Soubor je moc velký');
                else if (result.error == 'UNSUPPORTED_EXTENSION') this.showErrorTooltip('Soubor s toto příponou je zakázáno nahrávat');
                else this.editData.files.push(fileData);
            }

            this.uploadData.filesUploaded++;
        },
        async deleteFirst() {
            this.editData.files.shift();
            this.$nextTick(() => {
                this.editData.tab = 0;
            });
        },
        reload() {
            window.location.reload();
        },
        createTag() {
            this.$store.commit('setEditedTag', {
                name: '',
                code: '',
                color: `#${Math.floor(Math.random() * 16777215).toString(16)}`,
                isPublic: true,
                tags: [],
            });
        },
        async edit() {
            for (const file of this.files) {
                let splitName = file.name.split('.');
                let extension = splitName[splitName.length - 1];

                if (!file.type.includes('video') && !file.type.includes('image')) {
                    console.warn(`${file.name} is not image or video. Support for other filetypes will be soon™`);
                    continue;
                }

                let base64 = await this.getBase64(file);
                this.editData.files.push({
                    name: file.name,
                    extension,
                    mimeType: file.type,
                    newName: this.editData.renameFiles ? randomHash(32) : file.name.replace(`.${extension}`, ''),
                    description: '',
                    tags: this.editData.tagsToAdd ?? [],
                    file,
                    base64,
                });
            }
            this.action = 'edit';
			
            //Todo find if better fix
            this.$nextTick(() => {
                this.editData.files = [...this.editData.files];
            });
        },
        //TODO: try to do it in css? propably not it will fuck up the compoennt
        trimString(string, length) {
            return string.length > length ? string.substring(0, length) + '...' : string;
        },
        getBase64(file) {
            return new Promise((resolve, reject) => {
                var reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = function () {
                    resolve(reader.result);
                };
                reader.onerror = function (error) {
                    reject('Error: ' + error);
                };
            });
        },
    },
    async mounted() {
        this.$store.commit('getFileTags', 1);
        this.$store.onTagIU = (tag) => {
            this.addTag(this.editData.files[0], tag);
        };
    },
});
