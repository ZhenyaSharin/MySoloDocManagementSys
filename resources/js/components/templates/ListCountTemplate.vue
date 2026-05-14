<template>
	<div class="d-flex mr-4">
		<div v-if="count==5">
			5 шт.&nbsp;&nbsp;
		</div>
		<div v-else class="selected_title cursor-point" @click="getFunc(5)">
			5 шт.&nbsp;&nbsp;
		</div>
		<div v-if="count==10">
			10 шт.&nbsp;&nbsp;
		</div>
		<div v-else class="selected_title cursor-point" @click="getFunc(10)">
			10 шт.&nbsp;&nbsp;
		</div>
		<div v-if="count==20">
			20 шт.
		</div>
		<div v-else class="selected_title cursor-point" @click="getFunc(20)">
			20 шт.
		</div>
	</div>
</template>
<script>
	export default {
		props: {
			getData: Function,
			count: Number,
			isDocs: {
				type: Boolean,
				default: false,
			}
		},
		methods: {
			getFunc: function(data) {
				let mode = (this.$parent.dateModeLocal) ? this.$parent.dateModeLocal : false;
				if (this.isDocs === true) {
					this.getData(this.$parent.userId, data, mode, false);
				} else {
					this.getData(data, false, mode);
				}
			}
		},
	}
</script>