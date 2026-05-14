<template>
	<span :title="text">
		{{ frmtTitle(text, length) }}
	</span>
</template>
<script>
	export default {
		props: {
			text: String,
			length: Number,
		},
		methods: {
			frmtTitle: function(str, len) {
				if (str.length > len) {
					let n = str.slice(0, len).trim() + '...';
					return n;
				}
				return str;
			},
		}
	}
</script>