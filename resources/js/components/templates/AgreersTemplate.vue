<template>
	<div>
		<div class="form-group row">
			<div class="col-md-12 d-flex justify-content-between align-items-center mb-4">
				<label for="agreement" class="col-form-label">Отправить на <span v-if="docType!=null">
					<template v-if="docType.id==6">рассмотрение</template><template v-else>согласование</template></span>:</label>
				<div class="col-form-label font-bold some-transition txt-shad" v-if="docType.id==6">
					<i v-if="withoutAgr==true" class="fas fa-check-double fa-lg"/>&nbsp;&nbsp;<span v-if="docType!=null">Без рассмотрения</span>
				</div>
				<div v-else class="col-form-label cursor-point some-transition txt-shad" @click="withOut()">
					<div class="font-bold" v-if="withoutAgr==true" title="Нажмите чтобы отключить режим 'Без согласования'">
						<i class="fas fa-check-double fa-lg alert-red"/>&nbsp;&nbsp;<span v-if="docType!=null">Без согласования</span>
					</div>
					<div v-else class="greytxt" title="Нажмите чтобы включить режим 'Без согласования'">
						<i class="opacity-half fas fa-check-double fa-lg"/>&nbsp;&nbsp;<span v-if="docType!=null">Без согласования</span>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group row">
			<div class="col-md-12 d-flex" v-if="agreementType===1">
				<div class="agr-title flex-fill ta-center font-bold font-up p-1 p-sm-2">
					Групповое согласование
				</div>
				<div class="agr-title flex-fill ta-center font-up p-1 p-sm-2 cursor-point greytxt" @click="toggleAgrType(2)">
					Цепь согласования
				</div>
			</div>
			<div class="col-md-12 d-flex" v-if="agreementType===2">
				<div class="agr-title flex-fill ta-center font-up p-1 p-sm-2 cursor-point greytxt" @click="toggleAgrType(1)">
					Групповое согласование
				</div>
				<div class="agr-title flex-fill ta-center font-bold font-up p-1 p-sm-2">
					Цепь согласования
				</div>
			</div>
		</div>
		<template v-if="withoutAgr==true" title="Отключите режим 'Без согласования' для использования">
			<div class="form-group row">
				<div class="col-md-12">
					<vue-multiselect :options="usersList" :multiple="true" :close-on-select="false" :clear-on-select="true" :preserve-search="true" placeholder="Поиск пользователей" track-by="id" :preselect-first="false" no-options="Список пуст" disabled>
						<template slot="selection" slot-scope="{ values, search, isOpen }">
							<span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span>
						</template>
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
	            <label for="deadline" class="col-12 col-md-5 col-form-label text-md-right" style="line-height: 12px;">
	            	Срок согласования до:<br>
	            	<small class="form-text text-muted greytxt ml-1">
	            		(включительно)
	            	</small>
	            </label>
	            <div class="col-12 col-md-7">
	                <vue-datepicker id="deadline" valueType="format" format="DD.MM.YYYY" :disabled-date="datePickerOps.disabledDate" disabled style="width: inherit;" placeholder="ДД.ММ.ГГГГ"/>
	            </div>
	        </div>
		</template>
		<template v-else-if="withoutAgr==false">
			<div class="form-group row" v-if="agreementType===1">
				<div class="col-md-12">
					<vue-multiselect v-model="$parent.agreeValue" :options="usersList" :multiple="true" :close-on-select="false" :clear-on-select="true" :preserve-search="true" placeholder="Поиск пользователей" :custom-label="$root.namesFull" track-by="id" :preselect-first="false" :max="10" select-label="Enter чтобы выбрать" deselect-label="Enter чтобы удалить" selected-label="Выбрано" no-options="Список пуст">
						<template slot="selection" slot-scope="{ values, search, isOpen }">
							<span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">Пользователей выбрано: {{ values.length }}</span>
						</template>
						<template slot="noOptions">
							Список пуст.
						</template>
						<template slot="noResult">
							Ничего не найдено...
						</template>
					</vue-multiselect>
					<div v-for="item in $parent.agreeValue" class="font-bold m-2">
						<i class="fas fa-check-circle"/>&nbsp;&nbsp;{{ $root.makeFio(item.surname, item.firstname, item.patronymic) }}
					</div>
				</div>
			</div>
			<div class="form-group row" v-else-if="agreementType===2">
		        <div class="col-md-12">
		        	<vue-multiselect v-model="$parent.agreeValue" :options="usersList" :multiple="true" :close-on-select="false" :clear-on-select="true" :preserve-search="true" placeholder="Поиск пользователей" :custom-label="$root.namesFull" track-by="id" :preselect-first="false" :max="10" select-label="Enter чтобы выбрать" deselect-label="Enter чтобы удалить" selected-label="Выбрано">
						<template slot="selection" slot-scope="{ values, search, isOpen }">
							<span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">Пользователей выбрано:&nbsp;{{ values.length }} </span>
						</template>
						<template slot="noOptions">
							Список пуст.
						</template>
						<template slot="noResult">
							Ничего не найдено...
						</template>
					</vue-multiselect>
					<div v-for="(item, index) in $parent.agreeValue" class="font-bold m-2">
						{{ index+1 }}.&nbsp;&nbsp;{{ $root.makeFio(item.surname, item.firstname, item.patronymic) }}
					</div>
		        </div>
		    </div>
		    <div class="form-group row">
	            <label for="deadline" class="col-12 col-md-5 col-form-label text-md-right">
	            	Срок согласования до:
	            </label>
	            <div class="col-12 col-md-7">
	                <vue-datepicker id="deadline" v-model="$parent.deadline" valueType="format" format="DD.MM.YYYY" :disabled-date="datePickerOps.disabledDate" class="cursor-point" :lang="lang" style="width: inherit;" placeholder="ДД.ММ.ГГГГ"/>
	            </div>
	        </div>
		</template>
	</div>
</template>
<script>
	export default {
		props: {
			usersList: Array,
			docType: Object,
			getAgreers: Function,
			// getAgreementType: Function,
		},
		data() {
			return {
				agreementType: 1,
				withoutAgr: true,
				docAgree: [],
				value: [],
				yesterday: null,
				today: null,
				datePickerOps: {
					disabledDate: (date) => date < this.today,
				},
				lang: {
		          	formatLocale: {
		            	firstDayOfWeek: 1,
		          	},
		        },
			}
		},
		created() {
			this.today = new Date();
			this.today.setDate(this.today.getDate() - 1);
		},
		methods: {
			toggleAgrType: function(n) {
				// this.agreementType = (this.agreementType === 1) ? 2 : 1;
				this.agreementType = n;
				// this.getAgreementType(n);
				this.$parent.getAgreementType(n);
				this.value = [];
				this.docAgree = [];
				this.docAgree.push(this.docAgreeItem);
			},
			withOut: function() {
				this.withoutAgr = (this.withoutAgr == true) ? false : true;
				if (this.withoutAgr === true) {
					this.$parent.getAgreementType(0);
					this.$parent.agreeValue = [];
					this.docAgree = [];
					this.value = [];
					this.$parent.deadline = null;
				} else if(this.withoutAgr === false) {
					if (this.agreementType === 1) {
						this.$parent.getAgreementType(1);
						this.docAgree.push(this.docAgreeItem);
					} else if (this.agreementType === 2) {
						this.$parent.getAgreementType(2);
						this.docAgree.push(this.docAgreeItem);
					}
				}
			},
			addAgree: function(index) {
				if (this.docAgree[this.docAgree.length - 1] != null) {
					if (this.docAgree.length < 5) {
						this.docAgree.push(this.docAgreeItem);
					} 
				} else {
					console.log('пусто');
				}
			},
			removeAgree: function(index) {
				this.docAgree.splice(index, 1);
			},
			getAgree: function() {
				let agarr = [];
				if (this.withoutAgr === false) {
					this.value.forEach(item => {
						agarr.push({
							id: item.id,
						})
					});
				}
				this.getAgreers(agarr);
			},
			userExists: function(id) {
				if (this.docAgree.indexOf(id)!=-1) {
					return true;
				} else {
					return false;
				}
			},
		}
	}
</script>