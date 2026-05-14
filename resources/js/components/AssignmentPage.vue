<template>
	<div class="container card py-3">
		<div>
			<a href="/assignments?type=3&page=1">
				<i class="fas fa-long-arrow-alt-left fa-lg"/>&nbsp;&nbsp;<span class="font-bold">К списку поручений</span>
			</a>
		</div>
		<br/>
		<div class="row d-flex justify-content-between">
			<div class="col-md-8">
				<div class="d-flex justify-content-between">
					<div>
						<h2>
							Поручение:
						</h2>
					</div>
					<template v-if="data.authorId==userId&&data.status.id==6">
						<div v-if="editMode==false" class="docinfo_edit agreerslist_edit cursor-point" @click="editModeToggle()" title="Редактировать информацию в карточке документа">
							<i class="fas fa-edit fa-2x"/>
						</div>
						<div v-if="editMode==true" class="docinfo_edit agreerslist_edit cursor-point" @click="editModeToggle()" title="Закрыть редактирование">
							<i class="fas fa-times fa-2x"/>
						</div>
					</template>
				</div>
				<div class="back_white p-3 my-3">
					<template v-if="editMode==true">
						<textarea class="form-control" name="assignTitle" value="" required autocomplete="assignTitle" max="255" placeholder="Краткое описание поручения (макс. 256 символов)" id="assignTitle" rows="2" v-model="newText" maxlength="256">
                        </textarea>
					</template>
					<template v-else>
						<h4>
							{{ data.text }}
						</h4>
					</template>
<!-- 					<template v-if="editMode==true">
						<textarea class="form-control" name="description" value="" required autocomplete="description" max="255" placeholder="Текст поручения" id="description" rows="8" v-model="data.description" maxlength="2048">
                        </textarea>
					</template>
					<template v-else>
						<span class="font-bold" style="white-space: pre-line">
							{{ data.description }}
						</span>
					</template> -->
				</div>
				<!-- <br/><h3>{{ data.text }}</h3> -->
				<!-- <br/> -->
				<template v-if="data.executorId==userId">
					<template v-if="data.status.id==6">
						<div class="alert alert-primary d-flex flex-column my-3" role="alert">
							<vue-alert-inwork/>
						  	<div>
						  		Поручение прислано Вам...
						  	</div>
							<div class="py-3">
		                        <textarea class="form-control" name="comment" required autocomplete="comment" autofocus max="255" placeholder="Ваш комментарий" id="comment" rows="3" v-model="comment">
		                        </textarea>
				            </div>
				            <div class="alert-red mt-2" v-if="needComment==true">
                                <i class="fas fa-exclamation-circle fa-lg"/>&nbsp;Укажите причину
                            </div>
						  	<br/>
						  	<div v-if="makeShift==true" class="d-flex align-items-center mb-4">
						  		<vue-datepicker v-model="newDeadline" id="deadline" valueType="format" format="DD.MM.YYYY" :disabled-date="datePickerOps.disabledDate" class="cursor-point" :lang="lang" placeholder="ДД.ММ.ГГГГ"/>&nbsp;&nbsp;
						  		<div class="cursor-point font-bold" @click="initShift(false)">
						  			Скрыть
						  		</div>
						  		<div v-if="newDeadlineAlert==true" class="alert-red ml-4">
			                        <i class="fas fa-exclamation-circle fa-lg"/>&nbsp;Дата не назначена
			                    </div>
						  	</div>
						  	<div class="justify-content-center d-flex flex-column flex-sm-row">
			                    <button type="button" class="btn btn-primary no-round font-up flex-fill box-shad my-1 my-sm-0" @click="updateStatus('execution')">
			                    	Принять
			                    </button>
			                    <button v-if="makeShift==true" type="button" class="btn btn-success no-round font-up flex-fill box-shad mx-sm-2 my-1 my-sm-0" @click="shiftDeadline()">
			                        Запросить перенос срока
			                    </button>
			                    <button v-else type="button" class="btn btn-success no-round font-up flex-sm-fill box-shad mx-sm-2 my-1 my-sm-0" @click="initShift(true)">
			                        Запросить перенос срока
			                    </button>
			                    <button type="button" class="btn btn-danger no-round font-up flex-fill box-shad my-1 my-sm-0" data-toggle="modal" data-target="#refuseAssignModal" @click="refreshNote()">
			                        Отклонить
			                    </button>
			                </div>
						</div>
					</template>
					<template v-if="data.status.id==7">
						<div class="alert alert-primary d-flex flex-column" role="alert">
							
						  	<div>
						  		Исполнение поручения...
						  	</div>
						  	<div class="py-3">
		                        <textarea class="form-control" name="comment" required autocomplete="comment" autofocus max="255" placeholder="Ваш комментарий" id="comment" rows="3" v-model="comment">
		                        </textarea>
				            </div>
				            <div class="alert-red mt-2" v-if="needComment==true">
                                <i class="fas fa-exclamation-circle fa-lg"/>&nbsp;Укажите причину
                            </div>
						  	<br/>
						  	<div class="justify-content-center d-flex">
			                    <button type="button" class="btn btn-primary no-round font-up flex-sm-fill box-shad mr-3" @click="updateStatus('done')">
			                    	Исполнено
			                    </button>
			                    <button type="button" class="btn btn-danger no-round font-up flex-sm-fill box-shad ml-3" data-toggle="modal" data-target="#refuseAssignModal" @click="refreshNote()">
			                        Отклонить
			                    </button>
			                </div>
						</div>
					</template>
				</template>
				<template v-if="data.authorId==userId&&data.deadline.length>1">
					<template v-if="(data.deadline[0].approved_at==null&&data.deadline[0].refused_at==null)">
						<div class="alert alert-warning d-flex flex-column" role="alert">
						  	<div class="font-bold">
						  		Прислан запрос на перенос срока...
						  	</div>
						  	<div class="mt-2 mb-1">
						  		Текущий срок:&nbsp;&nbsp;<span class="font-bold blacktxt">{{ $root.frmtDate(actualDeadline) }}</span>
						  	</div>
						  	<div class="mb-2 mt-1">
						  		Запрашиваемый срок:&nbsp;&nbsp;<span class="font-bold blacktxt tt-underline">{{ $root.frmtDate(data.deadline[0].deadline) }}</span>
						  	</div>
						  	<div v-if="data.deadline[0].comment!=null" class="mb-3">
						  		Комментарий запрашивающего:&nbsp;&nbsp;<span class="font-bold blacktxt">{{ data.deadline[0].comment }}</span>
						  	</div>
						  	<div class="justify-content-center d-flex">
			                    <button type="button" class="btn btn-primary no-round font-up flex-sm-fill box-shad mr-3" @click="approveNewDeadline()">
			                    	Одобрить
			                    </button>
			                    <button type="button" class="btn btn-danger no-round font-up flex-sm-fill box-shad ml-3" data-toggle="modal" data-target="#refuseAssignDeadlineModal">
			                        Отклонить
			                    </button>
			                </div>
						</div>
					</template>
				</template>
				<template v-if="data.control!=null">
					<template v-if="this.data.control.userId==this.userId&&this.data.control.viewed_at==null">
						<div class="alert alert-warning d-flex flex-column" role="alert">
						  	<div>
						  		Поручение прислано Вам на контроль...
						  	</div>
						  	<br/>
						  	<div class="d-flex">
			                    <button type="button" class="btn btn-primary no-round font-up box-shad" @click="viewControl()">
			                    	<i class="fas fa-clipboard-check fa-lg"/>&nbsp;&nbsp;Подтвердить назначение
			                    </button>
			                </div>
						</div>
					</template>
				</template>
<!-- 				<div v-if="data.description!=null" class="back_white p-3">
					<span style="white-space: pre-line">{{ data.description }}</span>
				</div> -->
				<div class="table_scroll_581_y">
					<table class="table table-bordered my-4">
						<thead>
							<tr>
								<th class="td_light-grey" width="50%" style="border-right: 1px solid  #F5F5F5;">
									Данные:
								</th>
								<th class="td_light-grey" width="50%">
									
								</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th class="td_light-grey" scope="row">
									Автор поручения:
								</th>
								<td class="font-bold">
									<vue-link-userfio :data="data.author" :short="true"/>
								</td>
							</tr>
							<tr>
								<th class="td_light-grey" scope="row">
									Тип: 
								</th>
								<td class="font-bold">
									{{ data.typeName.title }}
								</td>
							</tr>
							<tr>
								<th class="td_light-grey" scope="row">
									Текст поручения: 
								</th>
								<td v-if="editMode==true" class="font-bold">
									<textarea class="form-control" name="description" value="" required autocomplete="description" max="255" placeholder="Текст поручения" id="description" rows="4" v-model="newDescription" maxlength="2048"/>
								</td>
								<td v-else class="font-bold">
									{{ data.description }}
								</td>
							</tr>
							<tr>
								<th class="td_light-grey" scope="row">
									Исполнитель поручения: 
								</th>
								<td class="font-bold">
									<vue-link-userfio :data="data.executor" :short="true"/>
								</td>
							</tr>
							<template v-if="editMode==false">
								<template v-if="data.documentBase!=null">
									<tr v-if="data.documentBase.status.docstatusId==2">
										<th class="td_light-grey" scope="row">
											Замечание-основание: 
										</th>
										<td>
											<span>
												{{ data.documentBase.description }}
											</span>
											<br/>
											<a class="font-bold" :href="'/document-'+data.documentBase.id" target="_blank">Ссылка на документ&nbsp;<i class="fas fa-external-link-square-alt fa-lg"/></a>
										</td>
									</tr>
									<tr v-else>
										<th class="td_light-grey" scope="row">
											Документ-основание: 
										</th>
										<td>
											<span>
												{{ data.documentBase.description }}
											</span>
											<br/>
											<a class="font-bold" :href="'/document-'+data.documentBase.id" target="_blank">Ссылка на документ&nbsp;<i class="fas fa-external-link-square-alt fa-lg"/></a>
										</td>
									</tr>
								</template>
								<template v-else-if="data.base!=null">
									<tr>
										<th class="td_light-grey" scope="row">
											Поручение-основание: 
										</th>
										<td>
											<span>
												{{ data.base.text }}
											</span>
											<br/>
											<a class="font-bold" :href="'/assignment-'+data.base.id" target="_blank">Ссылка на поручение&nbsp;<i class="fas fa-external-link-square-alt fa-lg"/></a>
										</td>
									</tr>
								</template>
							</template>
							<tr>
								<th class="td_light-grey" scope="row">
									Дата и время создания: 
								</th>
								<td class="font-bold">
									<vue-elem-timestamp :date-time="data.created_at"/>
								</td>
							</tr>
							<tr v-if="data.status.id==6||data.status.id==7">
								<th class="td_light-grey" scope="row">
									Срок исполнения: 
								</th>
								<!-- <td class="font-bold" title=""> -->
									<vue-td-dateleft :status="data.status.id" :date="actualDeadline" class="ta-left px-2"/>
<!-- 									<span class="status_refused timestamp_font" title="Срок исполнения уже прошёл...">
										{{ actualDeadline }}
									</span> -->
								<!-- </td> -->
							</tr>
							<tr v-else-if="data.status.id==9">
								<th class="td_light-grey" scope="row">
									Срок исполнения: 
								</th>
								<td class="font-bold" title="Поручение отмечено исполненым">
									<span class="status_approved">
										{{ actualDeadline }}
									</span>
								</td>
							</tr>
							<tr v-else-if="data.status.id==8">
								<th class="td_light-grey" scope="row">
									Срок исполнения: 
								</th>
								<td class="font-bold" title="Поручение отклонено...">
									<span class="status_refused">
										{{ actualDeadline }}
									</span>
								</td>
							</tr>
							<tr v-else-if="data.status.id==10">
								<th class="td_light-grey" scope="row">
									Срок исполнения: 
								</th>
								<td class="font-bold" title="Поручение отклонено автором...">
									<span class="font-bold">
										{{ actualDeadline }}
									</span>
								</td>
							</tr>
							<tr v-if="data.updated_at!=null">
								<th class="td_light-grey" scope="row">
									Дата и время последнего изменения: 
								</th>
								<td>
									<span class="font-bold">
										<vue-elem-timestamp :date-time="data.updated_at"/>
									</span>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="row" v-if="editMode==true">
					<div class="col-12">
						<hr/>
<!-- 						<template v-if="data.documentId!=null">
							<vue-template-basedocassign :opened="true" type="2" :base-tab="1"/>
						</template>
						<template v-else-if="data.baseId!=null">
							 <vue-template-basedocassign :opened="true" type="2" :base-tab="2"/>
						</template>
						<template v-else>
							 <vue-template-basedocassign :opened="true" type="2" :base-tab="1"/>
						</template> -->

						<template v-if="data.documentId!=null">
							<vue-template-basedocassign v-if="data.documentBase.status.docstatusId==2" :opened="true" type="1" :base-tab="3"/>
							<vue-template-basedocassign v-else :opened="true" type="1" :base-tab="1"/>
						</template>
						<template v-else-if="data.baseId!=null">
							 <vue-template-basedocassign :opened="true" type="1" :base-tab="2"/>
						</template>
						<template v-else>
							<vue-template-basedocassign :opened="true" type="1" :base-tab="1"/>
						</template>
						<hr/>
					</div>
				</div>
				<template v-if="editMode==true">
					<button class="btn btn-danger no-round font-bold box-shad mt-4" data-toggle="modal" data-target="#saveDocInfoModal">
						<i class="fas fa-save fa-lg"/>&nbsp;&nbsp;Сохранить изменения
					</button>
				</template>
				<div class="row mt-4" v-if="data.control!=false">
					<div class="col-md-12">
						<table class="table table-bordered mb-0">
							<thead class="thead-dark">
								<tr>
									<th scope="col" class="font-up">
										На личном контроле
									</th>
									<th scope="col" class="font-up ta-center">
										Назначивший
									</th>
									<th scope="col" class="font-up ta-right">
										Дата назначения
									</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td scope="col">
										<a class="font-bold hdr__fio"  :href="'/id_'+ data.control.user.login">
											{{ $root.makeFio(data.control.user.surname, data.control.user.firstname, data.control.user.patronymic) }}
										</a>
									</td>
									<td scope="col" class="ta-center">
										<a class="font-bold hdr__fio" :href="'/id_'+ data.control.user.login">
											{{ $root.makeFio(data.control.initiator.surname, data.control.initiator.firstname, data.control.initiator.patronymic) }}
										</a>
									</td>
									<td scope="col" class="ta-right">
										<vue-elem-timestamp :date-time="data.control.created_at"/>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="col-md-4 d-flex flex-column justify-content-between">
				<div>
					<div class="d-flex flex-column" v-if="data.status.id==6">
						<button class="btn btn-secondary btn-shad no-round font-bold" data-toggle="modal" data-target="#assignStatusLogModal">
							<i class="fas fa-list fa-lg"/>&nbsp;&nbsp;На рассмотрении
						</button>
						<br/>
						<br/>
						<button v-if="data.authorId==userId" class="btn btn-danger btn-shad no-round font-bold" title="Удалить заявку" data-toggle="modal" data-target="#deleteAssignAuthorModal" @click="getId(data.id)">
							<i class="fas fa-times fa-lg"/>&nbsp;&nbsp;Отменить поручение
						</button>
					</div>
					<div class="d-flex flex-column justify-content-end" v-else-if="data.status.id==7">
						<button class="btn btn-primary btn-shad no-round font-bold" data-toggle="modal" data-target="#assignStatusLogModal">
							<i class="fas fa-list fa-lg"/>&nbsp;&nbsp;На исполнении
						</button>
						<br/>
						<br/>
						<button v-if="data.authorId==userId" class="btn btn-danger btn-shad no-round font-bold" title="Удалить заявку" data-toggle="modal" data-target="#deleteAssignAuthorModal" @click="getId(data.id)">
							<i class="fas fa-times fa-lg"/>&nbsp;&nbsp;Отменить поручение
						</button>
					</div>
					<div class="d-flex flex-column justify-content-end" v-else-if="data.status.id==8">
						<button class="btn btn-danger btn-shad no-round font-bold" data-toggle="modal" data-target="#assignStatusLogModal">
							<i class="fas fa-list fa-lg"/>&nbsp;&nbsp;Отклонено
						</button>
					</div>
					<div class="d-flex flex-column justify-content-end" v-else-if="data.status.id==9">
						<button class="btn btn-success btn-shad no-round font-bold" data-toggle="modal" data-target="#assignStatusLogModal">
							<i class="fas fa-list fa-lg"/>&nbsp;&nbsp;Исполнено
						</button>
					</div>
					<div class="d-flex flex-column justify-content-end" v-else-if="data.status.id==10">
						<button class="btn btn-danger btn-shad no-round font-bold" data-toggle="modal" data-target="#assignStatusLogModal">
							<i class="fas fa-list fa-lg"/>&nbsp;&nbsp;Отклонено автором
						</button>
					</div>
					<br/>
					<br/>
					<div class="d-flex flex-column" v-if="data.deadline.length>1">
						<button class="btn btn-secondary btn-shad no-round font-bold py-2" data-toggle="modal" data-target="#deadlinesLogModal">
							<i class="fas fa-stopwatch fa-lg"/>&nbsp;&nbsp;Переносы сроков
						</button>
					</div>
				</div>
				<br/>
				<br/>
				<div class="d-flex flex-column justify-content-end" v-if="data.status.id==6||data.status.id==7">
					<button v-if="data.control==false" class="btn btn btn-success btn-shad no-round font-up font-bold py-3 box-shad_black" data-toggle="modal" data-target="#personalControlModal" @click="refreshCtrl()">
						<i class="fas fa-user-check fa-lg"/>&nbsp;&nbsp;личный контроль
					</button>
					<button v-else class="btn btn btn-success btn-shad no-round font-up font-bold py-3" disabled>
						<i class="fas fa-user-check fa-lg"/>&nbsp;&nbsp;личный контроль
					</button>
				</div>
				<br/>
				<template v-if="data.authorId==userId">
					<div v-if="data.fileAddition.length==0" class="row">
						<vue-template-newaddition :id="data.id" :type="'assign'"/>
					</div>
					<div v-else class="row">
						<vue-template-addition :data="data.fileAddition" :author="true" :type="'assign'"/>
					</div>
				</template>
				<template v-else>
					
				</template>
			</div>
		</div>
		<br/>
		<div class="btn btn-info no-round font-bold btn-shad width-sm-inherit" @click="$root.openRelsFunc(true, id, 'assign')">
			<i class="fas fa-link fa-lg"/>&nbsp;Добавить связи
		</div>
		<br/>
		<vue-template-relationslist :id="data.id" :user-id="userId" :author-id="data.authorId" :type="'assign'"/>
		<br/>
		<br/>
<!-- 		<pre>
			{{ data }}
		</pre> -->
		<div class="row" v-if="versions.documentId!=null||versions.baseAssignment!=null">
			<div class="col-md-12">
				<h4>
					Основание
				</h4>
				<template v-if="spinVers==true">
					<div class="row greyblck">
						<div class="col-md-12 d-flex justify-content-center align-items-center">
							<vue-spinner/>	
						</div>
					</div>
				</template>
				<template v-else>
					<div class="row greyblck table_scroll_998_y" v-if="versions.documentId!=null">
						<vue-item-base :baseDoc="versions.base" :baseAssign="null"/>
					</div>
					<div class="row greyblck table_scroll_998_y" v-else-if="versions.baseAssignment!=null">
						<vue-item-base :baseDoc="null" :baseAssign="versions.baseAssignment"/>
					</div>
				</template>
			</div>
		</div>
		<div class="row">
			<vue-template-newassignment :start="start" :user-id="Number(userId)" :users-list="usersList"/>
		</div>
<!-- 		<pre>
			{{ data }}
		</pre> -->
		<div class="row" v-if="data.byMain.length>0">
			<div class="col-md-12">
				<br/>
				<h4>
					Связанные поручения:
				</h4>
				<div class="table_scroll_998_y">
					<table class="table table-hover light-box-shad">
						<thead class="thead-dark">
							<tr>
								<th scope="col">
									Исполнитель
								</th>
								<th class="ta-center" scope="col">
									Дата создания
								</th>
								<th class="ta-center" scope="col">
									Дата отклика
								</th>
								<th class="ta-right" scope="col">
									Статус
								</th>
							</tr>
						</thead>
						<tbody v-if="spinOff===true">
							<tr>
								<td colspan="100%" class="ta-center mt-4">
									<vue-spinner/>
								</td>
							</tr>
						</tbody>
						<tbody v-else>
							<template v-for="item in data.byMain">
								<vue-item-assignmentpage-executor :data="item" :main="data"/>
							</template>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<vue-modal-refuseassignment/>
		<vue-modal-refuseassigndeadline/>
		<vue-modal-deadlineslog :list="data.deadline"/>
		<vue-modal-assignstatuslog :data="data.statusLog"/>
		<vue-modal-deleteassignauthor :id="delAssignId"/>
		<vue-modal-personalcontrol :list="usersList"/>
		<template v-if="data.fileAddition != []">
			<vue-modal-deleteaddition :id="id" :type="'assign'"/>
		</template>
		<vue-modal-saveassigninfo/>
		<!-- <vue-modal-relationslist :id="data.id" :user-id="userId" :author-id="data.authorId" :type="'assign'"/> -->
	</div>
</template>
<script>
	export default {
		props: {
			id: Number,
			userId: String
		},
		data() {
			return {
				data: {},
				versions: {},
				now: new Date(),
				datePickerOps: {
					disabledDate: (date) => date < new Date(),
				},
				newDeadline: null,
				makeShift: false,
				newDeadlineAlert: false,
				spinOff: true,
				assignMe: false,
				isMain: 0,
				currentAssignExId: null,
				spinVers: true,
				needComment: false,
				comment: '',
				delAssignId: null,
				usersList: [],
				isCreatingCtrl: false,
				valueCtrl: [],
				control: Object,
				start: {
					// type: null,
					// text: '',
					// description: '',
				},
				actualDeadline: '',
				lang: {
		          	formatLocale: {
		            	firstDayOfWeek: 1,
		          	},
		        },
		        editMode: false,
		        newType: null, 
		        newText: null,
		        newDescription: null,
		        baseDocId: null,
				baseAssignId: null,
			}
		},
		created() {
			this.$root.checkRole(this.userId);
            this.$root.docsAll();
            this.$root.assignsAll();
			this.getDataById();
			this.getUsersList();
			this.$root.getRelations(this.id, 'assign');
		},
		methods: {
			getDataById: function() {
				axios.post('api/getassignbyid', {id: this.id, info: 1}, {
					headers: {
						"Content-Type": "application/json"
					}
				})
				.then(response => {	
					if (response.data.error == 0) {
						this.data = response.data.result;
						console.log(this.data);
						this.makeStart();
						this.checkDeadline();
						if (this.data.viewed_at == null) {
							if (this.data.executorId == this.userId) {
								this.makeView();
							}
						}
						this.getVersions();
						// this.listByMain();
						this.spinOff = false;
					} else if (response.data.error == 2) {
						window.location.href = '/pagenotfound';
					} else {
						alert(response.data.error_message);
					}
				}).catch(error => {
					alert('Ошибка получения данных');
					console.log(error);
				});
			},
			getUsersList: function() {
				axios.post('api/getuserslist', {id: this.userId}, {
					headers: {
						"Content-Type": "application/json"
					}
				})
				.then(response => {
					if (response.data.error == 0) {
						this.usersList = response.data.result;
					} else {
						alert(response.data.error_message);
					}
				}).catch(error => {
					alert('Ошибка получения данных');
					console.log(error);
				});
			},
			refreshNote: function() {
				this.needComment = false;
			},
			getControl: function() {
				
			},
			updateStatus: function(alias) {
				if ((alias === 'rejected') && (this.comment === '')) {
					this.needComment = true;
				} else {
					this.needComment = false;
					axios.post('api/updateassignstatus', {assignmentId: this.id, alias: alias, deadline: this.$root.frmtDateIn(this.newDeadline, '18:00:00'), note: this.comment, executorId: this.userId }, {
				        headers: {
				        	"Content-Type": "application/json"
				        }
				    })
						.then(response => {
							if (response.data.error == 0) {
								this.$root.refreshPage();
							} else {
								this.userMessage = 2;
							}
						}).catch(error => {
							alert('Ошибка получения данных');
							this.userMessage = 2;
							console.log(error);
						});
				}
			},
            initShift: function(n) {
            	if (n === false) {
            		this.newDeadline = null;
            	}
            	this.makeShift = n;
            },
            makeView: function() {
            	axios.post('api/updateassignment', {id: this.id, viewed: 1}, {
					headers: {
						"Content-Type": "application/json"
					}
				})
				.then(response => {	
					if (response.data.error == 0) {
						console.log('Обновлено');
					} else {
						alert(response.data.error_message);
					}
				}).catch(error => {
					alert('Ошибка получения данных');
					console.log(error);
				});
            },
			getId: function(id) {
				this.getAssignId({
					assignId: id,
				})
			},
			getAssignId: function(data) {
				this.delAssignId = data.assignId;
			},
			refreshCtrl: function() {
				this.isCreatingCtrl = false;
				this.valueCtrl = [];
			},
			checkControl: function() {
				if (this.data.control!= null) {
					console.log('111');
					if (this.data.control.userId==this.userId&&this.data.control.viewed_at==null) {
						return true;
					} 
				}
				return false;
			},
			viewControl: function() {
				axios.post('api/updateassigncontrol', {id: this.data.control.id, viewed: 1}, {
					headers: {
						"Content-Type": "application/json"
					}
				})
				.then(response => {	
					if (response.data.error == 0) {
						console.log('Обновлено');
						this.$root.refreshPage();
					} else {
						alert(response.data.error_message);
					}
				}).catch(error => {
					alert('Ошибка получения данных');
					console.log(error);
				});
			},
			makeStart: function() {
				this.start = {
					type: this.data.typeName,
					text: this.data.text,
					description: this.data.description,
				};
            },
            approveNewDeadline: function() {
            	axios.post('api/updateassigndeadline', {id: this.data.deadline[0].id, approvedUserId: this.userId, approve: 1}, {
					headers: {
						"Content-Type": "application/json"
					}
				})
				.then(response => {	
					if (response.data.error == 0) {
						console.log('Обновлено');
						this.$root.refreshPage();
					} else {
						alert(response.data.error_message);
					}
				}).catch(error => {
					alert('Ошибка получения данных');
					console.log(error);
				});
            },
            refuseNewDeadline: function() {
				axios.post('api/updateassigndeadline', {id: this.data.deadline[0].id, approvedUserId: this.userId, refuse: 1}, {
					headers: {
						"Content-Type": "application/json"
					}
				})
				.then(response => {	
					if (response.data.error == 0) {
						console.log('Обновлено');
						this.$root.refreshPage();
					} else {
						alert(response.data.error_message);
					}
				}).catch(error => {
					alert('Ошибка получения данных');
					console.log(error);
				});
            },
            shiftDeadline: function() {
            	axios.post('api/addassigndeadline', {assignmentId: this.id, initiatorId: this.userId, deadline: this.$root.frmtDateIn(this.newDeadline, '18:00:00'), comment: this.comment, authorId: this.data.authorId }, {
					headers: {
						"Content-Type": "application/json"
					}
				})
				.then(response => {	
					if (response.data.error == 0) {
						console.log('Обновлено');
						this.$root.refreshPage();
					} else {
						alert(response.data.error_message);
					}
				}).catch(error => {
					alert('Ошибка получения данных');
					console.log(error);
				});
            },
            checkDeadline: function() {
            	for (let i = 0; i < this.data.deadline.length; i++) {
            		if (this.data.deadline[i].approved_at != null) {
            			this.actualDeadline = this.data.deadline[i].deadline;
            			break;
            		}
            	}
            },
            getVersions: function() {
            	axios.post('api/getassignmentversions', {id: this.id}, {
					headers: {
						"Content-Type": "application/json"
					}
				})
				.then(response => {	
					if (response.data.error == 0) {
						this.versions = response.data.result;
						this.spinVers = false;
					} else {
						alert(response.data.error_message);
					}
				}).catch(error => {
					alert('Ошибка получения данных');
					console.log(error);
				});
			},
			editModeToggle: function() {
				// this.$root.docsAll();
    //         	this.$root.assignsAll();
            	this.editMode = (this.editMode === true) ? false : true;
            	this.newType = this.data.typeId; 
		        this.newText = this.data.text;
		        this.newDescription = this.data.description;

				if (this.data.documentId != null) {
					if (this.data.documentBase.status.docstatusId == 2) {
						this.$root.baseRefusedDocId = {
							id: this.data.documentId,
							author: this.data.documentBase.authorData,
							description: this.data.documentBase.description,
							orderNum: this.data.documentBase.orderNum,
							creationDate: this.data.documentBase.creationDate,
							created_at: this.data.documentBase.created_at,
						};
					} else {
						this.$root.baseDocId = {
							id: this.data.documentId,
							author: this.data.documentBase.authorData,
							description: this.data.documentBase.description,
							orderNum: this.data.documentBase.orderNum,
							creationDate: this.data.documentBase.creationDate,
							created_at: this.data.documentBase.created_at,
						};
					}
				};
				if (this.data.baseId != null) {
					this.$root.baseAssignId = {
						id: this.data.baseId,
						text: this.data.base.text,
						created_at: this.data.base.created_at,
						author: this.data.base.author,
					};
				};
            },
            editAssign: function() {
            	let data = {
            		id: this.id,
            		typeId: this.data.typeId,
            		text: this.newText,
            		description: this.newDescription,
            	}; 
            	// if (this.baseDocId != null) {
            	// 	data.documentId = this.baseDocId.id;
            	// 	data.baseId = null;
            	// } else if (this.baseAssignId != null) {
            	// 	data.documentId = null;
            	// 	data.baseId = this.baseAssignId.id;
            	// } else {
            	// 	data.documentId = null;
            	// 	data.baseId = null;
            	// }

            	if ((this.$root.baseDocId != null) || (this.$root.baseRefusedDocId != null)) {
            		data.documentId = (this.$root.baseDocId != null) ? this.$root.baseDocId.id : this.$root.baseRefusedDocId.id;
            		data.baseId = null;
            	} else if (this.$root.baseAssignId != null) {
            		data.documentId = null;
            		data.baseId = this.$root.baseAssignId.id;
            	} else {
            		data.documentId = null;
            		data.baseId = null;
            	}
            	axios.post('api/updateassign', data, {
                        headers: {
                            "Content-Type": "application/json"
                        }
                    })
                    .then(response => {
                        if (response.data.error == 0) {
                            if (response.data.result != null) {
                                this.$root.refreshPage();
                            } else {
                                alert('Ошибка отправки данных12');
                            }
                        } else {
                            alert(response.data.error_message);
                        }
                    }).catch(error => {
                        alert('Ошибка получения данных13');
                        console.log(error);
                    });
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
		}
	}
</script>