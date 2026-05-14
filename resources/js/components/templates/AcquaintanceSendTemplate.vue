<template>
	<!-- <template v-if="$parent.docData.status[0].docstatusId!=5"> -->
		<div v-if="newSend==false" class="row d-flex justify-content-between align-items-center">
			<div class="col-12 col-md-7 d-flex justify-content-center justify-content-md-start">
				<button class="btn btn-addnew" @click="$parent.initSend()">
					<i class="fas fa-list fa-lg"/>&nbsp;&nbsp;Отправить на ознакомление
				</button>
			</div>
			<div class="col-12 col-md-5 d-flex justify-content-center justify-content-md-end pl-md-0" v-if="$parent.acqDocList.length>0">
				<button class="btn btn-secondary btn-big width-sm-inherit" data-toggle="modal" data-target="#acquaintanceListPageModal" @click="$parent.getAcqDocList()">
					<i class="far fa-list-alt fa-lg"/>&nbsp;&nbsp;На ознакомлении
				</button>
			</div>
		</div>
		<div v-else-if="newSend==true">
			<div class="card mb-4">
				<div class="card-header card-header_custom">
					<span class="font-up font-bold">
						Отправление на ознакомление
					</span>
					<div class="newuser_close shad-hover" @click="$parent.closeIt()">
						<i class="fas fa-times fa-lg"/>
					</div>
				</div>
				<div class="card-body">
					<div v-if="sendMessage===1" class="alert alert-success d-flex justify-content-between align-items-center">
				        <div>
				        	<i class="far fa-thumbs-up fa-lg"/>&nbsp;&nbsp;Вы успешно отправили документ на ознакомление
				        </div>
				    </div>
				    <div v-else-if="sendMessage===2" class="alert alert-danger d-flex justify-content-between align-items-center">
				    	<div>
				    		<i class="fas fa-exclamation-circle fa-lg"/>&nbsp;&nbsp;Не удалось отправить документ на ознакомление...
				    	</div>
				    </div>
				    <div v-else-if="sendMessage===3" class="alert alert-warning d-flex justify-content-between align-items-center">
				    	<div>
				    		<i class="fas fa-exclamation-triangle fa-lg"/>&nbsp;&nbsp;Пользователю уже был отправлен документ...
				    	</div>
				    </div>
				    <div v-else-if="sendMessage===4" class="alert alert-warning d-flex justify-content-between align-items-center">
				    	<div>
				    		<i class="fas fa-exclamation-triangle fa-lg"/>&nbsp;&nbsp;Вы не выбрали пользователя...
				    	</div>
				    </div>
				    <div v-else-if="sendMessage===0" class="alert alert-dark d-flex justify-content-between align-items-center">
				    	<div>
				    		<i class="fas fa-exclamation-triangle fa-lg"/>&nbsp;&nbsp;Если Вы не видите искомого пользователя в списке, значит скорее всего данная карточка уже была ему послана...
				    	</div>
				    </div>
                    <vue-multiselect v-model="$parent.value" :options="usersList" placeholder="Выберите пользователя" label="title" track-by="id" select-label="Enter чтобы выбрать" deselect-label="Enter чтобы удалить" selected-label="Выбрано" :searchable="true" :multiple="true" :custom-label="$root.namesFull">
                    	<template slot="noOptions" slot-scope="props">
							Список пуст.
						</template>
						<template slot="noResult" slot-scope="props">
							Ничего не найдено...
						</template>
                    </vue-multiselect>
					<br/>
					<div>
						<button class="btn btn-success btn_smaller no-round font-up font-bold" @click="$parent.sendDoc()">
							Отправить
						</button>
					</div>
				</div>
			</div>
		</div>
	<!-- </template> -->
</template>
<script>
	export default {
		props: {
			usersList: Array,
			sendMessage: Number,
			newSend: Boolean,
		},
	}
</script>