<template>
	<div class="modal fade" id="sendAgainModal" tabindex="-1" role="dialog" aria-labelledby="sendAgainModalTitle" aria-hidden="true">
			<div class="modal-dialog modal-lg  modal-dialog-centered" role="document">
				<div class="modal-content no-round">
					<div class="modal-header">
						<h5 class="modal-title font-up font-bold" id="sendAgainModalTitle">
							Выбрать пользователя на согласование
						</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="form-group row">
		                    <div class="col-md-12">
		                    	<vue-template-agreers :users-list="usersList" :doc-type="{ id: data.document.typeId }" :get-agreers="getAgreers" :get-agreement-type="getAgreementType" :without-agr="false"/>
		                    </div>
		                </div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-success no-round flex-fill" @click="sendAgain()">
							Отправить
						</button>
						<button type="button" class="btn btn-danger no-round flex-fill" data-dismiss="modal">
		                    Отмена
		                </button>
					</div>
				</div>
			</div>
		</div>
</template>
<script>
	export default {
		props: {
			data: Object,
			userId: String,
			usersList: Array,
		},
		data() {
			return {
				anotherAgreement: {
					prevUserId: null,
					userId: null,
					agreementId: null,
				},
				docAgree: [],
				docAgreeItem: 0,
				// usersList: [],
				// getAgreers: [],
				agree: [],
				withoutAgr: null,
			}
		},
		mounted() {
			this.docAgree.push(this.docAgreeItem);
			// axios.post('api/getuserslist', {id: this.userId}, {
			// 		headers: {
			// 			"Content-Type": "application/json"
			// 		}
			// 	})
			// 	.then(response => {
			// 		if (response.data.error == 0) {
			// 			this.usersList = response.data.result;
			// 		} else {
			// 			alert(response.data.error_message);
			// 		}
			// 	}).catch(error => {
			// 		alert('Ошибка получения данных');
			// 		console.log(error);
			// 	});
			console.log(this.data);
		},
		methods: {
			sendAgain: function() {
				// console.log(this.anotherAgreement);
				console.log(this.data.id);
				// axios.post('api/addagreementanduser', {agreementId: this.anotherAgreement.agreementId, userId: this.anotherAgreement.userId}, {
				// 	headers: {
				// 		"Content-Type": "application/json"
				// 	}
				// })
				// .then(response => {
				// 	if (response.data.error == 0) {
				// 		console.log(response.data.result.id);
				// 		// this.userMessage = 1;
				// 		this.$router.go();
				// 	} else {
				// 		// this.userMessage = 2;
				// 		console.log(response.data);
				// 	}
				// }).catch(error => {
				// 	alert('Ошибка получения данных');
				// 	this.userMessage = 2;
				// 	console.log(error);
				// });

				// console.log(this.anotherAgreement);
			},
			addAgree: function(index) {
				if (this.docAgree.length < 5) {
					this.docAgree.push(this.docAgreeItem);
				}
			},
			removeAgree: function(index) {
				this.docAgree.splice(index, 1);
			},
			getAgreers: function(data) {
				this.agree = data;
			},
			getAgreementType: function(data) {
				this.withoutAgr = data;
			},
		}
	}
</script>