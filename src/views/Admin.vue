<template>
	<div>
		<Navbar />
		<div>
			<v-card class="edit-image-wrapper">
				<v-tabs v-model="tab" background-color="primary" dark>
					<v-tab key="overview"> Overview </v-tab>
					<v-tab key="tags"> Tags </v-tab>
					<v-tab key="users"> Users </v-tab>
				</v-tabs>
				<v-tabs-items v-model="tab" class="edit-wrapper">
					<v-tab-item key="overview">
						<div class="tab-wrapper">
							<AdminOverview />
						</div>
					</v-tab-item>
					<v-tab-item key="tags">
						<div class="tab-wrapper">
							<TagOverview />
						</div>
					</v-tab-item>
					<v-tab-item key="users">
						<div class="tab-wrapper">
							<UserOverview />
						</div>
					</v-tab-item>
					<v-tab-item key="registerCodes">
						<div class="tab-wrapper">
							<RegisterCodesOverview />
						</div>
					</v-tab-item>
					<v-tab-item key="tempFiles">
						<div class="tab-wrapper">
							<TempFilesGrid />
						</div>
					</v-tab-item>
				</v-tabs-items>
			</v-card>
		</div>
	</div>
</template>

<script>
import Vue from "vue";
import Navbar from "../components/Navbar.vue";
import AdminOverview from "../components/AdminOverview.vue";
import TagOverview from "../components/TagOverview.vue";
import UserOverview from "../components/UserOverview.vue";
import RegisterCodesOverview from "../components/RegisterCodesOverview.vue";
import TempFilesGrid from "../components/TempFilesGrid.vue";

export default Vue.extend({
	name: "Admin",
	data: function () {
		return {
			tab: "overview",
		};
	},
	components: {
		Navbar,
		AdminOverview,
		TagOverview,
		UserOverview,
		RegisterCodesOverview,
		TempFilesGrid,
	},
	methods: {},
});
</script>

<style lang="less" scoped>
.tab-wrapper {
	min-height: 90vh;
}
</style>
