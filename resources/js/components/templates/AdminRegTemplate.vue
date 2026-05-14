<template>
    <div class="container card py-3">
    	<div class="row justify-content-center align-items-center">
            <div class="col-12">
            	<h1>
    	        	Регистрация администратора
    	        </h1>
                <div class="alert alert-success d-flex justify-content-between align-items-center" v-if="userMessage==1">
                    <div>
                        <i class="far fa-thumbs-up fa-lg"/>&nbsp;&nbsp;Вы успешно добавили администратора
                    </div>
                    <div class="shad-hover noteclose cursor-point greytxt" title="Закрыть уведомление" @click="closeMsg()">
                        <i class="fas fa-times fa-lg"/>
                    </div>
                </div>
                <div class="alert alert-danger d-flex justify-content-between align-items-center" v-else-if="userMessage==2">
                    <div>
                        <i class="fas fa-exclamation-circle fa-lg"/>&nbsp;&nbsp;Не удалось добавть администратора, возникла ошибка...
                    </div>
                    <div class="shad-hover noteclose cursor-point greytxt" title="Закрыть уведомление" @click="closeMsg()">
                        <i class="fas fa-times fa-lg"/>
                    </div>
                </div>
            	<div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <span class="font-bold font-up">
                                    Создание профиля администратора
                                </span>
                            </div>
                            <div class="card-body d-flex flex-column">
                                <div class="form-group row">
                                    <div class="col-md-7 offset-md-5">
                                        <small class="form-text text-muted greytxt ml-1">
                                            В системе отстутствует профиль пользователя-администратора. Необходимо создать администратора для дальнейшей корректной работы системы...
                                        </small>  
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="adminLogin" class="col-sm-5 col-form-label text-md-right">
                                        Логин
                                    </label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="adminLogin" placeholder="Имя хоста почтового сервера" name="adminLogin" required v-model="data.login">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="adminEmail" class="col-sm-5 col-form-label text-md-right">
                                        Электронная почта
                                    </label>
                                    <div class="col-sm-7">
                                        <input type="email" class="form-control" id="adminEmail" placeholder="Введите адрес рабочей почты" name="adminEmail" v-model="data.email" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="adminSurname" class="col-sm-5 col-form-label text-md-right">
                                        Фамилия
                                    </label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="adminSurname" placeholder="Введите фамилию администратора" name="adminSurname" v-model="data.surname" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="adminFirstname" class="col-sm-5 col-form-label text-md-right">
                                        Имя
                                    </label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="adminFirstname" placeholder="Введите имя пользователя" name="adminFirstname" v-model="data.firstname" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="adminPatronymic" class="col-sm-5 col-form-label text-md-right">
                                        Отчество
                                    </label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="adminPatronymic" placeholder="Выберите отчество пользователя" v-model="data.patronymic" name="adminPatronymic">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="adminPassword" class="col-sm-5 col-form-label text-md-right">
                                        Пароль
                                    </label>
                                    <div class="col-sm-7">
                                        <input type="password" class="form-control" id="adminPassword" placeholder="Пароль профиля администратора" name="adminPassword"  v-model="data.password" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row my-4">
                                <div class="col-md-6 offset-md-5">
                                    <button v-if="spinOffAdmin===true" class="btn btn-primary font-bold no-round font-up p-3 box-shad">
                                        <vue-spinner/>
                                    </button>
                                    <button v-else @click="addNewAdmin()" class="btn btn-primary font-bold no-round font-up p-3 box-shad">
                                        Создать администратора
                                    </button>
                                </div>
                            </div>
                        </div>
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
				data: {
                    login: 'Admin',
                    email: null,
                    surname: 'Admin',
                    firstname: 'Admin',
                    patronymic: 'Admin',
                    password: null,
                    roleId: 1,
                },
                userMessage: 0,
                spinOffAdmin: false,
			}
		},
        methods: {
            addNewAdmin: function() {
                this.spinOffAdmin = true;
                axios.post('api/addadmin', this.data, {
                    headers: {
                        "Content-Type": "application/json"
                    }
                })
                .then(response => {
                    if (response.data.error == 0) {
                        if (response.data.result != null) {
                            console.log('просмотрено');
                            this.userMessage = 1;
                            this.$router.push({ path: '/'});
                            this.$router.go();
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
                    this.userMessage = 2;
                    this.spinOffAdmin = false;
                });
            },
            closeMsg: function() {
                this.userMessage = 0;
            },
        },
	}
</script>