<template>
	<div class="d-flex flex-column pdfpreview">
		<div class="pdfpreview__title font-up font-bold ta-center tt-underline" style="text-transform: uppercase; text-align: center; font-weight: bold;">
			Лист согласования
		</div>
		<br/>
		<div>
			<span class="font-bold">К документу:</span>&nbsp;&nbsp;{{ data.description }}
		</div>
		<div>
			<span class="font-bold">Тип документа:</span>&nbsp;&nbsp;{{ data.type.title }}
		</div>
		<div>
			<span class="font-bold">Дата и время отправки на согласование:</span>&nbsp;&nbsp;<vue-elem-timestamp :date-time="data.created_at"/>
		</div>
<!-- 		<div v-if="data.baseId!=null">
			<span class="font-bold">Основание:</span>&nbsp;&nbsp;{{ data.baseId }}
		</div> -->
		<div>
			<span class="font-bold">Автор карточки документа:</span>&nbsp;&nbsp;{{ $root.makeFio(data.authorData.surname, data.authorData.firstname, data.authorData.patronymic) }}
		</div>
		<template v-if="data.type.id==2||data.type.id==6||data.type.id==7||data.typeId==15">
			<template v-if="data.diruser==false">
				<template v-if="data.type.id==2">
					<vue-elem-previewpdfrow :title="'Заказчик (контрагент)'" :data="null"/>
				</template>
				<template v-else>
					<vue-elem-previewpdfrow :title="'Адресат документа/Контрагент'" :data="null"/>
				</template>
			</template>
			<template v-else>
				<template v-if="data.type.id==2">
					<vue-elem-previewpdfrow :title="'Заказчик (контрагент)'" :data="$root.makeFio(data.diruser.user.surname, data.diruser.user.firstname, data.diruser.user.patronymic)"/>
				</template>
				<template v-else>
					<vue-elem-previewpdfrow :title="'Адресат документа/Контрагент'" :data="$root.makeFio(data.diruser.user.surname, data.diruser.user.firstname, data.diruser.user.patronymic)"/>
				</template>
			</template>
		</template>
		<template v-if="data.type.id==2">
			<vue-elem-previewpdfrow :title="'Номер договора'" :data="data.orderNum"/>
			<vue-elem-previewpdfrow :title="'Наименование'" :data="data.name"/>
			<vue-elem-previewpdfrow :title="'Дата закрытия'" :data="$root.frmtDate(data.creationDate, true)"/>
			<vue-elem-previewpdfrow :title="'Дата заключения'" :data="$root.frmtDate(data.closeDate, true)"/>
			<vue-elem-previewpdfrow :title="'Соисполнитель'" :data="data.coExecutor"/>
			<vue-elem-previewpdfrow :title="'Краткое наименование'" :data="data.colName"/>
			<vue-elem-previewpdfrow :title="'Сумма по договору'" :data="data.sumContract"/>
			<vue-elem-previewpdfrow :title="'Этапы'" :data="data.phases"/>
		</template>
		<template v-else-if="data.type.id==6">
			<vue-elem-previewpdfrow :title="'Внутренний номер'" :data="data.orderNum"/>
			<vue-elem-previewpdfrow :title="'Примечание'" :data="data.note"/>
			<vue-elem-previewpdfrow :title="'Дата получения письма'" :data="$root.frmtDate(data.creationDate, true)"/>
			<vue-elem-previewpdfrow :title="'Срок исполнения'" :data="$root.frmtDate(data.closeDate, true)"/>
		</template>
		<template v-else-if="data.type.id==12">
			<vue-elem-previewpdfrow :title="'Номер уведомления'" :data="data.orderNum"/>
			<vue-elem-previewpdfrow :title="'Дата создания'" :data="$root.frmtDate(data.creationDate, true)"/>
			<vue-elem-previewpdfrow :title="'Автор'" :data="data.author"/>
			<vue-elem-previewpdfrow :title="'Подписант'" :data="data.signatory"/>
			<vue-elem-previewpdfrow :title="'Дата ознакомления'" :data="$root.frmtDate(data.acqDate, true)"/>
		</template>
		<template v-else-if="data.type.id==9">
			<vue-elem-previewpdfrow :title="'Номер приказа по ОД'" :data="data.orderNum"/>
			<vue-elem-previewpdfrow :title="'Дата создания приказа'" :data="$root.frmtDate(data.creationDate, true)"/>
			<template v-if="data.executorUser">
				<vue-elem-previewpdfrow :title="'Исполнитель'" :data="$root.makeFio(data.executorUser.surname, data.executorUser.firstname, data.executorUser.patronymic)"/>
			</template>
			<template v-else>
				<vue-elem-previewpdfrow :title="'Исполнитель'" :data="null"/>
			</template>
		</template>
		<template v-else-if="data.type.id==9">
			<vue-elem-previewpdfrow :title="'Номер исходящего письма'" :data="data.orderNum"/>
			<vue-elem-previewpdfrow :title="'Дата отправления'" :data="$root.frmtDate(data.creationDate, true)"/>
			<vue-elem-previewpdfrow :title="'Кому на исполнение'" :data="data.letterExecutor"/>
		</template>
		<template v-else>
			<vue-elem-previewpdfrow :title="'Дата документа'" :data="$root.frmtDate(data.creationDate, true)"/>
			<vue-elem-previewpdfrow :title="'Номер документа'" :data="data.orderNum"/>
		</template>
<!-- 						<div>
			<span class="font-bold">Способ получения документа:</span>&nbsp;&nbsp;{{ data.deliveryType.title }}
		</div> -->
		<br/>
		<div class="pdfpreview__signs">
			<table class="pdf_agreements" width="100%">
				<thead>
					<tr>
<!-- 										<th class="standart-border ta-center pdf-agreements_th">
							Должность
						</th> -->
						<th class="standart-border ta-center pdf-agreements_th">
							Ф.И.О.
						</th>
						<th class="standart-border ta-center pdf-agreements_th">
							Дата решения
						</th>
						<th class="standart-border ta-center pdf-agreements_th">
							Статус
						</th>
						<th class="standart-border ta-center pdf-agreements_th">
							Примечание
						</th>
					</tr>
				</thead>
				<tbody>
					<template v-for="item in list.users">
						<tr class="standart-border">
							<td class="standart-border pdf-agreements_td ta-center">
								{{ $root.makeFio(item.user.surname, item.user.firstname, item.user.patronymic) }}
							</td>
							<td v-if="item.updated_at!=null" class="standart-border pdf-agreements_td ta-center">
								<vue-elem-timestamp :date-time="item.updated_at"/>
							</td>
							<td v-else class="ta-center">
								---
							</td>
							<template v-if="list.removed!=null&&item.updated_at==null">
								<td>
									<span class="standart-border ta-center">Отклонено</span>&nbsp;<br/>(автоматически)
								</td>
							</template>
							<template v-else>
								<td v-if="item.approved_at!=null" class="standart-border status_approved ta-center">
									Согласовано
								</td>
								<td v-else-if="item.refused_at!=null" class="standart-border status_refused ta-center">
									Отклонено
								</td>
								<td v-else class="standart-border status_considering ta-center">
									На рассмотрении
								</td>
							</template>
							<td v-if="item.note!=null" class="standart-border standart-border pdf-agreements_td ta-center p-2">
								{{ item.note }}
							</td>
							<td v-else class="p-2 ta-center">
								...
							</td>
						</tr>
					</template>
				</tbody>
			</table>
		</div>
		<br/>
		<div class="">
			<div class="font-bold my-2" style="text-indent: 1.5em; text-align: justify;">
				Для замечаний:
			</div>
			<div>
				<table class="pdf_agreements_notes" width="100%">
					<tbody>
						<tr class="underbody">
							<td colspan="100%">
								
							</td>
						</tr>
						<tr class="underbody">
							<td colspan="100%">
								
							</td>
						</tr>
						<tr class="underbody">
							<td colspan="100%">
								
							</td>
						</tr>
						<tr class="underbody">
							<td colspan="100%">
								
							</td>
						</tr>
						<tr class="underbody">
							<td colspan="100%">
								
							</td>
						</tr>
						<tr class="underbody">
							<td colspan="100%">
								
							</td>
						</tr>
						<tr class="underbody">
							<td colspan="100%">
								
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</template>
<script>
	export default {
		props: {
			list: Object,
			data: Object,
		},
	}
</script>