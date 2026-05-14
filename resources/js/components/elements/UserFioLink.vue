<template>
	<a v-if="short==true" :href="'id_'+ data.login" class="ws-nowrap">
		{{ $root.makeFio(data.surname, data.firstname, data.patronymic) }}
	</a>
	<a v-else :href="'id_'+ data.login">
		{{ data.surname }} {{ data.firstname }} {{ data.patronymic }}
	</a>
</template>
<script>
	export default {
		props: {
			data: Object,
			short: Boolean,
		},
		// data() {
		// 	return {

		// 	}
		// }
		methods: {
			// 
		}
	}
</script>