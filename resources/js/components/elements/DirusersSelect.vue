<template>
	<select class="form-select" aria-label="Default select example" style="width: 70px" @change="$root.getDocsByDiruserId(diruserId, $parent.userId)" v-model="diruserId">
		<option class="cursor-point" :value="null" selected>
			Все
		</option>
		<option class="cursor-point" v-for="item in $root.dirusersList" :value="item.id">
			<span v-if="item.firstname==null&&item.patronymic==null">
				{{ item.surname }}
			</span>
			<span v-else>
				{{ $root.makeFio(item.surname, item.firstname, item.patronymic) }}
			</span>
		</option>
	</select>
</template>
<script>
	export default {
		data() {
			return {
				diruserId: null,
			}
		},
	}
</script>