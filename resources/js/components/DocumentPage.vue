<template>
	<div class="container card py-3">
		<div class="row d-flex">
			<div class="col-12 d-flex justify-content-between">
				<vue-link-backlist :type-id="docData.typeId"/>
				<div class="greytxt ta-right">
					<div>
						<vue-elem-timestamp :date-time="docData.created_at" :short="false" title="Дата и время внесения в систему"/>
					</div>
					<div v-if="docData.updated_at!=null">
						<vue-elem-timestamp :date-time="docData.updated_at"/>&nbsp;<span class="font-bold">(посл. ред.)</span>
					</div>
				</div>
			</div>
		</div>
		<br/>
		<!-- <tr v-if="docData.updated_at!=null">
			<th class="td_light-grey" scope="row">
				Дата и время последнего редактирования: 
			</th>
			<td>
				<span class="font-bold">
					<vue-elem-timestamp :date-time="docData.updated_at"/>
				</span>
			</td>
		</tr> -->
		<br/>
		<div class="row d-flex justify-content-between align-items-center">
			<div class="col-12 col-sm-6 docinfo">
				<div class="custom-h3">
					&nbsp;Карточка документа:
				</div>
				<br/>
				<template v-if="editMode==true">
					<textarea class="form-control" autofocus maxlength="512" placeholder="Описание документа (макс. 512 символов)" rows="4" v-model="newDescription"/>
				</template>
				<template v-else>
					<div class="back_white font-bold p-3">
						<span style="white-space: pre-line; font-size: 18px;">{{ docData.description }}</span>
					</div>
				</template>
				<br/>
				<template v-if="docData.authorId==userId&&docData.status[0].docstatusId!=2&&docData.status[0].docstatusId!=4">
					<div v-if="editMode==false" class="docinfo_edit agreerslist_edit cursor-point" @click="editModeToggle()" title="Редактировать информацию в карточке документа">
						<i class="fas fa-edit fa-2x"/>
					</div>
					<div v-if="editMode==true" class="docinfo_edit agreerslist_edit cursor-point" @click="editModeToggle()" title="Закрыть редактирование">
						<i class="fas fa-times fa-2x"/>
					</div>
				</template>
			</div>
			<div class="col-12 col-sm-6 d-flex justify-content-end">
				<template v-if="docData.removed==null">
					<template v-if="docData.status[0].docstatusId!=4">
						<template v-if="docData.typeId!=6">
							<div v-if="docData.status[0].docstatusId==1" class="d-flex flex-column align-items-end">
								<button class="btn btn-secondary no-round font-bold btn-shad" data-toggle="modal" data-target="#docStatusLogModal">
									<i class="fas fa-list"/>&nbsp;&nbsp;{{ docData.status[0].docStatusTitle }}
								</button>
								<br/>
								<button v-if="userId==docData.authorId" class="btn btn-danger no-round btn-shad" data-toggle="modal" data-target="#deleteDocModal"> 
									<i class="fas fa-trash-alt fa-lg"/>&nbsp;&nbsp;Удалить карточку
								</button>
								<br>
							</div>
							<div v-else-if="docData.status[0].docstatusId==2" class="d-flex flex-column align-items-end">
								<button class="btn btn-danger no-round font-bold btn-shad" data-toggle="modal" data-target="#docStatusLogModal">
									<i class="fas fa-list"/>&nbsp;&nbsp;{{ docData.status[0].docStatusTitle }}
								</button>
								<br/>
								<button v-if="userId==docData.authorId" class="btn btn-danger no-round btn-shad" data-toggle="modal" data-target="#deleteDocModal"> 
									<i class="fas fa-trash-alt fa-lg"/>&nbsp;&nbsp;Удалить карточку
								</button>
								<br/>
							</div>
							<div v-else-if="docData.status[0].docstatusId==3" class="d-flex flex-column align-items-end">
								<button class="btn btn-success no-round font-bold btn-shad" data-toggle="modal" data-target="#docStatusLogModal">
									<template v-if="docData.agreements.users.length==1">
										<i class="fas fa-list"/>&nbsp;&nbsp;Подписано
									</template>
									<template v-else>
										<i class="fas fa-list"/>&nbsp;&nbsp;{{ docData.status[0].docStatusTitle }}
									</template>
								</button>
								<br/>
								<template v-if="userId==docData.authorId">
									<button v-if="docData.agreements.users.length==1&&docData.agreements.users[0].userId==docData.authorId" class="btn btn-danger no-round btn-shad" data-toggle="modal" data-target="#deleteDocModal" title='Удалить карточку документа "без согласования"'> 
										<i class="fas fa-trash-alt fa-lg"/>&nbsp;&nbsp;Удалить карточку
									</button>
								</template>
								<br/>
								<button v-if="docData.authorId==userId" class="btn btn-danger no-round btn-shad" data-toggle="modal" data-target="#addInArchiveModal">
									Отправить в архив
								</button>
							</div>
						</template>
						<template v-else>
							<button v-if="docData.authorId==userId" class="btn btn-danger no-round btn-shad" data-toggle="modal" data-target="#addInArchiveModal"> 
								Отправить в архив
							</button>
						</template>
					</template>
					<template v-else>
						<div class="d-flex flex-column align-items-end">
							<button class="btn btn-secondary no-round" disabled>
								<i class="fas fa-file-archive fa-lg"/> &nbsp;В архиве
							</button>
							<br/>
							<button class="btn btn-primary no-round btn-shad" data-toggle="modal" data-target="#getFromArchiveModal">
								<i class="fas fa-box-open fa-lg"/>&nbsp;Восстановить из архива
							</button>
						</div>
					</template>
				</template>
				<template v-else>
					<div class="d-flex flex-column align-items-end">
						<button class="btn btn-danger no-round" disabled> 
							<i class="fas fa-times fa-lg"/>&nbsp;&nbsp;Документ удалён
						</button>
						<br>
					</div>
				</template>
			</div>
		</div>
		<br/>
		<div class="row">
			<div class="col-12 col-lg-6">
				<div class="flex-fill">
					<div v-if="docData.type!=null">
						<div class="custom-h3">
							&nbsp;{{ docData.type.title }}
						</div>
					</div>
					<div v-if="acqDoc!==null" class="alert alert-primary d-flex justify-content-between align-items-center my-2" role="alert">
					  	<div>
					  		Документ прислан Вам на ознакомление...
					  	</div>
					  	<div>
					  		<button class="btn btn-info no-round box-shad font-bold font-up" @click="makeSeenAcqDoc()" title="Нажмите для ознакомления">
					  			Ознакомиться
					  		</button>
					  	</div>
					</div>
					<template v-if="docData.agreements!=false&&docData.removed==null">
						<template v-if="isAgree!=0">
							<div class="alert alert-success d-flex flex-column" role="alert">
							  	<div>
							  		Документ прислан Вам на согласование...
							  	</div>
								<div class="py-3">
			                        <textarea v-if="needComment==false" class="form-control" name="comment" required autocomplete="comment" autofocus maxlength="500" placeholder="Ваш комментарий (макс. 500 символов)" id="comment" rows="3" v-model="comment"/>
			                        <textarea v-else class="form-control redrow font-bold" name="comment" required autocomplete="comment" autofocus  maxlength="255" placeholder="Укажите причину!!!" id="comment" rows="3" @click="refreshNote()"/>
			                    </div>
			                    <div v-if="docData.agreements.deadline!=null">
			                    	Срок согласования до:&nbsp;
			                    	<span style="background-color: #fff" class="p-1">
			                    		<vue-td-dateleft :status="docData.status[0].docstatusId" :date="docData.agreements.deadline"/>
			                    	</span>
			                    </div>
							  	<br/>
							  	<div class="justify-content-center d-flex">
				                    <button type="button" class="btn btn-primary no-round font-up flex-sm-fill box-shad mr-2" @click="approveAgreement(actualAgreement, isAgree, userId, order)">
				                    	Согласовать
				                    </button>
				                    <button type="button" class="btn btn-danger no-round font-up flex-sm-fill box-shad ml-2" data-toggle="modal" data-target="#refuseDocModal">
				                        Отклонить
				                    </button>
				                </div>
							</div>
						</template>
					</template>
<!-- 					<button v-if="docData.status[0].docstatusId==1||docData.status[0].docstatusId==2" @click="openUpd()" class="btn btn-update wide_btn">
						<i class="fas fa-wrench fa-lg"/>&nbsp;&nbsp;Отправить на доработку
		        	</button> -->
					<!-- <br/> -->
					<!-- таблица -->
					<template v-if="docData.authorId==userId">
						<vue-template-editdocfile v-if="docData.status[0].docstatusId==1"/>
						<vue-template-editdocfile v-else-if="docData.status[0].docstatusId==3&&docData.agreements.users.length==1&&docData.agreements.users[0].userId==docData.authorId"/>
					</template>
					<div class="table_scroll_581_y">
						<table class="table table-bordered my-1">
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
										Дата документа:
									</th>
									<td v-if="editMode==true">
										<vue-datepicker id="contractDate" v-model="newCreationDate" valueType="format" format="DD.MM.YYYY" class="cursor-point" :lang="lang" placeholder="ДД.ММ.ГГГГ"/>
									</td>
									<td v-else>
										<span v-if="docData.creationDate!=null" class="font-bold">
											<vue-elem-timestamp :date-time="docData.creationDate" :short="true"/>
										</span>
										<span v-else class="font-bold">
											Не указано
										</span>
									</td>
								</tr>
								<tr>
									<th class="td_light-grey" scope="row">
										Номер документа: 
									</th>
									<td v-if="editMode==true">
										<input v-if="docData.orderNum!=null" type="text" class="form-control" placeholder="Введите номер документа" v-model="newOrderNum" maxlength="64">
										<input v-else type="text" class="form-control" placeholder="Введите номер документа" v-model="newOrderNum" maxlength="64">
									</td>
									<td v-else>
										<span v-if="docData.orderNum!=null" class="font-bold">
											{{ docData.orderNum }}
										</span>
										<span v-else class="font-bold">
											Не указано
										</span>
									</td>
								</tr>
								<tr v-if="docData.authorData!=null">
									<th class="td_light-grey" scope="row">
										Создал:
									</th>
									<td class="font-bold">
										<vue-link-userfio :data="docData.authorData" :short="true"/>
									</td>
								</tr>
								<tr v-if="docData.authorId!=docData.recorderId">
									<th class="td_light-grey" scope="row">
										Создал: 
									</th>
									<td class="font-bold">
										<vue-link-userfio :data="docData.recorder" :short="true"/>
									</td>
								</tr>
								<tr v-if="docData.authorId!=docData.recorderId">
									<th class="td_light-grey" scope="row">
										Создал: 
									</th>
									<td class="font-bold">
										<vue-link-userfio :data="docData.recorder" :short="true"/>
									</td>
								</tr>
								<template v-if="docData.agreements!=false">
									<template v-if="docData.agreements.users.length!=1&&docData.agreements.users[0].userId!=docData.authorId">
										<tr v-if="docData.created_at!=null">
											<th class="td_light-grey" scope="row">
												Дата отправки на согласование: 
											</th>
											<td class="font-bold">
												<vue-elem-timestamp :date-time="docData.created_at" :short="true"/>
											</td>
										</tr>
									</template>
								</template>
<!-- 								<tr v-if="docData.updated_at!=null">
									<th class="td_light-grey" scope="row">
										Дата последнего изменения: 
									</th>
									<td class="font-bold">
										<vue-elem-timestamp :date-time="docData.updated_at" :short="true"/>
									</td>
								</tr> -->
								<template v-if="editMode==false">
									<template v-if="docData.baseDoc!=null">
										<tr v-if="docData.baseDoc.status.docstatusId==2">
											<th class="td_light-grey" scope="row">
												Замечание-основание: 
											</th>
											<td>
												<span>
													{{ docData.baseDoc.description }}
												</span>
												<br/>
												<a class="font-bold" :href="'/document-'+docData.baseDoc.id" target="_blank">Ссылка на карточку документа&nbsp;<i class="fas fa-external-link-square-alt fa-lg"/></a>
											</td>
										</tr>
										<tr v-else>
											<th class="td_light-grey" scope="row">
												Документ-основание: 
											</th>
											<td>
												<span>
													{{ docData.baseDoc.description }}
												</span>
												<br/>
												<a class="font-bold" :href="'/document-'+docData.baseDoc.id" target="_blank">Ссылка на карточку документа&nbsp;<i class="fas fa-external-link-square-alt fa-lg"/></a>
											</td>
										</tr>
									</template>
									<template v-else-if="docData.baseAssignment!=null">
										<tr>
											<th class="td_light-grey" scope="row">
												Поручение-основание: 
											</th>
											<td>
												<span>
													{{ docData.baseAssignment.text }}
												</span>
												<br/>
												<a class="font-bold" :href="'/assignment-'+docData.baseAssignment.id">Ссылка на поручение&nbsp;<i class="fas fa-external-link-square-alt fa-lg" target="_blank"/></a>
											</td>
										</tr>
									</template>
								</template>
								<template v-if="docData.typeId==2||docData.typeId==6||docData.typeId==7||docData.typeId==14||docData.typeId==15">
									<tr v-if="editMode==true">
										<td colspan="100%">
											<vue-template-diruseradd :doc-type="docData.typeId"/>
										</td>
									</tr>
									<tr v-else>
										<th v-if="docData.typeId==2" class="td_light-grey" scope="row">
											Заказчик (контрагент)
										</th>
										<th v-else class="td_light-grey" scope="row">
											Адресат документа/Контрагент:
										</th>
										<td>
											<span v-if="docData.diruser!=false" class="font-bold">
												{{ $root.makeFio(docData.diruser.user.surname, docData.diruser.user.firstname, docData.diruser.user.patronymic) }}
											</span>
											<span v-else class="font-bold">
												Не указано
											</span>
										</td>
									</tr>
								</template>
								<template v-if="docData.typeId==2">
<!-- 									<tr>
										<th class="td_light-grey" scope="row">
											Номер договора: 
										</th>
										<td v-if="editMode==true">
											<input v-if="docData.orderNum!=null" type="text" class="form-control" placeholder="Введите номер договора" v-model="newOrderNum" maxlength="64">
											<input v-else type="text" class="form-control" placeholder="Введите номер договора" v-model="newOrderNum" maxlength="64">
										</td>
										<td v-else>
											<span v-if="docData.orderNum!=null" class="font-bold">
												{{ docData.orderNum }}
											</span>
											<span v-else class="font-bold">
												Не указано
											</span>
										</td>
									</tr> -->
									<tr>
										<th class="td_light-grey" scope="row">
											Наименование: 
										</th>
										<td v-if="editMode==true">
											<input v-if="docData.name!=null" type="text" class="form-control" placeholder="Введите наименование" v-model="newName">
											<input v-else type="text" class="form-control" placeholder="Введите наименование" v-model="newName">
										</td>
										<td v-else>
											<span v-if="docData.name!=null" class="font-bold">
												{{ docData.name }}
											</span>
											<span v-else class="font-bold">
												Не указано
											</span>
										</td>
									</tr>
<!-- 									<tr>
										<th class="td_light-grey" scope="row">
											Дата договора:
										</th>
										<td v-if="editMode==true">
											<vue-datepicker id="contractDate" v-model="newCreationDate" valueType="format" format="DD.MM.YYYY" class="cursor-point" :lang="lang" placeholder="ДД.ММ.ГГГГ"/>
										</td>
										<td v-else>
											<span v-if="docData.creationDate!=null" class="font-bold">
												<vue-elem-timestamp :date-time="docData.creationDate" :short="true"/>
											</span>
											<span v-else class="font-bold">
												Не указано
											</span>
										</td>
									</tr> -->
									<tr>
										<th class="td_light-grey" scope="row">
											Дата закрытия:
										</th>
										<td v-if="editMode==true">
											<vue-datepicker id="contractDate" v-model="newDocCloseDate" valueType="format" format="DD.MM.YYYY" class="cursor-point" :lang="lang" placeholder="ДД.ММ.ГГГГ"/>
										</td>
										<td v-else>
											<span v-if="docData.closeDate!=null" class="font-bold">
												<vue-elem-timestamp :date-time="docData.closeDate" :short="true"/>
											</span>
											<span v-else class="font-bold">
												Не указано
											</span>
										</td>
									</tr>
									<tr>
										<th class="td_light-grey" scope="row">
											Соисполнитель: 
										</th>
										<td v-if="editMode==true">
											<input v-if="docData.coExecutor!=null" type="text" class="form-control" placeholder="Введите имя соисполнителя" v-model="newCoExecutor">
											<input v-else type="text" class="form-control" placeholder="Введите имя соисполнителя" v-model="newCoExecutor">
										</td>
										<td v-else>
											<span v-if="docData.coExecutor!=null" class="font-bold">
												{{ docData.coExecutor }}
											</span>
											<span v-else class="font-bold">
												Не указано
											</span>
										</td>
									</tr>
									<tr>
										<th class="td_light-grey" scope="row">
											Краткое наименование: 
										</th>
										<td v-if="editMode==true">
											<input v-if="docData.colName!=null" type="text" class="form-control" placeholder="Введите разговорное название" v-model="newColName">
											<input v-else type="text" class="form-control" placeholder="Введите разговорное название" v-model="newColName">
										</td>
										<td v-else>
											<span v-if="docData.colName!=null" class="font-bold">
												{{ docData.colName }}
											</span>
											<span v-else class="font-bold">
												Не указано
											</span>
										</td>
									</tr>
									<tr>
										<th class="td_light-grey" scope="row">
											Сумма по договору: 
										</th>
										<td v-if="editMode==true">
											<input v-if="docData.sumContract!=null" type="text" class="form-control" placeholder="Введите сумму по договору" v-model="newSumContract">
											<input v-else type="text" class="form-control" placeholder="Введите сумму по договору" v-model="newSumContract">
										</td>
										<td v-else>
											<span v-if="docData.sumContract!=null" class="font-bold">
												{{ docData.sumContract }}
											</span>
											<span v-else class="font-bold">
												Не указано
											</span>
										</td>
									</tr>
									<tr>
										<th class="td_light-grey" scope="row">
											Этапы:
										</th>
										<td v-if="editMode==true">
											<textarea class="form-control" name="contractSteps" required autocomplete="contractSteps" autofocus max="255" placeholder="Введите этапы договора" id="contractsteps" rows="4" v-model="newPhases"/>
										</td>
										<td v-else>
											<div v-if="docData.phases==null">
												<span class="font-bold">
													Не указано
												</span>
											</div>
											<div v-else class="back_white p-3">
												<span style="white-space: pre-line">
													{{ docData.phases }}
												</span>
											</div>
										</td>
									</tr>
								</template>
								<template v-else-if="docData.typeId==6">
<!-- 									<tr>
										<th class="td_light-grey" scope="row">
											Входящий номер: 
										</th>
										<td v-if="editMode==true">
											<input v-if="docData.orderNum!=null" type="text" class="form-control" placeholder="Введите входящий номер" v-model="newOrderNum" maxlength="64">
											<input v-else type="text" class="form-control" placeholder="Введите входящий номер" v-model="newOrderNum" maxlength="64">
										</td>
										<td v-else>
											<span v-if="docData.orderNum!=null" class="font-bold">
												{{ docData.orderNum }}
											</span>
											<span v-else class="font-bold">
												Не указано
											</span>
										</td>
									</tr> -->
									<tr>
										<th class="td_light-grey" scope="row">
											Номер у отправителя: 
										</th>
										<td v-if="editMode==true">
											<input v-if="docData.outerNum!=null" type="text" class="form-control" placeholder="Введите номер у отправителя" v-model="newOuterNum" maxlength="32">
											<input v-else type="text" class="form-control" placeholder="Введите номер у отправителя" v-model="newOuterNum" maxlength="32">
										</td>
										<td v-else>
											<span v-if="docData.outerNum!=null" class="font-bold">
												{{ docData.outerNum }}
											</span>
											<span v-else class="font-bold">
												Не указано
											</span>
										</td>
									</tr>
									<tr>
										<th class="td_light-grey" scope="row">
											Дата отправления:
										</th>
										<td v-if="editMode==true">
											<vue-datepicker v-model="newOuterDate" valueType="format" format="DD.MM.YYYY" class="cursor-point" :lang="lang" placeholder="ДД.ММ.ГГГГ"/>
										</td>
										<td v-else>
											<span v-if="docData.outerDate!=null" class="font-bold">
												<vue-elem-timestamp :date-time="docData.outerDate" :short="true"/>
											</span>
											<span v-else class="font-bold">
												Не указано
											</span>
										</td>
									</tr>
									<tr>
										<th class="td_light-grey" scope="row">
											Примечание: 
										</th>
										<td v-if="editMode==true">
											<input v-if="docData.note!=null" type="text" class="form-control" placeholder="Введите примечание" v-model="newNote">
											<input v-else type="text" class="form-control" placeholder="Введите примечание" v-model="newNote">
										</td>
										<td v-else>
											<span v-if="docData.note!=null" class="font-bold">
												{{ docData.note }}
											</span>
											<span v-else class="font-bold">
												Не указано
											</span>
										</td>
									</tr>
<!-- 									<tr>
										<th class="td_light-grey" scope="row">
											Дата получения письма:
										</th>
										<td v-if="editMode==true">
											<vue-datepicker id="contractDate" v-model="newCreationDate" valueType="format" format="DD.MM.YYYY" class="cursor-point" :lang="lang" placeholder="ДД.ММ.ГГГГ"/>
										</td>
										<td v-else>
											<span v-if="docData.creationDate!=null" class="font-bold">
												<vue-elem-timestamp :date-time="docData.creationDate" :short="true"/>
											</span>
											<span v-else class="font-bold">
												Не указано
											</span>
										</td>
									</tr> -->
									<tr>
										<th class="td_light-grey" scope="row">
											Срок исполнения:
										</th>
										<td v-if="editMode==true">
											<vue-datepicker id="contractDate" v-model="newDocCloseDate" valueType="format" format="DD.MM.YYYY" class="cursor-point" :lang="lang" placeholder="ДД.ММ.ГГГГ"/>
										</td>
										<td v-else class="font-bold">
											<template v-if="docData.closeDate!=null">
												<vue-elem-timestamp :date-time="docData.closeDate" :short="true"/>
											</template>
											<template v-else>
												<span class="font-bold">
													Не указано
												</span>
											</template>
										</td>
									</tr>
								</template>
								<template v-else-if="docData.typeId==12">
<!-- 									<tr>
										<th class="td_light-grey" scope="row">
											Номер уведомления: 
										</th>
										<td v-if="editMode==true">
											<input v-if="docData.orderNum!=null" type="text" class="form-control" placeholder="Введите номер уведомления" v-model="newOrderNum" maxlength="64">
											<input v-else type="text" class="form-control" placeholder="Введите номер уведомления" v-model="newOrderNum" maxlength="64">
										</td>
										<td v-else>
											<span v-if="docData.orderNum!=null" class="font-bold">
												{{ docData.orderNum }}
											</span>
											<span v-else class="font-bold">
												Не указано
											</span>
										</td>
									</tr> -->
<!-- 									<tr>
										<th class="td_light-grey" scope="row">
											Дата создания:
										</th>
										<td v-if="editMode==true">
											<vue-datepicker id="contractDate" v-model="newCreationDate" valueType="format" format="DD.MM.YYYY" class="cursor-point" :lang="lang" placeholder="ДД.ММ.ГГГГ"/>
										</td>
										<td v-else>
											<span v-if="docData.creationDate!=null" class="font-bold">
												<vue-elem-timestamp :date-time="docData.creationDate" :short="true"/>
											</span>
											<span v-else class="font-bold">
												Не указано
											</span>
										</td>
									</tr> -->
									<tr>
										<th class="td_light-grey" scope="row">
											Автор: 
										</th>
										<td v-if="editMode==true">
											<input v-if="docData.author!=null" type="text" class="form-control" placeholder="Введите имя автора" v-model="newAuthor">
											<input v-else type="text" class="form-control" placeholder="Введите имя автора" v-model="newAuthor">
										</td>
										<td v-else>
											<span v-if="docData.author!=null" class="font-bold">
												{{ docData.author }}
											</span>
											<span v-else class="font-bold">
												Не указано
											</span>
										</td>
									</tr>
									<tr>
										<th class="td_light-grey" scope="row">
											Подписант: 
										</th>
										<td v-if="editMode==true">
											<input v-if="docData.signatory!=null" type="text" class="form-control" placeholder="Введите подписанта" v-model="newSignatory">
											<input v-else type="text" class="form-control" placeholder="Введите подписанта" v-model="newSignatory">
										</td>
										<td v-else>
											<span v-if="docData.signatory!=null" class="font-bold">
												{{ docData.signatory }}
											</span>
											<span v-else class="font-bold">
												Не указано
											</span>
										</td>
									</tr>
									<tr>
										<th class="td_light-grey" scope="row">
											Дата ознакомления:
										</th>
										<td v-if="editMode==true">
											<vue-datepicker id="acqDate" v-model="newAcqDate" valueType="format" format="DD.MM.YYYY" class="cursor-point" :lang="lang" placeholder="ДД.ММ.ГГГГ"/>
										</td>
										<td v-else>
											<span v-if="docData.acqDate!=null" class="font-bold">
												<vue-elem-timestamp :date-time="docData.acqDate" :short="true"/>
											</span>
											<span v-else class="font-bold">
												Не указано
											</span>
										</td>
									</tr>
								</template>
								<template v-else-if="docData.typeId==9">
<!-- 									<tr>
										<th class="td_light-grey" scope="row">
											Номер приказа по ОД: 
										</th>
										<td v-if="editMode==true">
											<input v-if="docData.orderNum!=null" type="text" class="form-control" placeholder="Введите номер приказа по ОД" v-model="newOrderNum" maxlength="64">
											<input v-else type="text" class="form-control" placeholder="Введите номер приказа по ОД" v-model="newOrderNum" maxlength="64">
										</td>
										<td v-else>
											<span v-if="docData.orderNum!=null" class="font-bold">
												{{ docData.orderNum }}
											</span>
											<span v-else class="font-bold">
												Не указано
											</span>
										</td>
									</tr> -->
<!-- 									<tr>
										<th class="td_light-grey" scope="row">
											Дата создания приказа:
										</th>
										<td v-if="editMode==true">
											<vue-datepicker id="contractDate" v-model="newCreationDate" valueType="format" format="DD.MM.YYYY" class="cursor-point" :lang="lang" placeholder="ДД.ММ.ГГГГ"/>
										</td>
										<td v-else>
											<span v-if="docData.creationDate!=null" class="font-bold">
												<vue-elem-timestamp :date-time="docData.creationDate" :short="true"/>
											</span>
											<span v-else class="font-bold">
												Не указано
											</span>
										</td>
									</tr> -->
									<tr>
										<th class="td_light-grey" scope="row">
											Исполнитель: 
										</th>
										<td v-if="editMode==true">
											<vue-multiselect v-model="newExecutor" :options="allUsersList" :multiple="false" :close-on-select="false" :clear-on-select="true" :preserve-search="true" placeholder="Поиск пользователей" :custom-label="$root.namesFull" track-by="id" :preselect-first="false" select-label="Enter чтобы выбрать" deselect-label="Enter чтобы удалить" selected-label="Выбрано">
												<template slot="noOptions" slot-scope="props">
													Список пуст.
												</template>
												<template slot="noResult" slot-scope="props">
													Ничего не найдено...
												</template>
											</vue-multiselect>
										</td>
										<td v-else>
											<span v-if="docData.executor!=null" class="font-bold">
												<vue-link-userfio :data="docData.executorUser" :short="true"/>
											</span>
											<span v-else class="font-bold">
												Не указано
											</span>
										</td>
									</tr>
								</template>
								<template v-else-if="docData.typeId==7">
<!-- 									<tr>
										<th class="td_light-grey" scope="row">
											Номер исходящего письма: 
										</th>
										<td v-if="editMode==true">
											<input v-if="docData.orderNum!=null" type="text" class="form-control" placeholder="Введите номер исходящего письма" v-model="newOrderNum" maxlength="64">
											<input v-else type="text" class="form-control" placeholder="Введите номер исходящего письма" v-model="newOrderNum" maxlength="64">
										</td>
										<td v-else>
											<span v-if="docData.orderNum!=null" class="font-bold">
												{{ docData.orderNum }}
											</span>
											<span v-else class="font-bold">
												Не указано
											</span>
										</td>
									</tr> -->
<!-- 									<tr>
										<th class="td_light-grey" scope="row">
											Дата письма:
										</th>
										<td v-if="editMode==true">
											<vue-datepicker id="contractDate" v-model="newCreationDate" valueType="format" format="DD.MM.YYYY" class="cursor-point" :lang="lang" placeholder="ДД.ММ.ГГГГ"/>
										</td>
										<td v-else>
											<span v-if="docData.creationDate!=null" class="font-bold">
												<vue-elem-timestamp :date-time="docData.creationDate" :short="true"/>
											</span>
											<span v-else class="font-bold">
												Не указано
											</span>
										</td>
									</tr> -->
									<tr>
										<th class="td_light-grey" scope="row">
											Номер у получателя: 
										</th>
										<td v-if="editMode==true">
											<input v-if="docData.outerNum!=null" type="text" class="form-control" placeholder="Введите номер у получателя" v-model="newOuterNum" maxlength="32">
											<input v-else type="text" class="form-control" placeholder="Введите номер у получателя" v-model="newOuterNum" maxlength="32">
										</td>
										<td v-else>
											<span v-if="docData.outerNum!=null" class="font-bold">
												{{ docData.outerNum }}
											</span>
											<span v-else class="font-bold">
												Не указано
											</span>
										</td>
									</tr>
									<tr>
										<th class="td_light-grey" scope="row">
											Дата получения:
										</th>
										<td v-if="editMode==true">
											<vue-datepicker v-model="newOuterDate" valueType="format" format="DD.MM.YYYY" class="cursor-point" :lang="lang" placeholder="ДД.ММ.ГГГГ"/>
										</td>
										<td v-else>
											<span v-if="docData.outerDate!=null" class="font-bold">
												<vue-elem-timestamp :date-time="docData.outerDate" :short="true"/>
											</span>
											<span v-else class="font-bold">
												Не указано
											</span>
										</td>
									</tr>
									<tr>
										<th class="td_light-grey" scope="row">
											Кому на исполнение: 
										</th>
										<td v-if="editMode==true">
											<input v-if="docData.letterExecutor!=null" type="text" class="form-control" placeholder="Введите кому отправить на исполнение" v-model="newLetterExecutor">
											<input v-else type="text" class="form-control" placeholder="Введите кому отправить на исполнение" v-model="newLetterExecutor">
										</td>
										<td v-else>
											<span v-if="docData.letterExecutor!=null" class="font-bold">
												{{ docData.letterExecutor }}
											</span>
											<span v-else class="font-bold">
												Не указано
											</span>
										</td>
									</tr>
									<tr>
										<th class="td_light-grey" scope="row">
											Примечание: 
										</th>
										<td v-if="editMode==true">
											<input v-if="docData.note!=null" type="text" class="form-control" placeholder="Введите примечание" v-model="newNote">
											<input v-else type="text" class="form-control" placeholder="Введите примечание" v-model="newNote">
										</td>
										<td v-else>
											<span v-if="docData.note!=null" class="font-bold">
												{{ docData.note }}
											</span>
											<span v-else class="font-bold">
												Не указано
											</span>
										</td>
									</tr>
								</template>
								<template v-else>
<!-- 									<tr>
										<th class="td_light-grey" scope="row">
											Номер документа: 
										</th>
										<td v-if="editMode==true">
											<input v-if="docData.orderNum!=null" type="text" class="form-control" placeholder="Введите номер документа" v-model="newOrderNum" maxlength="64">
											<input v-else type="text" class="form-control" placeholder="Введите номер документа" v-model="newOrderNum" maxlength="64">
										</td>
										<td v-else>
											<span v-if="docData.orderNum!=null" class="font-bold">
												{{ docData.orderNum }}
											</span>
											<span v-else class="font-bold">
												Не указано
											</span>
										</td>
									</tr> -->
								</template>
<!-- 								<tr v-if="docData.created_at!=null">
									<th class="td_light-grey" scope="row">
										Дата и время внесения в систему: 
									</th>
									<td class="font-bold">
										<vue-elem-timestamp :date-time="docData.created_at" :short="false"/>
									</td>
								</tr> -->
<!-- 								<tr v-if="docData.updated_at!=null">
									<th class="td_light-grey" scope="row">
										Дата и время последнего редактирования: 
									</th>
									<td>
										<span class="font-bold">
											<vue-elem-timestamp :date-time="docData.updated_at"/>
										</span>
									</td>
								</tr> -->
							</tbody>
						</table>
					</div>
					<div class="row" v-if="editMode==true">
						<div class="col-12">
							<hr/>
							<template v-if="docData.baseDoc!=null">
								<vue-template-basedocassign v-if="docData.baseDoc.status.docstatusId==2" :opened="true" type="1" :base-tab="3"/>
								<vue-template-basedocassign v-else :opened="true" type="1" :base-tab="1"/>
							</template>
							<template v-else-if="docData.baseAssignmentId!=null">
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
				</div>
				<div v-if="docData.status[0].docstatusId==2">
					<span class="alert-red font-up">Отклонено</span><br/>
					<div v-for="item in docData.agreements.users">
						<div v-if="item.refused_at!=null">
							Комментарий&nbsp;(<vue-link-userfio :data="item.user" :short="true"/>): 
							<div v-if="item.note!=null" class="back_white p-3 mt-1">
								<span style="white-space: pre-line">{{ item.note }}</span>
							</div>
							<span v-else>...</span>
						</div>
					</div>
				</div>
				<br/>
				<template v-if="docData.removed==null">
					<template v-if="docData.agreements!=false">
						<vue-template-acquaintancesend v-if="docData.status[0].docstatusId!=5" :users-list="usersList" :send-message="sendMessage" :new-send="newSend"/>
					</template>
				</template>
				<div v-if="docData.removed==null&&docData.status[0].docstatusId!=2&&docData.agreements!=false" class="d-flex justify-content-center justify-content-md-start">
					<div v-if="docData.agreements.users.length!=1&&docData.agreements.users[0].userId!=docData.authorId" class="btn btn-success no-round font-bold btn-shad width-sm-inherit" data-toggle="modal" data-target="#pdf__agreementslist">
						<i class="fas fa-file-pdf fa-lg"/>&nbsp;Предпросмотр согласования
					</div>
				</div>
				<!-- <br/> -->
				<div class="btn btn-info wide_btn no-round font-bold btn-shad width-sm-inherit my-2" @click="$root.openRelsFunc(true, id, 'doc')">
					<i class="fas fa-link fa-lg"/>&nbsp;Добавить связи
				</div>
				<br/>
				<br/>
				<template v-if="fileType==1||fileType==2">
					<template v-if="docData.agreements!=false">
						<template v-if="docData.status[0].docstatusId==3||docData.status[0].docstatusId==4">
							<div class="row" v-if="docData.agreements.users.length==1&&docData.agreements.users[0].userId==docData.authorId">
								<div class="col-md-12" v-if="docData.agreements!=false">
									<div class="input-group mb-3">
										<div class="input-group-prepend">
											<div class="input-group-text">
												<input id="agreelistType1" v-model="agreelistType" name="agreelistType" type="radio" aria-label="Radio button for following text input" value="1">
											</div>
										</div>
										<label class="form-control cursor-point mb-0" for="agreelistType1">
											Штамп согласования на титульном листе
										</label>
									</div>
								</div>	
							</div>
							<div v-else class="row">
								<div class="col-md-12" v-if="docData.agreements!=false">
									<div class="input-group mb-3">
										<div class="input-group-prepend">
											<div class="input-group-text">
												<input id="agreelistType1" v-model="agreelistType" name="agreelistType" type="radio" aria-label="Radio button for following text input" value="1">
											</div>
										</div>
										<label class="form-control cursor-point mb-0" for="agreelistType1">
											Штамп согласования на титульном листе
										</label>
									</div>
									<div class="input-group">
										<div class="input-group-prepend">
											<div class="input-group-text">
												<input id="agreelistType2" v-model="agreelistType" name="agreelistType" type="radio" aria-label="Radio button for following text input" value="2">
											</div>
										</div>
										<label class="form-control cursor-point mb-0" for="agreelistType2">
											Лист согласования
										</label>
									</div>
								</div>	
							</div>
							<br/>
							<div class="row mb-2" v-if="docData.agreements!=false">
								<div v-if="agreelistType==1" class="col-md-12">
									<template v-if="docData.agreements.users.length==1&&docData.agreements.users[0].userId==docData.authorId">
										<a :href="'api/getagreerstampswithout?id='+ id" class="btn btn-info no-round font-bold font-up btn_larger box-shad" target="_blank">
											<i class="fas fa-file-pdf fa-lg"/>&nbsp;Сохранить со штампом согласований
										</a>
									</template>
									<template v-else>
										<a :href="'api/getagreerstamps?id='+ id" class="btn btn-info no-round font-bold font-up btn_larger box-shad" target="_blank">
										<i class="fas fa-file-pdf fa-lg"/>&nbsp;Сохранить со штампом согласований
									</a>
									</template>
									<!-- makeagreersstampwithoutusers -->
								</div>	
								<div v-else-if="agreelistType==2" class="col-md-12">
									<!-- <a :href="'api/getagreerlist?id='+ id" class="btn btn-success no-round font-bold font-up btn_larger box-shad" target="_blank">
										<i class="fas fa-file-pdf fa-lg"/>&nbsp;Сохранить лист согласования
									</a> -->
									<button @click="makePDF()" class="btn btn-success no-round font-bold font-up btn_larger box-shad" target="_blank">
										<i class="fas fa-file-pdf fa-lg"/>&nbsp;Сохранить лист согласования
									</button>
								</div>
							</div>
						</template>
						<template v-else-if="docData.status[0].docstatusId==2">
							<div class="row">
								<div class="col-md-12" v-if="docData.agreements!=false">
									<div class="input-group mb-3">
										<div class="input-group-prepend">
											<div class="input-group-text">
												<input id="agreelistType1" v-model="agreelistType" name="agreelistType" type="radio" aria-label="Radio button for following text input" value="1">
											</div>
										</div>
										<label class="form-control cursor-point mb-0" for="agreelistType1">
											Штамп согласования на титульном листе
										</label>
									</div>
								</div>	
							</div>
							<div class="row mb-2">
								<div class="col-md-12">
									<!-- <a :href="'api/getagreerlist?id='+ id" class="btn btn-success no-round font-bold font-up btn_larger box-shad" target="_blank">
										<i class="fas fa-file-pdf fa-lg"/>&nbsp;Сохранить лист согласования
									</a> -->
									<button @click="makePDF()" class="btn btn-success no-round font-bold font-up btn_larger box-shad" target="_blank">
										<i class="fas fa-file-pdf fa-lg"/>&nbsp;Сохранить лист согласования
									</button>
								</div>
							</div>
						</template>
					</template>
				</template>
				<template v-else-if="fileType==3||fileType==0">
					<template v-if="docData.agreements!=false">
						<template v-if="docData.status[0].docstatusId==3||docData.status[0].docstatusId==4">
							<div class="input-group" v-if="docData.agreements.users.length>1&&docData.authorId!=docData.agreements.users[0].userId">
								<div class="input-group-prepend">
									<div class="input-group-text">
										<input id="agreelistType2" name="agreelistType" type="radio" aria-label="Radio button for following text input" value="2" checked>
									</div>
								</div>
								<label class="form-control cursor-point mb-0" for="agreelistType2">
									Лист согласования
								</label>
							</div>
							<br/>
							<div class="row mb-2" v-if="docData.agreements!=false">
								<div class="col-md-12">
									<a :href="'api/getagreerlist?id='+ id" class="btn btn-success no-round font-bold font-up btn_larger box-shad" target="_blank">
										<i class="fas fa-file-pdf fa-lg"/>&nbsp;Сохранить лист согласования
									</a>
								</div>
							</div>
						</template>
					</template>
				</template>
			</div>
			<div class="col-12 col-lg-6 d-flex flex-column align-items-center align-items-lg-end">
				<div v-if="fileType==1 || fileType==2">
					<div>
						<vue-frame-preview :file="docData.file"/>
					</div>
				</div>
				<div v-else-if="fileType==3" class="imgformatfile d-flex flex-column justify-content-center align-items-center p-4">
					<div data-toggle="modal" data-target="#documentImageModal" class="cursor-point box-shad_black" v-if="docData.file.file">
						<!-- <img v-if="$root.storage=='coresar'" :src="'/storage/app/public/images/thumbnails/' + docData.file.file +'_thumb.'+ docData.file.format" :alt="docData.file.file">
						<img v-else :src="'/storage/images/thumbnails/' + docData.file.file +'_thumb.'+ docData.file.format" :alt="docData.file.file"> -->
<!-- 						<img v-if="$root.storage=='coresar'" :src="'/storage/app/public/images/' +'.'+ docData.file.file + docData.file.format" :alt="docData.file.file" width="210">
						<img v-else :src="'/storage/images/' + docData.file.file +'.'+ docData.file.format" :alt="docData.file.file" width="210"> -->
						<img :src="'/storage/images/' + docData.file.file +'.'+ docData.file.format" :alt="docData.file.file" width="210">
					</div>
					<div v-else>Файл отсутствует...</div>
				</div>
				<div v-else class="noformatfile d-flex flex-column justify-content-center align-items-center p-4">
					<span class="font-bold">Файл:</span>
					<br/>
					<span v-if="docData.file.file">{{ docData.file.file }}.{{ docData.file.format }}</span>
					<span v-else>Файл отсутствует...</span>
				</div>
				<br/>
				<br/>
				<div v-if="docData.removed==null" class="d-flex flex-column align-items-end">
<!-- 					<template v-if="docData.status[0].docstatusId==1">
						<button class="btn btn-secondary no-round font-bold btn-shad">
							<i class="fas fa-edit fa-lg"/>&nbsp;Добавить файл
						</button>
					</template> -->
					<div class="d-flex flex-column align-items-end" v-if="docData.file!=null">
						<template v-if="fileType==2">
							<a :href="checkFile(docData.file.file, docData.file.format)" class="btn btn-primary no-round font-bold btn-shad"target="_blank">
								<i class="fas fa-file-download fa-lg"/>&nbsp;Сохранить оригинал документа
							</a>
							<br/>
							<!-- <a v-if="$root.storage=='coresar'" :href="'/storage/app/public/pdfs/'+ docData.file.file +'.pdf'" class="btn btn-danger no-round font-bold btn-shad" target="_blank">
								<i class="fas fa-file-pdf fa-lg"/>&nbsp;Сохранить PDF
							</a>
							<a v-else :href="'/storage/pdfs/'+ docData.file.file +'.pdf'" class="btn btn-danger no-round font-bold btn-shad" target="_blank">
								<i class="fas fa-file-pdf fa-lg"/>&nbsp;Сохранить PDF
							</a> -->
							<a :href="'/storage/pdfs/'+ docData.file.file +'.pdf'" class="btn btn-danger no-round font-bold btn-shad" target="_blank">
								<i class="fas fa-file-pdf fa-lg"/>&nbsp;Открыть PDF
							</a>
						</template>
						<template v-else-if="fileType==3">
							<a :href="checkFile(docData.file.file, docData.file.format)" class="btn btn-primary no-round font-bold btn-shad" download>
								<i class="fas fa-file-download fa-lg"/>&nbsp;Сохранить оригинал документа
							</a>
						</template>
						<template v-else>
							<a :href="checkFile(docData.file.file, docData.file.format)" class="btn btn-primary no-round font-bold btn-shad" target="_blank">
								<i class="fas fa-file-pdf fa-lg"/>&nbsp;Открыть оригинал документа
							</a>
						</template>
					</div>
					<div v-else class="d-flex flex-column">
						<button class="btn btn-secondary no-round font-bold" disabled>
							<i class="fas fa-file-download fa-lg"/>&nbsp;Файл документа отсутствует
						</button>
						<br/>
					</div>
					<br/>
				</div>
				<vue-alert-inwork/>
				<template v-if="docData.removed==null">
					<template v-if="docData.authorId==userId">
						<div v-if="docData.fileAddition.length==0" class="row">
							<vue-template-newaddition :id="docData.id" :type="'doc'"/>
						</div>
						<div v-else class="row d-flex justify-content-center align-items-center">
							<vue-template-addition :author="true" :data="docData.fileAddition"/>
						</div>
					</template>
					<template v-else>
						<div v-if="docData.fileAddition.length>0" class="row d-flex justify-content-center align-items-center">
							<vue-template-addition :author="false" :data="docData.fileAddition" :type="'doc'"/>
						</div>
					</template>
				</template>
			</div>
		</div>
		<vue-template-relationslist :id="docData.id" :user-id="userId" :author-id="docData.authorId" :type="'doc'"/>
		<br/>
		<div class="row">
			<div class="col-md-12" v-if="versions.base!=null||versions.baseAssignment!=null">
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
					<div>
						<div class="row greyblck" v-if="docData.baseId!=null">
							<vue-item-base :baseDoc="versions.base" :baseAssign="null"/>
						</div>
						<div class="row greyblck" v-else-if="docData.baseAssignmentId!=null">
							<vue-item-base :baseDoc="null" :baseAssign="docData.baseAssignment"/>
						</div>
					</div>
				</template>
			</div>
		</div>
		<br/>
		<div class="row">
		<vue-template-newdocument :user-id="Number(userId)" :start="start"/>
			<template v-if="docData.status[0].docstatusId==1||docData.status[0].docstatusId==2">
				<vue-template-newassignment :user-id="Number(userId)" :users-list="allUsersList" :start="startAssign" :update="true" ref="newAssign"/>
			</template>
		</div>
		<div class="row" v-if="docData.agreements!=false">
			<template v-if="docData.agreements.users.length==1&&docData.agreements.users[0].userId==docData.authorId">
				<div class="px-4">
					<h4>
						Документ без согласования
					</h4>
				</div>
			</template>
			<template v-else>
				<div class="col-md-12">
					<br/>
					<h4>Согласованты:&nbsp;</h4>
					<span v-if="docData.agreements.deadline!=null">
						(срок согласования до:&nbsp;<vue-td-dateleft :status="docData.status[0].docstatusId" :date="docData.agreements.deadline"/>)
					</span>
					<div class="table_scroll_998_y">
						<table class="table table-striped mt-1 light-box-shad">
							<thead class="thead-dark">
								<tr>
									<th scope="col">
										Согласовант
									</th>
									<th class="ta-center" scope="col">
										Дата отправки
									</th>
									<th class="ta-center" scope="col">
										Комментарий
									</th>
									<th class="ta-center" scope="col">
										Дата решения
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
								<template v-for="item in docData.agreements.users">
									<vue-item-agreersdocpage :data="item" :main="docData.agreements"/>
									<!-- {{ item }} -->
								</template>
							</tbody>
						</table>
					</div>
					<template v-if="docData.authorId==userId">
						<div v-if="docData.status[0].docstatusId==1||docData.status[0].docstatusId==3" class="agreerslist_edit cursor-point" title="Редактировать список" data-toggle="modal" data-target="#editAgreementUserListModal" @click="initEditAgr()">
							<i class="fas fa-edit fa-2x"/>
						</div>
					</template>
				</div>
			</template>
		</div>
		<div v-else class="row">
			<div>
				<h4>
					Процесс согласования прерван
				</h4>
			</div>
		</div>
		<div>
			<div class="modal fade" id="docImageModal" tabindex="-1" role="dialog" aria-labelledby="docImageTitle" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered" role="document">
					<div class="modal-content no-round">
						<div class="modal-header">
							<h5 class="modal-title font-up font-bold" id="docImageTitle">
								Скан документа
							</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body d-flex justify-content-center align-items-center">
							<img :src="docData.file" :alt="'Фото-'+docData.file" class="docpage__img_modal">
						</div>
						<div class="modal-footer ta-center">
							<span>
								Скан документа:&nbsp;<span class="font-bold">{{ docData.description }}</span>
							</span>
						</div>
					</div>
				</div>
			</div>
			<vue-modal-docstatuslog :doc-data="docData"/>
			<vue-modal-addinarchive :doc-data="docData"/>
			<template v-if="docData.agreements!=false">
				<vue-pdf-agreementslist v-if="docData.agreements.users.length!=1&&docData.agreements.users[0].userId!=docData.authorId" :list="docData.agreements" :data="docData"/>
			</template>
			<vue-modal-deletedoc :id="id"/>
			<template v-if="docData.agreements!=false">
				<vue-modal-acquaintancelist :list="acqDocList"/>
			</template>
			<vue-modal-refusedocument />
			<vue-modal-documentimage />
			<template v-if="docData.fileAddition!=[]">
				<vue-modal-deleteaddition :id="id" :type="'doc'"/>
			</template>
			<template v-if="docData.authorId==userId">
				<template v-if="docData.status[0].docstatusId==1||docData.status[0].docstatusId==3">
					<vue-modal-editagreeuserlist :documentId="id" :status="docData.status[0].docstatusId" :orderable="orderable" :order="currOrder" :list="docData.agreements.users" :agreementId="docData.agreements.id" :users="agrUsersList" :completed="completed"/>
				</template>
			</template>
			<vue-modal-savedocinfo/>
			<vue-modal-savefileinfo/>
			<vue-modal-getfromarchive :doc-data="docData"/>
			<!-- <vue-modal-relationslist :id="docData.id" :user-id="userId" :author-id="docData.authorId" :type="'doc'"/> -->
		</div>
	</div>
</template>
<script>
	export default {
		props: {
			id: Number,
			userId: Number,
		},
		data() {
			return {
				docData: {},
				spinOff: false,
				pdfButton: false,
				versions: {},
				agreelistType: 1,
				userArr: [],
				userArrItem: {
					id: null,
					agreementId: null,
					userId: null,
				},
				spinVers: true,
				newSend: false,
				value: [],
				usersList: [],
				agrUsersList: [],
				agrUsersArr: [],
				sendMessage: 0,
				acquaintancesList: [],
				acqDocList: [],
				acqDoc: null,
				acqDocArr: [],
				spinOffAcqModal: true,
				comment: '',
				needComment: false,
				isAgree: 0,
				order: null,
				actualAgreement: 0,
				fileType: 0,
				allUsersList: [],
				deliveryTypes: [],
				// start: {},
				start: {
					docType: {},
					docDesc: '',
					docNum: null,
					baseData: {
						docId: null,
						assignId: null,
					},
					diruser: null,
				},
				orderable: false,
				currOrder: null,
				completed: false,
				editMode: false,
				newDescription: '',
				newOrderNum: null,
				newName: null,
				newCreationDate: null,
				newDocCloseDate: null,
				newCustomer: null,
				newCoExecutor: null,
				newColName: null,
				newSumContract: null,
				newPhases: null,
				newNote: null,
				newAuthor: null,
				newSignatory: null,
				newAcqDate: null,
				newExecutor: null,
				newAddresser: null,
				newOuterNum: null,
				newOuterDate: null,
				lang: {
		          	formatLocale: {
		            	firstDayOfWeek: 1,
		          	},
		        },
		        startAssign: {},
		        updateAssign: 1,
		        editFile: null,
		        newDiruser: false,
				newDiruserArr: {
					surname: null,
					firstname: null,
					patronymic: null,
				},
				diruser: null,
				withoutDiruser: false,
				type: 'doc',
			}
		},
		created() {
			this.$root.checkRole(this.userId);
			axios.post('api/getdocbyid', {id: this.id, info: 1}, {
					headers: {
						"Content-Type": "application/json"
					}
				})
				.then(response => {	
					if (response.data.error == 0) {
						this.docData = response.data.result;
						// this.getAgreements();
						this.makeStart();
						if (this.docData.agreements != false) {
							this.yourAgreement();
							// console.log(this.isAgree);
						}
						this.docData.agreements.users.forEach(item => {
							this.agrUsersArr.push(item.userId);
						});
						// this.spinVers = false;
						this.getVersions();
						this.orderable = (this.docData.agreements.users[0].order != null) ? true : false;
						if (this.orderable === true) {
							let len = this.docData.agreements.users.length;
							this.currOrder = this.docData.agreements.users[len-1].order;
							this.completed = (this.docData.agreements.users[len-1].approved_at != null) ? true : false;
						}
						console.log(this.docData);
						// this.dirusers = this.docData.diruser;
					} else if (response.data.error == 2) {
						window.location.href = '/pagenotfound';
					} else {
						alert(response.data.error_message);
					}
				}).catch(error => {
					// alert('Ошибка получения данных');
					console.log(error);
				});
			axios.post('api/acquaintanceslist', {initiatorId: this.userId})
				.then(response => {
					if (response.data.error == 0) {
						this.acquaintancesList = response.data.result;
					} else {
						alert(response.data.error_message);
					}
				}).catch(error => {
					alert('Ошибка получения данных3');
					console.log(error);
				});
			this.getAcqDocList();
			axios.post('api/getuserslist', {id: this.userId}, {
					headers: {
						"Content-Type": "application/json"
					}
				})
				.then(response => {
					if (response.data.error == 0) {
						this.allUsersList = response.data.result;
					} else {
						alert(response.data.error_message);
					}
				}).catch(error => {
					alert('Ошибка получения данных4');
					console.log(error);
				});
			this.$root.getRelations(this.id, 'doc');
            this.$root.docsAll();
            this.$root.assignsAll();
		},
		methods: {
			openUpd: function() {
				window.scrollTo({
					top: 1200,
					behavior: "smooth",
				});
				this.startAssign = {
					type: {
						id: 1,
						title: 'Срочное',
					},
					text: 'Доработать документ в карточке №'+ this.id + '',
					description: 'Документ в карточке «'+ this.docData.description +'» необходимо доработать',
					executor: this.docData.authorData,
					authorId: this.userId,
				};
				console.log(this.startAssign);
				this.$refs.newAssign.initApp();
			},
			getUsersListSend: function() {
				axios.post('api/getuserslist', {id: this.userId}, {
					headers: {
						"Content-Type": "application/json"
					}
				})
				.then(response => {
					if (response.data.error == 0) {
						let list = [];
						this.usersList = [];
						this.agrUsersList = [];
						let newList = [];
						list = response.data.result;
						list.forEach(item => {
							if (this.acqDocArr.indexOf(item.id) === -1) {
								this.usersList.push(item);
							}
							if (this.agrUsersArr.indexOf(item.id) === -1) {
								this.agrUsersList.push(item);
							}
						});
					} else {
						alert(response.data.error_message);
					}
				}).catch(error => {
					alert('Ошибка получения данных6');
					console.log(error);
				});
			},
			checkFile: function(file, format) {
				format = format.toLowerCase();
				// let link = (this.$root.storage == 'coresar') ? '/storage/app/public/' : '/storage/';
				let link = '/storage/';
				let fullFile;
				if (format === 'pdf') {
					this.fileType = 1;
					fullFile = link + 'pdfs/'+ file +'_orig.'+ format;
				} else if ((format === 'doc') || (format === 'docx')) {
					this.fileType = 2;
					fullFile = link + 'pdfs/'+ file +'.'+ format;
				} else if ((format == 'png') || (format == 'jpg') || (format == 'jpeg') || (format == 'gif')) {
					this.fileType = 3;
					fullFile = link + 'images/'+ file +'.'+ format;
					console.log('формат '+ format);
				} else {
					this.fileType = 0;
					fullFile = link + 'etc/'+ file +'.'+ format;
				}
				// console.log(this.$root.storage);
				return fullFile;
			},
			initSend: function() {
				this.getUsersListSend();
				this.newSend = true;
			},
			initEditAgr: function() {
				this.getUsersListSend();
			},
			closeIt: function() {
				this.newSend = false;
				this.sendMessage = 0;
				this.value = [];
			},
			sendDoc: function() {
				if (this.value.length === 0) {
					this.sendMessage = 4;
				} else {
					let data = [];
					this.value.forEach(item => {
						data.push({
							userId: item.id,
							documentId: this.id,
							initiatorId: this.userId,
						});
					});
					axios.post('api/addacquaintance', data, {
				        headers: {
				        	"Content-Type": "application/json"
				        }
				     })
						.then(response => {
							if (response.data.error == 0) {
								this.sendMessage = 1;
								this.value = [];
								this.getUsersListSend();
								this.getAcqDocList();
								console.log('Добавлено');
							} else {
								this.userMessage = 2;
							}
						}).catch(error => {
							alert('Ошибка получения данных7');
							this.userMessage = 2;
							console.log(error);
						});

				}
			},
			checkAcq: function(arr) {
				arr.forEach(item => {
					if (item.userId == this.userId) {
						if (item.seen_at == null) {
							this.acqDoc = item.id;
						}
					}
				});
				// console.log(this.acqDoc);
			},
			makeSeenAcqDoc: function() {
				if (this.acqDoc !== null) {
					axios.post('api/updateacquaintance', {id: this.acqDoc, view: 1}, {
				        headers: {
				        	"Content-Type": "application/json"
				        }
				    })
						.then(response => {
							if (response.data.error == 0) {
								console.log('Просмотрено');
								this.$router.go();
							} else {
								alert(response.data.error_message);
							}
						}).catch(error => {
							alert('Ошибка получения данных8');
							this.userMessage = 2;
							console.log(error);
						});
				}
			},
			getAcqDocList: function() {
				axios.post('api/acquaintanceslist', {documentId: this.id})
					.then(response => {
						if (response.data.error == 0) {
							this.acqDocList = response.data.result;
							this.acqDocArr = [];
							this.acqDocList.forEach(item => {
								if (item.seen_at == null) {
									this.acqDocArr.push(item.userId);
								}
							});
							this.checkAcq(this.acqDocList);
							this.spinOffAcqModal = false;
						} else {
							alert(response.data.error_message);
						}
					}).catch(error => {
						alert('Ошибка получения данных9');
						console.log(error);
					});
			},
			refreshNote: function() {
				this.needComment = false;
				this.comment = '';
			},
			approveAgreement: function(id, agreementId, userId, order) {
                axios.post('api/updateagreement', {id: id, agreementId: agreementId, userId: userId, note: this.comment, order: order, documentId: this.id, approve: 1, authorId: this.docData.authorId }, {
                        headers: {
                            "Content-Type": "application/json"
                        }
                    })
                    .then(response => {
                        if (response.data.error == 0) {
                            if (response.data.result != null) {
                                this.$root.refreshPage();
                            } else {
                                alert('Ошибка отправки данных10');
                            }
                        } else {
                            alert(response.data.error_message);
                        }
                    }).catch(error => {
                        alert('Ошибка получения данных11');
                        console.log(error);
                    });
            },
            refuseAgreement: function(id, agreementId, userId) {
            	if (this.comment === '') {
					this.needComment = true;
				} else {
					this.needComment = false;
                    axios.post('api/updateagreement', {id: id, agreementId: agreementId, userId: userId, note: this.comment, refusedAt: 1, documentId: this.id, authorId: this.docData.authorId }, {
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
                }
            },
            yourAgreement: function() {
            	this.isAgree = 0;
            	let arr = this.docData.agreements.users;
            	if ((this.docData.agreements.approved_at == null) && (this.docData.agreements.refused_at == null)) {
            		// if (arr[0]['userId'] != this.docData.authorId) {
        			if (arr.length > 1) {
            			arr.forEach(item => {
            				if ((item.created_at != null) && (item.approved_at == null) && (item.refused_at == null)) {
            					if (item.userId == this.userId) {
			            			this.isAgree = item.agreementId;
			            			this.actualAgreement = item.id;
			            			this.order = item.order != null ? item.order : null;
			            		}
            				}
		            	});
		            } else {
        				if ((arr[0]['created_at'] != null) && (arr[0]['approved_at'] == null) && (arr[0]['refused_at'] == null)) {
        					if (arr[0]['userId'] == this.userId) {
		            			this.isAgree = arr[0]['agreementId'];
		            			this.actualAgreement = arr[0]['id'];
		            			this.order = arr[0]['order'] != null ? arr[0]['order'] : null;
		            		}
        				}
		            }
            		// }
            	}
            },
            makeStart: function() {
				this.start.docType = this.docData.type;
				this.start.docDesc = this.docData.description;
				this.start.docFile = {
					'id': this.docData.file.id,
					'title': this.docData.file.file,
					'format': this.docData.file.format,
					'fileId': this.docData.file.id,
				};
				this.start.dirusers = (this.docData.diruser != false) ? this.docData.diruser.user : null;
				if (this.docData.baseId != null) {
					this.start.baseData.docId = this.docData.baseId;
					this.start.baseData.assignId = '';
				} else if (this.docData.baseAssignmentId != null) {
					this.start.baseData.docId = '';
					this.start.baseData.assignId = this.docData.baseAssignmentId;
				} else {
					this.start.baseData.docId = '';
					this.start.baseData.assignId = '';
				}
				this.start.docNum = (this.docData.orderNum != null) ? this.docData.orderNum : null;
            },
            getVersions: function() {
            	axios.post('api/getdocumentversions', {id: this.id})
					.then(response => {
						if (response.data.error == 0) {
							this.versions = response.data.result;
							this.spinVers = false;
						} else {
							alert(response.data.error_message);
						}
					}).catch(error => {
						alert('Ошибка получения данных2');
						console.log(error);
					});
            },
            editModeToggle: function() {
				// this.$root.docsAll();
    //         	this.$root.assignsAll();
            	this.editMode = (this.editMode === true) ? false : true;
            	this.newDescription = this.docData.description;
            	this.newOrderNum = this.docData.orderNum;
            	this.newName = this.docData.name;
            	this.newCreationDate = this.docData.creationDate;
            	this.newDocCloseDate = this.docData.closeDate;
            	this.newCustomer = this.docData.customer;
            	this.newCoExecutor = this.docData.coExecutor;
				this.newColName = this.docData.colName;
				this.newSumContract = this.docData.sumContract;
				this.newPhases = this.docData.phases;
				this.newNote = this.docData.note;
				this.newAuthor = this.docData.author;
				this.newSignatory = this.docData.signatory;
				this.newAcqDate = this.docData.acqDate;
				if (this.docData.typeId == 9) {
					this.newExecutor = this.docData.executorUser;
				}
				this.newAddresser = this.docData.addresser;
				this.newLetterExecutor = this.docData.letterExecutor;
				if (this.docData.typeId == 2||this.docData.typeId == 6||this.docData.typeId == 7||this.docData.typeId == 14||this.docData.typeId == 15) {
					this.$root.getDirusers();
					this.diruser = this.docData.diruser.user;
				}
				this.newOuterNum = this.docData.outerNum;
				this.newOuterDate = this.docData.outerDate;
				if (this.docData.baseId != null) {
					if (this.docData.baseDoc.status.docstatusId == 2) {
						this.$root.baseRefusedDocId = {
							id: this.docData.baseId,
							author: this.docData.baseDoc.author,
							description: this.docData.baseDoc.description,
							orderNum: this.docData.baseDoc.orderNum,
							creationDate: this.docData.baseDoc.creationDate,
							created_at: this.docData.baseDoc.created_at,
						};
					} else {
						this.$root.baseDocId = {
							id: this.docData.baseId,
							author: this.docData.baseDoc.author,
							description: this.docData.baseDoc.description,
							orderNum: this.docData.baseDoc.orderNum,
							creationDate: this.docData.baseDoc.creationDate,
							created_at: this.docData.baseDoc.created_at,
						};
					}
				};
				if (this.docData.baseAssignmentId != null) {
					this.$root.baseAssignId = {
						id: this.docData.baseAssignmentId,
						text: this.docData.baseAssignment.text,
						created_at: this.docData.baseAssignment.created_at,
						author: this.docData.baseAssignment.author,
					};
				};
            },
            editDoc: function() {
            	console.log(this.newDocCloseDate);
            	let data = {
            		id: this.id,
            		description: this.newDescription,
            		orderNum: this.newOrderNum,
            		name: this.newName,
	            	creationDate: this.$root.frmtDateIn(this.newCreationDate),
	            	closeDate: this.$root.frmtDateIn(this.newDocCloseDate),
	            	customer: this.newCustomer,
	            	coExecutor: this.newCoExecutor,
					colName: this.newColName,
					sumContract: this.newSumContract,
					phases: this.newPhases,
					note: this.newNote,
					author: this.newAuthor,
					signatory: this.newSignatory,
					acqDate: this.$root.frmtDateIn(this.newAcqDate),
					addresser: this.newAddresser,
					letterExecutor: this.newLetterExecutor,
					outerNum: this.newOuterNum,
	            	outerDate: this.$root.frmtDateIn(this.newOuterDate),
            	}; 
            	if ((this.$root.baseDocId != null) || (this.$root.baseRefusedDocId != null)) {
            		data.baseId = (this.$root.baseDocId != null) ? this.$root.baseDocId.id : this.$root.baseRefusedDocId.id;
            		data.baseAssignmentId = null;
            	} else if (this.$root.baseAssignId != null) {
            		data.baseId = null;
            		data.baseAssignmentId = this.$root.baseAssignId.id;
            	} else {
            		data.baseId = null;
            		data.baseAssignmentId = null;
            	}
            	data.executor = (this.newExecutor != null) ? this.newExecutor.id : null;
            	if (this.docData.typeId == 2||this.docData.typeId == 6||this.docData.typeId == 7||this.docData.typeId == 14||this.docData.typeId == 15) {
					if (this.withoutDiruser == false) {
						data.diruser = (this.newDiruser == true) ? JSON.stringify(this.newDiruserArr) : JSON.stringify(this.diruser);
					} else {
						data.diruser = null;
					}
				}
            	// console.log(data.creationDate);
            	axios.post('api/updatedoc', data, {
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
            editFileDoc: function() {
            	let data = new FormData();
            	data.append('documentId', this.id);
				data.append('file', this.editFile);
            	axios.post('api/updatedocfile', data, {
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
            makePDF: async function() {
            	let data = {
            		document: this.docData,
            		typeId: this.docData.type.id,
					typeTitle: this.docData.type.title,
					authorSurname: this.docData.authorData.surname,
					authorFirstname: this.docData.authorData.firstname,
					authorPatronymic: this.docData.authorData.patronymic,
					diruser: this.docData.diruser,
					agreements: this.docData.agreements.users,
            	};
            	console.log(data);
            	axios.post('/api/pdf', data,
            			{
                       		headers: {
                           		"Content-Type": "application/json",
                           		responseType: 'blob'
                        }
                    })
                    .then(response => {
                        if (response.data.error == 0) {
                            if (response.data.result != null) {
								const blob = this.b64ToBlob(response.data.result, 'application/pdf');
							    const blobUrl = URL.createObjectURL(blob);
							    // window.open(blobUrl);
							    const link = document.createElement('a');
								link.href = blobUrl;
								const date = new Date();
								link.setAttribute('download', 'agreement-list'+'_doc-'+ this.id +'_'+ date.getTime() +'.pdf');
								document.body.appendChild(link);
								link.click();
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
        	b64ToBlob: function(b64Data, contentType = '', sliceSize = 512) {
        		const byteCharacters = atob(b64Data);
			    const byteArrays = [];

			    for (let offset = 0; offset < byteCharacters.length; offset += sliceSize) {
			        const slice = byteCharacters.slice(offset, offset + sliceSize);

			        const byteNumbers = new Array(slice.length);
			        for (let i = 0; i < slice.length; i++) {
			            byteNumbers[i] = slice.charCodeAt(i);
			        }

			        const byteArray = new Uint8Array(byteNumbers);
			        byteArrays.push(byteArray);
			    }

			    const blob = new Blob(byteArrays, { type: contentType });
			    return blob;
        	},
		}
	}
</script>