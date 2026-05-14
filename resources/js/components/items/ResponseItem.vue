<template>
	<tr v-if="data.agreed_at!=null" class="greenrow">
		<td scope="col">
			<a class="font-bold" :href="'/document-'+data.documentId">
				<vue-td-formattedtitle :text="data.document.description" :length="50"/>
			</a>
		</td>

		<td class="ta-center" scope="col">
			<button type="button" class="btn btn-success no-round" data-toggle="modal" data-target="#AgreementsListModal" @click="getId(data.documentId)">
				Согласованты
			</button>
		</td>
		<td class="ta-center" scope="col">
			{{ data.document.documentType }}
		</td>
		<td class="ta-center timestamp_font" scope="col">
			<vue-elem-timestamp :date-time="data.created_at"/>&nbsp;<span v-if="data.updated_at" class="redacted">(Изм.)</span>
		</td>
		<td v-if="data.document.typeId==6" scope="col" class="td__responsestatus ta-center">
			<button class="btn btn-success no-round" disabled>...</button>
		</td>
		<td v-else scope="col" class="td__responsestatus ta-center">
			<button v-if="data.agreementsAndUsers.length===1" class="btn btn-success no-round" disabled>Подписано</button>
			<button v-else-if="data.agreementsAndUsers.length>1" class="btn btn-success no-round" disabled>Согласовано</button>
		</td>
		<td scope="col" class="ta-center">
			<button class="btn btn-secondary no-round btn-squarebtn" title="Удалить заявку" disabled>
				<i class="fas fa-times fa-lg"/>
			</button>
		</td>
	</tr>
	<tr v-else-if="data.refused_at!=null" class="redrow">
		<td scope="col">
			<a class="font-bold" :href="'/document-'+data.documentId">
				<vue-td-formattedtitle :text="data.document.description" :length="50"/>
			</a>
		</td>
		<td class="ta-center" scope="col">
			<button type="button" class="btn btn-success no-round" data-toggle="modal" data-target="#AgreementsListModal" @click="getId(data.documentId)">
				Согласованты
			</button>
		</td>
		<td class="ta-center" scope="col">
			{{ data.document.documentType }}
		</td>
		<td class="ta-center timestamp_font" scope="col">
			<vue-elem-timestamp :date-time="data.created_at"/>&nbsp;<span v-if="data.updated_at" class="redacted">(Изм.)</span>
		</td>
		<td scope="col" class="td__responsestatus ta-center">
			<!-- <button class="btn btn-primary no-round" data-toggle="modal" data-target="#sendAgainModal" @click="openSendAgainModal(data.agreementsNotAgreed.userId, data.id)">
				Отправить ещё раз...
			</button> -->
			<button class="btn btn-danger no-round" data-toggle="modal" data-target="#sendAgainModal" disabled>
				Отклонено
			</button>
		</td>
		<td scope="col" class="ta-center">
			<button class="btn btn-secondary no-round btn-squarebtn" title="Удалить заявку" disabled>
				<i class="fas fa-times fa-lg"/>
			</button>
		</td>
	</tr>
	<tr v-else class="greyrow">
		<td scope="col">
			<a class="font-bold" :href="'/document-'+data.documentId">
				<vue-td-formattedtitle :text="data.document.description" :length="50"/>
			</a>
		</td>
		<td class="ta-center" scope="col">
			<button type="button" class="btn btn-success no-round" @click="getId(data.documentId)" data-toggle="modal" data-target="#AgreementsListModal">
				Согласованты
			</button>
		</td>
		<td class="ta-center" scope="col">
			{{ data.document.documentType }}
		</td>
		<td class="ta-center timestamp_font" scope="col">
			<vue-elem-timestamp :date-time="data.created_at"/>&nbsp;<span v-if="data.updated_at" class="redacted">(Изм.)</span>
		</td>
		<td scope="col" class="td__responsestatus ta-center">
			<button class="btn btn-secondary no-round" disabled>
				На рассмотрении...
			</button>
		</td>
		<td scope="col" class="ta-center">
			<button v-if="data.removed!=null" class="btn btn-info no-round btn-squarebtn" title="Отправить новую заявку" data-toggle="modal" data-target="#sendAgainModal" disabled>
				<i class="fas fa-redo-alt"/>
			</button>
			<button v-else class="btn btn-danger no-round btn-squarebtn" title="Удалить заявку" data-toggle="modal" data-target="#deleteAgreeModal" @click="getAgId(data.id, data.document.description)">
				<i class="fas fa-times fa-lg"/>
			</button>
		</td>
	</tr>
</template>
<script>
	export default {
		props: {
			data: Object,
			getDocId: Function,
			getAgreeId: Function,
			getSendAgainData: Function,
		},
		methods: {
			// openSendAgainModal: function(userId, agreementId) {
			// 	this.anotherAgreement = {
			// 		userId: userId,
			// 		agreementId: agreementId,
			// 	};
			// },
			getId: function(id) {
				this.getDocId({
					docId: id,
				})
			},
			getAgId: function(id, text) {
				if (text.length > 40) {
					text = text.slice(0, 40).trim() + '...';
				}
				console.log(text);
				this.getAgreeId({
					agreeId: id,
					agreeText: text,
				})
			},
			getAllData(data) {
				this.getSendAgainData({data: data});
			},
		}
	}
</script>