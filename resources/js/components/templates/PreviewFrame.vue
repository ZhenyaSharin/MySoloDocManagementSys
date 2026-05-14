<template>
	<div>
		<vue-alert-inwork/>
		<iframe class="previev-frame" width="520" height="680" :src = "fullFile" allowfullscreen webkitallowfullscreen/>
	</div>
</template>
<script>
	export default {
		props: {
			file: Object,
		},
		data() {
			return {
				fullFile: '',
				exists: false,
				link: null,
			}
		},
		mounted() {
			// this.link = (this.$root.storage == 'coresar') ? '/storage/app/public/' : '/storage/';
			this.link = '/storage/';
			this.fullFile = this.link + 'pdfs/'+ this.file.file +'.pdf'; //coresar
		},
	}
</script>