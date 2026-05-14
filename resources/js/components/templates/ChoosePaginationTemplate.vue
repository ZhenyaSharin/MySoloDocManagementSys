<template>
	<div>
		<template v-if="$root.spinOffSingle==true">
			<vue-template-listpagination class="vis_hid"/>
		</template>
		<template v-else>
			<template v-if="type==='doc'">
				<template v-if="$root.selectedTitle==1">
					<vue-template-listpagination v-if="$root.docsList.length>0" :count="$root.pagiCount" :current="$root.currPagiPage" :type="type" :disabled="$root.disablePagi"/>
				</template>
				<template v-else-if="$root.selectedTitle==2">
					<vue-template-listpagination v-if="$root.docsListAll.length>0" :count="$root.pagiCountAll" :current="$root.currPagiPageAll" :type="type" :disabled="$root.disablePagi"/>
				</template>
			</template>
			<template v-else-if="type==='assign'">
				<template v-if="$root.selectedTitle==1">
					<vue-template-listpagination v-if="$root.assignsShortList.length>0" :count="$root.pagiCount" :current="$root.currPagiPage" :type="type" :disabled="$root.disablePagi"/>
				</template>
				<template v-else-if="$root.selectedTitle==2">
					<vue-template-listpagination v-if="$root.assignsListExecutor.length>0" :count="$root.pagiCountEx" :current="$root.currPagiPageEx" :type="type" :disabled="$root.disablePagi"/>
				</template>
				<template v-else-if="$root.selectedTitle==3">
					<vue-template-listpagination v-if="$root.assignsShortListAll.length>0" :count="$root.pagiCountAll" :current="$root.currPagiPageAll" :type="type" :disabled="$root.disablePagi"/>
				</template>
			</template>
		</template>
	</div>
</template>
<script>
	export default {
		props: {
			type: {
				type: String,
				default: 'doc',
			},
		}
	}
</script>