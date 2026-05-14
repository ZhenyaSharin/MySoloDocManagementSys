<template>
	<div class="container">
		<!-- <div v-if="roleId==1"> -->
		<div v-if="$root.roleData.roleId==1">
			<vue-panel-admin :user-id="userId"/>
		</div>
		<div v-else-if="$root.roleData.roleId==2||$root.roleData.roleId==3||$root.roleData.roleId==4">
			<br/>
			<div class="row" v-if="tgType==1">
				<div class="col-12 col-md-6 d-flex ta-center font-up font-bold homepage__tab homepage__tab_selected">
					<span>
						Документ
					</span>
				</div>
				<div class="col-md-6 d-flex justify-content-end ta-center font-up cursor-point homepage__tab" @click="toggleType(2)">
					<span>
						Поручение
					</span>
				</div>
			</div>
			<div class="row" v-else-if="tgType==2">
				<div class="col-12 col-md-6 d-flex ta-center font-up homepage__tab cursor-point" @click="toggleType(1)">
					<span>
						Документ
					</span>
				</div>
				<div class="col-md-6 d-flex font-bold  justify-content-end ta-center font-up homepage__tab homepage__tab_selected">
					<span>
						Поручение
					</span>
				</div>
			</div>
			<br/>
			<div v-if="tgType==1" class="row d-flex align-items-center">
				<vue-template-newdocument :user-id="Number(userId)" :doc-count="$root.docCount"/>
			</div>
			<div v-else-if="tgType==2" class="row d-flex align-items-center">
				<vue-template-newassignment :user-id="Number(userId)" :users-list="usersList" :doc-count="$root.docCount"/>
			</div>
			<div v-if="tgType==1" class="transblock">
<!-- 				<div class="row">
					<vue-template-search :users-list="usersList"/>
				</div> -->
				<br/>
				<div class="row">
					<div class="col-md-12">
						<div class="card">
							<div class="card-header card-header_custom font-bold d-flex justify-content-between">
								<a href="/documents?type=1" class="font-bold font-up">
									Ваши недавние документы
								</a>
								<vue-template-listcount :count="$root.docCount" :getData="$root.getDocs" :date-mode="dateModeLocal" :is-docs="true"/>
							</div>
							<div class="openclose cursor-point" @click="$root.getDocs(userId, $root.docCount, dateModeLocal, true)" v-bind:class="[{ openclose_rotated: $root.docsPanel }, angleHistory]">
								<i class="fas fa-caret-down fa-2x"/>
							</div>
							<div class="card-body table_scroll_998_y" v-if="$root.docsPanel==true">
								<table class="table table-striped">
									<thead class="thead-dark">
										<tr>
											<th scope="col">
												Документ
											</th>
											<th class="ta-center" scope="col">
												Номер
											</th>
											<th class="ta-center" scope="col">
												Тип
											</th>
											<th class="ta-center timestamp_font p-1" scope="col">
												<div class="d-flex justify-content-center align-items-center">
													<div class="d-flex flex-column align-items-center px-1 mx-1 cursor-point" @click="toggleOrder()" title="Порядок по убыванию/возрастанию">
														<template v-if="$root.orderMode==='DESC'">
															<div>
																<i class="fas fa-angle-up fa-lg"/>
															</div>
															<div>
																<i class="fas fa-angle-down fa-lg mode-off"/>
															</div>
														</template>
														<template v-else-if="$root.orderMode==='ASC'">
															<div>
																<i class="fas fa-angle-up fa-lg mode-off"/>
															</div>
															<div>
																<i class="fas fa-angle-down fa-lg"/>
															</div>
														</template>
													</div>
													<div @click="toggleDate()">
														<vue-elem-datetoggle/>
													</div>
												</div>
											</th>
											<th class="ta-center" scope="col">
												Основание
											</th>
											<th class="ta-right" scope="col">
												Статус
											</th>
										</tr>
									</thead>
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
										<tbody v-if="$root.docsList.length>0">
											<template v-for="item in $root.docsList">
												<vue-item-documentitem :data="item" :date-mode="$root.dateMode"/>
											</template>
										</tbody>
										<tbody v-else>
											<tr class="tr-greyplug">
												<th colspan="100%" class="ta-center font-up">
													Документы ещё не созданы
												</th>
											</tr>
										</tbody>
									</template>
								</table>
							</div>
						</div>
					</div>
				</div>
				<br/>
				<div class="row">
					<div class="col-md-12">
						<div class="card">
							<div class="card-header card-header_custom font-bold d-flex justify-content-between">
								<div>
									<a href="/newagreements" class="font-bold font-up">Актуальные заявки на согласование</a>
									&nbsp;</span><span class="greytxt" v-if="countNew>0">(&nbsp;новых:<span class="status_refused font-bold">&nbsp;{{ countNew }}&nbsp;</span>)</span><span class="greytxt" v-else>(&nbsp;новых заявок нет&nbsp;)</span>&nbsp;
								</div>
								<!-- <vue-template-listcount :count="agrCount" :getData="getAgreements"/> -->
							</div>
							<div class="openclose cursor-point" @click="getAgreements(agrCount, true)" v-bind:class="[{ openclose_rotated: agrsPanel }, angleHistory]">
								<i class="fas fa-caret-down fa-2x"/>
							</div>
							<div class="card-body table_scroll_998_y" v-if="agrsPanel==true">
								<table class="table table-hover">
									<thead class="thead-dark">
										<tr>
											<th scope="col">
												Документ
											</th>
											<th class="ta-center" scope="col">
												Тип документа
											</th>
											<th class="ta-center" scope="col">
												Статус
											</th>
											<th class="ta-center" scope="col">
												Дата создания
											</th>
											<th class="ta-center" scope="col">
												Согласовать до
											</th>
											<th class="ta-right" scope="col">
												Автор
											</th>
										</tr>
									</thead>
									<template v-if="spinOffAgrs===true">
										<tbody>
											<tr>
												<td colspan="100%" class="ta-center mt-4">
													<vue-spinner/>
												</td>
											</tr>
										</tbody>
									</template>
									<template v-else-if="spinOffAgrs===false">
										<tbody v-if="agreeList.length>0">
											<template v-for="item in agreeList">
												<vue-item-agreementitem :data="item" :user-id="userId" document-id="item.agreement.documentId" @click.native="openAgreeModal(item.id, item.agreement.documentId, item.agreementId, item.documentFile, item.order)"/>
											</template>
										</tbody>
										<tbody v-else>
											<tr class="tr-greyplug">
												<th colspan="100%" class="ta-center font-up">
													Ничего не найдено
												</th>
											</tr>
										</tbody>
									</template>
								</table>
							</div>
						</div>
					</div>
				</div>
				<br/>
				<div class="row">
					<div class="col-md-12">
						<div class="card">
							<div class="card-header card-header_custom font-bold d-flex justify-content-between">
								<div>
									<a href="/acquaintances?type=1" class="font-bold font-up">Присланные на ознакомление документы</a>
									&nbsp;</span>
									<span class="greytxt" v-if="$root.acqCount>0">(&nbsp;Новых:<a href="/acquaintances?type=2" class="status_refused font-bold">&nbsp;{{ $root.acqCount }}&nbsp;</a>)</span><span class="greytxt" v-else>(&nbsp;Новых заявок нет&nbsp;)</span>&nbsp;
								</div>
<!-- 								<vue-template-listcount :count="agrCount" :getData="getAgreements"/> -->
							</div>
							<div class="openclose cursor-point" @click="$root.getAcquaintances(userId, true)" v-bind:class="[{ openclose_rotated: $root.ascsPanel }, angleHistory]">
								<i class="fas fa-caret-down fa-2x"/>
							</div>
							<div class="card-body table_scroll_998_y" v-if="$root.ascsPanel==true">
								<table class="table table-hover">
									<thead class="thead-dark">
										<tr>
											<th scope="col">
												Документ
											</th>
											<th class="ta-center" scope="col">
												Тип документа
											</th>
											<th class="ta-center" scope="col">
												Дата отправки
											</th>
											<th class="ta-center" scope="col">
												Отправлен кем
											</th>
											<th class="ta-right" scope="col">
												Статус
											</th>
										</tr>
									</thead>
									<template v-if="$root.spinOffAcqs===true">
										<tbody>
											<tr>
												<td colspan="100%" class="ta-center mt-4">
													<vue-spinner/>
												</td>
											</tr>
										</tbody>
									</template>
									<template v-else-if="$root.spinOffAcqs===false">
										<tbody v-if="$root.acquaintancesList.length>0">
											<template v-for="item in $root.acquaintancesList">
												<vue-item-acquaintanceslist :data="item"/>
											</template>
										</tbody>
										<tbody v-else>
											<tr class="tr-greyplug">
												<th colspan="100%" class="ta-center font-up">
													Список пуст
												</th>
											</tr>
										</tbody>
									</template>
								</table>
							</div>
						</div>
					</div>
				</div>
				<br/>
<!-- 				<div class="row">
					<div class="col-md-12">
						<div class="card">
							<div class="card-header card-header_custom font-bold d-flex justify-content-between">
								<a href="/letters" class="font-bold font-up">
									Входящие письма
								</a>
								<div class="d-flex mr-4">
									<div v-if="$root.lettersCount==5">
										5 шт.&nbsp;&nbsp;
									</div>
									<div v-else class="selected_title cursor-point" @click="$root.getListByDocTypeId(6, userId, dateModeLetter, 5, false)">
										5 шт.&nbsp;&nbsp;
									</div>
									<div v-if="$root.lettersCount==10">
										10 шт.&nbsp;&nbsp;
									</div>
									<div v-else class="selected_title cursor-point" @click="$root.getListByDocTypeId(6, userId, dateModeLetter, 10, false)">
										10 шт.&nbsp;&nbsp;
									</div>
									<div v-if="$root.lettersCount==20">
										20 шт.
									</div>
									<div v-else class="selected_title cursor-point" @click="$root.getListByDocTypeId(6, userId,dateModeLetter, 20, false)">
										20 шт.
									</div>
								</div>
							</div>
							<div class="openclose cursor-point" @click="$root.getListByDocTypeId(6, userId,dateModeLetter ,lettersCount, true)" v-bind:class="[{ openclose_rotated: $root.lettersPanel }, angleHistory]">
								<i class="fas fa-caret-down fa-2x"/>
							</div>
							<div class="card-body table_scroll_998_y" v-if="$root.lettersPanel==true">
								<table class="table table-striped">
									<thead class="thead-dark">
										<tr>
											<th scope="col">
												Документ
											</th>
											<th class="ta-center" scope="col">
												Внутренний номер
											</th>
											<th class="ta-center timestamp_font p-1" scope="col" @click="toggleDateLetter()">
												<vue-elem-datetoggle :title="'Дата получения'"/>
											</th>
											<th class="ta-center" scope="col">
												Адресат/Контрагент
											</th>
											<th class="ta-right" scope="col">
												Основание
											</th>
										</tr>
									</thead>
									<template v-if="$root.spinOffLetters===true">
										<tbody>
											<tr>
												<td colspan="100%" class="ta-center mt-4">
													<vue-spinner/>
												</td>
											</tr>
										</tbody>
									</template>
									<template v-else-if="($root.spinOffLetters===false)">
										<tbody v-if="$root.lettersList.length>0">
											<template v-for="item in $root.lettersList">
												<vue-item-letterlist :data="item" :date-mode="$root.dateMode"/>
											</template>
										</tbody>
										<tbody v-else>
											<tr class="tr-greyplug">
												<th colspan="100%" class="ta-center font-up">
													Писем ещё нет
												</th>
											</tr>
										</tbody>
									</template>
								</table>
							</div>
						</div>
					</div>
				</div>
				<br/> -->
<!-- 				<div class="row">
					<div class="col-md-12">
						<div class="card">
							<div class="card-header card-header_custom font-bold hptable d-flex justify-content-between align-items-center">
								<a href="/history">
									<span class="font-bold font-up">История Ваших согласований</span>&nbsp;&nbsp;
								</a>
								<vue-template-listcount :count="historyCount" :getData="openHistory"/>
								<div @click="openHistory(historyCount, true)" class="openclose cursor-point" v-bind:class="[{ openclose_rotated: historyPanel }, angleHistory]">
									<i class="fas fa-caret-down fa-2x"/>
								</div>
							</div>
							<div class="card-body table_scroll_998_y" v-if="historyPanel==true">
								<table class="table table-hover">
									<thead class="thead-dark">
										<tr>
											<th scope="col" width="160px">
												Документ
											</th>
											<th class="ta-center" scope="col" width="140px">
												Автор
											</th>
											<th class="ta-center" scope="col">
												Тип
											</th>
											<th class="ta-center" scope="col">
												Дата создания
											</th>
											<th class="ta-center" scope="col">
												Дата изменения
											</th>
											<th scope="col" class="ta-center" width="130px">
												Ваше решение
											</th>
											<th scope="col" width="160px" class="ta-right">
												Статус док-та
											</th>
										</tr>
									</thead>
									<template v-if="spinOffHistory===true">
										<tbody>
											<tr>
												<td colspan="100%" class="ta-center mt-4">
													<vue-spinner/>
												</td>
											</tr>
										</tbody>
									</template>
									<template v-else-if="spinOffHistory===false">
										<tbody v-if="historyLog.length>0">
											<template v-for="item in historyLog">
												<vue-item-historylog :data="item"/>
											</template>
										</tbody>
										<tbody v-else>
											<tr class="tr-greyplug">
												<th colspan="100%" class="ta-center font-up">
													Ничего не найдено
												</th>
											</tr>
										</tbody>
									</template>
								</table>
							</div>
						</div>
					</div>
				</div>
				<br/> -->
<!-- 				<div class="row">
					<div class="col-md-12">
						<div class="card">
							<div class="card-header card-header_custom font-bold d-flex justify-content-between">
								<a href="/agreements" class="font-bold font-up">Рассмотрение Ваших заявок на согласование</a>
								<vue-template-listcount :count="respCount" :getData="getResponses"/>
							</div>
							<div class="openclose cursor-point" @click="getResponses(respCount, true)" v-bind:class="[{ openclose_rotated: respsPanel }, angleHistory]">
								<i class="fas fa-caret-down fa-2x"/>
							</div>
							<div class="card-body table_scroll_998_y" v-if="respsPanel==true">
								<table class="table">
									<thead class="thead-dark">
										<tr>
											<th scope="col">
												Документ
											</th>
											<th class="ta-center" scope="col">
												Согласованты
											</th>
											<th class="ta-center" scope="col">
												Тип
											</th>
											<th class="ta-center" scope="col">
												Дата создания
											</th>
											<th class="ta-center" scope="col">
												Статус
											</th>
											<th class="ta-right" scope="col">
												Действие
											</th>
										</tr>
									</thead>
									<template v-if="spinOffResps===true">
										<tbody>
											<tr>
												<td colspan="100%" class="ta-center mt-4">
													<vue-spinner/>
												</td>
											</tr>
										</tbody>
									</template>
									<template v-else-if="spinOffResps===false">
										<tbody v-if="responsesList.length>0">
											<template v-for="item in responsesList">
												<vue-item-responseitem :data="item" :getDocId="getDocId" :getAgreeId="getAgreeId" :getSendAgainData="getSendAgainData"/>
											</template>
										</tbody>
										<tbody v-else>
											<tr class="tr-greyplug">
												<th colspan="100%" class="ta-center font-up">
													Ничего не найдено
												</th>
											</tr>
										</tbody>
									</template>
								</table>
							</div>
						</div>
					</div>
				</div> -->
			</div>
			<div v-else-if="tgType==2" class="transblock">
				<div class="row">
					<div class="col-md-12">
						<div class="card">
							<div class="card-header card-header_custom font-bold d-flex justify-content-between">
								<a href="/assignments?type=1" class="font-bold font-up">
									Ваши недавние поручения
								</a>
								<vue-template-listcount :count="authorAssignCount" :getData="getAssignmentsByAuthor"/>
							</div>
							<div class="openclose cursor-point" @click="getAssignmentsByAuthor(authorAssignCount, true)" v-bind:class="[{ openclose_rotated: authorAssignPanel }, angleHistory]">
								<i class="fas fa-caret-down fa-2x"/>
							</div>
							<div class="card-body table_scroll_998_y" v-if="authorAssignPanel==true">
								<table class="table">
									<thead class="thead-dark">
										<tr>
											<th width="220px" scope="col">
												Поручение
											</th>
											<th class="ta-center" scope="col">
												Тип
											</th>
											<th class="ta-center" scope="col">
												Дата создания
											</th>
											<th scope="col">
												Основание
											</th>
											<th class="ta-center" scope="col">
												Исполнитель
											</th>
											<th class="ta-center" scope="col">
												Срок исполнения
											</th>
											<th class="ta-right" scope="col">
												Статус
											</th>
											<th width="50px" class="ta-right px-1" scope="col">
												Действия
											</th>
										</tr>
									</thead>
									<template v-if="spinOffAuthorAssigns===true">
										<tbody>
											<tr>
												<td colspan="100%" class="ta-center mt-4">
													<vue-spinner/>
												</td>
											</tr>
										</tbody>
									</template>
									<template v-else-if="(spinOffAuthorAssigns===false)">
										<tbody v-if="assignsAuthorShortList.length>0">
											<template v-for="item in assignsAuthorShortList">
												<template v-if="item.main">
													<vue-item-assignment-author-multi :data="item" :get-arr="getArr"/>
												</template>
												<template v-else>
													<vue-item-assignment-author :get-assign-id="getAssignId" :data="item"/>
												</template>
											</template>
										</tbody>
										<tbody v-else>
											<tr class="tr-greyplug">
												<th colspan="100%" class="ta-center font-up">
													Поручения Вами ещё не созданы
												</th>
											</tr>
										</tbody>
									</template>
								</table>
							</div>
						</div>
					</div>
				</div>
				<br/>
				<div class="row" v-if="controlList.length!=0">
					<div class="col-md-12">
						<div class="card">
							<div class="card-header card-header_custom font-bold d-flex justify-content-between">
								<div>
									На личном контроле
									&nbsp;<span class="greytxt" v-if="countNewControl>0">(&nbsp;новых:<span class="status_refused font-bold">&nbsp;{{ countNewControl }}&nbsp;</span>)</span>
									</span></span><span class="greytxt" v-else>(&nbsp;новых назначений нет&nbsp;)</span>
								</div>
<!-- 								<vue-template-listcount :count="executorAssignCount" :getData="getAssignmentsByExecutor"/> -->
							</div>
							<div class="openclose cursor-point" @click="getAssignmentControls(true)" v-bind:class="[{ openclose_rotated: controlAssignPanel }, angleHistory]">
								<i class="fas fa-caret-down fa-2x"/>
							</div>
							<div class="card-body table_scroll_998_y" v-if="controlAssignPanel==true">
								<table class="table">
									<thead class="thead-dark">
										<tr>
											<th width="220px" scope="col">
												Поручение
											</th>
											<th class="ta-center" scope="col">
												Тип
											</th>
											<th class="ta-center" scope="col">
												Дата назначения
											</th>
											<th class="ta-center" scope="col">
												Назначивший
											</th>
											<th class="ta-center" scope="col">
												Срок исполнения
											</th>
											<th class="ta-right" scope="col">
												Статус
											</th>
										</tr>
									</thead>
									<template v-if="spinOffControls===true">
										<tbody>
											<tr>
												<td colspan="100%" class="ta-center mt-4">
													<vue-spinner/>
												</td>
											</tr>
										</tbody>
									</template>
									<template v-else-if="spinOffControls===false">
										<tbody v-if="controlList.length>0">
											<template v-for="item in controlList">
												<vue-item-assignmentcontrol :data="item"/>
											</template>
										</tbody>
										<tbody v-else>
											<tr class="tr-greyplug">
												<th colspan="100%" class="ta-center font-up">
													Поручения Вам ещё не присланы
												</th>
											</tr>
										</tbody>
									</template>
								</table>
							</div>
						</div>
					</div>
					<br/>
				</div>
				<br>
				<div class="row">
					<div class="col-md-12">
						<div class="card">
							<div class="card-header card-header_custom font-bold d-flex justify-content-between">
								<div>
									<a href="/assignments?type=2" class="font-bold font-up">
									Поручения, присланные Вам
									&nbsp;</a><span class="greytxt" v-if="countNewAssign>0">(&nbsp;новых:<span class="status_refused font-bold">&nbsp;{{ countNewAssign }}&nbsp;</span>)</span>
									</span></span><span class="greytxt"  v-else>(&nbsp;новых поручений нет&nbsp;)</span>
								</div>
								<vue-template-listcount :count="executorAssignCount" :getData="getAssignmentsByExecutor"/>
							</div>
							<div class="openclose cursor-point" @click="getAssignmentsByExecutor(executorAssignCount, true)" v-bind:class="[{ openclose_rotated: executorAssignPanel }, angleHistory]">
								<i class="fas fa-caret-down fa-2x"/>
							</div>
							<div class="card-body table_scroll_998_y" v-if="executorAssignPanel==true">
								<table class="table table-hover">
									<thead class="thead-dark">
										<tr>
											<th width="220px" scope="col">
												Поручение
											</th>
											<th class="ta-center" scope="col">
												Тип
											</th>
											<th class="ta-center" scope="col">
												Дата создания
											</th>
											<th class="ta-center" scope="col">
												Основание
											</th>
											<th class="ta-center" scope="col">
												Автор
											</th>
											<th class="ta-center" scope="col">
												Срок исполнения
											</th>
											<th class="ta-right" scope="col">
												Статус
											</th>
										</tr>
									</thead>
									<template v-if="spinOffExecutorAssigns===true">
										<tbody>
											<tr>
												<td colspan="100%" class="ta-center mt-4">
													<vue-spinner/>
												</td>
											</tr>
										</tbody>
									</template>
									<template v-else-if="(spinOffExecutorAssigns===false)">
										<tbody v-if="assignExecutorList.length>0">
											<template v-for="item in assignExecutorList">
												<!-- <vue-item-assignment-executor :data="item" @click.native="openExecutorModal(item.id)"/> -->
												<vue-item-assignment-executor :data="item"/>
											</template>
										</tbody>
										<tbody v-else>
											<tr class="tr-greyplug">
												<th colspan="100%" class="ta-center font-up">
													Поручения Вам ещё не присланы
												</th>
											</tr>
										</tbody>
									</template>
								</table>
							</div>
						</div>
					</div>
				</div>
				<br/>
			</div>
		</div>
		<template v-if="documentId!=null">
			<vue-modal-agreementslist :doc-id="documentId" :key="documentId" :user-id="userId"/>
		</template>
		<template v-if="agreementId!=null">
			<vue-modal-deleteagree :agree-id="agreementId" :agree-text="agreementText" :key="agreementId"/>
		</template>
		<template v-if="executorData.assignmentId!=null">
			<vue-modal-assignexecutor :deadline="executorData.assignmentId.deadline" :user-id="userId" :key="executorData.assignmentId"/>
		</template>
		<template v-if="sendAgainData!=null">
			<vue-modal-sendagain :user-id="userId" :data="sendAgainData" :users-list="usersList"/>
		</template>
		<!-- <template v-if="delAssignId!=null"> -->
		<vue-modal-deleteassignauthor :id="delAssignId"/>
		<!-- </template> -->
		<vue-modal-assignmentsmultilist :list="listModal" :type="listModalType"/>
	</div>
</template>

<script>
	// import axios from 'axios';
	// import ResponsesListItem;

	export default {
		props: {
			userId: String,
			roleId: {
				default: 2,
				type: Number,
			},
		},
		data() {
			return {
				usersList: [],
				// responsesList: [],
				lettersList: [],
				assignAuthorList: [],
				assignExecutorList: [],
				// acquaintancesList: [],
				// historyPanel: false,
				agrsPanel: true,
				// respsPanel: true,
				lettersPanel: true,
				authorAssignPanel: true,
				executorAssignPanel: true,
				controlAssignPanel: true,
				// ascsPanel: true,
				angleHistory: 'openclose',
				visHid: 'vis_hid',
				spinOffDocs: true,
				spinOffAgrs: true,
				// spinOffResps: true,
				// spinOffHistory: true,
				spinOffAuthorAssigns: true,
				spinOffExecutorAssigns: true,
				// spinOffLetters: true,
				// spinOffAcqs: true,
				spinOffControls: true,
				agreementDoc: {
					userId: this.userId,
					docId: null,
					docAgId: null,
					docAgName: null,
					agrId: null,
					docFile: null,
					order: null,
				},
				agreeList: [],
				docData: null,
				docId: null,
				documentId: null,
				agreementId: null,
				agreementText: null,
				// historyLog: [],
				// respCount: 10,
				agrCount: 5,
				authorAssignCount: 5,
				executorAssignCount: 5,
				// historyCount: 5,
				countNew: 0,
				lettersCount: 5,
				executorData: {
					initiatorId: this.userId,
					assignmentId: null,
					deadline: null,
					approvedUserId: null,
					initiatedAt: null,
				},
                countNewAssign: 0,
                sendAgainData: null,
				tgType: 1,
				// acqCount: 0,
				datePickerOps: {
					disabledDate: (date) => date < new Date(),
				},
				delAssignId: null,
				controlList: [],
				countNewControl: 0,
				listModal: [],
				listModalType: '',
				assignsAuthorShortList: [],
				dateModeLocal: true,
				dateModeLetter: true,
			}
		},
		mounted() {
			this.$root.getDocs(this.userId, this.$root.docCount, this.dateModeLocal, false);
			this.getAgreements(this.agrCount);
			// this.getResponses(this.respCount);
			// this.$root.getListByDocTypeId(6, this.userId, false, this.$root.lettersCount, false);
			this.$root.getAcquaintances(this.userId);
			this.getAssignmentsByAuthor(this.authorAssignCount);
			this.getAssignmentsByExecutor(this.executorAssignCount);
			this.getAssignmentControls();
			console.log(this.$root.lettersList);
		},
		updated() {
			this.checkNewDate();
		},
		created() {
			this.$root.checkRole(this.userId);
			axios.post('api/getuserslist', {id: this.userId}, {
					headers: {
						"Content-Type": "application/json"
					}
				})
				.then(response => {
					if (response.data.error == 0) {
						this.usersList = response.data.result;
					} else {
						// alert(response.data.error_message);
					}
				}).catch(error => {
					alert('Ошибка получения данных');
					console.log(error);
				});
			this.toggleType(this.$route.query.type);
		},
		methods: {
			toggleType: function(n) {
				this.$router.push({name: this.$route.name, query: { type: n }})
							.catch(err => {});
				if (n == 1) {
					this.tgType = n;
				} else if (n == 2) {
					this.tgType = n;
				}
			},
            getAgreements: function(count, start = false) {
            	this.spinOffAgrs = true;
            	if (start == true) {
					this.agrsPanel = (this.agrsPanel === false) ? true : false;
				};
				if (this.agrsPanel == true) {
					axios.post('api/getagreementslistbyuser', {userId: this.userId}, {
						headers: {
							"Content-Type": "application/json"
						}
					})
						.then(response => {
							var list = [];
							this.agreeList = [];
							this.countNew = 0;
							this.agrCount = count;
							if (response.data.error == 0) {
								list = response.data.result;
								for (var i = 0; i < count; i++) {
									if (list[i]) {
										this.agreeList.push(list[i]);
									}
								}
								list.forEach(item => {
									if (item['viewed_at'] == null) {
										this.countNew++;
									}
								});
								// console.log(this.agreeList);
								this.spinOffAgrs = false;
							} else {
								// alert(response.data.error_message);
							}
						}).catch(error => {
							alert('Ошибка получения данных');
							console.log(error);
						});
				};
            },
            getDeliveryTypes: function() {
            	axios.post('api/getdeliverytypes')
					.then(response => {	
						if (response.data.error == 0) {
							this.deliveryTypes = response.data.result;
						} else {
							// alert(response.data.error_message);
						}
					}).catch(error => {
						alert('Ошибка получения данных');
						console.log(error);
					});
            },
			openAgreeModal: function(agId, docId, agrId, docFile, order = null) {
				this.$root.makeViewedAgreement(agId);
				this.agreementDoc.docAgId = agId;
				this.agreementDoc.agrId = agrId;
				this.agreementDoc.docId = docId;
				this.agreementDoc.docFile = docFile;
				this.agreementDoc.order = order;
				axios.post('api/getdocbyid', {id: docId}, {
					headers: {
						"Content-Type": "application/json"
					}
				})
					.then(response => {
						if (response.data.error == 0) {
							this.agreementDoc.docAgName = response.data.result.description;
						} else {
							// alert(response.data.error_message);
						}
					}).catch(error => {
						alert('Ошибка получения данных');
						console.log(error);
					});
			},
			getAssignmentsByAuthor: function(count, start = false) {
				this.spinOffAuthorAssigns = true;
				this.authorAssignCount = count;
				if (start == true) {
					this.authorAssignPanel = (this.authorAssignPanel === false) ? true : false;
				}
            	axios.post('api/assignmentsbyauthor', {authorId: this.userId, count: count}, {
					headers: {
						"Content-Type": "application/json"
					}
				})
					.then(response => {
						if (response.data.error == 0) {
							this.assignAuthorList = response.data.result;
							this.assignsAuthorShortList = this.$root.getMultiple(this.assignAuthorList);
							this.spinOffAuthorAssigns = false;
							// console.log(this.assignsAuthorShortList);
						} else {
							// alert(response.data.error_message);
						}
					}).catch(error => {
						alert('Ошибка получения данных');
						console.log(error);
					});
			},
			getAssignmentsByExecutor: function(count, start = false) {
				this.spinOffExecutorAssigns = true;
				this.executorAssignCount = count;
				if (start == true) {
					this.executorAssignPanel = (this.executorAssignPanel === false) ? true : false;
				}
            	axios.post('api/assignexecutors', {executorId: this.userId, count: count}, {
					headers: {
						"Content-Type": "application/json"
					}
				})
					.then(response => {
						if (response.data.error == 0) {
							this.assignExecutorList = response.data.result;
							this.spinOffExecutorAssigns = false;
						} else {
							// alert(response.data.error_message);
						}
					}).catch(error => {
						alert('Ошибка получения данных');
						console.log(error);
					});
				axios.post('api/assignsnonviewed', {executorId: this.userId}, {
						headers: {
							"Content-Type": "application/json"
						}
					})
					.then(response => {
						var count;
						if (response.data.error == 0) {
							count = response.data.result;
							this.countNewAssign = count.length;
						// } else {
						// 	alert(response.data.error_message);
						}
					}).catch(error => {
						alert('Ошибка получения данных');
						console.log(error);
					});

			},
            refreshPageAgreement: function() {
                this.getAgreements(this.agrCount);
            },
            refreshExecutor: function() {
            	this.getAssignmentsByExecutor(this.executorAssignCount);
            },
            // getSendAgainData: function(data) {
			// 	this.sendAgainData = data.data;
			// },
			checkNewDate: function() {
				let date = new Date('2021-12-27 14:46:32.603687');
				date.setHours(date.getHours() + Math.abs(date.getTimezoneOffset()/60));
				let day = (date.getDate() < 10) ? ('0' + date.getDate()) : date.getDate();
				let dt = date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + day + ' ' + date.getHours() + ':' + date.getMinutes() + ':' + date.getSeconds();
			},
			send: function() {
				axios.post('api/mail', {
			        headers: {
			        	"Content-Type": "application/json"
			        }
			     })
					.then(response => {
						if (response.data.error == 0) {
							console.log('отправлено');
						} else {
							this.userMessage = 2;
						}
					}).catch(error => {
						alert('Ошибка получения данных');
						this.userMessage = 2;
						console.log(error);
					});
			},
			getAssignId: function(data) {
				this.delAssignId = data.assignId;
			},
			getAssignmentControls: function(start = false) {
				this.spinOffControls = true;
				if (start == true) {
					this.controlAssignPanel = (this.controlAssignPanel === false) ? true : false;
				}
            	axios.post('api/getassignments', {userId: this.userId}, {
					headers: {
						"Content-Type": "application/json"
					}
				})
					.then(response => {
						if (response.data.error == 0) {
							this.controlList = response.data.result;
							this.spinOffControls = false;
						} else {
							alert(response.data.error_message);
						}
					}).catch(error => {
						alert('Ошибка получения данных');
						console.log(error);
					});
			},
			getArr: function(data) {
				this.listModal = data.arr;
				this.listModalType = data.type;
				console.log(data);
			},
			toggleDate: function() {
				this.dateModeLocal = (this.$root.dateMode == 0) ? true : false;
				this.$root.spinOffDocs = true;
				this.$root.getDocs(this.userId, this.$root.docCount, this.dateModeLocal, false);
	        },
	        // toggleDateLetter: function() {
			// 	this.dateModeLetter = (this.$root.dateMode == 0) ? true : false;
			// 	this.$root.spinOffLetters = true;
			// 	this.$root.getListByDocTypeId(6, this.userId, this.dateModeLetter, this.$root.lettersCount, false)
	        // },
	        toggleOrder: function() {
	            this.$root.orderMode = (this.$root.orderMode === 'DESC') ? 'ASC' : 'DESC';
	            this.toggleDate();
	        },
		}
	}
</script>