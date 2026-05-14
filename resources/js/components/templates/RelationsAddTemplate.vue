<template>
	<div class="row">
		<div class="col-12">
			<div class="row">
				<div class="col-12">
					<div class="alert alert-success d-flex justify-content-between align-items-center" v-if="userMessage==1">
				        <div>
				        	<i class="far fa-thumbs-up fa-lg"/>&nbsp;&nbsp;Вы успешно создали добавили новые связи
				        </div>
				        <div class="shad-hover noteclose cursor-point greytxt" title="Закрыть уведомление" @click="closeMsg()">
				        	<i class="fas fa-times fa-lg"/>
				        </div>
				    </div>
				    <div class="alert alert-danger d-flex justify-content-between align-items-center" v-else-if="userMessage==2">
				    	<div>
				    		<i class="fas fa-exclamation-circle fa-lg"/>&nbsp;&nbsp;Не удалось создать новые связи, возникла ошибка...
				    	</div>
				    	<div class="shad-hover noteclose cursor-point greytxt" title="Закрыть уведомление" @click="closeMsg()">
				        	<i class="fas fa-times fa-lg"/>
				        </div>
				    </div>
					<div>
    					<h4>
    						Добавить связи с карточками документов
    					</h4>
    				</div>
					<div class="form-group row mb-2">
	        			<div class="col-md-12">
	        				<vue-multiselect v-model="docArr" :options="$root.filteredDocs" :multiple="true" :close-on-select="false" :clear-on-select="true" :preserve-search="true" placeholder="Поиск по карточкам документов" label="description" track-by="id" :preselect-first="false" select-label="Enter чтобы выбрать" deselect-label="Enter чтобы удалить" :custom-label="$root.baseDocTitle" selected-label="Выбрано">
	        					<template slot="noOptions" slot-scope="props">
									Список пуст.
								</template>
								<template slot="noResult" slot-scope="props">
									Ничего не найдено...
								</template>
	        				</vue-multiselect>
	        			</div>
		        	</div>
		        	<div class="form-group row mb-1">	
		        		<vue-template-multiselectlist :arr="docArr" :type="'doc'"/>
		        	</div>
				</div>
			</div>
    		<div class="row">
    			<div class="col-12">
    				<div>
    					<h4>
    						Добавить связи с поручениями
    					</h4>
    				</div>
		        	<div class="form-group row mb-2">
		        		<div class="col-md-12">
		        			<vue-multiselect v-model="assignArr" :options="$root.filteredAssigns" :multiple="true" :close-on-select="false" :clear-on-select="true" :preserve-search="true" placeholder="Поиск по поручениям" label="description" track-by="id" :preselect-first="false" select-label="Enter чтобы выбрать" deselect-label="Enter чтобы удалить" :custom-label="$root.baseAssignTitle" selected-label="Выбрано">
		        				<template slot="noOptions" slot-scope="props">
									Список пуст.
								</template>
								<template slot="noResult" slot-scope="props">
									Ничего не найдено...
								</template>
		        			</vue-multiselect>
		        		</div>
					</div>
					<div class="form-group row mb-1">	
		        		<vue-template-multiselectlist :arr="assignArr" :type="'assign'"/>
		        	</div>
	        	</div>
    		</div>
    		<div class="row">
    			<div class="col-12">
    				<template v-if="isCreating==true">
    					<button class="btn btn-success wide_btn font-bold font-up my-3 py-3 no-round">
							<vue-spinner/>
						</button>
    				</template>
    				<template v-else>
    					<button v-if="docArr.length>0||assignArr.length>0" class="btn btn-success wide_btn font-bold font-up my-3 py-3 no-round" @click="addRelation()">
							<i class="fas fa-link fa-lg"/>&nbsp;Добавить связи
						</button>
						<button v-else class="btn btn-success wide_btn font-bold font-up my-3 py-3 no-round" disabled>
							<i class="fas fa-link fa-lg"/>&nbsp;Добавить связи
					</button>
    				</template>
				</div>
    		</div>
		</div>
	</div>
</template>
<script>
	export default {
		// props: {
		// 	id: String,
		// },
		data() {
			return {
				newRel: {
					documentId1: null,
					documentId2: null,
					assignmentId1: null,
					assignmentId2: null,
					userId: this.$parent.userId,
				},
				relArr: [],
				docArr: [],
				assignArr: [],
				userMessage: 0,
				isCreating: false,
			}
		},
		// created() {
		// 	this.$root.filterRelations(this.$parent.relations, 'doc');
		// },
		methods: {
			addRelation: function() {
				this.isCreating = true;
				this.userMessage = 0;
				this.relArr = [];
				let data = {
					relations: [],
				};
				this.pushIntoDataArr(this.docArr, 'doc');
				this.pushIntoDataArr(this.assignArr, 'assign');
				data.relations = this.relArr;
				// console.log(data);
				axios.post('api/addrelation', data, {
			        headers: {
			        	"Content-Type": "application/json"
			        }
			    })
					.then(response => {
						if (response.data.error == 0) {
							console.log('Просмотрено');
							this.userMessage = 1;
							this.isCreating = false;
							this.$router.go();
						} else {
							this.userMessage = 2;
							this.isCreating = false;
						}
					}).catch(error => {
						alert('Ошибка получения данных7');
						this.userMessage = 2;
						this.isCreating = false;
						console.log(error);
					});
			},
			pushIntoDataArr: function(arr, type) {
				for (let i = 0; i < arr.length; i++) {
					let a = {
						documentId1: null,
						documentId2: null,
						assignmentId1: null,
						assignmentId2: null,
						userId: this.$parent.userId,
					};
					if (this.$parent.type === 'doc') {
						a.documentId1 = this.$parent.id;
						a.assignmentId1 = null;
						if (type === 'doc') {
							a.documentId2 = arr[i].id;
						} else if (type === 'assign') {
							a.assignmentId2 = arr[i].id;
						}
					} else if (this.$parent.type === 'assign') {
						a.assignmentId1 = this.$parent.id;
						a.documentId1 = null;
						if (type === 'doc') {
							a.documentId2 = arr[i].id;
						} else if (type === 'assign') {
							a.assignmentId2 = arr[i].id;
						}
					}
					this.relArr.push(a);
				}
			},
			closeMsg: function() {
				this.userMessage = 0;
			},
		},
	}
</script>