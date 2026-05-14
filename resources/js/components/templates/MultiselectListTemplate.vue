<template>
	<div class="col-12 my-1" v-if="type==='fio'">
		<template v-for="item in arr">
			<div class="mb-2" v-if="count===true">
				{{ index+1 }}.&nbsp;&nbsp;{{ $root.makeFio(item.surname, item.firstname, item.patronymic) }}
			</div>
			<div class="mb-2" v-else>
				<i class="fas fa-check-circle"/>&nbsp;&nbsp;{{ $root.makeFio(item.surname, item.firstname, item.patronymic) }}
			</div>
		</template>
	</div>
	<div class="col-12 my-1" v-else-if="type==='doc'">
		<template v-for="item in arr">
			<a :href="'/document-'+ item.id" target='_blank' class='undeline_none font-bold m-2' title='Открыть в новом окне'>
				<div v-if="count===true">
					{{ index+1 }}.&nbsp;&nbsp;<vue-td-formattedtitle :text="item.description" :length='40'/><i class='fas fa-external-link-alt fa-lg'/>
				</div>
				<div v-else>
					<i class="fas fa-check-circle"/>&nbsp;&nbsp;<vue-td-formattedtitle :text="item.description" :length='40'/><i class='fas fa-external-link-alt fa-lg'/>
				</div>
			</a>
		</template>
	</div>
	<div class="col-12 my-1" v-else-if="type==='assign'">
		<template v-for="item in arr">
			<a :href="'/assignment-'+ item.id" target='_blank' class='undeline_none font-bold m-2' title='Открыть в новом окне'>
				<div v-if="count===true">
					{{ index+1 }}.&nbsp;&nbsp;<vue-td-formattedtitle :text="item.text" :length='40'/><i class='fas fa-external-link-alt fa-lg'/>
				</div>
				<div v-else>
					<i class="fas fa-check-circle"/>&nbsp;&nbsp;<vue-td-formattedtitle :text="item.text" :length='40'/><i class='fas fa-external-link-alt fa-lg'/>
				</div>
			</a>
		</template>
	</div>
</template>
<script>
	export default {
		props: {
			arr: Array,
			type: String,
			count: {
				type: Boolean,
				default: false,
			},
		},
	}
</script>