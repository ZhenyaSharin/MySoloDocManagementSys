<template>
	<div class="col-md-12">
    	<div v-if="userMessage===1" class="alert alert-success d-flex justify-content-between align-items-center">
	        <div>
	        	<i class="far fa-thumbs-up fa-lg"/>&nbsp;&nbsp;Вы успешно создали новое поручение
	        </div>
	        <div class="shad-hover noteclose cursor-point greytxt" title="Закрыть уведомление" @click="closeMsg()">
	        	<i class="fas fa-times fa-lg"/>
	        </div>
	    </div>
	    <div v-else-if="userMessage===2" class="alert alert-danger d-flex justify-content-between align-items-center">
	    	<div>
	    		<i class="fas fa-exclamation-circle fa-lg"/>&nbsp;&nbsp;Не удалось создать новое поручение, возникла ошибка. Проверьте правильность заполняемых данных...
	    	</div>
	    	<div class="shad-hover noteclose cursor-point greytxt" title="Закрыть уведомление" @click="closeMsg()">
	        	<i class="fas fa-times fa-lg"/>
	        </div>
	    </div>
	    <template v-if="newApp===0">
	    	<div v-if="update===false">
	    		<div v-if="start==null" class="d-flex justify-content-end align-items-center">
		    		<button @click="initApp()" class="btn btn-addnew">
						Создать поручение&nbsp;&nbsp;<i class="fas fa-plus fa-lg"/>
		        	</button>
		        </div>
		        <div v-else class="d-flex align-items-center mt-4">
		        	<button @click="initApp()" class="btn btn-addnew">
						<i class="fas fa-copy fa-lg"/>&nbsp;&nbsp;Создать поручение на основе текущего
		        	</button>
		    	</div>
	    	</div>
	    </template>
    	<template v-else-if="newApp===1">
    		<div class="card mt-4">
				<div class="card-header card-header_custom font-bold font-up">
					Создать поручение
				</div>
				<div class="newuser_close shad-hover" @click="closeIt()">
					<i class="fas fa-times fa-lg"/>
				</div>
				<div class="card-body card-body_custom">
					<vue-alert-inwork v-if="start!=null"/>
					<div class="row">
			        	<div class="col-md-12">
			        		<div class="form-group row">
			            		<label for="department" class="col-md-5 col-form-label text-md-right">
			            			Тип поручения:<span class="alert-red">&nbsp;*</span>
			            		</label>
				            	<div class="col-md-6">
			                        <vue-multiselect v-model="assignType" :options="assignmentTypes" placeholder="Выберите тип" label="title" track-by="id" select-label="Enter чтобы выбрать" deselect-label="Enter чтобы удалить" selected-label="Выбрано" :searchable="false">
			                        	<template slot="noOptions" slot-scope="props">
											Список пуст.
										</template>
										<template slot="noResult" slot-scope="props">
											Ничего не найдено...
										</template>
			                        </vue-multiselect>
			                    </div>
			            	</div>
			            	<div class="form-group row">
			                    <label for="assignTitle" class="col-md-5 col-form-label text-md-right">
			                    	Название:<span class="alert-red">&nbsp;*</span>
			                    </label>
			                    <div class="col-md-6">
			                        <textarea class="form-control" name="assignTitle" value="" required autocomplete="assignTitle" max="255" placeholder="Краткое описание поручения (макс. 256 символов)" id="assignTitle" rows="2" v-model="assignArr.text" maxlength="256">
			                        </textarea>
			                    </div>
			                </div>
			                <div class="form-group row">
			                    <label for="description" class="col-md-5 col-form-label text-md-right">
			                    	Текст поручения:<span class="alert-red">&nbsp;*</span>
			                    </label>
			                    <div class="col-md-6">
			                        <textarea class="form-control" name="description" value="" required autocomplete="description" max="255" placeholder="Текст поручения" id="description" rows="8" v-model="assignArr.description" maxlength="2048">
			                        </textarea>
			                    </div>
			                </div>
			                <div class="form-group row">
					        	<label for="deadline" class="col-md-5 col-form-label text-md-right">
					        		Исполнители:<span class="alert-red">&nbsp;*</span>
					        	</label>
						        <div class="col-md-6">
				                	<div class="d-flex flex-column mb-2">
				                        <vue-multiselect v-model="value" :options="usersList" :multiple="true" :close-on-select="false" :clear-on-select="true" :preserve-search="true" placeholder="Поиск пользователей" :custom-label="$root.namesFull" track-by="id" :preselect-first="false" :max="5" select-label="Enter чтобы выбрать" deselect-label="Enter чтобы удалить" selected-label="Выбрано">
											<template slot="selection" slot-scope="{ values, search, isOpen }">
												<span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">Пользователей выбрано:&nbsp;{{ values.length }} </span>
											</template>
											<template slot="noOptions" slot-scope="props">
												Список пуст.
											</template>
											<template slot="noResult" slot-scope="props">
												Ничего не найдено...
											</template>
				                        </vue-multiselect>
				                        <template v-for="(item, index) in value">
				                        	<div v-if="index==0" class="font-bold m-2">
												{{ index+1 }}.&nbsp;&nbsp;{{ $root.makeFio(item.surname, item.firstname, item.patronymic) }}<span class="greytxt">&nbsp;&nbsp;-- Основной исполнитель
												</span>
											</div>
											<div v-else class="font-bold m-2">
												{{ index+1 }}.&nbsp;&nbsp;{{ $root.makeFio(item.surname, item.firstname, item.patronymic) }}
											</div>
				                        </template>
				                	</div>
				                </div>
				            </div>
			                <div class="form-group row">
			                    <label for="deadline" class="col-md-5 col-form-label text-md-right">
			                    	Срок исполнения до:<span class="alert-red">&nbsp;*</span>
			                    </label>
			                    <div class="col-md-6">
			                        <vue-datepicker id="deadline" v-model="assignArr.deadline" valueType="format" format="DD.MM.YYYY" :disabled-date="datePickerOps.disabledDate" class="cursor-point" :lang="lang" placeholder="ДД.ММ.ГГГГ"/>
			                    </div>
			                </div>
			                <div v-if="update===false" class="form-group row">
			                    <label for="deadline" class="col-md-5 col-form-label text-md-right">
			                    	Документ-основание:
			                    </label>

			                    <div class="col-md-6 mt-2">
									<vue-template-basedocassign v-if="start==null" :docs-without="docsWithout" :type="2"/>
									<vue-template-basedocassign v-else :docs-without="docsWithout" :type="2" :base-tab="2" :opened="true"/>
									<vue-alert-docassignselect/>
			                    </div>
			                </div>
			                <div v-else class="form-group row">
			                    <label for="deadline" class="col-md-5 col-form-label text-md-right">
			                    	Документ-основание:
			                    </label>
			                    <div class="col-md-6 py-2 font-bold">
									<a :href="'/document-'+ $parent.id">
										{{ $parent.docData.description }}
									</a>
			                    </div>
			                </div>

			                <div class="form-group row">
			                    <label for="deadline" class="col-md-5 col-form-label text-md-right">
			                    	Приложение к поручению:
			                    </label>
			                    <div class="col-md-6">
									<div class="d-flex align-items-center">
					        			<div class="custom-file">
					        				<input v-if="newAdd==true" type="file" class="custom-file-input" ref="file" id="customFile" @change="uploadFile()" multiple>
					        				<input v-else type="file" class="custom-file-input" ref="file" id="customFile" disabled>
											<label class="custom-file-label" for="customFile" data-browse="Обзор файлов">Выберите файл(ы)</label>
					        			</div>
									</div>
									<div v-if="$refs.file!=null">
										<table v-for="(item, index) in fileArr" class="table mt-4">
											<tbody>
												<tr>
													<th class="ta-center" scope="row">
														{{ index+1 }}
													</th>
													<td>
														{{ item.name }}
													</td>
													<td class="ta-right cursor-point" @click="delFileItem(index)">
														<i class="fas fa-times fa-lg"/>
													</td>
												</tr>
												<td colspan="100%">
													<textarea class="form-control" name="description" required autocomplete="description" autofocus max="255" :placeholder="'Комментарий к файлу (' + (index+1) +')'" id="description" rows="2" v-model="comment[index]"/>
												</td>
											</tbody>
										</table>
									</div>
			                    </div>
			                </div>
			                <div class="form-group row">
				            	<div class="col-md-7 offset-md-5">
			                    	<small class="form-text text-muted greytxt ml-1">
										<span class="alert-red">*&nbsp;</span> Обязательные для заполнения поля.
									</small>  
			                    </div>
			            	</div>
			        	</div>
			        </div>
			        <div class="form-group row">
			            <div class="col-md-12 d-flex justify-content-center">
			            	<a v-if="isCreating==true" class="btn btn-primary btn__creation font-bold no-round font-up p-3 box-shad ta-center" disabled>
			                    <vue-spinner/>
			                </a>
			                <a v-else class="btn btn-primary btn__creation font-bold no-round font-up p-3 box-shad" @click="addNewAssign()">
			                    Создать поручение
			                </a>
			            </div>
			        </div>
			    </div>
			</div>
    	</template>
		<br/>
	</div>
</template>
<script>
	import DatePicker from 'vue2-datepicker';
  	import 'vue2-datepicker/index.css';
	import Multiselect from 'vue-multiselect';
	export default {
		components: { 
			DatePicker,
		},
		props: {
			userId: Number,
			usersList: Array,
			start: Object,
			update: {
				type: Boolean,
				default: false,
			},
		},
		data() {
			return {
				userMessage: 0,
				newApp: 0,
				deleteFileSign: false,
				baseAssign: null,
				docsWithout: [],
				docAgreeItem: null,
				withoutAgr: null,
				deadline: null,
				today: null,
				datePickerOps: {
					disabledDate: (date) => date < this.today,
				},
				assignmentTypes: [],
				assignArr: {
					typeId: null,
					text: '',
					deadline: null,
					documentId: null,
					authorId: this.userId,
					executorId: [],
					baseId: null,
					description: '',
				},
				agreementType: 1,
				assignType: null,
				value: [],
				baseData: {
					docId: '',
					assignId: '',
				},
				agree: [],
				isCreating: false,
				lang: {
		          	formatLocale: {
		            	firstDayOfWeek: 1,
		          	},
		        },
		        newAdd: true,
		        fileArr: [],
		        file: null,
		        comment: [],
		        agreeValue: [],
			}
		},
		created() {
			this.today = new Date();
			this.today.setDate(this.today.getDate() - 1);
			// console.log(this.start);
		},
		methods: {
			initApp: function() {
				this.newApp = 1;
				this.value = [];
				this.getAssignTypes();
				this.$root.assignsAll();
				this.$root.docsAll(true);
				if (this.update === true) {
					// console.log(this.$parent.startAssign);
					this.assignArr.text = this.$parent.startAssign.text;
					this.assignArr.description = this.$parent.startAssign.description;
					this.assignType = this.$parent.startAssign.type;
					this.value.push(this.$parent.startAssign.executor);
					this.$root.baseDocId = {
						id: this.$parent.docData.id,
						author: this.$parent.docData.authorData,
						description: this.$parent.docData.description,
						orderNum: this.$parent.docData.orderNum,
						creationDate: this.$parent.docData.creationDate,
						created_at: this.$parent.docData.created_at,
					};
					// console.log(this.value);
				} else if (this.start) {
					this.assignArr.text = this.start.text;
					this.assignArr.description = this.start.description;
					this.assignType = this.start.type;
					// console.log('start');
					// console.log(this.start);
					this.$root.baseAssignId = {
						id: this.$parent.data.id,
						text: this.$parent.data.text,
						created_at: this.$parent.data.created_at,
						author: this.$parent.data.author,
					};
				};
			},
			closeIt: function() {
				this.newApp = 0;
				this.userMessage = 0;
				this.docType = null;
				this.clearAssign();
				// this.update = false;
				this.isCreating = false;
			},
			selectBase(n) {
				this.basetabPanelType = n;
			},
			addNewAssign: function() {
				console.log(this.assignArr);
				this.isCreating = true;
				let executors = [];
				let data = new FormData();
				data.append('typeId', this.assignType.id);

				this.baseData.docId = (this.$root.baseDocId != null) ? this.$root.baseDocId.id : '';
				this.baseData.assignId = (this.$root.baseAssignId != null) ? this.$root.baseAssignId.id : '';

				if (this.baseData.docId != null) {
					data.append('documentId', this.baseData.docId);
				}
				if (this.baseData.assignId != null) {
					data.append('baseId', this.baseData.assignId);
				}
				data.append('typeId', this.assignType.id);
				this.value.forEach(item => {
					executors.push({
						id: item.id,
					});
				});
				if (this.assignArr.deadline != null) {
					data.append('deadline', this.$root.frmtDateIn(this.assignArr.deadline, '18:00:00'));
				};
				data.append('text', this.assignArr.text);
				data.append('description', this.assignArr.description);
				data.append('authorId', this.userId);
				data.append('executors', JSON.stringify(executors));
				if (this.fileArr != []) {
					for (let i = 0; i < this.fileArr.length; i++) {
						this.comment[i] = (this.comment[i] !== undefined) ? this.comment[i] : '';
						data.append('files[]', this.$refs.file.files[i]);
						data.append('comment[]', this.comment[i]);
					}
				}
				// console.log(data.get('baseId'));
				axios.post('api/newassignment', data, {
			        headers: {
			        	"Content-Type": "application/json"
			        }
			     })
					.then(response => {
						if (response.data.error == 0) {
							this.userMessage = 1;
							this.newApp = 0;
							if (this.start == null) {
								this.$parent.spinOffAuthorAssigns = true;
								if (this.$parent.authorAssignCount != null) {
									this.$parent.getAssignmentsByAuthor(this.$parent.authorAssignCount);
									this.$parent.getAssignmentsByExecutor(this.$parent.executorAssignCount)
								} else {
									this.$parent.assignsById();
								}
							}
							this.isCreating = false;
						} else {
							this.userMessage = 2;
							this.isCreating = false;
						}
					}).catch(error => {
						this.userMessage = 2;
						alert('Ошибка получения данных');
						console.log(error);
						this.isCreating = false;
					});
			},
			clearFile: function() {
				this.docFile = '';
				document.querySelector('.custom-file-label').innerHTML = 'Выберите файл';
				this.deleteFileSign = false;
			},
			clearAssign: function() {
				this.assignArr.text = '';
				this.assignArr.deadline = null;
				this.assignBase = null;
				this.assignType = null;
				this.value = null;
				this.assignArr.description = '';
				this.fileArr = [];
			},
			// getBase: function(data) {
			// 	this.baseData.docId = (data.docId != null) ? data.docId : '';
			// 	this.baseData.assignId = (data.assignId != null) ? data.assignId : '';
			// },
			getAgreers: function(data) {
				this.agree = data;
			},
			getAgreementType: function(data) {
				this.withoutAgr = data;
				console.log(data);
			},
			closeMsg: function() {
				this.userMessage = 0;
				this.clearAssign();
			},
			getAssignTypes: function() {
				axios.post('api/getassignmenttypes', {
					headers: {
						"Content-Type": "application/json"
					}
				})
					.then(response => {
						if (response.data.error == 0) {
							this.assignmentTypes = response.data.result;
						} else {
							alert(response.data.error_message);
						}
					}).catch(error => {
						alert('Ошибка получения данных');
						console.log(error);
					});
			},
			uploadFile: function() {
				for (let i = 0; i < this.$refs.file.files.length; i++) {
					this.fileArr.push(this.$refs.file.files[i]);
				}
			    this.file = this.$refs.file.files[0];
			    this.deleteFileSign = true;
			    console.log(this.$refs.file.files);
			},
			delFileItem: function(index) {
				this.fileArr.splice(index, 1);
			},
		}
	}
</script>