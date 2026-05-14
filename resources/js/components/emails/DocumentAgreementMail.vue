<template>
	<div>
		<span>
			{{ name }}
		</span>
		<br/>
		<span>
			{{ data }}
		</span>
	</div>
</template>
<script>
	export default {
		props: {
			name: String,
			data: String,
		}
	}
</script>