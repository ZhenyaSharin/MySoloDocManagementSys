<template>
	<div v-if="fileLoad==true">
		<div v-if="fileFormat=='pdf'">
			<a :href="link" :title="title" class="alert-red" target="_blank">
				<i class="fas fa-file-pdf fa-lg"/>&nbsp;&nbsp;Открыть файл&nbsp;&nbsp;<i class="fas fa-external-link-alt fa-lg"></i>
			</a>
		</div>
		<div>
			<a v-if="fileName!=null" :href="link" :title="title" :download="fileName + '.' + fileFormat">
				<i class="fas fa-file-download fa-lg"/>&nbsp;&nbsp;{{ makeShort(title) }}
			</a>
			<a v-else :href="link" :title="title" download>
				<i class="fas fa-file-download fa-lg"/>&nbsp;&nbsp;{{ makeShort(title) }}
			</a>
		</div>
	</div>
	<div v-else>
		<div v-if="blank==true">
			<a v-if="pic==true" :href="link" :title="title" target="_blank">
				<i class="fas fa-file-download fa-lg"/>&nbsp;&nbsp;{{ makeShort(title) }}
			</a>
			<a v-else :href="link" :title="title" target="_blank">
				{{ makeShort(title) }}
			</a>
		</div>
		<div v-else>
			<a v-if="pic==true" :href="link" :title="title">
				<i class="fas fa-file-download fa-lg"/>&nbsp;&nbsp;{{ makeShort(title) }}
			</a>
			<a v-else :href="link" :title="title">
				{{ makeShort(title) }}
			</a>
		</div>
	</div>
</template>
<script>
	export default {
		props: {
			link: String,
			title: String,
			pic: {
				type: Boolean,
				default: false,
			},
			blank: {
				type: Boolean,
				default: false,
			},
			fileName: {
				type: String,
				default: null,
			},
			fileLoad: {
				type: Boolean,
				default: false,
			},
			fileFormat: {
				type: String,
				default: null,
			},
		},
		methods: {
			makeShort: function(str) {
				if (str.length > 30) {
					return str.slice(0, 30) + '...';
				} else {
					return str;
				}
			},
		},
	}
</script>