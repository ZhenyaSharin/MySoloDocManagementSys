<template>
	<div>
		<div class="form-group row">
			<label for="department" class="col-md-4 col-form-label text-md-right">
				Адресат документа/Контрагент
			</label>
	    	<div class="col-md-8">
	    		<template v-if="docType!=null&&$parent.withoutDiruser==false">
	    			<vue-multiselect v-if="$parent.newDiruser==false" v-model="$parent.diruser" :options="$root.directUsers" placeholder="Выберите или нажмите «без адресата»" label="surname" track-by="id" select-label="Выбрать" deselect-label="Удалить" selected-label="Выбрано" :searchable="true" :custom-label="$root.namesFull">
	                	<template slot="noOptions">
	                		Список пуст.
	                	</template>
						<template slot="noResult">
							Никого не найдено...&nbsp;&nbsp;<button class="btn btn-primary no-round box-shad font-bold lil_btn" @click="initNewDiruser(true)"><i class="fa fa-plus"/>&nbsp;Добавить</button>
						</template>
	                </vue-multiselect>
	                <vue-multiselect v-else :options="$root.directUsers" placeholder="" label="surname" track-by="id" select-label="Enter чтобы выбрать" deselect-label="Enter чтобы удалить" selected-label="Выбрано" :searchable="true" disabled>
	                	<template slot="noOptions">
	                		Список пуст.
	                	</template>
						<template slot="noResult">
							Никого не найдено...
						</template>
	                </vue-multiselect>
	    		</template>
	    		<template v-else>
	    			<vue-multiselect :options="$root.directUsers" placeholder="" label="surname" track-by="id" select-label="Enter чтобы выбрать" deselect-label="Enter чтобы удалить" selected-label="Выбрано" :searchable="true" disabled>
	                	<template slot="noOptions">
	                		Список пуст.
	                	</template>
						<template slot="noResult">
							Никого не найдено...
						</template>
	                </vue-multiselect>
	    		</template>
	        </div>
		</div>
		<template v-if="docType!=null">
			<div class="form-group row" v-if="$parent.newDiruser==false">
	        	<div class="col-12 col-md-8 offset-md-4 font-bold" v-if="$parent.withoutDiruser==false">
	        		<div class="row d-flex justify-content-between">
	        			<div class="col-12 col-sm-6 ta-center">
	        				<button class="btn btn-primary font-bold ws-nowrap m-1 mx-lg-2" @click="initNewDiruser(true)">
		                    	<i class="fas fa-plus fa-lg"/>&nbsp;&nbsp;Добавить нового
		                    </button>
	        			</div>
	        			<div class="col-12 col-sm-6 ta-center">
	        				<button class="btn btn-secondary font-bold ws-nowrap m-1 mx-lg-2" @click="noDiruser(true)">
		                    	Без адресата&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		                    </button>
	        			</div>
	        		</div>
	            </div>
	            <div class="col-md-8 offset-md-4 font-bold" v-else>
	            	<div class="row d-flex justify-content-between">
	            		<div class="col-12 col-sm-6 ta-center">
	            			<button class="btn btn-primary font-bold  m-1 mx-lg-2" disabled>
		                    	<i class="fas fa-plus fa-lg"/>&nbsp;&nbsp;Добавить нового
		                    </button>
	            		</div>
	            		<div class="col-12 col-sm-6 ta-center">
		        			<button class="btn btn-secondary font-bold m-1 mx-lg-2" @click="noDiruser(false)">
		                    	Без адресатa&nbsp;&nbsp;<i class="fas fa-check-circle fa-lg"/>
		                    </button>
		                </div>
	            	</div>
	            </div>
	    	</div>
		</template>
		<div class="square_grey mb-4" v-if="$parent.newDiruser==true">
			<template v-if="diruserType==1">
				<div>
	    			<div class="form-group row">
	            		<label for="dirSurname" class="col-md-4 col-form-label text-md-right font-bold text-underline">
	            			Фамилия
	            		</label>
		            	<div class="col-md-8">
	                        <input v-model="$parent.newDiruserArr.surname" type="text" class="form-control" id="dirSurname" aria-describedby="emailHelp" placeholder="Введите фамилию" maxlength="64">
	                    </div>
	            	</div>
	            	<div class="form-group row">
	            		<label for="dirFirstname" class="col-md-4 col-form-label text-md-right font-bold text-underline">
	            			Имя
	            		</label>
		            	<div class="col-md-8">
	                        <input v-model="$parent.newDiruserArr.firstname" type="text" class="form-control" id="dirFirstname" aria-describedby="emailHelp" placeholder="Введите имя" maxlength="32">
	                    </div>
	            	</div>
	            	<div class="form-group row">
	            		<label for="dirPatronymic" class="col-md-4 col-form-label text-md-right font-bold text-underline">
	            			Отчество
	            		</label>
		            	<div class="col-md-8">
	                        <input v-model="$parent.newDiruserArr.patronymic" type="text" class="form-control" id="dirPatronymic" aria-describedby="emailHelp" placeholder="Введите отчество" maxlength="32">
	                    </div>
	            	</div>
	            </div>
			</template>
			<template v-else>
				<div @click="toggleDiruser(1)">
	    			<div class="form-group row">
	            		<label for="dirSurname" class="col-md-4 col-form-label text-md-right font-bold cursor-point diruser-select_hover">
	            			Фамилия
	            		</label>
		            	<div class="col-md-8">
	                        <input type="text" class="form-control cursor-point" id="dirSurname" aria-describedby="emailHelp" placeholder="Введите фамилию" disabled>
	                    </div>
	            	</div>
	            	<div class="form-group row">
	            		<label for="dirFirstname" class="col-md-4 col-form-label text-md-right font-bold cursor-point diruser-select_hover">
	            			Имя
	            		</label>
		            	<div class="col-md-8">
	                        <input type="text" class="form-control cursor-point" id="dirFirstname" aria-describedby="emailHelp" placeholder="Введите имя" disabled>
	                    </div>
	            	</div>
	            	<div class="form-group row">
	            		<label for="dirPatronymic" class="col-md-4 col-form-label text-md-right font-bold cursor-point diruser-select_hover">
	            			Отчество
	            		</label>
		            	<div class="col-md-8">
	                        <input type="text" class="form-control cursor-point" id="dirPatronymic" aria-describedby="emailHelp" placeholder="Введите отчество" disabled>
	                    </div>
	            	</div>
	            </div>
			</template>
			<template v-if="diruserType==2">
				<div>
	            	<div class="form-group row mb-0">
	            		<label for="dirFirm" class="col-md-4 col-form-label text-md-right font-bold text-underline">
	            			Организация
	            		</label>
		            	<div class="col-md-8">
	                        <input v-model="$parent.newDiruserArr.surname" type="text" class="form-control" id="dirFirm" placeholder="Введите название организации" maxlength="128">
	                    </div>
	            	</div>
	            </div>
			</template>
			<template v-else>
				<div @click="toggleDiruser(2)">
					<div class="form-group row mb-0">
	            		<label for="dirFirm" class="col-md-4 col-form-label text-md-right font-bold cursor-point diruser-select_hover">
	            			Организация
	            		</label>
		            	<div class="col-md-8">
	                        <input type="text" class="form-control cursor-point" id="dirFirm" placeholder="Введите название организации" disabled  maxlength="64">
	                    </div>
	            	</div>
				</div>
			</template>
	    	<div class="cursor-point font-bold shadow__lit" id="hideNewDiruser" @click="initNewDiruser(false)">
	    		Скрыть&nbsp;<i class="fas fa-times fa-lg"/>
	    	</div>
		</div>
	</div>
</template>
<script>
	export default {
		props: {
			// newDiruser: Boolean,
			docType: Number,
		},
		data() {
			return {
				diruserType: 1,
			}
		},
		methods: {
			noDiruser: function(n) {
				this.$parent.withoutDiruser = n;
				this.$parent.newDiruserArr = {
					surname:  null,
					firstname: null,
					patronymic: null,
				};
				this.$parent.dirusers = null;	
			},
			initNewDiruser: function(n) {
				this.$parent.newDiruser = n;
				this.$parent.newDiruserArr = {
					surname: null,
					firstname: null,
					patronymic: null,
				}
			},
			toggleDiruser: function(type) {
            	this.diruserType = type;
            	this.$parent.newDiruserArr = {
					surname: null,
					firstname: null,
					patronymic: null,
				};
            },
		},
	}
</script>