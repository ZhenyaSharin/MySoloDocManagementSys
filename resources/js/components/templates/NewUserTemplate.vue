<template>
	<div class="col-md-12">
	    <div class="alert alert-success d-flex justify-content-between align-items-center" v-if="userMessage==1">
	        <div>
	        	<i class="far fa-thumbs-up fa-lg"/>&nbsp;&nbsp;Вы успешно добавили нового пользователя
	        </div>
	        <div class="shad-hover noteclose cursor-point greytxt" title="Закрыть уведомление" @click="closeMsg()">
	        	<i class="fas fa-times fa-lg"/>
	        </div>
	    </div>
	    <div class="alert alert-danger d-flex justify-content-between align-items-center" v-else-if="userMessage==2">
	    	<div>
	    		<i class="fas fa-exclamation-circle fa-lg"/>&nbsp;&nbsp;Не удалось добавить пользователя, возникла ошибка...
	    	</div>
	    	<div class="shad-hover noteclose cursor-point greytxt" title="Закрыть уведомление" @click="closeMsg()">
	        	<i class="fas fa-times fa-lg"/>
	        </div>
	    </div>
    	<div v-if="newser===0">
    		<button @click="initNew()" class="btn btn-addnew">
        		<i class="fas fa-plus-square fa-lg"/>&nbsp;&nbsp;Добавить нового пользователя
        	</button>
    	</div>
        <div v-else class="card">
			<div class="card-header card-header_custom font-bold font-up">
				Создать нового пользователя
			</div>
			<div class="newuser_close" @click="closeIt()">
				<i class="fas fa-times fa-lg"/>
			</div>
			<div class="card-body">
		        <div class="row">
		            <div class="col-md-6">
		                <div class="form-group row">
		                    <label for="email" class="font-bold col-md-5 col-form-label text-md-right">
		                    	Эл.почта
		                    </label>
		                    <div class="col-md-7">
		                        <input id="email" type="email" class="form-control" name="email" value="" required autocomplete="email" v-model="info.email">

		<!--                             <span class="invalid-feedback" role="alert">
		                                <strong>{{ $message }}</strong>
		                            </span> -->
		                    </div>
		                </div>
		                <div class="form-group row">
		                    <label for="surname" class="font-bold col-md-5 col-form-label text-md-right">
		                    	Фамилия
		                    </label>
		                    <div class="col-md-7">
		                        <input id="surname" type="text" class="form-control" name="login" value="" required autocomplete="surname" autofocus v-model="info.surname">

		<!--                             <span class="invalid-feedback" role="alert">
		                                <strong>{{ $message }}</strong>
		                            </span> -->
		                    </div>
		                </div>

		                <div class="form-group row">
		                    <label for="firstname" class="font-bold col-md-5 col-form-label text-md-right">
		                    	Имя
		                    </label>
		                    <div class="col-md-7">
		                        <input id="firstname" type="text" class="form-control" name="login" value="" required autocomplete="firstname" autofocus v-model="info.firstname">

		<!--                             <span class="invalid-feedback" role="alert">
		                                <strong>{{ $message }}</strong>
		                            </span> -->
		                    </div>
		                </div>

		                <div class="form-group row">
		                    <label for="patronymic" class="font-bold col-md-5 col-form-label text-md-right">
		                    	Отчество
		                    </label>
		                    <div class="col-md-7">
		                        <input id="patronymic" type="text" class="form-control" name="login" value="" required autocomplete="patronymic" autofocus v-model="info.patronymic">

		<!--                             <span class="invalid-feedback" role="alert">
		                                <strong>{{ $message }}</strong>
		                            </span> -->
		                    </div>
		                </div>
		            </div>
		            <div class="col-md-6">
		            	<div class="form-group row">
		                    <label for="login" class="font-bold col-md-3 col-form-label text-md-right">
		                    	Логин
		                    </label>
		                    <div class="col-md-9">
		                        <input id="login" type="text" class="form-control" name="login" value="" required autocomplete="login" autofocus v-model="info.login">

		<!--                             <span class="invalid-feedback" role="alert">
		                                <strong>{{ $message }}</strong>
		                            </span> -->
		                    </div>
		                </div>

		                <div class="form-group row">
		                    <label for="password" class="font-bold col-md-3 col-form-label text-md-right">
		                    	Пароль
		                    </label>
		                    <div class="col-md-9">
		                        <input id="password" type="password" class="form-control" name="password" required autocomplete="new-password" v-model="info.password">
		<!-- 
		                            <span class="invalid-feedback" role="alert">
		                                <strong>{{ $message }}</strong>
		                            </span> -->
		                    </div>
		                </div>
						<div class="form-group row">
		                    <label for="department" class="font-bold col-md-3 col-form-label text-md-right pl-1">
		                    	Роль
		                    </label>
		                    <div class="col-md-9">
	                            <select class="form-control" name="role" id="inputDepartment" required autocomplete="role" v-model="info.roleId">
	                                <template v-for="item in $parent.roles">
	                                    <option v-if="item.id==info.roleId" :value="item.id" selected>
	                                        {{ item.title }}
	                                    </option>
	                                    <option v-else :value="item.id">
	                                        {{ item.title }}
	                                    </option>
	                                </template>
	                            </select>
		                    </div>
		                </div>
		                <div class="form-group row">
		                    <label for="department" class="font-bold col-md-3 col-form-label text-md-right pl-1">
		                    	Подразделения
		                    </label>
		                    <div class="col-md-9">
		                        <vue-template-departmentadd :deps="departments" :list="$parent.departments" :get-deps="getDeps"/>
		                    </div>
		                </div>
		            </div>
		        </div>
		        <div class="form-group row mb-0 mt-4">
		            <div class="col-md-6 offset-md-5">
		                <button @click="addNewUser()" class="btn btn-primary font-bold no-round font-up p-3 box-shad">
		                    Создать пользователя
		                </button>
		            </div>
		        </div>
		    </div>
		</div>
	</div>
</template>
<script>
	export default {
		data() {
			return {
				newser: 0,
				userMessage: 0,
				info: {
					login: '',
					surname: '',
					firstname: '',
					patronymic: '',
					department: 0,
					email: '',
					password: '',
					passwordDouble: '',
					roleId: 2,
					department: [],
					adminId: null,
				},
				departments: [],
				doublePass: '',
				depValue: [],
			}
		},
		mounted() {
			// console.log(this.$parent.userId);
		},
		methods: {
			initNew: function() {
				this.newser = 1;
			},
			addNewUser: function() {
				if (this.departments != null) {
					this.info.adminId = this.$parent.userId;
					this.info.department = JSON.stringify(this.departments);
					axios.post('api/adduser', this.info, {
                        headers: {
                            "Content-Type": "application/json"
                        }
                    })
                    .then(response => {
                        if (response.data.error == 0) {
                            if (response.data.result != null) {
                                console.log('просмотрено');
                                this.userMessage = 1;
                                this.$parent.usersList();
                                this.refreshInfo();
                            } else {
                            	this.userMessage = 2;
                                alert('Ошибка отправки данных');
                            }
                        } else {
                        	this.userMessage = 2;
                            // alert(response.data.error_message);
                        }
                    }).catch(error => {
                        alert('Ошибка получения данных');
                        console.log(error);
                    });
				}
    			// console.log(this.info);
			},
			closeIt: function() {
				this.newser = 0;
				this.refreshInfo();
				this.closeMsg();
			},
			refreshInfo: function() {
				this.info = {
					login: '',
					surname: '',
					firstname: '',
					patronymic: '',
					department: [],
					email: '',
					password: '',
					passwordDouble: '',
					department: [],
					roleId: 2,
					adminId: this.$parent.userId,
				};
				this.departments = [];
				this.depValue = [];
			},
			getDeps: function(data) {
                // console.log(data);
                this.departments = data.data;
            },
            closeMsg: function() {
  				this.userMessage = 0;
			},
		},
	}
</script>