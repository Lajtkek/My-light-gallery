<template>
	<div class="super-flex">
		<div v-for="file in files" :key="file.idFile" class="file">
			<div class="file-prev">
				<FilePreview :file="file" />
				<div class="btn-wrapper">
					<v-btn :loading="file.loading" @click="acceptFile(file)">ACCEPT</v-btn>
					<v-btn :loading="file.loading" @click="rejectFile(file)">REJECT</v-btn>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
// import Vue from "vue";
// import store from '../store/store.js'

import FilePreview from "../components/FilePreview.vue";
export default {
	name: "TempFilesGrid",
	components: {
		FilePreview,
	},
	props: {},
	data: function () {
		return {
			files: [],
		};
	},
	mounted(){
		this.realod();
	},
	watch: {},
	methods: {
		async realod(){
			let request = await this.get("files/getFiles", {
				offset: 0,
				onlyTemporary: 1
			});
			if(request.data){
				this.files = request.data.files.map(x => ({
					...x,
					loading: false,
				}));
			}else
			this.showErrorTooltip("Nepodařilo se načíst soubory");
		},
		async acceptFile(file){
			file.loading = true;

			
		},
		async rejectFile(file){
			file.loading = true;
			let request = await this.post("files/rejectFile", {
				idFile: file.idFile
			});

			if(!request.error){
				this.files = this.files.filter(x => x.idFile != file.idFile);
			}
			file.loading = false;
		}
	},
};
</script>

<style lang="less" scoped>
.super-flex{
	display: flex;
	justify-content: center;
	align-items: center;
}
.btn-wrapper{
	padding: 20px;
	display: flex;
	justify-content: center;
	align-items: center;
	
	button:first-child{
		margin-right: 10px;
	}
}
</style>
