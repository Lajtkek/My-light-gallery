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
        File options&nbsp;
        <v-btn color="green" @click="upload" right class="pull-right">
          Upload
        </v-btn>
      </div>
      <div class="edit-block">
        <div class="arrow" @click="move(-1)">
          <v-icon>mdi-arrow-left</v-icon>
        </div>
        <v-card class="edit-image-wrapper">
          <v-tabs v-model="editData.tab" background-color="primary" dark>
            <v-tab v-for="file in editData.files" :key="file.name">
              {{ trimString(file.name, 20) }}
            </v-tab>
          </v-tabs>
          <v-tabs-items v-model="editData.tab" class="edit-wrapper">
            <v-tab-item v-for="file in editData.files" :key="file.name">
              <v-card flat>
                <div class="edit-image-card">
                  <img :src="file.base64" class="edit-image" />
                </div>
                <div class="edit-image-info-card">
                  <v-form>
                    <v-container>
                      <v-row>
                        <v-col cols="12" sm="6" md="6">
                          <v-text-field
                            label="Name"
                            v-model="file.newName"
                          ></v-text-field>
                        </v-col>
                        <v-col cols="12" sm="6" md="6">
                          <v-autocomplete
                            v-if="editData.hackBool"
                            clearable
                            v-model="editData.tagToAdd"
                            :items="selectableTags(file)"
                            @change="addTag(file)"
                          ></v-autocomplete>
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
        <div class="arrow" @click="move(1)">
          <v-icon>mdi-arrow-right</v-icon>
        </div>
      </div>
    </div>
    <div v-if="action == 'upload-progress'">
      <v-progress-linear :value="30"></v-progress-linear>
    </div>
  </div>
</template>

<script src="../assets/js/upload.js" lang="ts"></script>
<style  src="../assets/styles/upload.less" lang="less" scoped>