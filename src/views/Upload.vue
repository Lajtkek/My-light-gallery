<template>
    <div>
        <Navbar />
        <div v-if="action == 'upload'">
            <div class="upload-text">Upload</div>
            <div class="upload-input-wrapper">
                <v-file-input v-model="files" counter multiple show-size accept="image/*,video/*" truncate-length="20"></v-file-input>
                <v-container>
                    <v-row>
                        <v-col cols="6" sm="6" md="6">
                            <v-checkbox v-model="editData.renameFiles" label="Rename all files with random hash"></v-checkbox>
                        </v-col>
                        <v-col>
                            <TagSelect :preselectTags="true" v-model="editData.tagsToAdd"></TagSelect>
                        </v-col>
                    </v-row>
                </v-container>
                <v-card-actions class="justify-center">
                    <v-btn color="blue" @click="edit" :disabled="files.length == 0"> Confirm and edit </v-btn>
                </v-card-actions>
            </div>
        </div>
        <div v-if="action == 'edit'">
            <div v-if="!editingImage">
                <div class="edit-text">
                    <span class="md">Files left {{ filesToDo }}</span>
                    <span class="sm">Uploading {{ uploadData.files.length }} files</span>
                </div>
                <div class="edit-block" v-if="currentFileData">
                    <v-card class="edit-image-wrapper">
                        <div class="edit-image-card">
                            <FilePreview :file="currentFileData" class="edit-image"></FilePreview>
                        </div>
                        <div class="edit-image-info-card">
                            <v-form>
                                <v-container>
                                    <v-row>
                                        <v-col cols="12" sm="12" md="6">
                                            <v-text-field label="Name" v-model="currentFileData.newName"></v-text-field>
                                        </v-col>
                                        <v-col cols="9" sm="9" md="4">
                                            <TagSelect v-model="currentFileData.tags" :preselectTags="true" :showChips="false" :preselectedTags="currentFileData.tags" ref="tagSelect"></TagSelect>
                                        </v-col>
                                        <v-col cols="3" sm="3" md="2" class="super-flex">
                                            <v-btn @click="createTag" class="pull-right">Create tag</v-btn>
                                        </v-col>
                                    </v-row>
                                    <v-row>
                                        <v-col cols="12" sm="6" md="6">
                                            <v-textarea v-model="currentFileData.description" placeholder="Description" background-color="grey lighten-2" solo></v-textarea>
                                        </v-col>
                                        <v-col cols="12" sm="6" md="6" v-if="$refs.tagSelect">
                                            <v-chip
                                                v-for="tag in $store.state.fileTags.filter((x) => currentFileData.tags.includes(x.idTag))"
                                                :key="tag.idTag"
                                                close
                                                close-icon="mdi-delete"
                                                class="tag"
                                                :color="tag.color"
                                                @click:close="$refs.tagSelect[index].remove(tag)"
                                                >{{ tag.name }}</v-chip
                                            >
                                        </v-col>
                                    </v-row>
									<v-row v-if="currentFileData.mimeType.includes('image')">
										<v-btn @click="editingImage = true">Edit obrázku</v-btn>
									</v-row>
                                </v-container>
                            </v-form>
                        </div>
                    </v-card>
                    <div class="action-buttons-wrapper">
                        <div class="action-btn" @click="uploadFirst()">
                            <v-icon>mdi-arrow-right</v-icon>
                        </div>
                        <div class="action-btn error" @click="deleteFirst()">
                            <v-icon>mdi-delete</v-icon>
                        </div>
                    </div>
                </div>
				<div v-else-if="editData.files.length > 0">
					Soubor se připravuje
				</div>
            </div>
			<div class="file-edit" v-else>
				<tui-image-editor ref="editor" :include-ui="editorOptions.useDefaultUI" :options="editorOptions.options"></tui-image-editor>
				<div class="edit-action-buttons-wrapper">
					<v-btn @click="editingImage = false">Back</v-btn>
					<v-btn @click="applyImageEdit">Save</v-btn>
				</div>
			</div>
        </div>
        <div class="upload-progress-container" v-if="action == 'upload-progress'">
            <div v-if="uploadData.uploadedFiles < editData.files.length">
                Upload progress
                <v-progress-linear :value="(uploadData.uploadedFiles / editData.files.length) * 100"></v-progress-linear>
                <div class="text-progress">
                    <!-- Todo: put in some methods? -->
                    ({{ editData.files[this.uploadData.uploadedFiles].name }}) Uploaded {{ uploadData.uploadedFiles }} out of {{ editData.files.length }} files.
                </div>
            </div>
            <div v-else>
                <h1 class="center-text">Upload complete</h1>
                <br />
                <div class="file-wrapper">
                <FilePreview v-for="file in uploadedFiles" :key="file.idFile" :file="file" class="file"/>
                </div>
                <!-- Lazy, but it wont cause any bugs and it is cost efficient -->
                <v-btn color="green" @click="reload" right class="upload-more-btn"> Upload more </v-btn>
            </div>
        </div>
        <TagEditor></TagEditor>
    </div>
</template>

<script src="../assets/js/upload.js" lang="ts"></script>
<style src="../assets/styles/main.less" lang="less" scoped></style>
<style rel="stylesheet" src="../plugins/css/tui-image-editor.css" lang="css"></style>
<style src="../assets/styles/upload.less" lang="less" scoped></style>
