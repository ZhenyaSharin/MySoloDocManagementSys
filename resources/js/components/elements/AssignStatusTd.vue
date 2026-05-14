<template>
	<td class="ta-right status_considering timestamp_font" v-if="status.id==6">
		<template v-if="dateStatus==0">
			Просрочен
		</template>
		<template v-else-if="dateStatus==1" class="ta-right status_considering p-1">
			{{ status.title }}
			<br>
			<span class="greytxt">
				(Срок - менее 3 дней)
			</span>
		</template>
		<template v-else-if="dateStatus==2" class="ta-right status_considering">
			{{ status.title }}
		</template>
	</td>
	<td class="ta-right status_executing timestamp_font" v-else-if="status.id==7">
		<template v-if="dateStatus==0">
			Просрочен
		</template>
		<template v-else-if="dateStatus==1" class="ta-right status_executing p-1">
			{{ status.title }}
			<br>
			<span class="greytxt">
				(Срок - менее 3 дней)
			</span>
		</template>
		<template v-else-if="dateStatus==2" class="ta-right status_executing">
			{{ status.title }}
		</template>
	</td>
	<td class="ta-right status_refused timestamp_font" v-else-if="status.id==8">
		{{ status.title }}
	</td>
	<td class="ta-right status_approved timestamp_font" v-else-if="status.id==9">
		{{ status.title }}
	</td>
	<td class="ta-right status_refused timestamp_font" v-else-if="status.id==10">
		{{ status.title }}
	</td>
</template>
<script>
	export default {
		props: {
			status: Object,
			dateStatus: Number,
		},
	}
</script>