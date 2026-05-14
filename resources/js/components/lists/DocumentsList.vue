<template>
	<div class="container">
		<div class="row d-flex align-items-center">
			<vue-template-newdocument :user-id="userId"/>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header card-header_custom font-bold font-up d-flex justify-content-between align-items-center" v-if="$root.selectedTitle==1">
						<div>
							Ваши документы
						</div>
						<div class="selected_title cursor-point ta-right" @click="toggleTitle(2)">
							Все документы
						</div>
					</div>
					<div class="card-header card-header_custom font-bold font-up d-flex justify-content-between align-items-center" v-else-if="$root.selectedTitle==2">
						<div class="selected_title cursor-point" @click="toggleTitle(1)">
							Ваши документы
						</div>
						<div class="ta-right">
							Все документы
						</div>
					</div>
					<div class="card-body table_scroll_998_y">
						<table class="table th-vert-middle table-striped">
							<vue-thead-docslist :user-id="userId" :is-type="true" :is-status="true"/>
							<template v-if="$root.spinOffDocs===true">
								<tbody>
									<tr>
										<td colspan="100%" class="ta-center mt-4">
											<vue-spinner/>
										</td>
									</tr>
								</tbody>
							</template>
							<template v-else-if="($root.spinOffDocs===false)">
								<tbody v-if="$root.sortArr!=null">
									<template v-if="$root.sortArr.length>0">
										<!-- <vue-item-letterlist v-for="(item, index) in $root.sortArr" :data="item" :date-mode="$root.dateMode"  :index="item.id" :key="item.id"/> -->
										<vue-item-documentitem v-for="(item, index) in $root.sortArr" :date-mode="$root.dateMode" :data="item" :index="item.id" :is-diruser="true" :key="item.id"/>
									</template>
									<template v-else>
										<vue-tr-notfound/>
									</template>
								</tbody>
								<tbody v-else-if="$root.docsList.length>0">
									<template v-for="(item, index) in $root.docsList">
										<vue-item-documentitem v-if="((index >= $root.docsListFirst - 1) && (index <= $root.docsListLast - 1))" :date-mode="$root.dateMode" :data="item" :index="item.id" :is-diruser="true"/>
									</template>
								</tbody>
								<tbody v-else-if="$root.docsListAll.length>0">
									<template v-for="(item, index) in $root.docsListAll">
										<vue-item-documentitem v-if="((index >= $root.docsListFirstAll - 1) && (index <= $root.docsListLastAll - 1))" :date-mode="$root.dateMode" :data="item" :index="item.id" :is-diruser="true"/>
									</template>
								</tbody>
								<tbody v-else>
									<tr>
										<th colspan="100%" class="ta-center font-up tr-greyplug">
											Документы ещё не созданы
										</th>
									</tr>
								</tbody>
							</template>
						</table>
						<template>
							<template v-if="$root.spinOffDocs==true">
								<vue-template-listpagination class="vis_hid"/>
							</template>
							<template v-else>
								<template v-if="$root.selectedTitle==1">
									<vue-template-listpagination v-if="$root.docsList.length>0" :count="$root.pagiCount" :current="$root.currPagiPage" :disabled="$root.disablePagi"/>
								</template>
								<template v-else-if="$root.selectedTitle==2">
									<vue-template-listpagination v-if="$root.docsListAll.length>0" :count="$root.pagiCountAll" :current="$root.currPagiPageAll" :disabled="$root.disablePagi"/>
								</template>
							</template>
						</template>
					</div>
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
				docCount: null,
				// sortMode: false,
				sortArr: null,
				sort: {
					desc: null,
					orderNum: null,
					docType: null,
				},
				disablePagi: false,
			}
		},
		created(){
			axios.post('api/getdeliverytypes')
				.then(response => {	
					if (response.data.error == 0) {
						this.deliveryTypes = response.data.result;
					} else {
						alert(response.data.error_message);
					}
				}).catch(error => {
					alert('Ошибка получения данных');
					console.log(error);
				});
			this.$root.getDocumentTypes();
			this.toggleTitle(this.$route.query.type);
		},
		methods: {
			toggleTitle: function(n = 1) {
				if (n == 1) {
					this.$root.selectedTitle = n;
					this.$root.spinOffDocs = true;
					this.$root.docsListAll = [];
					this.$root.getDocs(this.userId, null, true);
					this.$root.makeParamsPage(this.$root.currPagiPage);
					console.log(this.$root.currPagiPage);
					this.$root.routerPush({
						type: n,
						page: this.$root.currPagiPage,
					});
				} else if (n == 2) {
					this.$root.selectedTitle = n;
					this.$root.spinOffDocs = true;
					this.$root.docsList = [];
					this.$root.docsAll(true);
					this.$root.makeParamsPage(this.$root.currPagiPageAll);
					console.log(this.$root.currPagiPageAll);
					this.$root.routerPush({
						type: n,
						page: this.$root.currPagiPageAll,
					});

				};
			},
		}
	}
</script>