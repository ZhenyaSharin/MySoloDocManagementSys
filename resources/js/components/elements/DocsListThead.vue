<template>
	<thead class="th-vert-middle thead-dark">
		<tr>
			<th scope="col">
				ID
			</th>
			<th class="py-2 pr-2" scope="col">
				<div class="cursor-point" @click="sortModeToggle('description')">
					Документ
				</div>
				<div class="basetab__search">
	        		<div class="form-group row mb-0">
	        			<div class="col-md-12" v-if="$root.selectedTitle==1">
	        				<input type="text" class="form-control sort_thead-input" placeholder="Поиск" aria-label="Поиск по спискам" v-model.trim="sort.desc" v-on:change="sortList($root.docsList, 'description')">
	        			</div>
	        			<div class="col-md-12" v-else-if="$root.selectedTitle==2">
	        				<input type="text" class="form-control sort_thead-input" placeholder="Поиск" aria-label="Поиск по спискам" v-model.trim="sort.desc" v-on:change="sortList($root.docsListAll, 'description')">
	        			</div>
	        		</div>
	        	</div>
			</th>
			<th scope="col" class="py-2 px-1 ws-nowrap">
				<div class="cursor-point" @click="sortModeToggle('orderNum')">
					{{ titleNum }}
				</div>
				<div class="basetab__search m-0">
	        		<div class="form-group row mb-0">
	        			<div class="col-md-12" v-if="$root.selectedTitle==1">
	        				<input type="text" class="form-control sort_thead-input" placeholder="Поиск по спискам" aria-label="Поиск" v-model.trim="sort.orderNum" v-on:change="sortList($root.docsList, 'orderNum')">
	        			</div>
	        			<div class="col-md-12" v-else-if="$root.selectedTitle==2">
	        				<input type="text" class="form-control sort_thead-input" placeholder="Поиск" aria-label="Поиск по спискам" v-model.trim="sort.orderNum" v-on:change="sortList($root.docsListAll, 'orderNum')">
	        			</div>
	        		</div>
	        	</div>
			</th>
			<th v-if="isType===true" class="ta-center py-2 px-1" scope="col">
				<div class="cursor-point" @click="sortModeToggle('type')">
					Тип
				</div>
				<div class="basetab__search m-0">
					<div class="multiselect_custom-sort col-md-12" v-if="$root.selectedTitle==1">
						<vue-multiselect v-model="sort.docType" :options="$root.documentTypes" placeholder="Выберите" label="title" track-by="id" select-label="Выбрать" deselect-label="Удалить" selected-label="Выбрано" :searchable="true" @input="sortList($root.docsList, 'docType')" style="font-size: 12px;">
                        	<template slot="noOptions" slot-scope="props">
								Список пуст.
							</template>
							<template slot="noResult" slot-scope="props">
								Ничего не найдено...
							</template>
                        </vue-multiselect>
	        		</div>
	        		<div class="multiselect_custom-sort col-md-12" v-if="$root.selectedTitle==2">
		        		<vue-multiselect v-model="sort.docType" :options="$root.documentTypes" placeholder="Выберите" label="title" track-by="id" select-label="Выбрать" deselect-label="Удалить" selected-label="Выбрано" :searchable="true" @input="sortList($root.docsListAll, 'docType')" style="font-size: 12px;">
                        	<template slot="noOptions" slot-scope="props">
								Список пуст.
							</template>
							<template slot="noResult" slot-scope="props">
								Ничего не найдено...
							</template>
                        </vue-multiselect>
	        		</div>
	        	</div>
			</th>
			<th class="ta-center timestamp_font py-2 p-1" scope="col">
				<div class="d-flex justify-content-center align-items-center">
					<vue-elem-ascdesc/>
					<template v-if="isType===true">
						<div @click="$root.toggleDate(userId)">
							<vue-elem-datetoggle/>
						</div>
					</template>
					<template v-else>
						<div @click="$root.toggleDate(userId, typeId)">
							<vue-elem-datetoggle/>
						</div>
					</template>
				</div>
			</th>
			<!-- <th v-if="isDiruser===true" class="ta-center" scope="col"> -->
			<th class="ta-center py-2 px-1" scope="col">
				<div class="cursor-point" @click="sortModeToggle('type')">
					Адресат/Контрагент
				</div>
				<div class="basetab__search m-0">
					<div class="multiselect_custom-sort col-md-12 px-0" v-if="$root.selectedTitle==1">
						<vue-multiselect v-model="sort.diruser" :options="$root.directUsers" placeholder="Выберите" label="title" track-by="id" select-label="Выбрать" deselect-label="Убрать" selected-label="Удалить" :searchable="true" @input="sortList($root.docsList, 'diruser')" :custom-label="$root.namesFull">
                        	<template slot="noOptions" slot-scope="props">
								Список пуст.
							</template>
							<template slot="noResult" slot-scope="props">
								Ничего не найдено...
							</template>
                        </vue-multiselect>
	        		</div>
	        		<div class="multiselect_custom-sort col-md-12 px-0" v-if="$root.selectedTitle==2">
		        		<vue-multiselect v-model="sort.diruser" :options="$root.directUsers" placeholder="Выберите" label="surname" track-by="id" select-label="Выбрать" deselect-label="Удалить" selected-label="Выбрано" :searchable="true" @input="sortList($root.docsListAll, 'diruser')" :custom-label="$root.namesFull">
                        	<template slot="noOptions">
                        		Список пуст.
                        	</template>
							<template slot="noResult">
								Ничего не найдено...
							</template>
                        </vue-multiselect>
	        		</div>
	        	</div>
				<!-- <vue-select-dirusers/> -->
			</th>
			<th v-if="isStatus===true" class="ta-center py-2 px-1" scope="col">
				Основание
			</th>
			<th v-if="isStatus===true" class="ta-right py-2" scope="col">
				Статус
			</th>
			<th v-if="isStatus===false" class="ta-right py-2 pl-1" scope="col">
				Основание
			</th>
		</tr>
	</thead>
</template>
<script>
	export default {
		props: {
			title: {
				type: String,
				default: 'Дата документа',
			},
			titleNum: {
				type: String,
				default: 'Номер док-та',
			},
			isDiruser: {
				type: Boolean,
				default: false,
			},
			isType: {
				type: Boolean,
				default: false,
			},
			isStatus: {
				type: Boolean,
				default: false,
			},
			typeId: Number,
			userId: Number,
		},
		data() {
			return {
				sortArr: null,
				sort: {
					desc: null,
					orderNum: null,
					docType: null,
					diruser: null,
				},
			}
		},
		created() {
			this.$root.getDirusers();
		},
		methods: {
			sortModeToggle: function(type) {
				// this.$root.sortList = [];
				this.$root.sortMode = (this.$root.sortMode === false) ? true : false;
				if (this.$root.sortMode === false) {
					this.$root.sortArr = null;
					this.sort = {
						desc: null,
						orderNum: null,
						docType: null,
					};
				} else {
					console.log(this.$root.documentTypes);
				}
				this.spinOffDocs = false;
				console.log(this.spinOffDocs);
			},
			sortList: function(arr, type = 'description') {
				let newArr = arr;
				if (this.$root.sortArr === null) {
					this.$root.sortArr = [];
					this.$root.disablePagi = false;
				} else {
					newArr = (this.$root.sortArr.length > 0) ? this.$root.sortArr : arr;
					this.$root.sortArr = [];
				}
				if (type === 'description') {
					console.log(newArr);
					if (this.sort.desc.length >= 3) {
						newArr.forEach(item => {
							if (item[type].toLowerCase().includes(this.sort.desc.toLowerCase()) == true) {
								this.$root.sortArr.push(item);
							}
						});
						console.log('done');
						this.$root.disablePagi = true;
					} else {
						this.$root.sortArr = null;
						this.$root.disablePagi = false;
					};
				} else if (type === 'orderNum') {
					console.log(newArr);
					if (this.sort.orderNum.length >= 2) {
						newArr.forEach(item => {
							if (item[type] != null) {
								if (item[type].toLowerCase().includes(this.sort.orderNum.toLowerCase()) == true) {
									this.$root.sortArr.push(item);
								}
							}
						});
						console.log('done');
						this.$root.disablePagi = true;
					} else {
						this.$root.sortArr = null;
						this.$root.disablePagi = false;
					};
				} else if (type === 'docType') {
					console.log(newArr);
					if (this.sort.docType != null) {
						newArr.forEach(item => {
							if (item.typeId == this.sort.docType.id) {
								this.$root.sortArr.push(item);
							}
						});
						console.log('done');
						this.$root.disablePagi = true;
					} else {
						this.$root.sortArr = null;
						this.$root.disablePagi = false;
					};
				} else if (type === 'diruser') {
					console.log(this.sort.diruser);
					if (this.sort.diruser != null) {
						newArr.forEach(item => {
							if (item.diruser != false) {
								if (item.diruser.user.id == this.sort.diruser.id) {
									this.$root.sortArr.push(item);
								}
							}
						});
						console.log('doneDir');
						this.$root.disablePagi = true;
					} else {
						this.$root.sortArr = null;
						this.$root.disablePagi = false;
					};
				}
				console.log(this.$root.sortArr);
			},
		},
	}
</script>