<template>
    <div class="modal fade" id="userprofile_modal" tabindex="-1" role="dialog" aria-labelledby="userprofile_modal_label1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content no-round">
                <div class="modal-header">
                    <h5 class="modal-title font-up font-bold" id="userprofile_modal_label1">
                        Информация о пользователе
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-sm-6 offset-sm-4">
                            <a class="font-bold mb-2" :href="'/id_'+ data.login">
                                Страница пользователя &nbsp;<i class="fas fa-external-link-alt fa-lg"/>
                            </a>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="user__mod_login" class="col-sm-4 col-form-label text-sm-right">
                            Логин
                        </label>
                        <div class="col-sm-6 userprofile__item">
                            <input type="text" class="form-control-plaintext" id="user__mod_login" v-model="data.login">
                            <div class="col-sm-1 userprofile__edit">
                                <i class="fas fa-edit fa-lg"/>
                            </div>
                        </div>  
                    </div>
                    <div class="form-group row">
                        <label for="user__mod_email" class="col-sm-4 col-form-label text-sm-right">
                            Электронная почта
                        </label>
                        <div class="col-sm-6 userprofile__item">
                            <input type="text" class="form-control-plaintext" id="user__mod_email" v-model="data.email">
                            <div class="col-sm-1 userprofile__edit">
                                <i class="fas fa-edit fa-lg"/>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="user__mod_surname" class="col-sm-4 col-form-label text-sm-right">
                            Фамилия
                        </label>
                        <div class="col-sm-6 userprofile__item">
                            <input v-if="data.surname" type="text" class="form-control-plaintext" id="user__mod_surname" v-model="data.surname">
                            <input v-else type="text" class="form-control-plaintext" id="user__mod_surname" placeholder="Не указано">
                            <div class="col-sm-1 userprofile__edit">
                                <i class="fas fa-edit fa-lg"/>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="user__mod_firstname" class="col-sm-4 col-form-label text-sm-right">
                            Имя
                        </label>
                        <div class="col-sm-6 userprofile__item">
                            <input v-if="data.firstname" type="text" class="form-control-plaintext" id="user__mod_firstname" v-model="data.firstname">
                            <input v-else type="text" class="form-control-plaintext" id="user__mod_firstname" readonly placeholder="Не указано">
                            <div class="col-sm-1 userprofile__edit">
                                <i class="fas fa-edit fa-lg"/>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="user__mod_patronymic" class="col-sm-4 col-form-label text-sm-right">
                            Отчество
                        </label>
                        <div class="col-sm-6 userprofile__item">
                            <input v-if="data.patronymic" type="text" class="form-control-plaintext" id="user__mod_patronymic" v-model="data.patronymic">
                            <input v-else type="text" class="form-control-plaintext" id="user__mod_patronymic" readonly placeholder="Не указано">
                            <div class="col-sm-1 userprofile__edit">
                                <i class="fas fa-edit fa-lg"/>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputDepartment" class="col-sm-4 col-form-label text-sm-right">
                            Подразделение
                        </label>
                        <div class="col-sm-6">
<!--                             <select class="form-control" name="department" id="inputDepartment"required autocomplete="department" v-model="info.department">
                                <template v-for="item in $parent.departments">
                                    <option v-if="item.id==info.department" :value="item.id" selected>
                                        {{ item.title }}
                                    </option>
                                    <option v-else :value="item.id">
                                        {{ item.title }}
                                    </option>
                                </template>
                            </select> -->
                            <vue-template-departmentadd :deps="data.department" :list="$parent.departments" :get-deps="getDeps"/>
                        </div>
                    </div>
                    <div class="form-group row greyPassword mb-2">
                        <label for="inputRole" class="col-sm-4 col-form-label text-sm-right">
                            Роль
                        </label>
                        <div class="col-sm-6">
                            <select class="form-control" name="role" id="inputDepartment" required autocomplete="role" v-model="data.roleId">
                                <template v-for="item in $parent.roles">
                                    <option v-if="item.id==data.roleId" :value="item.id" selected>
                                        {{ item.title }}
                                    </option>
                                    <option v-else :value="item.id">
                                        {{ item.title }}
                                    </option>
                                </template>
                            </select>
                        </div>
                        <div class="col-md-8 offset-4">
                            <button class="btn btn-warning no-round mt-3 font-bold" @click="updateRole(data.roleId)" data-dismiss="modal">
                                <span v-if="isUpdating==false">
                                    <i class="fas fa-sync-alt fa-lg"/>&nbsp;&nbsp;Изменить
                                </span>
                                <span v-else style="font-size: 0.3em;">
                                    <vue-spinner/>
                                </span>
                            </button>
                        </div>
                    </div>
                    <div class="form-group row greyPassword">
                        <label for="newPassword" class="col-sm-4 col-form-label text-sm-right">
                            Новый пароль
                        </label>
                        <div class="col-sm-6">
                            <input type="password" minlength="9" class="form-control ml-0" id="newPassword" placeholder="Мин. 9 символов" name="newPassword" v-model="$parent.newPassword">
                            <small class="form-text status_refused ml-1" v-if="$parent.newPasswordErr==true">
                                <i class="fas fa-exclamation-circle"/>&nbsp;&nbsp;Произошла ошибка...
                            </small>
                            <small class="form-text status_approved ml-1" v-if="$parent.newPasswordOk==true">
                                <i class="fas fa-exclamation-circle"/>&nbsp;&nbsp;Пароль успешно обновлён...
                            </small>
                            <button class="btn btn-warning no-round mt-3 font-bold" @click="refreshPass()">
                                <span v-if="isCreating==false">
                                    <i class="fas fa-sync-alt fa-lg"/>&nbsp;&nbsp;Обновить
                                </span>
                                <span v-else style="font-size: 0.3em;">
                                    <vue-spinner/>
                                </span>
                            </button>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-6 offset-sm-4 mt-4">
                            <button v-if="data.removed==null" class="btn btn-danger no-round" @click="removeUser(1)" data-dismiss="modal" >
                                <i class="fas fa-trash-alt"/>&nbsp;Удалить/заблокировать пользователя
                            </button>
                            <button v-else class="btn btn-success no-round" @click="removeUser(0)" data-dismiss="modal">
                                Разблокировать пользователя
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-primary no-round" @click="updateUser()" data-dismiss="modal">
                        Сохранить изменения
                    </button>
                    <button type="button" class="btn btn-secondary no-round" data-dismiss="modal">
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
            departments: Array,
            data: Object,
        },
        data() {
            return {
                isCreating: false,
                isUpdating: false,
            }
        },
        methods: {
            removeUser: function(del) {
                if (this.data.roleId != 1) {
                    axios.post('/api/updateuser', {
                            id: this.data.id, 
                            login: this.data.login, 
                            delete: del
                        }, {
                            headers: {
                                "Content-Type": "application/json"
                        }
                    })
                    .then((response) => {
                        if (response.data.error === '0') {
                            // window.location.reload(true);
                            this.$parent.usersList();
                            // this.$router.go();
                        } else {
                            alert(response.data.error + ' : ' + response.data.result);
                        }
                        // console.log(this.users);
                    })
                    .catch(error => {
                        console.log(error)
                    });
                }
            },
            updateUser: function() {
                let arr;
                arr = {
                    id: this.data.id,
                    login: this.data.login,
                    email: this.data.email,
                    surname: this.data.surname,
                    firstname: this.data.firstname,
                    patronymic: this.data.patronymic,
                    department: JSON.stringify(this.data.department),
                    roleid: this.data.roleid,
                };
                axios.post('api/updateuser', arr, {
                        headers: {
                            "Content-Type": "application/json"
                        }
                    })
                    .then(response => {
                        if (response.data.error == 0) {
                            if (response.data.result != null) {
                                console.log('просмотрено');
                                // this.$router.go();
                                this.$parent.usersList(this.$route.query.order);
                            } else {
                                alert('Ошибка отправки данных');
                            }
                        } else {
                            // this.userMessage = 2;
                            alert(response.data.error_message);
                        }
                    }).catch(error => {
                        alert('Ошибка получения данных');
                        console.log(error);
                    });
            },
            refreshPass: function() {
                this.$parent.newPassword = this.$parent.newPassword.trim();
                this.isCreating = true;
                this.$parent.newPasswordErr = (this.$parent.newPassword.length < 9) ? true : false;
                if ((this.$parent.newPasswordErr == false) && (this.$parent.newPassword.length >= 9)) {
                    axios.post('api/updatepassword', {id: this.data.id, password: this.$parent.newPassword}, {
                        headers: {
                            "Content-Type": "application/json"
                        }
                    })
                    .then(response => {
                        if (response.data.error == 0) {
                            if (response.data.result != null) {
                                console.log('просмотрено');
                                // this.$router.go();
                                this.$parent.usersList();
                                this.$parent.newPassword = '';
                                this.isCreating = false;
                                this.$parent.newPasswordOk = true;
                            } else {
                                alert('Ошибка отправки данных');
                                this.isCreating = false;
                            }
                        } else {
                            // this.userMessage = 2;
                            this.isCreating = false;
                            alert(response.data.error_message);
                        }
                    }).catch(error => {
                        alert('Ошибка получения данных');
                        console.log(error);
                        this.isCreating = false;
                    });
                    console.log('ewfwef');
                } else {
                    this.isCreating = false;
                    this.$parent.newPasswordErr = true;
                }
            },
            getDeps: function(data) {
                // console.log(data);
                this.data.department = data.data;
            },
            updateRole: function(roleId) {
                let data = {
                    userId: this.data.id,
                    roleId: roleId,
                    adminId: this.$parent.$parent.userId,
                };
                axios.post('/api/updaterole', data, {
                    headers: {
                        "Content-Type": "application/json"
                    }
                })
                .then((response) => {
                    if (response.data.error === '0') {
                        // window.location.reload(true);
                        this.$parent.usersList();
                        // this.$router.go();
                    } else {
                        alert(response.data.error + ' : ' + response.data.result);
                    }
                    // console.log(this.users);
                })
                .catch(error => {
                    console.log(error)
                });
            },
        }
    }
</script>