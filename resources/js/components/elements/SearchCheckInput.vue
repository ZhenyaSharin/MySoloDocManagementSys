<template>
	<div class="d-flex mr-2 mb-2" v-for="(item, index) in mainArray">
       	<div v-if="array.includes(item.id)" class="d-flex flex-wrap greyrow">
			<div class="p-2">
				<input :id="'doctype'+ index" type="checkbox" aria-label="1й" :value="item.id" v-model="docTypesArr" checked>
			</div>
			<label class="py-2 pr-3 m-0 cursor-point" :for="'doctype'+ index">
				{{ item.title }}
			</label>
		</div>
		<div v-else class="d-flex flex-wrap greyrow">
			<div class="p-2">
				<input :id="'doctype'+index" type="checkbox" aria-label="1й"  v-model="docTypesArr">
			</div>
			<label class="py-2 pr-3 m-0 cursor-point" :for="'doctype'+ index">
				{{ item.title }}
			</label>
		</div>
	</div>
</template>
<script>
	export default {
		props: {
			mainArray: Array,
			array: Array,
			getArr: Function,
		},

	}
</script>