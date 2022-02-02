<template>
  <div>
    <Navbar />
    <div v-if="action == 'upload'">
      <div class="upload-text">Upload</div>
      <div class="upload-input-wrapper">
        <v-file-input
          v-model="files"
          counter
          multiple
          show-size
          accept="image/*,video/*"
          truncate-length="20"
        ></v-file-input>
        <v-card-actions class="justify-center">
          <v-btn color="blue" @click="edit" :disabled="files.length == 0">
            Review and upload
          </v-btn>
        </v-card-actions>
      </div>
    </div>
    <div v-if="action == 'edit'">
      <div class="edit-text">
        <span class="md">Files left {{ editData.files.length }}</span>
        <span class="sm">Uploading {{ uploadData.files.length }} files</span>
      </div>
      <div class="edit-block" v-if="editData.files.length > 0">
        <v-card class="edit-image-wrapper">
          <v-tabs-items v-model="editData.tab" class="edit-wrapper">
            <v-tab-item v-for="file in editData.files" :key="file.name">
              <v-card flat>
                <div class="edit-image-card">
                  <FilePreview :file="file" class="edit-image"></FilePreview>
                </div>
                <div class="edit-image-info-card">
                  <v-form>
                    <v-container>
                      <v-row>
                        <v-col cols="12" sm="12" md="6">
                          <v-text-field
                            label="Name"
                            v-model="file.newName"
                          ></v-text-field>
                        </v-col>
                        <v-col cols="9" sm="9" md="4">
                          <v-autocomplete
                            v-if="editData.hackBool"
                            clearable
                            v-model="editData.tagToAdd"
                            :items="selectableTags(file)"
                            @change="addTag(file)"
                          ></v-autocomplete>
                        </v-col>
                        <v-col cols="3" sm="3" md="2" class="super-flex">
                          <v-btn @click="createTag" class="pull-right">Create tag</v-btn>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col cols="12" sm="6" md="6">
                          <v-textarea
                            v-model="file.description"
                            placeholder="Description"
                            background-color="grey lighten-2"
                            solo
                          ></v-textarea>
                        </v-col>
                        <v-col cols="12" sm="6" md="6">
                          <v-chip
                            v-for="tag in file.tags"
                            :key="tag.idTag"
                            close
                            close-icon="mdi-delete"
                            class="tag"
                            :color="tag.color"
                            @click:close="removeTag(file, tag)"
                            >{{ tag.name }}</v-chip
                          >
                        </v-col>
                      </v-row>
                    </v-container>
                  </v-form>
                </div>
              </v-card>
            </v-tab-item>
          </v-tabs-items>
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
      <div class="super-flex" v-else>
        Plase wait for all items to upload.
      </div>
    </div>
    <div class="upload-progress-container" v-if="action == 'upload-progress'">
      <div v-if="uploadData.uploadedFiles<editData.files.length">
        Upload progress
        <v-progress-linear :value="uploadData.uploadedFiles/editData.files.length*100"></v-progress-linear>
        <div class="text-progress">
          <!-- Todo: put in some methods? -->
          ({{(editData.files[this.uploadData.uploadedFiles]).name}}) Uploaded {{uploadData.uploadedFiles}} out of {{editData.files.length}} files.
        </div>
      </div>
      <div v-else>
        <h1 class="center-text">Upload complete</h1>
        <br>
        <!-- Lazy, but it wont cause any bugs and it is cost efficient -->
        <v-btn color="green" @click="reload" right class="upload-more-btn">
          Upload more
        </v-btn>
      </div>
    </div>
    <TagEditor></TagEditor>
  </div>
</template>

<script src="../assets/js/upload.js" lang="ts"></script>
<style src="../assets/styles/main.less" lang="less" scoped></style>
<style src="../assets/styles/upload.less" lang="less" scoped></style>