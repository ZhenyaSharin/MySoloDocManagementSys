<template>
	<div class="d-flex justify-content-center cursor-point" @click="$root.toggleDateMode(7)" title="Порядок по дате документа/дате создания карточки документа">
		<div v-if="$root.dateMode==0">
			<div class="ta-center ws-nowrap">
				{{ title }}
			</div>
			<div class="ta-center ws-nowrap mode-off">
				Дата создания
			</div>
		</div> 
		<div v-else-if="$root.dateMode==1">
			<div class="ta-center ws-nowrap mode-off">
				{{ title }}
			</div>
			<div class="ta-center ws-nowrap">
				Дата создания
			</div>
		</div>  
		<div class="ml-1 d-flex justify-content-center align-items-center">
			<i class="fas fa-arrows-alt-v fa-2x"/>
		</div>
	</div>
</template>
<script>
	export default {
		props: {
			title: {
				type: String,
				default: "Дата документа",
			},
		},
	}
</script>