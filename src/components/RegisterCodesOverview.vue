<template>
  <div>
    <v-data-table :headers="headers" :items="codes" :items-per-page="50">
      <template v-slot:top>
        <v-toolbar flat>
          <v-toolbar-title>RegisterCodes</v-toolbar-title>
          <v-divider class="mx-4" inset vertical></v-divider>
          <v-spacer></v-spacer>
          <v-btn @click="showCodeDialog()">Create new code</v-btn>
        </v-toolbar>
      </template>
    </v-data-table>
    <v-dialog v-model="showDialog" max-width="500px">
      <v-card>
        <v-container>
          <v-row>
            <v-col cols="12" sm="12" md="12">
              <v-text-field v-model="code" label="Code"></v-text-field>
              <v-btn @click="createCode()">Create new code</v-btn>
            </v-col>
          </v-row>
        </v-container>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
export default {
  name: "RegisterCodesOverview",
  data: function () {
    return {
      showDialog: false,
      code: "",
      headers: [
        { text: "ID", value: "idRegisterCode" },
        { text: "Code", value: "code" },
        { text: "Used", value: "used" },
      ],
      codes: [],
    };
  },
  async mounted() {
    this.reloadCodes();
  },
  methods: {
    async reloadCodes() {
        let result = await this.get("registerCodes/getRegisterCodes", {});
        if (!result.error) {
            this.codes = result.data;
    }
    },
    showCodeDialog() {
      this.showDialog = true;
      this.code = "";
    },
    async createCode() {
        if(this.codes.some(x => x.code?.toString().toLowerCase() == this.code?.toString().toLowerCase())){
          this.showErrorTooltip("Tento kód už existuje");
          return;
        }
        this.showDialog = false;
        const result = await this.post("registerCodes/registerCodeI", {
          code: this.code
        });

        if(result.error)
          this.showErrorTooltip("Nepodařilo se vytvořit kód");
        else{
          this.showSuccessTooltip("Kód byl vytvořen");
          this.reloadCodes();
        }
    }
  },
};
</script>

<style lang="less" scoped>
</style>
