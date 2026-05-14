<template>
	<div class="container loginpg">
	    <div class="row justify-content-center align-items-center">
	        <div class="col-sm-12 col-lg-6">
	            <div class="card">
	                <div class="card-header font-bold">{{ __('Войдите в аккаунт') }}</div>

	                <div class="card-body">
<!-- 	                    <form method="POST" action="{{ route('login') }}">
	                        @csrf -->
	                        <div class="form-group row">
	                            <label for="login" class="col-md-4 col-form-label text-md-right">
	                            	{{ __('Ваш логин') }}
	                            </label>
	                            <div class="col-md-6">
	                                <input id="login" type="login" class="form-control @error('login') is-invalid @enderror" name="login" value="{{ old('login') }}" required autocomplete="login" autofocus>

	                                @error('login')
	                                    <span class="invalid-feedback" role="alert">
	                                        <strong>
	                                        {{ $message }}</strong>
	                                    </span>
	                                @enderror
	                            </div>
	                        </div>

	                        <div class="form-group row">
	                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Пароль') }}</label>

	                            <div class="col-md-6">
	                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

	                                @error('password')
	                                    <span class="invalid-feedback" role="alert">
	                                        <strong>{{ $message }}</strong>
	                                    </span>
	                                @enderror
	                            </div>
	                        </div>

	                        <div class="form-group row">
	                            <div class="col-md-6 offset-md-4">
	                                <div class="form-check">
	                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

	                                    <label class="form-check-label" for="remember">
	                                        {{ __('Запомнить пароль') }}
	                                    </label>
	                                </div>
	                            </div>
	                        </div>

	                        <div class="form-group row mb-0">
	                            <div class="col-md-8 offset-md-4">
	                                <button type="submit" class="btn btn-primary no-round font-bold shad">
	                                    {{ __('Войти') }}
	                                </button>

	                                @if (Route::has('password.request'))
	                                    <a class="btn btn-link" href="{{ route('password.request') }}">
	                                        {{ __('Забыли пароль?') }}
	                                    </a>
	                                @endif
	                            </div>
	                        </div>
	                    <!-- </form> -->
	                </div>
	            </div>
	        </div>
	    </div>
	    <br/>
	    <br/>
	</div>
</template>
<script>
	export default {
		data() {
			return {
				login: '',
				password: '',
				remember: '',
				message: null,
			}
		},
		methods: {
			login: function() {
				axios.post('/login', {
					login: this.login,
					password: this.password,
					remember: this.remember,

				}).then(response => {
					if (response.data.error == 0) {
						this.acquaintancesList = response.data.result;
					} else {
						alert(response.data.error_message);
					}
				}).catch(error => {
					alert('Ошибка получения данных3');
					console.log(error);
				});
			};
		},
	}
</script>