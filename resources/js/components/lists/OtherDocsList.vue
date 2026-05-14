<template>
	<div class="container">
		<div class="row d-flex align-items-center">
			<vue-template-newdocument :user-id="userId" :type-id="typeId"/>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header card-header_custom font-bold font-up d-flex justify-content-between align-items-center" v-if="$root.selectedTitle==1">
						<div>
							Ваши прочие документы
						</div>
						<div class="selected_title ta-right cursor-point" @click="$root.toggleTitle(2)">
							Все прочие документы
						</div>
					</div>
					<div class="card-header card-header_custom font-bold font-up d-flex justify-content-between align-items-center" v-else-if="$root.selectedTitle==2">
						<div class="selected_title cursor-point" @click="$root.toggleTitle(1)">
							Ваши прочие документы
						</div>
						<div class="ta-right">
							Все прочие документы
						</div>
					</div>
					<vue-table-docsbytype :type-id="typeId" :user-id="userId" :is-diruser="true"/>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	export default {
		props: {
			userId: String,
		},
		data() {
			return {
				typeId: 4,
			}
		},
		mounted() {
			this.$root.getListByDocTypeId(this.typeId, this.userId, true);
		},
	}
</script>